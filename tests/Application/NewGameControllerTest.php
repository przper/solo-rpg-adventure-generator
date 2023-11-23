<?php


use App\Enum\DungeonLength;
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

        $client->request('GET', '/new');

        $this->assertResponseIsSuccessful();

        $client->submitForm('Submit', [
            'game[type]' => 'railroad',
            'game[length]' => DungeonLength::SHORT->value,
        ]);

        $this->assertResponseRedirects('/play');
        $this->assertBrowserHasCookie($session->getName());
    }
}
