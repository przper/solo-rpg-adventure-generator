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

        $client->submitForm('Railroad', [
            'type' => 'railroad',
        ]);

        $this->assertResponseRedirects('/play');
        $this->assertBrowserHasCookie($session->getName());
    }
}
