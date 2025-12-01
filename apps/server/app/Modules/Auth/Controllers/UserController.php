<?php

namespace App\Modules\Auth\Controllers;

use App\Abstracts\BaseController;
use App\Modules\Auth\Requests\UserActivateRequest;
use App\Modules\Auth\Requests\UserRetireRequest;
use App\Modules\Auth\Requests\UserStoreRequest;
use App\Modules\Auth\Requests\UserSuspendRequest;
use App\Modules\Auth\Requests\UserUpdateRequest;
use App\Modules\Auth\Models\User;
use App\Modules\Auth\Resources\UserResource;
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

    public function store(UserStoreRequest $request)
    {
        Gate::authorize("create", User::class);
        $user = User::create($request->validated());
        return new UserResource($user);
    }

    public function update(UserUpdateRequest $request, int $id)
    {
        Gate::authorize("update", User::class);
        User::findOrFail($id)->update($request->validated());
        return $this->noContentResponse();
    }

    public function suspend(UserSuspendRequest $request, User $id)
    {
        Gate::authorize("update", User::class);
        User::findOrFail($id)->update($request->validated());
        return $this->noContentResponse();
    }

    public function retire(UserRetireRequest $request, int $id)
    {
        Gate::authorize("update", User::class);
        User::findOrFail($id)->update($request->validated());
        return $this->noContentResponse();
    }

    public function activate(UserActivateRequest $request, int $id)
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
