<?php

namespace App\Modules\Auth\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Auth\Requests\UserActivateRequest;
use App\Modules\Auth\Requests\UserDestroyRequest;
use App\Modules\Auth\Requests\UserRetireRequest;
use App\Modules\Auth\Requests\UserStoreRequest;
use App\Modules\Auth\Requests\UserSuspendRequest;
use App\Modules\Auth\Requests\UserUpdateRequest;
use App\Modules\Auth\Models\User;
use App\Modules\Auth\Resources\UserResource;
use App\Modules\Auth\Resources\UserResourceCollection;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

#[Group(weight: 1)]
class UserController extends BaseController
{
    /**
     * Find all users
     *
     * Retrive a list of users. Allows pagination, relations and filter query.
     *
     * Authorization
     * - User with roles: any
     */
    #[
        QueryParameter(
            name: "page[number]",
            type: "integer",
            description: "Current page number (default: 1)",
            example: 1,
        ),
    ]
    #[
        QueryParameter(
            name: "page[size]",
            type: "integer",
            description: "Size of current page (default: 15, max: 100)",
            example: 15,
        ),
    ]
    #[
        QueryParameter(
            name: "include",
            type: "string",
            description: "Include nested relations </br>" .
                " Allow relations: position </br>" .
                "Example: include=position",
        ),
    ]
    #[
        QueryParameter(
            name: "filter[*]",
            type: "string",
            description: "Filter by fields </br>" .
                "Allow fields: email, username, role, positionId, status </br>" .
                "Example: filter[email]=admin@gmail.com&filter[status]=active",
        ),
    ]
    public function index()
    {
        Gate::authorize("viewAny", User::class);

        $users = QueryBuilder::for(User::class)
            ->allowedIncludes("position")
            ->allowedFilters([
                AllowedFilter::exact("email"),
                AllowedFilter::exact("username"),
                AllowedFilter::exact("role"),
                AllowedFilter::exact("positionId", "position_id"),
                AllowedFilter::exact("status"),
            ])
            ->autoPaginate();

        return UserResourceCollection::make($users);
    }

    /**
     * Find user by Id
     *
     * Return a unique user. Allow relations query.
     *
     * Authorization
     * - User with roles: any
     */
    #[
        QueryParameter(
            name: "include",
            type: "string",
            description: "Include nested relations </br>" .
                " Allow relations: position </br>" .
                "Example: include=position",
        ),
    ]
    public function show(int $id)
    {
        Gate::authorize("view", User::class);

        $user = QueryBuilder::for(User::class)->allowedIncludes("position")->findOrFail($id);

        return UserResource::make($user);
    }

    /**
     * Create user
     *
     * Return created user.
     *
     * Authorization
     * - User with roles: ADMIN
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(UserStoreRequest $request)
    {
        $createdUser = User::create($request->validated());
        return new UserResource($createdUser);
    }

    /**
     * Update user
     *
     * Return no content.
     *
     * Authorization
     * - User with roles: ADMIN
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UserUpdateRequest $request)
    {
        $request->user->update($request->validated());
        return $this->noContentResponse();
    }

    /**
     * Suspend user
     *
     * Return no content.
     *
     * Authorization
     * - User with roles: ADMIN
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function suspend(UserSuspendRequest $request)
    {
        $request->user->update($request->validated());
        return $this->noContentResponse();
    }

    /**
     * Retire user
     *
     * Return no content.
     *
     * Authorization
     * - User with roles: ADMIN
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function retire(UserRetireRequest $request)
    {
        $request->user->update($request->validated());
        return $this->noContentResponse();
    }

    /**
     * Re-activate user
     *
     * Return no content.
     *
     * Authorization
     * - User with roles: ADMIN
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function activate(UserActivateRequest $request)
    {
        $request->user->update($request->validated());
        return $this->noContentResponse();
    }

    /**
     * Delete user by Id
     *
     * Permanently delete user. Return no content.
     *
     * Authorization
     * - User with roles: ADMIN
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(UserDestroyRequest $request)
    {
        $request->user->delete();
        return $this->noContentResponse();
    }
}
