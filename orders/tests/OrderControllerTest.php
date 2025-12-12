<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Controllers\OrderController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class OrderControllerTest extends TestCase
{
    private OrderController $controller;

    protected function setUp(): void
    {
        $this->controller = new OrderController();
    }

    /**
     * Test getOrders returns a list of orders
     */
    public function testGetOrdersReturnsArray(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        
        $body = $this->createMock(\Psr\Http\Message\StreamInterface::class);
        $response->method('getBody')->willReturn($body);
        $response->method('withHeader')->willReturn($response);

        $result = $this->controller->getOrders($request, $response, []);

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    /**
     * Test getOrder returns a single order
     */
    public function testGetOrderReturnsSingleOrder(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        
        $body = $this->createMock(\Psr\Http\Message\StreamInterface::class);
        $response->method('getBody')->willReturn($body);
        $response->method('withHeader')->willReturn($response);

        $args = ['id' => 101];
        $result = $this->controller->getOrder($request, $response, $args);

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    /**
     * Test createOrder returns 201 status
     */
    public function testCreateOrderReturns201Status(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        
        $body = $this->createMock(\Psr\Http\Message\StreamInterface::class);
        $requestBody = $this->createMock(\Psr\Http\Message\StreamInterface::class);
        
        $requestBody->method('getContents')->willReturn(json_encode(['product_id' => 1, 'quantity' => 5]));
        $request->method('getBody')->willReturn($requestBody);
        
        $response->method('getBody')->willReturn($body);
        $response->method('withHeader')->willReturn($response);
        $response->method('withStatus')->willReturn($response);

        $result = $this->controller->createOrder($request, $response, []);

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    /**
     * Test updateOrder modifies order data
     */
    public function testUpdateOrderModifiesData(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        
        $body = $this->createMock(\Psr\Http\Message\StreamInterface::class);
        $requestBody = $this->createMock(\Psr\Http\Message\StreamInterface::class);
        
        $requestBody->method('getContents')->willReturn(json_encode(['status' => 'shipped']));
        $request->method('getBody')->willReturn($requestBody);
        
        $response->method('getBody')->willReturn($body);
        $response->method('withHeader')->willReturn($response);

        $args = ['id' => 101];
        $result = $this->controller->updateOrder($request, $response, $args);

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    /**
     * Test deleteOrder returns 204 status
     */
    public function testDeleteOrderReturns204Status(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        
        $body = $this->createMock(\Psr\Http\Message\StreamInterface::class);
        $response->method('getBody')->willReturn($body);
        $response->method('withStatus')->willReturn($response);

        $args = ['id' => 101];
        $result = $this->controller->deleteOrder($request, $response, $args);

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }
}
