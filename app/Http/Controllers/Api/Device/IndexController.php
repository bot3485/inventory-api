<?php

namespace App\Http\Controllers\Api\Device;

use App\Http\Controllers\Controller;
use App\Models\Api\Device;
use App\Http\Requests\Api\Device\StoreRequest;
use App\Http\Requests\Api\Device\UpdateRequest;
use App\Http\Resources\Api\Device\IndexResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @group Device Inventory
 * @authenticated
 */
class IndexController extends Controller
{
    /**
     * List Devices
     * * Получение списка устройств с фильтрацией.
     * @queryParam location_id int Фильтр по ID локации. Example: 5
     * @queryParam status string Фильтр по статусу (stock, active, repair, retired). Example: active
     * @queryParam search string Поиск по серийнику или инвентарному номеру. Example: SN-123
     */
    public function index(Request $request)
    {
        $devices = Device::query()
            ->with(['model.vendor', 'location']) // Жадная загрузка всей цепочки
            ->when($request->location_id, fn($q) => $q->where('location_id', $request->location_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->search, function ($q, $search) {
                $q->where('serial_number', 'like', "%{$search}%")
                    ->orWhere('inventory_number', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15);

        return IndexResource::collection($devices);
    }

    /**
     * Store Device
     * * Регистрация новой единицы оборудования.
     */
    public function store(StoreRequest $request)
    {
        $device = Device::create($request->validated());

        return new IndexResource($device->load(['model', 'location']));
    }

    /**
     * Show Device
     * * Детальная информация об устройстве со всей историей и характеристиками.
     */
    public function show(Device $device)
    {
        return new IndexResource($device->load(['model.vendor', 'model.type', 'location']));
    }

    /**
     * Update Device
     * * Изменение данных устройства (перемещение в другую локацию, смена статуса или IP).
     */
    public function update(UpdateRequest $request, Device $device)
    {
        $device->update($request->validated());

        return new IndexResource($device->load(['model', 'location']));
    }

    /**
     * Delete Device
     * * Удаление устройства из системы.
     */
    public function destroy(Device $device)
    {
        $device->delete();
    }
}
