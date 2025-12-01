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

    public function createCustomeModelName(string $name): string
    {
        $name = str_replace(["Request", "Resource"], "Dto", $name);
        $name = str_replace(["StoreDto"], "CreateDto", $name);
        return $name;
    }

    public function updateTypeReference(Reference|Schema|Type $type): void
    {
        if ($type instanceof Reference) {
            $type->fullName = $this->createCustomeModelName($type->fullName);
        } elseif ($type->type === "array" && $type->items instanceof Reference) {
            $type->items->fullName = $this->createCustomeModelName($type->items->fullName);
        }
    }

    public function updateSchemas(OpenApi $document)
    {
        $newSchemas = [];
        foreach ($document->components->schemas as $schemaName => $schema) {
            $newSchemaName = $this->createCustomeModelName($schemaName);

            if (isset($schema->type->properties)) {
                $newProperties = [];
                foreach ($schema->type->properties as $propertyName => $propertySchema) {
                    $this->updateTypeReference($propertySchema);
                    $newProperties[Str::camel($propertyName)] = $propertySchema;
                }
                $schema->type->properties = $newProperties;
            }

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
                $content->fullName = $this->createCustomeModelName($content->fullName);
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

            foreach ($response->content as $contentType => $content) {
                if ($content instanceof Schema) {
                    $this->updateTypeReference($content->type);
                }
            }
        }
    }
}
