<?php

namespace Tests\Feature;

use App\Facades\DaDataApi;
use App\Models\Counterparty;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CounterpartyTest extends TestCase
{
    use RefreshDatabase;

    public function testUnauthorizedUserCannotAccessCounterpartyEndpoints(): void
    {
        $response = $this->getJson('/api/counterparty');
        $response->assertUnauthorized();

        $response = $this->postJson('/api/counterparty', []);
        $response->assertUnauthorized();
    }

    public function testAuthenticatedUserCanGetTheirCounterparties(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Counterparty::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/counterparty');
        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['user_id', 'short_name', 'inn', 'ogrn', 'address']
                ],
                'links',
                'meta'
            ]);

        $response->assertJsonCount(3, 'data');
    }

    public function testAuthenticatedUserCanCreateACounterparty(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $mockData = [
            [
                'data' => [
                    'name' => [
                        'short_with_opf' => 'Test Company LLC'
                    ],
                    'inn' => '1234567890',
                    'ogrn' => '1234567890123',
                    'address' => [
                        'unrestricted_value' => 'Test Address'
                    ]
                ]
            ]
        ];

        DaDataApi::shouldReceive('findByInn')
            ->with('1234567890')
            ->andReturn($mockData);

        $response = $this->postJson('/api/counterparty', [
            'inn' => '1234567890',
        ]);

        $response->assertCreated()
            ->assertJsonStructure(['user_id', 'short_name', 'inn', 'ogrn', 'address']);

        $this->assertDatabaseHas('counterparties', [
            'user_id' => $user->id,
            'inn' => '1234567890',
            'short_name' => 'Test Company LLC',
            'ogrn' => '1234567890123',
            'address' => 'Test Address',
        ]);
    }

    public function testAuthenticatedUserCanOnlySeeTheirOwnCounterparties(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        Sanctum::actingAs($user1);

        Counterparty::factory()->count(2)->create(['user_id' => $user1->id]);
        Counterparty::factory()->count(3)->create(['user_id' => $user2->id]);

        $response = $this->getJson('/api/counterparty');
        $response->assertOk();
        $response->assertJsonCount(2, 'data');
    }

    public function testCounterpartyValidationRules(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/counterparty', [
            'inn' => '',
        ]);

        $response->assertUnprocessable()
            ->assertJsonStructure([
                'message',
                'errors' => ['inn']
            ]);
    }

    public function testCounterpartyCreationHandlesApiError(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        DaDataApi::shouldReceive('findByInn')
            ->with('9999999999')
            ->andThrow(new \Exception('DaData API error'));

        $response = $this->postJson('/api/counterparty', [
            'inn' => '9999999999',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['message']);
    }
}
