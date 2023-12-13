<?php

namespace App\Policies;

use App\Models\Image;

class ImagePolicy
{
    public function get(string $type, int $userId): bool
    {
        return Auth::user()->id === $userId || $user->isAdmin;
    }

    public function createUserImage(Request $request, int $userId): bool
    {
        return Auth::user()->id === $userId || $user->isAdmin;
    }
}
