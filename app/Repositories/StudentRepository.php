<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

/**
 *
 */
class StudentRepository
{

    /**
     * @param User $student
     * @param array $data
     * @return User
     */
    public function updateProfile(User $student, array $data): User
    {
        if (Arr::has($data, 'password')) {
            $data['password'] = Hash::make($data['password']);
            unset($data['password_confirmation']);
        }

        $student->update($data);
        return $student;
    }
}