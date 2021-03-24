<?php

namespace Garbetjie\Http\RequestLogging\Tests\Context;

use Garbetjie\Http\RequestLogging\Context\ResponseContext;
use Garbetjie\Http\RequestLogging\Tests\CreatesResponses;
use PHPUnit\Framework\TestCase;
use function base64_encode;
use function strlen;

class ResponseContextTest extends TestCase
{
    use CreatesResponses;

    protected function setUp(): void
    {
        header_remove();
    }

    public function testIsCallable()
    {
        $this->assertIsCallable(new ResponseContext());
    }

    public function testPsrResponse()
    {
        $context = (new ResponseContext())->__invoke($this->createPsrResponse());

        $this->assertContextHasRequiredKeys($context);
        $this->assertContextMatches($context, 'body', ['content-type', 'set-cookie']);
    }

    public function testLaravelResponse()
    {
        $context = (new ResponseContext())->__invoke($this->createLaravelResponse());

        $this->assertContextHasRequiredKeys($context);
        $this->assertContextMatches($context, 'body', ['content-type', 'set-cookie']);
    }

    public function testStringResponse()
    {
        header('Content-Type: application/json');
        header('Cow: moo');
        header('Set-Cookie: cookie=munch');

        $context = (new ResponseContext())->__invoke($this->createStringResponse());

        $this->assertContextHasRequiredKeys($context);
        $this->assertContextMatches($context, 'body', ['content-type', 'set-cookie', 'cow']);
    }

    protected function assertContextHasRequiredKeys(array $context)
    {
        foreach (['status_code', 'body', 'body_length', 'headers'] as $key) {
            $this->assertArrayHasKey($key, $context);
        }
    }

    protected function assertContextMatches(array $context, ?string $body, array $headers)
    {
        $this->assertEquals(base64_encode($body), $context['body']);
        $this->assertEquals(strlen($body), $context['body_length']);
        $this->assertIsArray($context['headers']);

        foreach ($headers as $header) {
            $this->assertArrayHasKey($header, $context['headers']);
        }
    }
}