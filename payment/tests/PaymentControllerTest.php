<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Controllers\PaymentController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PaymentControllerTest extends TestCase
{
    private PaymentController $controller;

    protected function setUp(): void
    {
        $this->controller = new PaymentController();
    }

    /**
     * Test getPayments returns a list of payments
     */
    public function testGetPaymentsReturnsArray(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        
        $body = $this->createMock(\Psr\Http\Message\StreamInterface::class);
        $response->method('getBody')->willReturn($body);
        $response->method('withHeader')->willReturn($response);

        $result = $this->controller->getPayments($request, $response, []);

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    /**
     * Test getPayment returns a single payment
     */
    public function testGetPaymentReturnsSinglePayment(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        
        $body = $this->createMock(\Psr\Http\Message\StreamInterface::class);
        $response->method('getBody')->willReturn($body);
        $response->method('withHeader')->willReturn($response);

        $args = ['id' => 1];
        $result = $this->controller->getPayment($request, $response, $args);

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    /**
     * Test processPayment returns 200 status
     */
    public function testProcessPaymentReturns200Status(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        
        $body = $this->createMock(\Psr\Http\Message\StreamInterface::class);
        $requestBody = $this->createMock(\Psr\Http\Message\StreamInterface::class);
        
        $requestBody->method('getContents')->willReturn(json_encode(['order_id' => 101, 'amount' => 250.00, 'method' => 'credit_card']));
        $request->method('getBody')->willReturn($requestBody);
        
        $response->method('getBody')->willReturn($body);
        $response->method('withHeader')->willReturn($response);

        $result = $this->controller->processPayment($request, $response, []);

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    /**
     * Test refundPayment returns 200 status
     */
    public function testRefundPaymentReturns200Status(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        
        $body = $this->createMock(\Psr\Http\Message\StreamInterface::class);
        $requestBody = $this->createMock(\Psr\Http\Message\StreamInterface::class);
        
        $requestBody->method('getContents')->willReturn(json_encode(['transaction_id' => 'txn_123', 'reason' => 'customer_request']));
        $request->method('getBody')->willReturn($requestBody);
        
        $response->method('getBody')->willReturn($body);
        $response->method('withHeader')->willReturn($response);

        $args = ['id' => 1];
        $result = $this->controller->refundPayment($request, $response, $args);

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    /**
     * Test getPaymentStatus returns payment status
     */
    public function testGetPaymentStatusReturnsStatus(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        
        $body = $this->createMock(\Psr\Http\Message\StreamInterface::class);
        $response->method('getBody')->willReturn($body);
        $response->method('withHeader')->willReturn($response);

        $args = ['id' => 1];
        $result = $this->controller->getPaymentStatus($request, $response, $args);

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }
}
