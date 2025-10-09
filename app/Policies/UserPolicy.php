<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determina si el usuario puede ver la lista de usuarios.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->can('users.view');
    }

    /**
     * Determina si el usuario puede ver un usuario específico.
     */
    public function view(User $user, User $model): bool
    {
        return $user->hasRole('admin') || $user->id === $model->id;
    }

    /**
     * Determina si el usuario puede crear nuevos usuarios.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->can('users.create');
    }

    /**
     * Determina si el usuario puede actualizar un usuario.
     */
    public function update(User $user, User $model): bool
    {
        return $user->hasRole('admin') || 
               $user->can('users.update') || 
               $user->id === $model->id;
    }

    /**
     * Determina si el usuario puede eliminar un usuario.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->hasRole('admin') || $user->can('users.delete');
    }

    public function restore(User $user, User $model): bool
    {
        return false;
    }

    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
}
