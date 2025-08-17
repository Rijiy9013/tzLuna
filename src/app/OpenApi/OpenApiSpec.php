<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    description: 'Справочник организаций / зданий / деятельностей',
    title: 'Catalog API'
)]
#[OA\Server(
    url: '/api/v1',
    description: 'v1 base'
)]
#[OA\SecurityScheme(
    securityScheme: 'ApiToken',
    type: 'http',
    description: 'Передавайте Authorization: Bearer {API_TOKEN}',
    bearerFormat: 'Token',
    scheme: 'bearer'
)]
final class OpenApiSpec
{
}
