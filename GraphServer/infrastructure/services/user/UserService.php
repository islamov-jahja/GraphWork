<?php


namespace app\infrastructure\services\user;


use app\domain\exceptions\WrongEmailOrPasswordException;
use app\domain\repositories\IUserRepository;
use app\domain\services\IUserService;
use app\infrastructure\helpers\AuthHelper;
use app\infrastructure\services\user\dto\LoginDTO;
use app\infrastructure\services\user\dto\UserSignupDTO;

class UserService implements IUserService
{
    private  $userRepository;
    public function __construct(IUserRepository  $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function signup(UserSignupDTO $user)
    {
        $this->userRepository->save($user->getEntity());
    }

    public function login(LoginDTO $loginDTO)
    {
        $user = $this->userRepository->getByEmail($loginDTO->getEmail());
        if (!\Yii::$app->getSecurity()->validatePassword($loginDTO->getPassword(), $user->getPassword())){
            throw new WrongEmailOrPasswordException('Неверный логин или пароль');
        }

        $token = \Yii::$app->getRequest()->getCsrfToken();
        $user->setToken($token);
        $this->userRepository->update($user);
        return $token;
    }

    public function logout()
    {
        $user = AuthHelper::getAuthenticatedUser($this->userRepository);
        $user->setToken(null);
        $this->userRepository->update($user);
    }
}