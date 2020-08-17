<?php namespace App\Repositories;

use App\Model\User;
use App\Repositories\Interfaces\AuthInterface;

class AuthRepository implements AuthInterface
{
    /**
     * @param array $data
     * @return User
     */
    public function Insert(array $data): ?User
    {
        $user = new User();

        $user->email = $data['email'];
        $user->name = $data['name'];
        $user->password = bcrypt($data['password']);

        $user->save();

        //TODO get inserted id
        $user->id = 1;

        return $user;
    }

    /**
     * @param string $email
     * @return User
     */
    public function GetByEmail(string $email): User
    {
        return User::where('email', $email)->first();
    }
}