<?php

namespace App;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(title="API de Microserviços", version="1.0")
 * @OA\Server(url="http://localhost:8000")
 */
abstract class BaseController
{
    /**
     * @OA\SecurityScheme(
     *     securityScheme="jwt",
     *     type="http",
     *     scheme="bearer",
     *     bearerFormat="JWT",
     *     description="Autenticação baseada em JSON Web Token"
     * )
     */
    public function __construct() {}
}
