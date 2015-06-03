<?php
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 03.06.2015
 * Time: 16:03
 */
class OAuthController extends BaseController
{

    public function loginVk()
    {
        $user = App::make('UsersService')->getUser();
        if (!$user->isGuest()) {
            return Redirect::to("/");
        }

        $error = Input::get('error');
        $code = Input::get('code');
        if (empty($error) && !empty($code)) {
            try {
                $json = json_decode(file_get_contents("https://oauth.vk.com/access_token?client_id=" . Config::get('services.vk.id') . "&client_secret=" . Config::get('services.vk.key') . "&code=" . $code . "&redirect_uri=" . urlencode(URL::to("/") . "/oauth/vk")), true);
                if (!empty($json['access_token'])) {
                    $userResp = json_decode(file_get_contents("https://api.vk.com/method/users.get?v=5.33&access_token=" . $json['access_token']), true);
                    if (!empty($userResp['response']) && is_array($userResp['response']) && count($userResp['response']) == 1 && !empty($userResp['response'][0]['id'])) {
                        $data = $userResp['response'][0];
                        $existingUser = User::where('socialid', '=', $data['id'])->where('type', '=', User::TYPE_VK)->first();
                        if (empty($existingUser)) {
                            $user->guestid = null;
                            $user->socialid = $data['id'];
                            $user->type = User::TYPE_VK;
                            $user->email = null;
                            $user->password = null;
                            $name = array_filter([$data['first_name'], $data['last_name']]);
                            $user->name = implode(" ", $name);
                            $user->save();
                        } else {
                            $user = $existingUser;
                        }
                        Auth::loginUsingId($user->id);
                        Redirect::to("/");
                    }
                }
            } catch (Exception $e) {
            }
        }

        return Redirect::to("/login");
    }

}