<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\PasswordRepository;

class PasswordService
{
    public function __construct(
        private readonly PasswordRepository $passwordRepository
    ) {}

    public function update(User $user, string $password): void
    {
        $this->passwordRepository->update($user, $password);
    }
}
