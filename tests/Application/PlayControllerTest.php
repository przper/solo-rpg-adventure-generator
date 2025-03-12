<?php

namespace App\Tests\Application;

use App\Service\Game\Game;
use App\Tests\SessionHelper;
use App\Service\Game\GameFactory;
use App\Service\Game\PlayerPosition;
use App\Service\Map\Railroad\RailroadMapBuilder;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PlayControllerTest extends WebTestCase
{
    use SessionHelper;

    /** @test */
    public function new_game()
    {
        $client = static::createClient();
        $session = $this->createSession($client);

        $client->request('GET', '/play/new');

        $this->assertResponseIsSuccessful();

        $client->request('GET', '/play/new?type=railroad');
        $this->assertResponseRedirects('/play');
        $client->followRedirect();

        $this->assertBrowserHasCookie($session->getName());
    }

    /** @test */
    public function play_redirect_if_no_game_in_session()
    {
        $client = static::createClient();

        $client->request('GET', '/play');

        $this->assertResponseRedirects('/play/new');
    }

    /** @test */
    public function play_route_loads_game_from_session()
    {
        $client = static::createClient();
        $session = $this->createSession($client);

        $game = self::getContainer()
            ->get(GameFactory::class)
            ->setMapBuilder(
                self::getContainer()
                    ->get(RailroadMapBuilder::class)
                    ->setMaxRoomsCount(1)
            )
            ->create();

        $session->set('game', $game);
        $session->save();

        $client->request('GET', '/play');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Survive, brave adventurer...');
    }

    /** @test */
    public function player_can_be_moved()
    {
        $client = static::createClient();
        $session = $this->createSession($client);

        /** @var Game $game */
        $game = self::getContainer()
            ->get(GameFactory::class)
            ->setMapBuilder(
                self::getContainer()
                    ->get(RailroadMapBuilder::class)
                    ->setMaxRoomsCount(2)
            )
            ->create();
        $playerPositionBefore = $game->getPlayerPosition();

        $session->set('game', $game);
        $session->save();

        $client->request('GET', '/play?direction=east');

        $this->assertResponseIsSuccessful();

        /** @var PlayerPosition $playerPositionAfter */
        $playerPositionAfter = $session->get('game')->getPlayerPosition();
        $this->assertNotEquals($playerPositionBefore->getX(), $playerPositionAfter->getX());
    }
}
