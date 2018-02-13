<?php

namespace Tests\Feature\Clients;

use Martin\Clients\Client;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClientCrudApiTest extends TestCase
{
    use RefreshDatabase;

    private function makeDemoClient($overrides = []) {
        return factory(Client::class)->make($overrides);
    }

    private function createDemoClient($overrides = []) {
        $client = $this->makeDemoClient($overrides);
        $client->save();
        return $client->fresh();
    }


    /** @test */
    public function it_lists_clients() {
        $this->createDemoClient(['name' => 'TestCompany']);

        $response = $this->callGet('/api/clients');
        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'TestCompany'
            ])
            ->assertJsonStructure([
                [ // array of objects
                    'name',
                    'code',
                    'description',
                    'status',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]
            ]);
    }

    /** @test */
    public function it_shows_a_client() {
        $client = $this->createDemoClient(['name' => 'DemoClient']);

        $response = $this->callGet('/api/clients/' . $client->id);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $client->id
            ])
            ->assertJsonStructure([
                'id',
                'name',
                'code',
                'description',
                'status',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
    }

    /** @test */
    public function it_shows_404_when_client_id_not_found() {
        $client = $this->createDemoClient(['name' => 'DemoClient3']);
        $nonExistingId = ++ $client->id;

        $response = $this->callGet('/api/clients/' . $nonExistingId);
        $response->assertStatus(404);
    }

    /** @test */
    public function it_stores_a_client() {
        $clientData = $this->makeDemoClient(['name' => 'ValidClientName'])->toArray();

        $response = $this->callPost('/api/clients', $clientData);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'ValidClientName',
            ])
            ->assertJsonStructure([
                'id',
                'name',
                'code',
                'description',
                'status',
                'created_at',
                'updated_at',
            ]);

        $id = $response->json('id');
        $response->assertHeader('Location', '/api/clients/' . $id);
    }

    /** @test */
    public function it_updates_a_client() {
        $client = $this->createDemoClient(['name' => 'DemoClient']);
        $this->assertDatabaseHas('clients', $client->toArray());

        $client->name = 'RenamedClient';

        $response = $this->callPatch('/api/clients/' . $client->id, $client->toArray());
        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'code',
                'description',
                'status',
                'created_at',
                'updated_at',
            ])
            ->assertJsonFragment([
                'name'  => 'RenamedClient',
            ]);
    }

    /** @test */
    public function it_deletes_a_client() {
        $client = $this->createDemoClient(['name' => 'TerribleClient']);
        $this->assertDatabaseHas('clients', $client->toArray());

        $response = $this->callDelete('/api/clients/' . $client->id);
        $response->assertStatus(204);
    }
}
