<?php

namespace App\Providers;

use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\Operation;
use Dedoc\Scramble\Support\Generator\Reference;
use Dedoc\Scramble\Support\Generator\Schema;
use Dedoc\Scramble\Support\Generator\Types\Type;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Str;

class ScrambleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Scramble::configure()
            ->withDocumentTransformers(function (OpenApi $document) {
                $this->updateSchemas($document);
            })
            ->withOperationTransformers(function (Operation $operation) {
                $this->updateOperationRequestBodies($operation);
                $this->updateOperationResponses($operation);
            });
    }

    public function findAndRenameSchemaNames(string $name): string
    {
        $name = str_replace(["Request", "ResourceCollection", "Resource"], ["Dto", "ListDto", "Dto"], $name);
        $name = str_replace(["StoreDto"], "CreateDto", $name);
        return $name;
    }

    public function updateTypeReference(Reference|Schema|Type $type): void
    {
        if ($type instanceof Reference) {
            $type->fullName = $this->findAndRenameSchemaNames($type->fullName);
        } elseif ($type->type === "array" && isset($type->items) && $type->items instanceof Reference) {
            $type->items->fullName = $this->findAndRenameSchemaNames($type->items->fullName);
        }

        if (isset($type->properties)) {
            $newProperties = [];
            foreach ($type->properties as $propertyName => $propertySchema) {
                $this->updateTypeReference($propertySchema);
                $newProperties[Str::camel($propertyName)] = $propertySchema;
            }
            $type->properties = $newProperties;
        }

        if (isset($type->required)) {
            $type->required = array_map(function ($property) {
                return Str::camel($property);
            }, $type->required);
        }
    }

    public function updateSchemas(OpenApi $document)
    {
        $newSchemas = [];
        foreach ($document->components->schemas as $schemaName => $schema) {
            $newSchemaName = $this->findAndRenameSchemaNames($schemaName);
            $this->updateTypeReference($schema->type);
            $newSchemas[$newSchemaName] = $schema;
        }
        $document->components->schemas = $newSchemas;
    }

    public function updateOperationRequestBodies(Operation $operation)
    {
        if (!isset($operation->requestBodyObject)) {
            return;
        }

        foreach ($operation->requestBodyObject->content as $contentType => $content) {
            if ($content instanceof Reference) {
                $this->updateTypeReference($content);
            }
        }
    }

    public function updateOperationResponses(Operation $operation)
    {
        if (!isset($operation->responses)) {
            return;
        }

        foreach ($operation->responses as $response) {
            if (!isset($response->content)) {
                continue;
            }

            $response->description = $this->findAndRenameSchemaNames($response->description);

            foreach ($response->content as $contentType => $content) {
                if ($content instanceof Schema) {
                    $this->updateTypeReference($content->type);
                }
            }
        }
    }
}
