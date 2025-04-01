<?php

namespace App\Tests\Application;

use App\Core\Map\Map;
use App\Game\Game;
use App\Game\PlayerPosition;
use App\Tests\Fixtures\Dummies\DummyEncounters;
use App\Tests\Fixtures\Dummies\DummyFogOfWar;
use App\Tests\SessionHelper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PlayControllerTest extends WebTestCase
{
    use SessionHelper;

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

        $game = new Game(new Map(10, 10), new DummyFogOfWar(), new DummyEncounters());

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

        $game = new Game(new Map(10, 10), new DummyFogOfWar(), new DummyEncounters());
        $playerPositionBefore = $game->getPlayerPosition();

        $session->set('game', $game);
        $session->save();

        $client->request('GET', '/play?action=move&direction=east');

        $this->assertResponseIsSuccessful();

        /** @var PlayerPosition $playerPositionAfter */
        $playerPositionAfter = $session->get('game')->getPlayerPosition();
        $this->assertNotEquals($playerPositionBefore->getX(), $playerPositionAfter->getX());
    }
}
