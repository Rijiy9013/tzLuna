<?php

namespace App\Http\Controllers;

use App\Application\Organization\OrganizationService;
use App\Http\Requests\Organization\{OrganizationStoreRequest, OrganizationUpdateRequest};
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Organizations')]
class CrudOrganizationController extends Controller
{
    public function __construct(private OrganizationService $service)
    {
    }

    #[OA\Post(
        path: '/organizations',
        summary: 'Создать организацию',
        security: [['ApiToken' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'building_id'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'ООО «Рога и Копыта»'),
                    new OA\Property(property: 'building_id', type: 'string', format: 'uuid', example: 'aaaaaaaa-bbbb-cccc-dddd-eeeeeeeeeeee'),
                    new OA\Property(
                        property: 'phones',
                        type: 'array',
                        items: new OA\Items(type: 'string'),
                        example: ['2-222-222', '+7 (923) 666-13-13']
                    ),
                    new OA\Property(
                        property: 'activity_ids',
                        type: 'array',
                        items: new OA\Items(type: 'string', format: 'uuid'),
                        example: ['11111111-2222-3333-4444-555555555555']
                    ),
                ]
            )
        ),
        tags: ['Organizations'],
        responses: [
            new OA\Response(response: 201, description: 'Created'),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 422, description: 'Validation Error'),
        ]
    )]
    public function store(OrganizationStoreRequest $request)
    {
        $organization = $this->service->create($request->validated());
        return OrganizationResource::make($organization)->response()->setStatusCode(201);
    }

    #[OA\Put(
        path: '/organizations/{id}',
        summary: 'Обновить организацию',
        security: [['ApiToken' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'ООО «Рога и Копыта+»'),
                    new OA\Property(property: 'building_id', type: 'string', format: 'uuid', example: 'aaaaaaaa-bbbb-cccc-dddd-eeeeeeeeeeee'),
                    new OA\Property(
                        property: 'phones',
                        type: 'array',
                        items: new OA\Items(type: 'string'),
                        example: ['3-333-333']
                    ),
                    new OA\Property(
                        property: 'activity_ids',
                        type: 'array',
                        items: new OA\Items(type: 'string', format: 'uuid'),
                        example: ['11111111-2222-3333-4444-555555555555', '66666666-7777-8888-9999-000000000000']
                    ),
                ]
            )
        ),
        tags: ['Organizations'],
        parameters: [
            new OA\PathParameter(name: 'id', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'OK'),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 404, description: 'Not Found'),
            new OA\Response(response: 422, description: 'Validation Error'),
        ]
    )]
    #[OA\Patch(
        path: '/organizations/{id}',
        summary: 'Частично обновить организацию',
        security: [['ApiToken' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent( // все поля необязательны
                properties: [
                    new OA\Property(property: 'name', type: 'string'),
                    new OA\Property(property: 'building_id', type: 'string', format: 'uuid'),
                    new OA\Property(property: 'phones', type: 'array', items: new OA\Items(type: 'string')),
                    new OA\Property(property: 'activity_ids', type: 'array', items: new OA\Items(type: 'string', format: 'uuid')),
                ]
            )
        ),
        tags: ['Organizations'],
        parameters: [
            new OA\PathParameter(name: 'id', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'OK'),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 404, description: 'Not Found'),
            new OA\Response(response: 422, description: 'Validation Error'),
        ]
    )]
    public function update(OrganizationUpdateRequest $request, Organization $organization)
    {
        $organization = $this->service->update($organization, $request->validated());
        return OrganizationResource::make($organization);
    }

    #[OA\Delete(
        path: '/organizations/{id}',
        summary: 'Удалить организацию',
        security: [['ApiToken' => []]],
        tags: ['Organizations'],
        parameters: [
            new OA\PathParameter(name: 'id', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 204, description: 'No Content'),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 404, description: 'Not Found'),
        ]
    )]
    public function destroy(Organization $organization)
    {
        $this->service->delete($organization);
        return response()->noContent();
    }
}
