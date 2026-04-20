<?php

namespace App\Http\Controllers\Api\DeviceModel;

use App\Http\Controllers\Controller;
use App\Models\Api\DeviceModel;
use Illuminate\Http\Request;
use App\Http\Resources\Api\DeviceModel\IndexResource;
use App\Http\Requests\Api\DeviceModel\StoreRequest;
use Illuminate\Support\Str;

/**
 * @group Catalog Management
 * @authenticated
 */
class IndexController extends Controller
{
    /**
     * Get Device Models
     * * Список всех моделей с подгрузкой вендора и типа.
     * @queryParam vendor_id int Filter by vendor ID. Example: 1
     */
    public function index(Request $request)
    {
        $query = DeviceModel::with(['vendor', 'type']);

        if ($request->has('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        return IndexResource::collection($query->get());
    }

    /**
     * Create Device Model
     * * @bodyParam vendor_id int required ID производителя. Example: 1
     * @bodyParam device_type_id int required ID типа устройства. Example: 2
     * @bodyParam name string required Название модели. Example: Catalyst 2960-X
     * @bodyParam specs_template object JSON-шаблон характеристик. Example: {"ports": 24, "poe": true}
     */
    public function store(StoreRequest $request)
    {
        $model = DeviceModel::create($request->validated() + [
            'slug' => Str::slug($request->name)
        ]);
        return new IndexResource($model);
    }

    /**
     * Display the specified resource.
     */
    public function show(DeviceModel $deviceModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DeviceModel $deviceModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeviceModel $deviceModel)
    {
        //
    }
}
