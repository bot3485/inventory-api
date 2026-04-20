<?php

namespace App\Http\Controllers\Api\Location;

use App\Http\Controllers\Controller;
use App\Models\Api\Location;
use App\Http\Requests\Api\Location\IndexRequest;
use App\Http\Resources\Api\Location\IndexResource;
use Illuminate\Support\Str;

class IndexController extends Controller
{
    /**
     * Get Locations Tree
     * * Retrieve a hierarchical list of all locations (Buildings -> Rooms -> Racks).
     * @group Inventory Management
     * @authenticated
     */
    public function index()
    {
        // Получаем дерево: только родители, подгружаем детей рекурсивно
        $locations = Location::whereNull('parent_id')
            ->with('children')
            ->orderBy('sort_order')
            ->get();

        return IndexResource::collection($locations);
    }

    /**
     * Create Location
     * * @group Inventory Management
     * @authenticated
     * @bodyParam name string required The name of the location. Example: Server Room B
     * @bodyParam type string required Location type (building, floor, room, rack). Example: room
     * @bodyParam parent_id int ID of the parent location. Example: 1
     * @bodyParam metadata object Flexible data (photos, maps, media).
     * @bodyParam metadata.media array List of media files.
     */
    public function store(IndexRequest $request)
    {
        $data = $request->validated();
        // Авто-генерация слага: Baku Office -> baku-office
        $data['slug'] = Str::slug($data['name']);

        $location = Location::create($data);

        return new IndexResource($location);
    }

    public function show(Location $location)
    {
        // Загружаем родителя и детей для полной картины в Postman
        return new IndexResource($location->load(['parent', 'children']));
    }

    public function update(IndexRequest $request, Location $location)
    {
        $data = $request->validated();

        if ($request->has('name')) {
            $data['slug'] = Str::slug($data['name']);
        }

        $location->update($data);

        return new IndexResource($location);
    }

    public function destroy(Location $location)
    {
        $location->delete();
        return response()->json(['message' => 'Location deleted successfully']);
    }
}
