<?php

namespace App\Controllers;

use App\BaseController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Annotations as OA;

class OrderController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/orders/orders",
     *     summary="Lista todos os pedidos",
     *     tags={"Orders"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de pedidos",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Order"))
     *     ),
     *     security={{"jwt": {}}}
     * )
     */
    public function getOrders(Request $request, Response $response, array $args): Response
    {
        $data = [
            ['id' => 101, 'product_id' => 1, 'status' => 'pending'],
            ['id' => 102, 'product_id' => 2, 'status' => 'shipped']
        ];
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * @OA\Post(
     *     path="/orders/orders",
     *     summary="Cria um novo pedido",
     *     tags={"Orders"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/OrderInput")
     *     ),
     *     @OA\Response(response=201, description="Pedido criado", @OA\JsonContent(ref="#/components/schemas/Order")),
     *     security={{"jwt": {}}}
     * )
     */
    public function createOrder(Request $request, Response $response, array $args): Response
    {
        $body = json_decode($request->getBody()->getContents(), true);
        $body['id'] = rand(100, 1000);
        $body['status'] = 'pending';
        $response->getBody()->write(json_encode($body));
        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
    }
    
    /**
     * @OA\Schema(
     *     schema="Order",
     *     type="object",
     *     @OA\Property(property="id", type="integer"),
     *     @OA\Property(property="product_id", type="integer"),
     *     @OA\Property(property="status", type="string")
     * )
     *
     * @OA\Schema(
     *     schema="OrderInput",
     *     type="object",
     *     @OA\Property(property="product_id", type="integer")
     * )
     */
    public function dummySchema() {}
}
