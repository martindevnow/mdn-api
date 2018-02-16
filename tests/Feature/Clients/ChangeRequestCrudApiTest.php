<?php

namespace Tests\Feature\Clients;

use Martin\Clients\ChangeRequest;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChangeRequestCrudApiTest extends TestCase
{
    use RefreshDatabase;

    private function makeDemoChangeRequest(array $overrides = []): ChangeRequest {
        return factory(ChangeRequest::class)->make($overrides);
    }

    private function createDemoChangeRequest(array $overrides = []): ChangeRequest {
        $changeRequest = $this->makeDemoChangeRequest($overrides);
        $changeRequest->save();
        return $changeRequest->fresh();
    }

    private function getDataArrayWithoutMutations(ChangeRequest $changeRequest): array {
        $changeRequestDataArray = $changeRequest->toArray();
        unset($changeRequestDataArray['requested_at']);
        unset($changeRequestDataArray['fulfilled_at']);
        return $changeRequestDataArray;
    }

    /** @test */
    public function changeRequest__index__happyPath() {
        $this->createDemoChangeRequest(['description' => 'TestChangeRequest']);

        $response = $this->callGet('/api/changeRequests');
        $response->assertStatus(200)
            ->assertJsonFragment([
                'description' => 'TestChangeRequest'
            ])
            ->assertJsonStructure([
                [ // array of objects
                    'description',
                    'requested_at',
                    'fulfilled_at',
                    'project_id',
                    'user_id',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]
            ]);
    }

    /** @test */
    public function changeRequest__show__happyPath() {
        $changeRequest = $this->createDemoChangeRequest(['description' => 'DemoChangeRequest']);

        $response = $this->callGet('/api/changeRequests/' . $changeRequest->id);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $changeRequest->id
            ])
            ->assertJsonStructure([
                'id',
                'description',
                'requested_at',
                'fulfilled_at',
                'project_id',
                'user_id',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
    }

    /** @test */
    public function changeRequest__show__404_for_invalid_id() {
        $changeRequest = $this->createDemoChangeRequest(['description' => 'DemoChangeRequest3']);
        $nonExistingId = ++ $changeRequest->id;
        $randomString = $changeRequest->description;

        $response = $this->callGet('/api/changeRequests/' . $nonExistingId);
        $response->assertStatus(404);

        $response = $this->callGet('/api/changeRequests/' . $randomString);
        $response->assertStatus(404);
    }

    /** @test */
    public function changeRequest__store__happyPath() {
        $changeRequestData = $this->makeDemoChangeRequest(['description' => 'ValidChangeRequestName'])->toArray();

        $response = $this->callPost('/api/changeRequests', $changeRequestData);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'description' => 'ValidChangeRequestName',
            ])
            ->assertJsonStructure([
                'id',
                'description',
                'requested_at',
                'fulfilled_at',
                'project_id',
                'user_id',
                'created_at',
                'updated_at',
            ]);

        $id = $response->json('id');
        $response->assertHeader('Location', '/api/changeRequests/' . $id);
    }

    /** @test */
    public function changeRequest__update__happyPath() {
        $changeRequest = $this->createDemoChangeRequest(['description' => 'DemoChangeRequest']);
        $changeRequestData = $this->getDataArrayWithoutMutations($changeRequest);

        $this->assertdatabaseHas('change_requests', $changeRequestData);
        $changeRequest->description = 'RedescriptiondChangeRequest';

        $response = $this->callPatch('/api/changeRequests/' . $changeRequest->id, $changeRequest->toArray());
        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'description',
                'requested_at',
                'fulfilled_at',
                'project_id',
                'user_id',
                'created_at',
                'updated_at',
            ])
            ->assertJsonFragment([
                'description'  => 'RedescriptiondChangeRequest',
            ]);
    }

    /** @test */
    public function changeRequest__delete__happyPath() {
        $changeRequest = $this->createDemoChangeRequest(['description' => 'TerribleChangeRequest']);
        $changeRequestData = $this->getDataArrayWithoutMutations($changeRequest);
        $this->assertdatabaseHas('change_requests', $changeRequestData);

        $response = $this->callDelete('/api/changeRequests/' . $changeRequest->id);
        $response->assertStatus(204);
    }

    /** @test */
    public function changeRequest__delete__404_for_invalid_id() {
        $changeRequest = $this->createDemoChangeRequest(['description' => 'DemoChangeRequest3']);
        $nonExistingId = ++ $changeRequest->id;
        $randomString = $changeRequest->description;

        $response = $this->callDelete('/api/changeRequests/' . $nonExistingId);
        $response->assertStatus(404);

        $response = $this->callDelete('/api/changeRequests/' . $randomString);
        $response->assertStatus(404);
    }
}
