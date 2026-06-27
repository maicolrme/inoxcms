<?php

namespace App\Policies;

use App\Models\User;
use \App\Models\Profile;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProfilePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Profile $model): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Profile $model): bool
    {
        return true;
    }

    public function delete(User $user, Profile $model): bool
    {
        return true;
    }
}
