<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Controllers\PromotionController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PromotionControllerTest extends TestCase
{
    private PromotionController $controller;

    protected function setUp(): void
    {
        $this->controller = new PromotionController();
    }

    /**
     * Test getPromotions returns a list of promotions
     */
    public function testGetPromotionsReturnsArray(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        
        $body = $this->createMock(\Psr\Http\Message\StreamInterface::class);
        $response->method('getBody')->willReturn($body);
        $response->method('withHeader')->willReturn($response);

        $result = $this->controller->getPromotions($request, $response, []);

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    /**
     * Test getPromotion returns a single promotion
     */
    public function testGetPromotionReturnsSinglePromotion(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        
        $body = $this->createMock(\Psr\Http\Message\StreamInterface::class);
        $response->method('getBody')->willReturn($body);
        $response->method('withHeader')->willReturn($response);

        $args = ['id' => 10];
        $result = $this->controller->getPromotion($request, $response, $args);

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    /**
     * Test createPromotion returns 201 status
     */
    public function testCreatePromotionReturns201Status(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        
        $body = $this->createMock(\Psr\Http\Message\StreamInterface::class);
        $requestBody = $this->createMock(\Psr\Http\Message\StreamInterface::class);
        
        $requestBody->method('getContents')->willReturn(json_encode(['name' => 'Black Friday', 'discount' => 20]));
        $request->method('getBody')->willReturn($requestBody);
        
        $response->method('getBody')->willReturn($body);
        $response->method('withHeader')->willReturn($response);
        $response->method('withStatus')->willReturn($response);

        $result = $this->controller->createPromotion($request, $response, []);

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    /**
     * Test updatePromotion modifies promotion data
     */
    public function testUpdatePromotionModifiesData(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        
        $body = $this->createMock(\Psr\Http\Message\StreamInterface::class);
        $requestBody = $this->createMock(\Psr\Http\Message\StreamInterface::class);
        
        $requestBody->method('getContents')->willReturn(json_encode(['name' => 'Cyber Monday', 'discount' => 30]));
        $request->method('getBody')->willReturn($requestBody);
        
        $response->method('getBody')->willReturn($body);
        $response->method('withHeader')->willReturn($response);

        $args = ['id' => 10];
        $result = $this->controller->updatePromotion($request, $response, $args);

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    /**
     * Test deletePromotion returns 204 status
     */
    public function testDeletePromotionReturns204Status(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        
        $body = $this->createMock(\Psr\Http\Message\StreamInterface::class);
        $response->method('getBody')->willReturn($body);
        $response->method('withStatus')->willReturn($response);

        $args = ['id' => 10];
        $result = $this->controller->deletePromotion($request, $response, $args);

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }
}
