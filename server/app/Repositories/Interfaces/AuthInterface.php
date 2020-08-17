<?php namespace App\Repositories\Interfaces;

use App\Model\User;

interface AuthInterface
{
    /**
     * @param array $data
     * @return User
     */
    public function Insert(array $data): ?User;

    /**
     * @param string $email
     * @return User
     */
    public function GetByEmail(string $email): User;
}