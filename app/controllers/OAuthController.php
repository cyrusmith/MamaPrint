<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use mamaprint\application\services\UserService;
use mamaprint\domain\user\UserRepositoryInterface;
use User\User;

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 03.06.2015
 * Time: 16:03
 */
class OAuthController extends BaseController
{


    public function __construct(
        UserRepositoryInterface $userRepository,
        UserService $userService)
    {
        $this->userService = $userService;
        $this->userRepository = $userRepository;
    }

    public function loginVk()
    {

        if(Auth::check()) {
            return Redirect::to("/");
        }

        $guestUser = $this->userRepository->findGuest(Session::get('guestid'));

        $error = Input::get('error');
        $code = Input::get('code');
        if (empty($error) && !empty($code)) {
            try {
                $json = json_decode(file_get_contents("https://oauth.vk.com/access_token?client_id=" . Config::get('services.vk.id') . "&client_secret=" . Config::get('services.vk.key') . "&code=" . $code . "&redirect_uri=" . urlencode(URL::to("/") . "/oauth/vk")), true);
                if (!empty($json['access_token'])) {
                    $userResp = json_decode(file_get_contents("https://api.vk.com/method/users.get?v=5.33&access_token=" . $json['access_token']), true);
                    if (!empty($userResp['response']) && is_array($userResp['response']) && count($userResp['response']) == 1 && !empty($userResp['response'][0]['id'])) {
                        $data = $userResp['response'][0];
                        $socialId = $data["id"];
                        $name = implode(" ", array_filter([$data['first_name'], $data['last_name']]));
                        $existingUser = $this->userRepository->findSocial($socialId, User::TYPE_VK);
                        if (empty($existingUser)) {
                            $user = $this->userService->saveSocialUser(!empty($guestUser) ? $guestUser->id : null, $socialId, User::TYPE_VK, $name);
                        } else {
                            $user = $existingUser;
                        }
                        Auth::loginUsingId($user->id);
                        Redirect::to("/");
                    }
                }
            } catch (Exception $e) {
                Log::error($e);
                throw $e;
            }
        }

        return Redirect::to("/login");
    }

}