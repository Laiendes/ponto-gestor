<?php

namespace App\Repositories;

use App\Models\User;

class PasswordRepository
{
    public function update(User $user, string $password): void
    {
        $user->update([
            'password' => $password,
        ]);
    }
}
