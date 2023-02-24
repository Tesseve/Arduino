<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Player;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlayerControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(
            User::factory()->create(['email' => 'admin@admin.com'])
        );

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_displays_index_view_with_players(): void
    {
        $players = Player::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('players.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.players.index')
            ->assertViewHas('players');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_player(): void
    {
        $response = $this->get(route('players.create'));

        $response->assertOk()->assertViewIs('app.players.create');
    }

    /**
     * @test
     */
    public function it_stores_the_player(): void
    {
        $data = Player::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('players.store'), $data);

        $this->assertDatabaseHas('players', $data);

        $player = Player::latest('id')->first();

        $response->assertRedirect(route('players.edit', $player));
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_player(): void
    {
        $player = Player::factory()->create();

        $response = $this->get(route('players.show', $player));

        $response
            ->assertOk()
            ->assertViewIs('app.players.show')
            ->assertViewHas('player');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_player(): void
    {
        $player = Player::factory()->create();

        $response = $this->get(route('players.edit', $player));

        $response
            ->assertOk()
            ->assertViewIs('app.players.edit')
            ->assertViewHas('player');
    }

    /**
     * @test
     */
    public function it_updates_the_player(): void
    {
        $player = Player::factory()->create();

        $data = [
            'name' => $this->faker->name(),
        ];

        $response = $this->put(route('players.update', $player), $data);

        $data['id'] = $player->id;

        $this->assertDatabaseHas('players', $data);

        $response->assertRedirect(route('players.edit', $player));
    }

    /**
     * @test
     */
    public function it_deletes_the_player(): void
    {
        $player = Player::factory()->create();

        $response = $this->delete(route('players.destroy', $player));

        $response->assertRedirect(route('players.index'));

        $this->assertModelMissing($player);
    }
}
