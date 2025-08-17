<?php

namespace App\Http\Controllers;

use App\Application\Organization\OrganizationQueryService;
use App\Http\Requests\Organization\{OrganizationInRectRequest,
    OrganizationNearRequest,
    OrganizationsByActivityRequest,
    OrganizationsByBuildingRequest,
    OrganizationSearchRequest};
use App\Http\Resources\OrganizationResource;
use App\Infrastructure\Persistence\Eloquent\Query\GeoQueries;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Organizations')]
class OrganizationController extends Controller
{
    public function __construct(
        private OrganizationQueryService $service,
        private GeoQueries               $geo
    )
    {
    }

    #[OA\Get(
        path: '/organizations/{id}',
        summary: 'Карточка организации по ID',
        security: [['ApiToken' => []]],
        tags: ['Organizations'],
        parameters: [
            new OA\PathParameter(
                name: 'id',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'uuid')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'OK'),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 404, description: 'Not Found'),
        ]
    )]
    public function show(string $id)
    {
        $data = $this->service->show($id);
        return OrganizationResource::make($data);
    }

    #[OA\Get(
        path: '/organizations/search',
        summary: 'Поиск организаций по названию',
        security: [['ApiToken' => []]],
        tags: ['Organizations'],
        parameters: [
            new OA\QueryParameter(name: 'q', required: true, schema: new OA\Schema(type: 'string', minLength: 2)),
            new OA\QueryParameter(name: 'per_page', required: false, schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 200)),
        ],
        responses: [
            new OA\Response(response: 200, description: 'OK (пагинация)'),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 422, description: 'Validation Error'),
        ]
    )]
    public function searchByName(OrganizationSearchRequest $request)
    {
        $builder = $this->service->builderForSearchByName($request->validated('q'));
        return OrganizationResource::collection($builder->paginate((int)($request->validated('per_page') ?? 20)));
    }

    #[OA\Get(
        path: '/buildings/{id}/organizations',
        summary: 'Список организаций в здании',
        security: [['ApiToken' => []]],
        tags: ['Organizations'],
        parameters: [
            new OA\PathParameter(name: 'id', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
            new OA\QueryParameter(name: 'per_page', required: false, schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 200)),
        ],
        responses: [
            new OA\Response(response: 200, description: 'OK (пагинация)'),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function byBuilding(string $id, OrganizationsByBuildingRequest $request)
    {
        $builder = $this->service->builderForBuilding($id);
        return OrganizationResource::collection($builder->paginate((int)($request->validated('per_page') ?? 50)));
    }

    #[OA\Get(
        path: '/activities/{id}/organizations',
        summary: 'Организации по виду деятельности (включая дочерние)',
        security: [['ApiToken' => []]],
        tags: ['Organizations'],
        parameters: [
            new OA\PathParameter(name: 'id', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
            new OA\QueryParameter(name: 'per_page', required: false, schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 200)),
        ],
        responses: [
            new OA\Response(response: 200, description: 'OK (пагинация)'),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 404, description: 'Not Found'),
        ]
    )]
    public function byActivity(string $id, OrganizationsByActivityRequest $request)
    {
        $builder = $this->service->builderForActivityId($id);
        return OrganizationResource::collection($builder->paginate((int)($request->validated('per_page') ?? 50)));
    }

    #[OA\Get(
        path: '/activities/slug/{slug}/organizations',
        summary: 'Организации по slug вида деятельности (включая дочерние)',
        security: [['ApiToken' => []]],
        tags: ['Organizations'],
        parameters: [
            new OA\PathParameter(name: 'slug', required: true, schema: new OA\Schema(type: 'string')),
            new OA\QueryParameter(name: 'per_page', required: false, schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 200)),
        ],
        responses: [
            new OA\Response(response: 200, description: 'OK (пагинация)'),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 404, description: 'Not Found'),
        ]
    )]
    public function byActivitySlug(string $slug, OrganizationsByActivityRequest $request)
    {
        $builder = $this->service->builderForActivitySlug($slug);
        return OrganizationResource::collection($builder->paginate((int)($request->validated('per_page') ?? 50)));
    }

    #[OA\Get(
        path: '/organizations/near',
        summary: 'Организации в радиусе от точки',
        security: [['ApiToken' => []]],
        tags: ['Organizations'],
        parameters: [
            new OA\QueryParameter(name: 'lat', required: true, schema: new OA\Schema(type: 'number', format: 'float')),
            new OA\QueryParameter(name: 'lng', required: true, schema: new OA\Schema(type: 'number', format: 'float')),
            new OA\QueryParameter(name: 'radius_m', required: true, schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 100000)),
            new OA\QueryParameter(name: 'per_page', required: false, schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 200)),
        ],
        responses: [
            new OA\Response(response: 200, description: 'OK (пагинация)'),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 422, description: 'Validation Error'),
        ]
    )]
    public function near(OrganizationNearRequest $request)
    {
        $page = $this->geo->paginateNear(
            (float)$request->validated('lat'),
            (float)$request->validated('lng'),
            (int)$request->validated('radius_m'),
            (int)($request->validated('per_page') ?? 50)
        );
        return OrganizationResource::collection($page);
    }

    #[OA\Get(
        path: '/organizations/in-rect',
        summary: 'Организации в прямоугольной области',
        security: [['ApiToken' => []]],
        tags: ['Organizations'],
        parameters: [
            new OA\QueryParameter(name: 'min_lat', required: true, schema: new OA\Schema(type: 'number', format: 'float')),
            new OA\QueryParameter(name: 'min_lng', required: true, schema: new OA\Schema(type: 'number', format: 'float')),
            new OA\QueryParameter(name: 'max_lat', required: true, schema: new OA\Schema(type: 'number', format: 'float')),
            new OA\QueryParameter(name: 'max_lng', required: true, schema: new OA\Schema(type: 'number', format: 'float')),
            new OA\QueryParameter(name: 'per_page', required: false, schema: new OA\Schema(type: 'integer', maximum: 200, minimum: 1)),
        ],
        responses: [
            new OA\Response(response: 200, description: 'OK (пагинация)'),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 422, description: 'Validation Error'),
        ]
    )]
    public function inRect(OrganizationInRectRequest $request)
    {
        $page = $this->geo->paginateInRect(
            (float)$request->validated('min_lat'),
            (float)$request->validated('min_lng'),
            (float)$request->validated('max_lat'),
            (float)$request->validated('max_lng'),
            (int)($request->validated('per_page') ?? 50)
        );
        return OrganizationResource::collection($page);
    }
}
