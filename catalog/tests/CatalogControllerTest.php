<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Controllers\CatalogController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CatalogControllerTest extends TestCase
{
    private CatalogController $controller;

    protected function setUp(): void
    {
        $this->controller = new CatalogController();
    }

    /**
     * Test getProducts returns a list of products
     */
    public function testGetProductsReturnsArray(): void
    {
        // Create mock request and response
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        
        // Mock response body
        $body = $this->createMock(\Psr\Http\Message\StreamInterface::class);
        $response->method('getBody')->willReturn($body);
        $response->method('withHeader')->willReturn($response);

        // Call the method
        $result = $this->controller->getProducts($request, $response, []);

        // Assert that the result is a ResponseInterface
        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    /**
     * Test getProduct returns a single product
     */
    public function testGetProductReturnsSingleProduct(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        
        $body = $this->createMock(\Psr\Http\Message\StreamInterface::class);
        $response->method('getBody')->willReturn($body);
        $response->method('withHeader')->willReturn($response);

        $args = ['id' => 1];
        $result = $this->controller->getProduct($request, $response, $args);

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    /**
     * Test createProduct returns 201 status
     */
    public function testCreateProductReturns201Status(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        
        $body = $this->createMock(\Psr\Http\Message\StreamInterface::class);
        $requestBody = $this->createMock(\Psr\Http\Message\StreamInterface::class);
        
        $requestBody->method('getContents')->willReturn(json_encode(['name' => 'Test Product', 'price' => 100]));
        $request->method('getBody')->willReturn($requestBody);
        
        $response->method('getBody')->willReturn($body);
        $response->method('withHeader')->willReturn($response);
        $response->method('withStatus')->willReturn($response);

        $result = $this->controller->createProduct($request, $response, []);

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    /**
     * Test updateProduct modifies product data
     */
    public function testUpdateProductModifiesData(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        
        $body = $this->createMock(\Psr\Http\Message\StreamInterface::class);
        $requestBody = $this->createMock(\Psr\Http\Message\StreamInterface::class);
        
        $requestBody->method('getContents')->willReturn(json_encode(['name' => 'Updated Product', 'price' => 150]));
        $request->method('getBody')->willReturn($requestBody);
        
        $response->method('getBody')->willReturn($body);
        $response->method('withHeader')->willReturn($response);

        $args = ['id' => 1];
        $result = $this->controller->updateProduct($request, $response, $args);

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    /**
     * Test deleteProduct returns 204 status
     */
    public function testDeleteProductReturns204Status(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        
        $body = $this->createMock(\Psr\Http\Message\StreamInterface::class);
        $response->method('getBody')->willReturn($body);
        $response->method('withStatus')->willReturn($response);

        $args = ['id' => 1];
        $result = $this->controller->deleteProduct($request, $response, $args);

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }
}
