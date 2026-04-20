<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Api\Vendor;
use Illuminate\Http\Request;
use App\Http\Resources\Api\Vendor\IndexResource;
use App\Http\Requests\Api\Vendor\StoreRequest as VendorRequest;
use App\Http\Requests\Api\Vendor\UpdateRequest as VendorUpdateRequest;
use Illuminate\Support\Str;

class IndexController extends Controller
{
    public function index()
    {
        return IndexResource::collection(Vendor::all());
    }

    public function store(VendorRequest $request)
    {
        $vendor = Vendor::create($request->validated() + ['slug' => Str::slug($request->name)]);
        return new IndexResource($vendor);
    }

    /**
     * Display the specified resource.
     */
    public function show(Vendor $vendor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vendor $vendor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vendor $vendor)
    {
        //
    }
}
