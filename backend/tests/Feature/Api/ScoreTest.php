<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Score;

use App\Models\Player;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScoreTest extends TestCase
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
    public function it_gets_scores_list(): void
    {
        $scores = Score::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.scores.index'));

        $response->assertOk()->assertSee($scores[0]->mode);
    }

    /**
     * @test
     */
    public function it_stores_the_score(): void
    {
        $data = Score::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.scores.store'), $data);

        $this->assertDatabaseHas('scores', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_score(): void
    {
        $score = Score::factory()->create();

        $player = Player::factory()->create();

        $data = [
            'value' => $this->faker->randomNumber,
            'mode' => $this->faker->text(255),
            'player_id' => $player->id,
        ];

        $response = $this->putJson(route('api.scores.update', $score), $data);

        $data['id'] = $score->id;

        $this->assertDatabaseHas('scores', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_score(): void
    {
        $score = Score::factory()->create();

        $response = $this->deleteJson(route('api.scores.destroy', $score));

        $this->assertModelMissing($score);

        $response->assertNoContent();
    }
}
