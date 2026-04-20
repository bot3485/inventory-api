<?php

namespace App\Policies\Api;

use App\Models\Api\DeviceType; // Убедись, что путь соответствует твоим моделям
use App\Models\Api\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DeviceTypePolicy
{
    use HandlesAuthorization;

    /**
     * Просматривать список типов могут все авторизованные пользователи.
     * Это нужно для работы фильтров и выпадающих списков на фронтенде.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Просматривать детали конкретного типа могут все.
     */
    public function view(User $user, DeviceType $deviceType): bool
    {
        return true;
    }

    /**
     * Создавать новые типы (например, добавить "Smart Watch" в каталог) может только админ.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Редактировать название или иконку типа может только админ.
     */
    public function update(User $user, DeviceType $deviceType): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Удалять категорию может только админ.
     * Напоминаю: в контроллере мы уже добавили защиту от удаления, если есть привязанные модели.
     */
    public function delete(User $user, DeviceType $deviceType): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Для Soft Deletes (если потребуются в будущем)
     */
    public function restore(User $user, DeviceType $deviceType): bool
    {
        return $user->role === 'admin';
    }

    public function forceDelete(User $user, DeviceType $deviceType): bool
    {
        return $user->role === 'admin';
    }
}
