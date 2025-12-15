<?php

namespace App\Modules\Auth\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Auth\Enums\UserStatus;
use App\Modules\Auth\Requests\UserReactivateRequest;
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
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

#[Group(weight: 1)]
class UserController extends BaseController
{
    /**
     * Find all users
     *
     * Retrive a list of users. Allows pagination, relations and filter query.
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
     * - User must be administrator.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(UserStoreRequest $request)
    {
        $createdUser = User::create($request->validated());
        return $this->createdResponse(UserResource::make($createdUser));
    }

    /**
     * Update user
     *
     * Return no content.
     *
     * Authorization
     * - User must be administrator.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UserUpdateRequest $request)
    {
        $user = $request->getQueriedUserOrFail();

        if ($user->status !== UserStatus::ACTIVE) {
            throw new ConflictHttpException("Cannot update. " . $user->status->description());
        }

        $user->update($request->validated());
        return $this->noContentResponse();
    }

    /**
     * Suspend user
     *
     * Return no content.
     *
     * Authorization
     * - User must be administrator.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function suspend(UserSuspendRequest $request)
    {
        $user = $request->getQueriedUserOrFail();

        if ($user->status === UserStatus::SUSPENDED) {
            throw new ConflictHttpException("Cannot suspend. User is already suspended.");
        }

        $user->update($request->validated());
        $user->tokens()->delete();
        return $this->noContentResponse();
    }

    /**
     * Retire user
     *
     * Return no content.
     *
     * Authorization
     * - User must be administrator.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function retire(UserRetireRequest $request)
    {
        $user = $request->getQueriedUserOrFail();

        if ($user->status === UserStatus::RETIRED) {
            throw new ConflictHttpException("Cannot retire. User is already retired.");
        }

        $user->update($request->validated());
        return $this->noContentResponse();
    }

    /**
     * Reactivate user
     *
     * Return no content.
     *
     * Authorization
     * - User must be administrator.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function reactivate(UserReactivateRequest $request)
    {
        $user = $request->getQueriedUserOrFail();

        if ($user->status === UserStatus::ACTIVE) {
            throw new ConflictHttpException("Cannot reactivate. User is already active.");
        }

        $user->update($request->validated());
        return $this->noContentResponse();
    }

    /**
     * Delete user by Id
     *
     * Permanently delete user. * Return no content.
     *
     * Authorization
     * - User must be administrator.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(UserDestroyRequest $request)
    {
        $user = $request->getQueriedUserOrFail();

        if ($user->status !== UserStatus::ACTIVE) {
            throw new ConflictHttpException("Cannot delete. " . $user->status->description());
        }

        $user->delete();
        return $this->noContentResponse();
    }
}
