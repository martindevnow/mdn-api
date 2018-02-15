<?php

namespace Tests\Feature\Projects;

use Martin\Projects\Server;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServerCrudApiTest extends TestCase
{
    use RefreshDatabase;

    private function makeDemoServer(array $overrides = []): Server {
        return factory(Server::class)->make($overrides);
    }

    private function createDemoServer(array $overrides = []): Server {
        $server = $this->makeDemoServer($overrides);
        $server->save();
        return $server->fresh();
    }

    private function getDataArrayWithoutMutations(Server $server): array {
        $serverDataArray = $server->toArray();
        unset($serverDataArray['purchased_at']);
        unset($serverDataArray['expires_at']);
        return $serverDataArray;
    }

    /** @test */
    public function server__index__happyPath() {
        $this->createDemoServer(['name' => 'TestServer']);

        $response = $this->callGet('/api/servers');
        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'TestServer'
            ])
            ->assertJsonStructure([
                [ // array of objects
                    'name',
                    'host',
                    'os',
                    'username',
                    'email',
                    'purchased_at',
                    'expires_at',
                    'cost_monthly',
                    'currency',
                    'billing_cycle',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]
            ]);
    }

    /** @test */
    public function server__show__happyPath() {
        $server = $this->createDemoServer(['name' => 'DemoServer']);

        $response = $this->callGet('/api/servers/' . $server->id);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $server->id
            ])
            ->assertJsonStructure([
                'id',
                'name',
                'host',
                'os',
                'username',
                'email',
                'purchased_at',
                'expires_at',
                'cost_monthly',
                'currency',
                'billing_cycle',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
    }

    /** @test */
    public function server__show__404_for_invalid_id() {
        $server = $this->createDemoServer(['name' => 'DemoServer3']);
        $nonExistingId = ++ $server->id;
        $randomString = $server->name;

        $response = $this->callGet('/api/servers/' . $nonExistingId);
        $response->assertStatus(404);

        $response = $this->callGet('/api/servers/' . $randomString);
        $response->assertStatus(404);
    }

    /** @test */
    public function server__store__happyPath() {
        $serverData = $this->makeDemoServer(['name' => 'ValidServerName'])->toArray();

        $response = $this->callPost('/api/servers', $serverData);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'ValidServerName',
            ])
            ->assertJsonStructure([
                'id',
                'name',
                'host',
                'os',
                'username',
                'email',
                'purchased_at',
                'expires_at',
                'cost_monthly',
                'currency',
                'billing_cycle',
                'created_at',
                'updated_at',
            ]);

        $id = $response->json('id');
        $response->assertHeader('Location', '/api/servers/' . $id);
    }

    /** @test */
    public function server__update__happyPath() {
        $server = $this->createDemoServer(['name' => 'DemoServer']);
        $serverData = $this->getDataArrayWithoutMutations($server);

        $this->assertDatabaseHas('servers', $serverData);
        $server->name = 'RenamedServer';

        $response = $this->callPatch('/api/servers/' . $server->id, $server->toArray());
        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'host',
                'os',
                'username',
                'email',
                'purchased_at',
                'expires_at',
                'cost_monthly',
                'currency',
                'billing_cycle',
                'created_at',
                'updated_at',
            ])
            ->assertJsonFragment([
                'name'  => 'RenamedServer',
            ]);
    }

    /** @test */
    public function server__delete__happyPath() {
        $server = $this->createDemoServer(['name' => 'TerribleServer']);
        $serverData = $this->getDataArrayWithoutMutations($server);
        $this->assertDatabaseHas('servers', $serverData);

        $response = $this->callDelete('/api/servers/' . $server->id);
        $response->assertStatus(204);
    }

    /** @test */
    public function server__delete__404_for_invalid_id() {
        $server = $this->createDemoServer(['name' => 'DemoServer3']);
        $nonExistingId = ++ $server->id;
        $randomString = $server->name;

        $response = $this->callDelete('/api/servers/' . $nonExistingId);
        $response->assertStatus(404);

        $response = $this->callDelete('/api/servers/' . $randomString);
        $response->assertStatus(404);
    }
}
