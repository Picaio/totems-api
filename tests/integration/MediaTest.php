<?php

declare(strict_types=1);

namespace Tests\integration;

class MediaTest extends BaseTestCase
{
    private static int $id;

    /**
     * Test Get All Medias.
     */
    public function testGetMedias(): void
    {
        $response = $this->runApp('GET', '/api/v1/medias');

        $result = (string) $response->getBody();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);
        $this->assertStringContainsString('pagination', $result);
        $this->assertStringContainsString('data', $result);
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test Get Medias By Page.
     */
    public function testGetMediasByPage(): void
    {
        $response = $this->runApp('GET', '/api/v1/medias?page=1&perPage=3');

        $result = (string) $response->getBody();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);
        $this->assertStringContainsString('pagination', $result);
        $this->assertStringContainsString('data', $result);
        $this->assertStringContainsString('status', $result);
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test Get One Media.
     */
    public function testGetMedia(): void
    {
        $response = $this->runApp('GET', '/api/v1/medias/1');

        $result = (string) $response->getBody();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);
        $this->assertStringContainsString('id', $result);
        $this->assertStringContainsString('name', $result);
        $this->assertStringContainsString('status', $result);
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test Get Media Not Found.
     */
    public function testGetMediaNotFound(): void
    {
        $response = $this->runApp('GET', '/api/v1/medias/123456789');

        $result = (string) $response->getBody();

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringNotContainsString('id', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Create Media.
     */
    public function testCreateMedia(): void
    {
        $response = $this->runApp(
            'POST',
            '/api/v1/medias',
            ['name' => 'New Media', 'description' => 'My Desc.']
        );

        $result = (string) $response->getBody();

        self::$id = json_decode($result)->message->id;

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);
        $this->assertStringContainsString('id', $result);
        $this->assertStringContainsString('name', $result);
        $this->assertStringContainsString('status', $result);
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test Get Media Created.
     */
    public function testGetMediaCreated(): void
    {
        $response = $this->runApp('GET', '/api/v1/medias/' . self::$id);

        $result = (string) $response->getBody();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);
        $this->assertStringContainsString('id', $result);
        $this->assertStringContainsString('name', $result);
        $this->assertStringContainsString('status', $result);
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test Create Media Without Name.
     */
    public function testCreateMediaWithOutMediaName(): void
    {
        $response = $this->runApp('POST', '/api/v1/medias');

        $result = (string) $response->getBody();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringNotContainsString('id', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Create Media With Invalid MediaName.
     */
    public function testCreateMediaWithInvalidMediaName(): void
    {
        $response = $this->runApp(
            'POST',
            '/api/v1/medias',
            ['name' => '', 'status' => 1]
        );

        $result = (string) $response->getBody();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Create Media With Invalid Status.
     */
    public function testCreateMediaWithInvalidStatus(): void
    {
        $response = $this->runApp(
            'POST',
            '/api/v1/medias',
            ['name' => 'ToDo', 'status' => 123]
        );

        $result = (string) $response->getBody();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Create Media Without Authorization Bearer JWT.
     */
    public function testCreateMediaWithoutBearerJWT(): void
    {
        $auth = self::$jwt;
        self::$jwt = '';
        $response = $this->runApp(
            'POST',
            '/api/v1/medias',
            ['name' => 'my media', 'status' => 0]
        );
        self::$jwt = $auth;

        $result = (string) $response->getBody();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Create Media With Invalid JWT.
     */
    public function testCreateMediaWithInvalidJWT(): void
    {
        $auth = self::$jwt;
        self::$jwt = 'invalidToken';
        $response = $this->runApp(
            'POST',
            '/api/v1/medias',
            ['name' => 'my media', 'status' => 0]
        );
        self::$jwt = $auth;

        $result = (string) $response->getBody();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Create Media With Forbidden JWT.
     */
    public function testCreateMediaWithForbiddenJWT(): void
    {
        $auth = self::$jwt;
        self::$jwt = 'Bearer eyJ0eXAiOiJK1NiJ9.eyJzdWIiOiI4Ii';
        $response = $this->runApp(
            'POST',
            '/api/v1/medias',
            ['name' => 'my media', 'status' => 0]
        );
        self::$jwt = $auth;

        $result = (string) $response->getBody();

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Update Media.
     */
    public function testUpdateMedia(): void
    {
        $response = $this->runApp(
            'PUT',
            '/api/v1/medias/' . self::$id,
            ['name' => 'Update Media', 'description' => 'Update Desc', 'status' => 1]
        );

        $result = (string) $response->getBody();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);
        $this->assertStringContainsString('id', $result);
        $this->assertStringContainsString('name', $result);
        $this->assertStringContainsString('status', $result);
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test Update Media Without Send Data.
     */
    public function testUpdateMediaWithOutSendData(): void
    {
        $response = $this->runApp('PUT', '/api/v1/medias/' . self::$id);

        $result = (string) $response->getBody();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringNotContainsString('id', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Update Media Not Found.
     */
    public function testUpdateMediaNotFound(): void
    {
        $response = $this->runApp(
            'PUT',
            '/api/v1/medias/123456789',
            ['name' => 'Media']
        );

        $result = (string) $response->getBody();

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringNotContainsString('id', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Update Media of Another User.
     */
    public function testUpdateMediaOfAnotherUser(): void
    {
        $response = $this->runApp(
            'PUT',
            '/api/v1/medias/6',
            ['name' => 'Media']
        );

        $result = (string) $response->getBody();

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringNotContainsString('id', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Delete Media.
     */
    public function testDeleteMedia(): void
    {
        $response = $this->runApp('DELETE', '/api/v1/medias/' . self::$id);

        $result = (string) $response->getBody();

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test Delete Media Not Found.
     */
    public function testDeleteMediaNotFound(): void
    {
        $response = $this->runApp('DELETE', '/api/v1/medias/123456789');

        $result = (string) $response->getBody();

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringNotContainsString('id', $result);
        $this->assertStringContainsString('error', $result);
    }
}
