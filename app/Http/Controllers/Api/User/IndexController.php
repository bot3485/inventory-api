<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Api\User;
use App\Http\Resources\Api\User\IndexResource;
use App\Http\Requests\Api\User\StoreRequest;
use App\Http\Requests\Api\User\UpdateRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * @group Employee Management
 * API for managing the provider's staff.
 */
class IndexController extends Controller
{
    /**
     * User List
     * Retrieve a list of all system users with filtering and pagination support.
     * * @group User Management
     * @authenticated
     * @queryParam filter[name] Search by partial name match. Example: ivan
     * @queryParam filter[email] Search by exact email match. Example: admin@test.com
     * @queryParam filter[search] Global search across name and email fields. Example: pushkin
     * @queryParam sort Field for sorting. Available: name, created_at. Use "-" for descending order. Example: -created_at
     * @responseFile storage/responses/users/get.json
     */
    public function index()
    {
        // Start building the query via QueryBuilder
        $users = QueryBuilder::for(User::class)
            // NON-OBVIOUS POINT #1: allowedIncludes
            // Without this line, the client cannot retrieve IP data even if it exists in the DB.
            // Request: ?include=details
            ->allowedIncludes('details')

            ->allowedFilters(
                'email',
                'role', // Replaced is_admin with roles (admin, manager, user)

                AllowedFilter::partial('name'),

                // Filter by IP from the related user_details table
                // Request: ?filter[ip]=192.168.
                AllowedFilter::partial('ip', 'details.ip_address'),

                // Global search
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->where(function ($query) use ($value) {
                        $query->where('name', 'LIKE', "%{$value}%")
                            ->orWhere('email', 'LIKE', "%{$value}%");
                    });
                }),
            )
            ->allowedSorts('name', 'created_at')
            ->defaultSort('-created_at')
            ->paginate(10)
            ->withQueryString();

        return IndexResource::collection($users);
    }

    /**
     * Create Employee
     * Register a new employee in the system.
     */
    public function store(StoreRequest $request)
    {
        // NON-OBVIOUS POINT #2: $request->validated()
        // We do not manually map 'name' => $request->name.
        // The validated() method only retrieves fields permitted in StoreRequest.
        $data = $request->validated();

        // Hash the password before saving
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        return new IndexResource($user);
    }

    /**
     * View Profile
     *
     * Retrieve detailed information about a specific employee.
     * You can optionally include technical metadata using `?include=details`.
     *
     * @group User Management
     * @authenticated
     * * @urlParam id int required The unique ID of the user. Example: 1
     * @queryParam include string Include related technical data. Allowed: details. Example: details
     * * @responseFile storage/responses/users/show.json
     */
    public function show($id)
    {
        // We use QueryBuilder instead of simple Route Model Binding
        // to allow dynamic includes like ?include=details
        $user = QueryBuilder::for(User::class)
            ->allowedIncludes('details')
            ->findOrFail($id);

        return new IndexResource($user);
    }

    /**
     * Update User
     * Update existing employee data.
     * * @urlParam user int required ID of the user being updated. Example: 1
     * @authenticated
     * @responseFile status=200 storage/responses/users/update.json
     * @responseFile status=403 storage/responses/users/errors/403.json
     */
    public function update(UpdateRequest $request, User $user)
    {
        // Check permissions via Policy (UserPolicy)
        Gate::authorize('update', $user);

        $user->update($request->validated());

        return new IndexResource($user);
    }

    /**
     * Delete Employee
     * Remove an employee from the system.
     */
    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);

        $user->delete();

        return response()->noContent();
    }
}
