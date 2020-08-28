<?php


namespace app\infrastructure\repositories\user;


use app\domain\entities\User;
use app\domain\exceptions\NotFoundException;
use app\domain\exceptions\UserExistException;
use app\domain\exceptions\WrongEmailOrPasswordException;
use app\domain\repositories\IUserRepository;

class UserRepository implements IUserRepository
{
    public function save(User  $user): void
    {
        $userObject = new \app\infrastructure\persistance\User();
        $userObject->token = $user->getToken();
        $userObject->email = $user->getEmail();
        $userObject->password = $user->getPassword();
        $userObject->name = $user->getName();

        if (\app\infrastructure\persistance\User::findOne(['email' => $user->getEmail()]) != null){
            throw new UserExistException('Данный пользователь существует');
        }

        if ($userObject->validate()){
            $userObject->save();
        }
    }

    public function getByEmail(string $email): User
    {
        $user = \app\infrastructure\persistance\User::findOne(['email' => $email]);
        if ($user == null){
            throw new WrongEmailOrPasswordException('Неверный логин или пароль');
        }

        return new User($user->name, $user->password, $user->email, $user->token, $user->id);
    }

    public function update(User $user): void
    {
        \app\infrastructure\persistance\User::updateAll([
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'name' => $user->getName(),
            'token' => $user->getToken()
        ], ['email' => $user->getEmail()]);
    }

    public function getByToken(string $token): User
    {
        $user = \app\infrastructure\persistance\User::findOne(['token' => $token]);
        if ($user == null){
            throw new NotFoundException();
        }

        return new User($user->name, $user->password, $user->email, $user->token, $user->id);
    }
}