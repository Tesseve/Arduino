<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Player;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlayerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the player can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the player can view the model.
     */
    public function view(?User $user, Player $model): bool
    {
        return true;
    }

    /**
     * Determine whether the player can create models.
     */
    public function create(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the player can update the model.
     */
    public function update(User $user, Player $model): bool
    {
        return true;
    }

    /**
     * Determine whether the player can delete the model.
     */
    public function delete(User $user, Player $model): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete multiple instances of the model.
     */
    public function deleteAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the player can restore the model.
     */
    public function restore(User $user, Player $model): bool
    {
        return false;
    }

    /**
     * Determine whether the player can permanently delete the model.
     */
    public function forceDelete(User $user, Player $model): bool
    {
        return false;
    }
}
