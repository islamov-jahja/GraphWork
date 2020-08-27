<?php


namespace app\domain\repositories;


use app\domain\entities\User;

interface IUserRepository
{
    public function save(User $user): void;
    public function getByEmail(string $email): User;
    public function update(User $user): void;
    public function getByToken(string $token): User;
}