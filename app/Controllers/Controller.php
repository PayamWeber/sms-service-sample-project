<?php

namespace App\Controllers;

use App\Exceptions\AuthenticationException;
use App\Repositories\AccessTokenRepository;
use App\Repositories\UserRepository;

abstract class Controller
{
    /**
     * Here we check the token provided is valid and set the user information if it's valid
     * @return void
     * @throws AuthenticationException
     */
    protected function auth()
    {
        $token = str_replace(['Bearer ', 'bearer '] , '', request()->header('authorization', ''));
        $accessToken = (new AccessTokenRepository())->findByToken($token);
        if($accessToken){
            $user = (new UserRepository())->find($accessToken['user_id']);
            auth()->setUser($user);
        }else{
            throw new AuthenticationException();
        }
    }
}