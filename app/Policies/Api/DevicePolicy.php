<?php

namespace App\Policies\Api;

use App\Models\Api\Device; // Убедись, что путь соответствует твоим моделям
use App\Models\Api\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DevicePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     * Просматривать список устройств могут все авторизованные сотрудники.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     * Просматривать конкретное устройство могут все.
     */
    public function view(User $user, Device $device): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     * Создавать новые устройства могут только админы и менеджеры склада.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    /**
     * Determine whether the user can update the model.
     * Редактировать (менять IP, локацию) могут админы и менеджеры.
     */
    public function update(User $user, Device $device): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    /**
     * Determine whether the user can delete the model.
     * Удалять оборудование из базы может только администратор.
     */
    public function delete(User $user, Device $device): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Опционально: методы для Soft Deletes (если используешь)
     */
    public function restore(User $user, Device $device): bool
    {
        return $user->role === 'admin';
    }

    public function forceDelete(User $user, Device $device): bool
    {
        return $user->role === 'admin';
    }
}
