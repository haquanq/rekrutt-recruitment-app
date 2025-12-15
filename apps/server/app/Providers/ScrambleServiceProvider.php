<?php

namespace App\Providers;

use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\Combined\AllOf;
use Dedoc\Scramble\Support\Generator\Components;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\Operation;
use Dedoc\Scramble\Support\Generator\Reference;
use Dedoc\Scramble\Support\Generator\Response;
use Dedoc\Scramble\Support\Generator\Schema;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Dedoc\Scramble\Support\Generator\Types\ArrayType;
use Dedoc\Scramble\Support\Generator\Types\BooleanType;
use Dedoc\Scramble\Support\Generator\Types\IntegerType;
use Dedoc\Scramble\Support\Generator\Types\ObjectType;
use Dedoc\Scramble\Support\Generator\Types\StringType;
use Dedoc\Scramble\Support\Generator\Types\Type;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class ScrambleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Scramble::configure()
            ->expose(false)
            ->withDocumentTransformers(function (OpenApi $document) {
                $this->updateDocumentSecurity($document);
                $this->updateDocumentSchemas($document);
                $this->updateDocumentResponse($document);
            })
            ->withOperationTransformers(function (Operation $operation) {
                $this->updateOperationRequestBodies($operation);
                $this->updateOperationResponses($operation);
            });
    }

    public function findAndRenameSchemaNames(string $name): string
    {
        $name = str_replace(["Request", "ResourceCollection", "Resource"], ["Dto", "PageDto", "Dto"], $name);
        $name = str_replace(["StoreDto"], "CreateDto", $name);
        return $name;
    }

    public function createApiResponseMetadataSchema(): Schema
    {
        return Schema::fromType(
            new ObjectType()
                ->addProperty("success", new BooleanType()->setDescription("Success state")->example(true))
                ->addProperty("status_code", new IntegerType()->setDescription("Status code")->example(200))
                ->addProperty(
                    "timestamp",
                    new StringType()->setDescription("Timestamp")->format("date-time")->example("2050-01-01T00:00:00Z"),
                )
                ->addProperty(
                    "request_id",
                    new StringType()
                        ->setDescription("Request id")
                        ->format("uuid")
                        ->example("00000000-0000-0000-0000-000000000000"),
                )
                ->setRequired(["success", "status_code", "timestamp", "request_id"]),
        );
    }

    public function updateDocumentSecurity(OpenApi $document): void
    {
        $document->secure(
            SecurityScheme::http("Bearer")->setDescription(
                "For web browsers, the token is saved via Cookies and automatically sent for every request. </br>" .
                    "For other clients, manually set this header will override the default behaviour.",
            ),
        );
    }

    public function updateTypeReference(Reference|Schema|Type $type): void
    {
        if ($type instanceof Reference) {
            $type->fullName = $this->findAndRenameSchemaNames($type->fullName);
        } elseif ($type instanceof ArrayType && $type->items instanceof Reference) {
            $type->items->fullName = $this->findAndRenameSchemaNames($type->items->fullName);
        }

        if ($type instanceof ObjectType) {
            $newProperties = [];
            foreach ($type->properties as $propertyName => $propertySchema) {
                $this->updateTypeReference($propertySchema);
                $newProperties[Str::camel($propertyName)] = $propertySchema;
            }
            $type->properties = $newProperties;

            $type->required = array_map(function ($property) {
                return Str::camel($property);
            }, $type->required);
        }
    }

    public function updateDocumentSchemas(OpenApi $document)
    {
        if (!isset($document->components->schemas)) {
            return;
        }

        $document->components->addSchema("ApiResponseMetadata", $this->createApiResponseMetadataSchema());

        $newSchemas = [];
        foreach ($document->components->schemas as $schemaName => $schema) {
            $newSchemaName = $this->findAndRenameSchemaNames($schemaName);
            $this->updateTypeReference($schema->type);
            $newSchemas[$newSchemaName] = $schema;
        }
        $document->components->schemas = $newSchemas;
    }

    public function updateDocumentResponse(OpenApi $document)
    {
        if (!isset($document->components->responses)) {
            return;
        }

        foreach ($document->components->responses as $response) {
            $this->updateResponse($response);
        }
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

        foreach ($operation->responses as &$response) {
            if ($response instanceof Reference) {
                continue;
            }

            $this->updateResponse($response);
        }
    }

    public function updateResponse(Response $response)
    {
        $response->description = $this->getStatusCodeDescription($response->code);

        foreach ($response->content as $contentTypeName => $content) {
            if (
                $content instanceof Schema &&
                $content->type instanceof ObjectType &&
                !isset($content->type->properties["data"])
            ) {
                $content->type = new ObjectType()->addProperty("data", $content->type);
            }

            $allOfType = new AllOf()->setItems([
                $content->type,
                new Reference("schemas", "ApiResponseMetadata", new Components()),
            ]);

            foreach ($allOfType->items as $item) {
                $this->updateTypeReference($item);
            }

            $response->content[$contentTypeName] = Schema::fromType($allOfType);
        }
    }

    public function getStatusCodeDescription(int $statusCode): string
    {
        return match ($statusCode) {
            200 => "OK",
            201 => "Created",
            204 => "No Content",
            400 => "Bad Request",
            401 => "Unauthorized",
            403 => "Forbidden",
            404 => "Not Found",
            409 => "Conflict",
            422 => "Validation Error",
            500 => "Internal Server Error",
            default => "Unknown Status Code",
        };
    }
}
