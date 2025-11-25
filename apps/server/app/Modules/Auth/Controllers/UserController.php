<?php

namespace App\Modules\Auth\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Auth\Enums\UserRole;
use App\Modules\Auth\Requests\StoreUserRequest;
use App\Modules\Auth\Requests\UpdateUserRequest;
use App\Modules\Auth\Models\User;
use App\Modules\Auth\Requests\UpdateUserStatusRequest;
use App\Modules\Auth\Resources\UserResource;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends BaseController
{
    public function index()
    {
        Gate::authorize("findAll", User::class);
        return $this->okResponse(UserResource::collection(User::all()));
    }

    public function show(int $id)
    {
        Gate::authorize("findById", User::class);
        $user = User::findOrFail($id);
        return $this->okResponse(new UserResource($user));
    }

    public function store(StoreUserRequest $request)
    {
        Gate::authorize("create", User::class);
        $user = User::create($request->validated());
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, int $id)
    {
        Gate::authorize("update", User::class);
        User::findOrFail($id)->update($request->validated());
        return $this->noContentResponse();
    }

    public function updateStatus(UpdateUserStatusRequest $request, int $id)
    {
        Gate::authorize("update", User::class);
        User::findOrFail($id)->update($request->validated());
        return $this->noContentResponse();
    }

    public function destroy(int $id)
    {
        Gate::authorize("delete", User::class);
        User::findOrFail($id)->delete();
        return $this->noContentResponse();
    }
}
