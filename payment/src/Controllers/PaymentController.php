<?php

namespace App\Controllers;

use App\BaseController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Annotations as OA;

class PaymentController extends BaseController
{
    /**
     * @OA\Post(
     *     path="/payment/process",
     *     summary="Processa um pagamento",
     *     tags={"Payment"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PaymentInput")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pagamento processado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/PaymentResult")
     *     ),
     *     security={{"jwt": {}}}
     * )
     */
    public function processPayment(Request $request, Response $response, array $args): Response
    {
        $body = json_decode($request->getBody()->getContents(), true);
        $result = [
            'status' => 'processed',
            'transaction_id' => uniqid('txn_'),
            'amount' => $body['amount'] ?? 0
        ];
        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    /**
     * @OA\Schema(
     *     schema="PaymentInput",
     *     type="object",
     *     required={"amount", "card_token"},
     *     @OA\Property(property="amount", type="number", format="float", example=199.99),
     *     @OA\Property(property="card_token", type="string", example="tok_abcdef123456")
     * )
     *
     * @OA\Schema(
     *     schema="PaymentResult",
     *     type="object",
     *     @OA\Property(property="status", type="string", example="processed"),
     *     @OA\Property(property="transaction_id", type="string", example="txn_656e5f3f0d8a9"),
     *     @OA\Property(property="amount", type="number", format="float", example=199.99)
     * )
     */
    public function dummySchema() {}
}
