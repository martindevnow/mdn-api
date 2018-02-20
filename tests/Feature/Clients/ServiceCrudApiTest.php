<?php

namespace Tests\Feature\Projects;

use Martin\Clients\Service;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServiceCrudApiTest extends TestCase
{
    use RefreshDatabase;

    private function makeDemoService(array $overrides = []): Service {
        return factory(Service::class)->make($overrides);
    }

    private function createDemoService(array $overrides = []): Service {
        $service = $this->makeDemoService($overrides);
        $service->save();
        return $service->fresh();
    }

    private function getDataArrayWithoutMutations(Service $service): array {
        $serviceDataArray = $service->toArray();
        unset($serviceDataArray['activated_at']);
        unset($serviceDataArray['deactivated_at']);
        unset($serviceDataArray['valid_from_date']);
        unset($serviceDataArray['valid_until_date']);
        $serviceDataArray['rate'] = $serviceDataArray['rate'] * 100;
        return $serviceDataArray;
    }

    /** @test */
    public function service__index__happyPath() {
        $this->createDemoService(['description' => 'TestService']);

        $response = $this->callGet('/api/services');
        $response->assertStatus(200)
            ->assertJsonFragment([
                'description' => 'TestService'
            ])
            ->assertJsonStructure([
                [ // array of objects
                    'project_id',
                    'description',
                    'rate',
                    'billing_frequency',
                    'activated_at',
                    'deactivated_at',
                    'valid_from_date',
                    'valid_until_date',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]
            ]);
    }

    /** @test */
    public function service__show__happyPath() {
        $service = $this->createDemoService(['description' => 'DemoService']);

        $response = $this->callGet('/api/services/' . $service->id);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $service->id
            ])
            ->assertJsonStructure([
                'id',
                'project_id',
                'description',
                'rate',
                'billing_frequency',
                'activated_at',
                'deactivated_at',
                'valid_from_date',
                'valid_until_date',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
    }

    /** @test */
    public function service__show__404_for_invalid_id() {
        $service = $this->createDemoService(['description' => 'DemoService3']);
        $nonExistingId = ++ $service->id;
        $randomString = $service->description;

        $response = $this->callGet('/api/services/' . $nonExistingId);
        $response->assertStatus(404);

        $response = $this->callGet('/api/services/' . $randomString);
        $response->assertStatus(404);
    }

    /** @test */
    public function service__store__happyPath() {
        $serviceData = $this->makeDemoService(['description' => 'ValidServiceName'])->toArray();

        $response = $this->callPost('/api/services', $serviceData);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'description' => 'ValidServiceName',
            ])
            ->assertJsonStructure([
                'id',
                'project_id',
                'description',
                'rate',
                'billing_frequency',
                'activated_at',
                'deactivated_at',
                'valid_from_date',
                'valid_until_date',
                'created_at',
                'updated_at',
            ]);

        $id = $response->json('id');
        $response->assertHeader('Location', '/api/services/' . $id);
    }

    /** @test */
    public function service__update__happyPath() {
        $service = $this->createDemoService(['description' => 'DemoService']);
        $serviceData = $this->getDataArrayWithoutMutations($service);

        $this->assertDatabaseHas('services', $serviceData);
        $service->description = 'RedescriptiondService';

        $response = $this->callPatch('/api/services/' . $service->id, $service->toArray());
        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'project_id',
                'description',
                'rate',
                'billing_frequency',
                'activated_at',
                'deactivated_at',
                'valid_from_date',
                'valid_until_date',
                'created_at',
                'updated_at',
            ])
            ->assertJsonFragment([
                'description'  => 'RedescriptiondService',
            ]);
    }

    /** @test */
    public function service__delete__happyPath() {
        $service = $this->createDemoService(['description' => 'TerribleService']);
        $serviceData = $this->getDataArrayWithoutMutations($service);
        $this->assertDatabaseHas('services', $serviceData);

        $response = $this->callDelete('/api/services/' . $service->id);
        $response->assertStatus(204);
    }

    /** @test */
    public function service__delete__404_for_invalid_id() {
        $service = $this->createDemoService(['description' => 'DemoService3']);
        $nonExistingId = ++ $service->id;
        $randomString = $service->description;

        $response = $this->callDelete('/api/services/' . $nonExistingId);
        $response->assertStatus(404);

        $response = $this->callDelete('/api/services/' . $randomString);
        $response->assertStatus(404);
    }
}
