<?php

namespace App\Http\Controllers\Api;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    description: "API Documentation untuk UpcycleMatch. Mendukung JWT Bearer Token dan Basic Auth.",
    title: "UpcycleMatch API",
    contact: new OA\Contact(email: "admin@upcyclematch.id")
)]
#[OA\Server(
    url: "http://127.0.0.1:8000",
    description: "Local Server"
)]
#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    name: "Authorization",
    in: "header",
    scheme: "bearer",
    bearerFormat: "JWT"
)]
#[OA\SecurityScheme(
    securityScheme: "basicAuth",
    type: "http",
    scheme: "basic"
)]
#[OA\SecurityScheme(
    securityScheme: "apiKeyAuth",
    type: "apiKey",
    name: "X-API-KEY",
    in: "header"
)]
class SwaggerConfig
{
    #[OA\Get(
        path: "/api/v1/analytics",
        summary: "Dapatkan Statistik Platform",
        description: "Mengembalikan data analitik limbah dan produk di UpcycleMatch.",
        tags: ["Public"],
        responses: [
            new OA\Response(response: 200, description: "Successful operation")
        ]
    )]
    public function analytics() {}
}
