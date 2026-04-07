<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(title: 'LoginFlow API', version: '1.0.0', description: 'Documentação da API do LoginFlow')]
#[OA\Server(url: '/', description: 'Servidor local')]
#[OA\SecurityScheme(securityScheme: 'sanctum', type: 'http', scheme: 'bearer', bearerFormat: 'JWT')]

abstract class Controller
{
    //
}
