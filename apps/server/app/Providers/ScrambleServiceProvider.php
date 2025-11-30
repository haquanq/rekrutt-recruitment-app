<?php

namespace App\Providers;

use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\Operation;
use Dedoc\Scramble\Support\Generator\Reference;
use Dedoc\Scramble\Support\Generator\Schema;
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

    public function createDtoName(string $name): string
    {
        $name = str_replace(["Request", "Resource"], "Dto", $name);
        $name = str_replace(["StoreDto"], "CreateDto", $name);
        return $name;
    }

    public function updateSchemas(OpenApi $document)
    {
        $newSchemas = [];
        foreach ($document->components->schemas as $schemaName => $schema) {
            $newSchemaName = $this->createDtoName($schemaName);
            $schema->setTitle($newSchemaName);

            $newProperties = [];
            if (isset($schema->type->properties)) {
                foreach ($schema->type->properties as $propertyName => $propertyMetadata) {
                    $newProperties[Str::camel($propertyName)] = $propertyMetadata;
                }
            }
            $schema->type->properties = $newProperties;
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
                $content->fullName = $this->createDtoName($content->fullName);
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
                    if ($content->type instanceof Reference) {
                        $content->type->fullName = $this->createDtoName($content->type->fullName);
                    } elseif (isset($content->type->items)) {
                        $content->type->items->fullName = $this->createDtoName($content->type->items->fullName);
                    }
                }
            }
        }
    }
}
