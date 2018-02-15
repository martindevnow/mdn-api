<?php

namespace Tests\Feature\Works;

use Martin\Projects\Work;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WorkCrudApiTest extends TestCase
{
    use RefreshDatabase;

    private function makeDemoWork(array $overrides = []): Work {
        return factory(Work::class)->make($overrides);
    }

    private function createDemoWork(array $overrides = []): Work {
        $work = $this->makeDemoWork($overrides);
        $work->save();
        return $work->fresh();
    }

    private function getDataArrayWithoutMutations(Work $work): array {
        $workDataArray = $work->toArray();
        unset($workDataArray['performed_at']);
        return $workDataArray;
    }

    /** @test */
    public function work__index__happyPath() {
        $this->createDemoWork(['details' => 'TestWork']);

        $response = $this->callGet('/api/works');
        $response->assertStatus(200)
            ->assertJsonFragment([
                'details' => 'TestWork'
            ])
            ->assertJsonStructure([
                [ // array of objects
                    'project_id',
                    'details',
                    'duration',
                    'performed_at',
                    'billable',
                    'type',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]
            ]);
    }

    /** @test */
    public function work__show__happyPath() {
        $work = $this->createDemoWork(['details' => 'DemoWork']);

        $response = $this->callGet('/api/works/' . $work->id);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $work->id
            ])
            ->assertJsonStructure([
                'id',
                'project_id',
                'details',
                'duration',
                'performed_at',
                'billable',
                'type',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
    }

    /** @test */
    public function work__show__404_for_invalid_id() {
        $work = $this->createDemoWork(['details' => 'DemoWork3']);
        $nonExistingId = ++ $work->id;
        $randomString = $work->details;

        $response = $this->callGet('/api/works/' . $nonExistingId);
        $response->assertStatus(404);

        $response = $this->callGet('/api/works/' . $randomString);
        $response->assertStatus(404);
    }

    /** @test */
    public function work__store__happyPath() {
        $workData = $this->makeDemoWork(['details' => 'ValidWorkName'])->toArray();

        $response = $this->callPost('/api/works', $workData);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'details' => 'ValidWorkName',
            ])
            ->assertJsonStructure([
                'id',
                'project_id',
                'details',
                'duration',
                'performed_at',
                'billable',
                'type',
                'created_at',
                'updated_at',
            ]);

        $id = $response->json('id');
        $response->assertHeader('Location', '/api/works/' . $id);
    }

    /** @test */
    public function work__update__happyPath() {
        $work = $this->createDemoWork(['details' => 'DemoWork']);
        $workData = $this->getDataArrayWithoutMutations($work);

        $this->assertDatabaseHas('works', $workData);
        $work->details = 'RedetailsdWork';

        $response = $this->callPatch('/api/works/' . $work->id, $work->toArray());
        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'project_id',
                'details',
                'duration',
                'performed_at',
                'billable',
                'type',
                'created_at',
                'updated_at',
            ])
            ->assertJsonFragment([
                'details'  => 'RedetailsdWork',
            ]);
    }

    /** @test */
    public function work__delete__happyPath() {
        $work = $this->createDemoWork(['details' => 'TerribleWork']);
        $workData = $this->getDataArrayWithoutMutations($work);
        $this->assertDatabaseHas('works', $workData);

        $response = $this->callDelete('/api/works/' . $work->id);
        $response->assertStatus(204);
    }

    /** @test */
    public function work__delete__404_for_invalid_id() {
        $work = $this->createDemoWork(['details' => 'DemoWork3']);
        $nonExistingId = ++ $work->id;
        $randomString = $work->details;

        $response = $this->callDelete('/api/works/' . $nonExistingId);
        $response->assertStatus(404);

        $response = $this->callDelete('/api/works/' . $randomString);
        $response->assertStatus(404);
    }
}
