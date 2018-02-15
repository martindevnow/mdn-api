<?php

namespace Tests\Feature\Projects;

use Martin\Projects\Domain;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DomainCrudApiTest extends TestCase
{
    use RefreshDatabase;

    private function makeDemoDomain(array $overrides = []): Domain {
        return factory(Domain::class)->make($overrides);
    }

    private function createDemoDomain(array $overrides = []): Domain {
        $domain = $this->makeDemoDomain($overrides);
        $domain->save();
        return $domain->fresh();
    }

    private function getDataArrayWithoutMutations(Domain $domain): array {
        $domainDataArray = $domain->toArray();
        unset($domainDataArray['originally_registered_at']);
        unset($domainDataArray['expires_at']);
        return $domainDataArray;
    }

    /** @test */
    public function domain__index__happyPath() {
        $this->createDemoDomain(['name' => 'TestDomain']);

        $response = $this->callGet('/api/domains');
        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'TestDomain'
            ])
            ->assertJsonStructure([
                [ // array of objects
                    'project_id',
                    'name',
                    'registrar',
                    'originally_registered_at',
                    'expires_at',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]
            ]);
    }

    /** @test */
    public function domain__show__happyPath() {
        $domain = $this->createDemoDomain(['name' => 'DemoDomain']);

        $response = $this->callGet('/api/domains/' . $domain->id);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $domain->id
            ])
            ->assertJsonStructure([
                'id',
                'project_id',
                'name',
                'registrar',
                'originally_registered_at',
                'expires_at',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
    }

    /** @test */
    public function domain__show__404_for_invalid_id() {
        $domain = $this->createDemoDomain(['name' => 'DemoDomain3']);
        $nonExistingId = ++ $domain->id;
        $randomString = $domain->name;

        $response = $this->callGet('/api/domains/' . $nonExistingId);
        $response->assertStatus(404);

        $response = $this->callGet('/api/domains/' . $randomString);
        $response->assertStatus(404);
    }

    /** @test */
    public function domain__store__happyPath() {
        $domainData = $this->makeDemoDomain(['name' => 'ValidDomainName'])->toArray();

        $response = $this->callPost('/api/domains', $domainData);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'ValidDomainName',
            ])
            ->assertJsonStructure([
                'id',
                'project_id',
                'name',
                'registrar',
                'originally_registered_at',
                'expires_at',
                'created_at',
                'updated_at',
            ]);

        $id = $response->json('id');
        $response->assertHeader('Location', '/api/domains/' . $id);
    }

    /** @test */
    public function domain__update__happyPath() {
        $domain = $this->createDemoDomain(['name' => 'DemoDomain']);
        $domainData = $this->getDataArrayWithoutMutations($domain);

        $this->assertDatabaseHas('domains', $domainData);
        $domain->name = 'RenamedDomain';

        $response = $this->callPatch('/api/domains/' . $domain->id, $domain->toArray());
        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'project_id',
                'name',
                'registrar',
                'originally_registered_at',
                'expires_at',
                'created_at',
                'updated_at',
            ])
            ->assertJsonFragment([
                'name'  => 'RenamedDomain',
            ]);
    }

    /** @test */
    public function domain__delete__happyPath() {
        $domain = $this->createDemoDomain(['name' => 'TerribleDomain']);
        $domainData = $this->getDataArrayWithoutMutations($domain);
        $this->assertDatabaseHas('domains', $domainData);

        $response = $this->callDelete('/api/domains/' . $domain->id);
        $response->assertStatus(204);
    }

    /** @test */
    public function domain__delete__404_for_invalid_id() {
        $domain = $this->createDemoDomain(['name' => 'DemoDomain3']);
        $nonExistingId = ++ $domain->id;
        $randomString = $domain->name;

        $response = $this->callDelete('/api/domains/' . $nonExistingId);
        $response->assertStatus(404);

        $response = $this->callDelete('/api/domains/' . $randomString);
        $response->assertStatus(404);
    }
}
