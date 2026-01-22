<?php

namespace App\Tests\Unit\Authentication;

use App\Authentication\Security\JWTAuthenticator;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\ExpiredTokenException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class JWTAuthenticatorTest extends TestCase
{
    private JWTAuthenticator $authenticator;

    protected function setUp(): void
    {
        // Create a mock of the parent class and partially mock necessary methods
        $this->authenticator = $this->getMockBuilder(JWTAuthenticator::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['onAuthenticationFailure'])
            ->getMock();
    }

    public function testOnAuthenticationFailureWithExpiredToken(): void
    {
        $request = new Request();
        $exception = new ExpiredTokenException();

        $response = $this->authenticator->onAuthenticationFailure($request, $exception);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/login', $response->getTargetUrl());

        // Verify cookie is cleared
        $cookies = $response->headers->getCookies();
        $this->assertCount(1, $cookies);
        $this->assertEquals('BEARER', $cookies[0]->getName());
        $this->assertEmpty($cookies[0]->getValue());
    }

    public function testOnAuthenticationFailureWithOtherException(): void
    {
        $request = new Request();
        $exception = $this->createMock(AuthenticationException::class);

        // Configure the parent method to return a specific response
        $expectedResponse = new Response('Authentication failed');
        $this->authenticator->expects($this->once())
            ->method('parent::onAuthenticationFailure')
            ->with($request, $exception)
            ->willReturn($expectedResponse);

        $response = $this->authenticator->onAuthenticationFailure($request, $exception);

        $this->assertSame($expectedResponse, $response);
    }
}
