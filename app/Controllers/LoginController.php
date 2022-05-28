<?php

namespace App\Controllers;

use App\Repositories\AccessTokenRepository;
use App\Repositories\UserRepository;
use App\Routing\Response\JsonResponse;
use Carbon\Carbon;

class LoginController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function login(): JsonResponse
    {
        $repo = new UserRepository();

        $user = $repo->findByUserName(request()->get('username'));

        if ($user) {
            $password = request()->get('password');

            if (password_verify($password, $user['password'])) {
                $token = str_random(50);
                (new AccessTokenRepository())->create([
                    'user_id' => $user['id'],
                    'token' => hash('sha256', $token),
                    'created_at' => Carbon::now()->toDateTimeString(),
                ]);

                return JsonResponse::from([
                    'status' => true,
                    'data' => [
                        'token' => $token
                    ]
                ]);
            }
        }

        return JsonResponse::from([
            'status' => false,
            'message' => 'wrong credentials'
        ]);
    }
}