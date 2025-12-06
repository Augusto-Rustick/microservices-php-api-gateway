<?php

namespace App\Controllers;

use App\BaseController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Annotations as OA;

class CatalogController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/catalog/products",
     *     summary="Lista todos os produtos",
     *     tags={"Catalog"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de produtos",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Product"))
     *     )
     * )
     */
    public function getProducts(Request $request, Response $response, array $args): Response
    {
        $data = [
            ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
            ['id' => 2, 'name' => 'Mouse', 'price' => 25]
        ];
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * @OA\Get(
     *     path="/catalog/products/{id}",
     *     summary="Busca um produto pelo ID",
     *     tags={"Catalog"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Detalhes do produto", @OA\JsonContent(ref="#/components/schemas/Product")),
     *     @OA\Response(response=404, description="Produto não encontrado")
     * )
     */
    public function getProduct(Request $request, Response $response, array $args): Response
    {
        $data = ['id' => (int)$args['id'], 'name' => 'Laptop', 'price' => 1200];
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * @OA\Post(
     *     path="/catalog/products",
     *     summary="Cria um novo produto",
     *     tags={"Catalog"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ProductInput")
     *     ),
     *     @OA\Response(response=201, description="Produto criado", @OA\JsonContent(ref="#/components/schemas/Product")),
     *     security={{"jwt": {}}}
     * )
     */
    public function createProduct(Request $request, Response $response, array $args): Response
    {
        $body = json_decode($request->getBody()->getContents(), true);
        $body['id'] = rand(10, 1000);
        $response->getBody()->write(json_encode($body));
        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
    }
    
    /**
     * @OA\Schema(
     *     schema="Product",
     *     type="object",
     *     @OA\Property(property="id", type="integer"),
     *     @OA\Property(property="name", type="string"),
     *     @OA\Property(property="price", type="number", format="float")
     * )
     *
     * @OA\Schema(
     *     schema="ProductInput",
     *     type="object",
     *     @OA\Property(property="name", type="string"),
     *     @OA\Property(property="price", type="number", format="float")
     * )
     */
    public function updateProduct(Request $request, Response $response, array $args): Response
    {
        $body = json_decode($request->getBody()->getContents(), true);
        $body['id'] = (int)$args['id'];
        $response->getBody()->write(json_encode($body));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function deleteProduct(Request $request, Response $response, array $args): Response
    {
        $response->getBody()->write(json_encode(['message' => 'Produto deletado']));
        return $response->withStatus(204);
    }
}
