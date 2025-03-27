<?php

namespace App\Tests\Application;

use App\Tests\SessionHelper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NewGameControllerTest extends WebTestCase
{
    use SessionHelper;

    /** @test */
    public function new_game()
    {
        $client = static::createClient();
        $session = $this->createSession($client);

        $client->request('GET', '/play/new');

        $this->assertResponseIsSuccessful();

        $client->submitForm('Begin adventure!', [
            'new_game[length]' => 'short',
            'new_game[mapType]' => 'Railroad',
            'new_game[system]' => 'DnD_5E',
        ]);

        $this->assertResponseRedirects('/play');
        $this->assertBrowserHasCookie($session->getName());
    }
}
