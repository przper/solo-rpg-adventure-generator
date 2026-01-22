<?php

namespace App\Authentication\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Exception\ExpiredTokenException;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authenticator\JWTAuthenticator as BaseJWTAuthenticator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class JWTAuthenticator extends BaseJWTAuthenticator
{
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        if ($exception instanceof ExpiredTokenException) {
            $response = new RedirectResponse('/login');
            $response->headers->clearCookie('BEARER');
            return $response;
        }

        return parent::onAuthenticationFailure($request, $exception);
    }
}
