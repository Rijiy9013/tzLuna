<?php

namespace App\Http\Controllers;

use App\Application\Building\BuildingService;
use App\Http\Requests\Building\{BuildingStoreRequest, BuildingUpdateRequest};
use App\Http\Resources\BuildingResource;
use App\Models\Building;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Buildings')]
class BuildingController extends Controller
{
    public function __construct(private BuildingService $service)
    {
    }

    #[OA\Get(
        path: '/buildings',
        summary: 'Список зданий (пагинация)',
        security: [['ApiToken' => []]],
        tags: ['Buildings'],
        parameters: [
            new OA\QueryParameter(
                name: 'per_page',
                required: false,
                schema: new OA\Schema(type: 'integer', maximum: 200, minimum: 1)
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'OK (пагинация)'),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function index(Request $request)
    {
        $page = $this->service->paginate((int)($request->input('per_page') ?? 50));
        return BuildingResource::collection($page);
    }

    #[OA\Get(
        path: '/buildings/{id}',
        summary: 'Карточка здания',
        security: [['ApiToken' => []]],
        tags: ['Buildings'],
        parameters: [
            new OA\PathParameter(
                name: 'id',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'uuid')
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'OK'),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 404, description: 'Not Found'),
        ]
    )]
    public function show(Building $building)
    {
        $model = $this->service->show($building);
        return BuildingResource::make($model);
    }

    #[OA\Post(
        path: '/buildings',
        summary: 'Создать здание',
        security: [['ApiToken' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['address', 'lat', 'lng'],
                properties: [
                    new OA\Property(property: 'address', type: 'string', example: 'г. Москва, ул. Ленина, 1'),
                    new OA\Property(property: 'lat', type: 'number', format: 'float', example: 55.7558),
                    new OA\Property(property: 'lng', type: 'number', format: 'float', example: 37.6176),
                ]
            )
        ),
        tags: ['Buildings'],
        responses: [
            new OA\Response(response: 201, description: 'Created'),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 422, description: 'Validation Error'),
        ]
    )]
    public function store(BuildingStoreRequest $request)
    {
        $model = $this->service->create($request->validated());
        // Если хочешь строгую семантику — можно вернуть 201:
        // return BuildingResource::make($model)->response()->setStatusCode(201);
        return BuildingResource::make($model);
    }

    #[OA\Put(
        path: '/buildings/{id}',
        summary: 'Обновить здание (полная замена)',
        security: [['ApiToken' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'address', type: 'string', example: 'г. Москва, пр-т Мира, 10'),
                    new OA\Property(property: 'lat', type: 'number', format: 'float', nullable: true),
                    new OA\Property(property: 'lng', type: 'number', format: 'float', nullable: true),
                ]
            )
        ),
        tags: ['Buildings'],
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
        path: '/buildings/{id}',
        summary: 'Частично обновить здание',
        security: [['ApiToken' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent( // все поля необязательны
                properties: [
                    new OA\Property(property: 'address', type: 'string'),
                    new OA\Property(property: 'lat', type: 'number', format: 'float', nullable: true),
                    new OA\Property(property: 'lng', type: 'number', format: 'float', nullable: true),
                ]
            )
        ),
        tags: ['Buildings'],
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
    public function update(BuildingUpdateRequest $request, Building $building)
    {
        $model = $this->service->update($building, $request->validated());
        return BuildingResource::make($model);
    }

    #[OA\Delete(
        path: '/buildings/{id}',
        summary: 'Удалить здание',
        security: [['ApiToken' => []]],
        tags: ['Buildings'],
        parameters: [
            new OA\PathParameter(name: 'id', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 204, description: 'No Content'),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 404, description: 'Not Found'),
        ]
    )]
    public function destroy(Building $building)
    {
        $this->service->delete($building);
        return response()->noContent();
    }
}
