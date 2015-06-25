<?php
use Account\OperationPurchase;
use Account\OperationRefill;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Order\Order;

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 25.12.2014
 * Time: 12:14
 */
class OrderService
{

    /**
     * @param $orderId
     * @throws Exception
     */
    public function payOrder($orderId, $paymentAmount, $paymentCurrency, $paymentTransactionId, $paymentEmail = '')
    {

        try {

            DB::beginTransaction();

            $order = Order::find($orderId);

            if (empty($order)) {
                throw new InvalidArgumentException("Order #$orderId not found");
            }

            $account = $order->user->accounts()->where('currency', '=', $paymentCurrency)->first();

            if (empty($account)) {
                throw new InvalidArgumentException('Could not find account with currency' . $paymentCurrency);
            }

            $refill = new OperationRefill();
            $refill->amount = intval($paymentAmount * 100);

            $refill->gateway = 'onpay';
            $refill->gateway_operation_id = $paymentTransactionId;
            $account->addOperation($refill);
            $account->save();

            if ($order->status !== Order::STATUS_PENDING) {
                throw new InvalidArgumentException("Order #$orderId already payed");
            }

            $sum = intval($order->total);

            $user = User::find($order->user->id);
            $cart = $user->cart;
            $cart->items()->delete();

            $account = $user->accounts()->first();

            $purchase = new OperationPurchase();
            $purchase->amount = $sum;
            $account->addOperation($purchase);
            $order->status = \Order\Order::STATUS_COMPLETE;

            $account->save();
            $order->save();

            $userCatalogItemIds = [];
            foreach ($user->catalogItems as $userCatalogItem) {
                $userCatalogItemIds[] = $userCatalogItem->id;
            }

            $catalogItems = [];
            foreach ($order->items as $item) {
                if (!in_array($item->catalogItem->id, $userCatalogItemIds)) {
                    $catalogItems[] = $item->catalogItem;
                }
            }
            $user->attachCatalogItems($catalogItems);


            $downloadToken = $this->createDownloadLink($order->id);

            $userEmail = $paymentEmail;
            $userName = '';

            $user = $order->user;
            $emailConfirmHash = null;

            if (!$user->isGuest()) {
                $userName = $user->name;
                if (!empty($user->email)) {
                    $userEmail = $user->email;
                }
                else if (!empty($paymentEmail) && !empty($user->socialid) && empty($user->email)) {
                    $emailConfirmHash = UserPending::createSocialConfirm($paymentEmail, $user->socialid);
                }
            }

            if (!empty($userEmail)) {

                $todata = [
                    'email' => $userEmail,
                    'name' => $userName
                ];

                try {
                    Mail::send('emails.payments.order', array(
                        'isGuest' => $user->isGuest(),
                        'confirm_hash' => $emailConfirmHash,
                        'orderId' => $order->id,
                        'token' => $downloadToken
                    ), function ($message) use ($todata) {
                        Log::debug($todata);
                        $message->from('noreply@' . $_SERVER['HTTP_HOST'])->to($todata['email'], empty($todata['name']) ? "Клиент mama-print" : $todata['name'])->subject('Покупка на сайте mama-print.ru');
                    });
                } catch (Exception $e) {
                    Log::error("Failed to send message: " . $e->getMessage());
                }
            }

            DB::commit();

        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            throw $e;
        }
    }

    public function createDownloadLink($orderId)
    {

        if (empty($orderId)) {
            throw new InvalidArgumentException("Cannot create download link. Order id is empty.");
        }

        try {
            DB::beginTransaction();
            $order = \Order\Order::find($orderId);
            if (empty($order) || \Order\Order::STATUS_COMPLETE != $order->status) {
                throw new Exception("Нельзя создать временную ссылку т.к. заказ пока не оплачен или не существует");
            }
            $link = new DownloadLink();
            $link->order()->associate($order);
            $link->token = str_random(40);
            $link->save();

            DB::commit();
            return $link->token;
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

    }

    public function createOrderArchive($orderId)
    {
        $order = \Order\Order::find($orderId);

        if (empty($order) || \Order\Order::STATUS_COMPLETE != $order->status) {
            throw new Exception("Заказ не существует или еще не оплачен");
        }

        $DS = DIRECTORY_SEPARATOR;
        $path = Config::get('mamaprint.tmp_orders_path');
        $file = $path . $DS . $order->id . ".zip";

        if (!file_exists($file)) {

            if (!file_exists($path)) {
                if (mkdir($path, 0777, true) !== true) {
                    throw new Exception(Lang::get('messages.error.could_not_create_folder', [
                        'path' => $path
                    ]));
                }
            }

            $zip = new ZipArchive();
            $zip->open($file, ZipArchive::CREATE);

            $attachmentsService = App::make('AttachmentService');

            foreach ($order->items as $item) {
                $n = 1;
                $catalogItem = $item->catalogItem;
                $attachments = $catalogItem->attachments;
                foreach ($attachments as $attachment) {
                    $attachmentPath = $attachmentsService->getFilePath($attachment->id);
                    if ($attachmentPath) {
                        $zip->addFile($attachmentPath, $catalogItem->slug . '/' . $n . '.' . $attachment->extension);
                    }
                    $n++;
                }
            }

            $zip->close();
        }

        return $file;

    }

}