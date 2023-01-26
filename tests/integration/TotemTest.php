<?php

declare(strict_types=1);

namespace Tests\integration;

class TotemTest extends BaseTestCase
{
    private static int $id;

    /**
     * Test Get All Totems.
     */
    public function testGetTotems(): void
    {
        $response = $this->runApp('GET', '/api/v1/totems');

        $result = (string) $response->getBody();
        $value = json_encode(json_decode($result));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);
        $this->assertStringContainsString('id', $result);
        $this->assertStringContainsString('name', $result);
        $this->assertStringContainsString('description', $result);
        $this->assertMatchesRegularExpression('{"code":200,"status":"success"}', (string) $value);
        $this->assertMatchesRegularExpression('{"name":"[A-Za-z0-9_. ]+","description":"[A-Za-z0-9_. ]+"}', (string) $value);
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test Get Totems By Page.
     */
    public function testGetTotemsByPage(): void
    {
        $response = $this->runApp('GET', '/api/v1/totems?page=1&perPage=3');

        $result = (string) $response->getBody();
        $value = (string) json_encode(json_decode($result));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);
        $this->assertStringContainsString('id', $result);
        $this->assertStringContainsString('name', $result);
        $this->assertStringContainsString('description', $result);
        $this->assertStringContainsString('pagination', $result);
        $this->assertMatchesRegularExpression('{"code":200,"status":"success"}', $value);
        $this->assertMatchesRegularExpression('{"name":"[A-Za-z0-9_. ]+","description":"[A-Za-z0-9_. ]+"}', $value);
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test Get One Totem.
     */
    public function testGetTotem(): void
    {
        $response = $this->runApp('GET', '/api/v1/totems/1');

        $result = (string) $response->getBody();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);
        $this->assertStringContainsString('id', $result);
        $this->assertStringContainsString('name', $result);
        $this->assertStringContainsString('description', $result);
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test Get Totem Not Found.
     */
    public function testGetTotemNotFound(): void
    {
        $response = $this->runApp('GET', '/api/v1/totems/123456789');

        $result = (string) $response->getBody();

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringNotContainsString('id', $result);
        $this->assertStringNotContainsString('description', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Create Totem.
     */
    public function testCreateTotem(): void
    {
        $response = $this->runApp(
            'POST',
            '/api/v1/totems',
            ['name' => 'My Test Totem', 'description' => 'New Totem...']
        );

        $result = (string) $response->getBody();

        self::$id = json_decode($result)->message->id;

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);
        $this->assertStringContainsString('id', $result);
        $this->assertStringContainsString('name', $result);
        $this->assertStringContainsString('description', $result);
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test Get Totem Created.
     */
    public function testGetTotemCreated(): void
    {
        $response = $this->runApp('GET', '/api/v1/totems/' . self::$id);

        $result = (string) $response->getBody();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);
        $this->assertStringContainsString('id', $result);
        $this->assertStringContainsString('name', $result);
        $this->assertStringContainsString('description', $result);
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test Create Totem Without Name.
     */
    public function testCreateTotemWithoutName(): void
    {
        $response = $this->runApp('POST', '/api/v1/totems');

        $result = (string) $response->getBody();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringNotContainsString('description', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Create Totem With Invalid Name.
     */
    public function testCreateTotemWithInvalidName(): void
    {
        $response = $this->runApp(
            'POST',
            '/api/v1/totems',
            ['name' => '']
        );

        $result = (string) $response->getBody();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringNotContainsString('description', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Update Totem.
     */
    public function testUpdateTotem(): void
    {
        $response = $this->runApp(
            'PUT',
            '/api/v1/totems/' . self::$id,
            ['name' => 'Victor Totems', 'description' => 'Pep.']
        );

        $result = (string) $response->getBody();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);
        $this->assertStringContainsString('id', $result);
        $this->assertStringContainsString('name', $result);
        $this->assertStringContainsString('description', $result);
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test Update Totem Without Send Data.
     */
    public function testUpdateTotemWithOutSendData(): void
    {
        $response = $this->runApp('PUT', '/api/v1/totems/' . self::$id);

        $result = (string) $response->getBody();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);
        $this->assertStringContainsString('id', $result);
        $this->assertStringContainsString('name', $result);
        $this->assertStringContainsString('description', $result);
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test Update Totem Not Found.
     */
    public function testUpdateTotemNotFound(): void
    {
        $response = $this->runApp(
            'PUT',
            '/api/v1/totems/123456789',
            ['name' => 'Totem']
        );

        $result = (string) $response->getBody();

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringNotContainsString('id', $result);
        $this->assertStringNotContainsString('name', $result);
        $this->assertStringNotContainsString('description', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Delete Totem.
     */
    public function testDeleteTotem(): void
    {
        $response = $this->runApp('DELETE', '/api/v1/totems/' . self::$id);

        $result = (string) $response->getBody();

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test Delete Totem Not Found.
     */
    public function testDeleteTotemNotFound(): void
    {
        $response = $this->runApp('DELETE', '/api/v1/totems/123456789');

        $result = (string) $response->getBody();

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringNotContainsString('id', $result);
        $this->assertStringNotContainsString('name', $result);
        $this->assertStringNotContainsString('description', $result);
        $this->assertStringContainsString('error', $result);
    }
}
