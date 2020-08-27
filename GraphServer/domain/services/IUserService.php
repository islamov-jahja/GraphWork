<?php


namespace app\domain\services;


use app\infrastructure\services\user\dto\LoginDTO;
use app\infrastructure\services\user\dto\UserSignupDTO;

interface IUserService
{
    public function signup(UserSignupDTO $user);
    public function login(LoginDTO $loginDTO);

    public function logout();
}