<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Score;
use App\Models\Player;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlayerScoresTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create(['email' => 'admin@admin.com']);

        Sanctum::actingAs($user, [], 'web');

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_gets_player_scores(): void
    {
        $player = Player::factory()->create();
        $scores = Score::factory()
            ->count(2)
            ->create([
                'player_id' => $player->id,
            ]);

        $response = $this->getJson(route('api.players.scores.index', $player));

        $response->assertOk()->assertSee($scores[0]->mode);
    }

    /**
     * @test
     */
    public function it_stores_the_player_scores(): void
    {
        $player = Player::factory()->create();
        $data = Score::factory()
            ->make([
                'player_id' => $player->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.players.scores.store', $player),
            $data
        );

        $this->assertDatabaseHas('scores', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $score = Score::latest('id')->first();

        $this->assertEquals($player->id, $score->player_id);
    }
}
