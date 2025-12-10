<?php

namespace App\Modules\ContractType\Controllers;

use App\Abstracts\BaseController;
use App\Modules\ContractType\Requests\ContractTypeDestroyRequest;
use App\Modules\ContractType\Requests\ContractTypeStoreRequest;
use App\Modules\ContractType\Requests\ContractTypeUpdateRequest;
use App\Modules\ContractType\Models\ContractType;
use App\Modules\ContractType\Resources\ContractTypeResource;
use App\Modules\ContractType\Resources\ContractTypeResourceCollection;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ContractTypeController extends BaseController
{
    /**
     * Find all contract types
     *
     * Return a list of contract types. Allows pagination and filter query.
     *
     * Authorization
     * - User can be anyone.
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
            name: "filter[*]",
            type: "string",
            description: "Filter by fields </br>" . "Allow fields: name </br>" . "Example: filter[name]=Full-time",
        ),
    ]
    public function index()
    {
        Gate::authorize("viewAny", ContractType::class);

        $contractTypes = QueryBuilder::for(ContractType::class)
            ->allowedFilters([AllowedFilter::partial("name")])
            ->autoPaginate();

        return ContractTypeResourceCollection::make($contractTypes);
    }

    /**
     * Find contract type by Id
     *
     * Return a unique contract type.
     *
     * Authorization
     * - User can be anyone.
     */
    public function show(int $id)
    {
        Gate::authorize("view", ContractType::class);
        $contractType = ContractType::findOrFail($id);
        return ContractTypeResource::make($contractType);
    }

    /**
     * Create contract type
     *
     * Return created contract type.
     *
     * Authorization
     * - User must be hiring manager or recruiter.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(ContractTypeStoreRequest $request)
    {
        $createdContractType = ContractType::create($request->validated());
        return $this->okResponse(ContractTypeResource::make($createdContractType));
    }

    /**
     * Update contract type
     *
     * Return no content.
     *
     * Authorization
     * - User must be hiring manager or recruiter.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(ContractTypeUpdateRequest $request, int $id)
    {
        $request->getContractTypeOrFail()->update($request->validated());
        return $this->noContentResponse();
    }

    /**
     * Delete contract type by Id
     *
     * Permanently delete contract type. * Return no content.
     *
     * Authorization
     * - User must be hiring manager or recruiter.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(ContractTypeDestroyRequest $request)
    {
        $request->getContractTypeOrFail()->delete();
        return $this->noContentResponse();
    }
}
