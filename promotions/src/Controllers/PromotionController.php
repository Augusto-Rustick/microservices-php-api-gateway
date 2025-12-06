<?php

namespace App\Controllers;

use App\BaseController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Annotations as OA;

class PromotionController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/promotions/promotions",
     *     summary="Lista todas as promoções ativas",
     *     tags={"Promotions"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de promoções",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Promotion"))
     *     )
     * )
     */
    public function getPromotions(Request $request, Response $response, array $args): Response
    {
        $data = [
            ['id' => 10, 'name' => 'Black Friday', 'discount' => '20%'],
            ['id' => 11, 'name' => 'Natal', 'discount' => '10%']
        ];
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    /**
     * @OA\Schema(
     *     schema="Promotion",
     *     type="object",
     *     @OA\Property(property="id", type="integer"),
     *     @OA\Property(property="name", type="string"),
     *     @OA\Property(property="discount", type="string")
     * )
     */
    public function dummySchema() {}
}
