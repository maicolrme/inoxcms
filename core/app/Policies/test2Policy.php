<?php

namespace App\Policies;

use App\Models\User;
use \App\Models\test2;
use Illuminate\Auth\Access\HandlesAuthorization;

class test2Policy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, test2 $model): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, test2 $model): bool
    {
        return true;
    }

    public function delete(User $user, test2 $model): bool
    {
        return true;
    }
}
