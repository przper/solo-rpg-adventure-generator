<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;

trait SessionHelper
{
    /** @link https://github.com/symfony/symfony/discussions/46961#discussioncomment-4573371 */
    public function createSession(KernelBrowser $client): Session
    {
        $session = $this->getContainer()->get('session.factory')->createSession();

        $session->start();
        $session->save();

        $sessionCookie = new Cookie(
            name: $session->getName(),
            value: $session->getId(),
            domain: 'localhost',
        );
        $client->getCookieJar()->set($sessionCookie);

        return $session;
    }
}
