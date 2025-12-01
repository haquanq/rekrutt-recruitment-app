<?php

namespace App\Modules\ContractType\Controllers;

use App\Abstracts\BaseController;
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
     * Retrive a list of contract types. Allows pagination and filter query.
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
     * Return a unique contract type
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
     * Return created contract type
     */
    public function store(ContractTypeStoreRequest $request)
    {
        Gate::authorize("create", ContractType::class);
        $createdContractType = ContractType::create($request->validated());
        return $this->okResponse(new ContractTypeResource($createdContractType));
    }

    /**
     * Update contract type
     *
     * Return no content
     */
    public function update(ContractTypeUpdateRequest $request, int $id)
    {
        Gate::authorize("update", ContractType::class);
        ContractType::findOrFail($id)->update($request->validated());
        return $this->noContentResponse();
    }

    /**
     * Delete contract type by Id
     *
     * Permanently delete contract type. Return no content
     */
    public function destroy(int $id)
    {
        Gate::authorize("delete", ContractType::class);
        ContractType::findOrFail($id)->delete();
        return $this->noContentResponse();
    }
}
