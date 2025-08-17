<?php

namespace App\Http\Controllers;

use App\Application\Activity\ActivityService;
use App\Http\Requests\Activity\{ActivityStoreRequest, ActivityUpdateRequest};
use App\Http\Resources\ActivityResource;
use App\Models\Activity;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Activities')]
class ActivityController extends Controller
{
    public function __construct(private ActivityService $service)
    {
    }

    #[OA\Get(
        path: '/activities',
        summary: 'Список деятельностей',
        security: [['ApiToken' => []]],
        tags: ['Activities'],
        parameters: [
            new OA\QueryParameter(name: 'per_page', required: false, schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 200))
        ],
        responses: [
            new OA\Response(response: 200, description: 'OK'),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function index(Request $request)
    {
        $perPage = $request->integer('per_page');
        $builder = $this->service->builderForList();

        $result = $perPage ? $builder->paginate($perPage) : $builder->get();

        return ActivityResource::collection($result);
    }

    #[OA\Get(
        path: '/activities/{id}',
        summary: 'Получить деятельность',
        security: [['ApiToken' => []]],
        tags: ['Activities'],
        parameters: [
            new OA\PathParameter(name: 'id', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'OK'),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 404, description: 'Not Found'),
        ]
    )]
    public function show(Activity $activity)
    {
        $model = $this->service->show($activity);
        return ActivityResource::make($model);
    }

    #[OA\Post(
        path: '/activities',
        summary: 'Создать деятельность',
        security: [['ApiToken' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'slug'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Еда'),
                    new OA\Property(property: 'slug', type: 'string', example: 'eda'),
                    new OA\Property(property: 'parent_id', type: 'string', format: 'uuid', nullable: true),
                ]
            )
        ),
        tags: ['Activities'],
        responses: [
            new OA\Response(response: 201, description: 'Created'),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 422, description: 'Validation Error'),
        ]
    )]
    public function store(ActivityStoreRequest $request)
    {
        $model = $this->service->create($request->validated());
        return ActivityResource::make($model)->response()->setStatusCode(201);
    }

    #[OA\Put(
        path: '/activities/{id}',
        summary: 'Обновить деятельность',
        security: [['ApiToken' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Еда и напитки'),
                    new OA\Property(property: 'slug', type: 'string', example: 'food'),
                    new OA\Property(property: 'parent_id', type: 'string', format: 'uuid', nullable: true),
                ]
            )
        ),
        tags: ['Activities'],
        parameters: [
            new OA\PathParameter(name: 'id', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'OK'),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 404, description: 'Not Found'),
            new OA\Response(response: 422, description: 'Validation Error'),
        ]
    )]
    #[OA\Patch(
        path: '/activities/{id}',
        summary: 'Частичное обновление',
        security: [['ApiToken' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string'),
                    new OA\Property(property: 'slug', type: 'string'),
                    new OA\Property(property: 'parent_id', type: 'string', format: 'uuid', nullable: true),
                ]
            )
        ),
        tags: ['Activities'],
        parameters: [
            new OA\PathParameter(name: 'id', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'OK'),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 404, description: 'Not Found'),
            new OA\Response(response: 422, description: 'Validation Error'),
        ]
    )]
    public function update(ActivityUpdateRequest $request, Activity $activity)
    {
        $model = $this->service->update($activity, $request->validated());
        return ActivityResource::make($model);
    }

    #[OA\Delete(
        path: '/activities/{id}',
        summary: 'Удалить деятельность',
        security: [['ApiToken' => []]],
        tags: ['Activities'],
        parameters: [
            new OA\PathParameter(name: 'id', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))
        ],
        responses: [
            new OA\Response(response: 204, description: 'No Content'),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 404, description: 'Not Found'),
        ]
    )]
    public function destroy(Activity $activity)
    {
        $this->service->delete($activity);
        return response()->noContent();
    }
}
