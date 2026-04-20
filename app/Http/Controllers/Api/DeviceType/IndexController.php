<?php

namespace App\Http\Controllers\Api\DeviceType;

use App\Http\Controllers\Controller;
use App\Models\Api\DeviceType; // Убедись, что путь верный
use App\Http\Requests\Api\DeviceType\StoreRequest;
use App\Http\Requests\Api\DeviceType\UpdateRequest;
use App\Http\Resources\Api\DeviceType\IndexResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;

/**
 * @group Catalog Management
 * @authenticated
 */
class IndexController extends Controller
{
    /**
     * List Device Types
     * * Получение списка всех типов оборудования (Switch, Router, PC и т.д.).
     */
    public function index(): AnonymousResourceCollection
    {
        return IndexResource::collection(DeviceType::orderBy('name')->get());
    }

    /**
     * Store Device Type
     * * Создание новой категории оборудования.
     */
    public function store(StoreRequest $request): IndexResource
    {
        $deviceType = DeviceType::create($request->validated() + [
            'slug' => Str::slug($request->name)
        ]);

        return new IndexResource($deviceType);
    }

    /**
     * Show Device Type
     * * Информация о конкретном типе.
     */
    public function show(DeviceType $deviceType): IndexResource
    {
        return new IndexResource($deviceType);
    }

    /**
     * Update Device Type
     */
    public function update(UpdateRequest $request, DeviceType $deviceType): IndexResource
    {
        $data = $request->validated();

        if ($request->has('name')) {
            $data['slug'] = Str::slug($request->name);
        }

        $deviceType->update($data);

        return new IndexResource($deviceType);
    }

    /**
     * Remove Device Type
     */
    public function destroy(DeviceType $deviceType): JsonResponse
    {
        // Можно добавить проверку: нельзя удалить тип, если есть привязанные модели
        if ($deviceType->models()->exists()) {
            return response()->json([
                'message' => 'Нельзя удалить тип, к которому привязаны модели устройств'
            ], 422);
        }

        $deviceType->delete();

        return response()->json(null, 204);
    }
}
