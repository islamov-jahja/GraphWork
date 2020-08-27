<?php


namespace app\infrastructure\helpers;


use app\domain\entities\User;
use app\domain\repositories\IUserRepository;

class AuthHelper
{
    public static function getAuthenticatedUser(IUserRepository $userRepository): User
    {
        $token = \Yii::$app->request->headers->get('Authorization');
        $token = substr($token, 7);
        return $userRepository->getByToken($token);
    }
}