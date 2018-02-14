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
    public function client__index__happyPath() {
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
    public function client__show__happyPath() {
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
    public function client__show__404_for_invalid_id() {
        $client = $this->createDemoClient(['name' => 'DemoClient3']);
        $nonExistingId = ++ $client->id;
        $randomString = $client->name;

        $response = $this->callGet('/api/clients/' . $nonExistingId);
        $response->assertStatus(404);

        $response = $this->callGet('/api/clients/' . $randomString);
        $response->assertStatus(404);
    }

    /** @test */
    public function client__store__happyPath() {
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
    public function client__update__happyPath() {
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
    public function client__update__500_on_repeat_client_code() {
        /** @var TYPE_NAME $client1 */
        $client1 = $this->createDemoClient(['code' => 'FavoriteClient']);
        $client2 = $this->createDemoClient(['code' => 'DemoClient']);
        $this->assertDatabaseHas('clients', $client1->toArray());
        $this->assertDatabaseHas('clients', $client2->toArray());

        $client1->code = $client2->code;

        $response = $this->callPatch('/api/clients/' . $client1->id, $client1->toArray());
        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['code']
            ])
            ->assertJsonFragment([
                'code' => [
                    'The code has already been taken.',
                ],
            ]);
    }

    /** @test */
    public function client__delete__happyPath() {
        $client = $this->createDemoClient(['name' => 'TerribleClient']);
        $this->assertDatabaseHas('clients', $client->toArray());

        $response = $this->callDelete('/api/clients/' . $client->id);
        $response->assertStatus(204);
    }

    /** @test */
    public function client__delete__404_for_invalid_id() {
        $client = $this->createDemoClient(['name' => 'DemoClient3']);
        $nonExistingId = ++ $client->id;
        $randomString = $client->name;

        $response = $this->callDelete('/api/clients/' . $nonExistingId);
        $response->assertStatus(404);

        $response = $this->callDelete('/api/clients/' . $randomString);
        $response->assertStatus(404);
    }
}
