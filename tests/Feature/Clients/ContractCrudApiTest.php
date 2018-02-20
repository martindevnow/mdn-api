<?php

namespace Tests\Feature\Projects;

use Martin\Clients\Contract;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContractCrudApiTest extends TestCase
{
    use RefreshDatabase;

    private function makeDemoContract(array $overrides = []): Contract {
        return factory(Contract::class)->make($overrides);
    }

    private function createDemoContract(array $overrides = []): Contract {
        $contract = $this->makeDemoContract($overrides);
        $contract->save();
        return $contract->fresh();
    }

    private function getDataArrayWithoutMutations(Contract $contract): array {
        $contractDataArray = $contract->toArray();
        unset($contractDataArray['activated_at']);
        unset($contractDataArray['deactivated_at']);
        unset($contractDataArray['valid_from_date']);
        unset($contractDataArray['valid_until_date']);
        $contractDataArray['programming_hourly_rate'] = $contractDataArray['programming_hourly_rate'] * 100;
        $contractDataArray['sysadmin_hourly_rate'] = $contractDataArray['sysadmin_hourly_rate'] * 100;
        $contractDataArray['consulting_hourly_rate'] = $contractDataArray['consulting_hourly_rate'] * 100;
        return $contractDataArray;
    }

    /** @test */
    public function contract__index__happyPath() {
        $this->createDemoContract(['programming_hourly_rate' => 45.56]);

        $response = $this->callGet('/api/contracts');
        $response->assertStatus(200)
            ->assertJsonFragment([
                'programming_hourly_rate' => 45.56
            ])
            ->assertJsonStructure([
                [ // array of objects
                    'project_id',
                    'programming_hourly_rate',
                    'sysadmin_hourly_rate',
                    'consulting_hourly_rate',
                    'activated_at',
//                    'deactivated_at',       // null by default
                    'valid_from_date',
//                    'valid_until_date',     // null by default
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]
            ]);
    }

    /** @test */
    public function contract__show__happyPath() {
        $contract = $this->createDemoContract(['sysadmin_hourly_rate' => 22.53]);

        $response = $this->callGet('/api/contracts/' . $contract->id);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'sysadmin_hourly_rate' => 22.53
            ])
            ->assertJsonStructure([
                'id',
                'project_id',
                'programming_hourly_rate',
                'sysadmin_hourly_rate',
                'consulting_hourly_rate',
                'activated_at',
//                'deactivated_at',       // null by default
                'valid_from_date',
//                'valid_until_date',     // null by default
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
    }

    /** @test */
    public function contract__show__404_for_invalid_id() {
        $contract = $this->createDemoContract(['consulting_hourly_rate' => 10.32]);
        $nonExistingId = ++ $contract->id;
        $randomString = "randomString";

        $response = $this->callGet('/api/contracts/' . $nonExistingId);
        $response->assertStatus(404);

        $response = $this->callGet('/api/contracts/' . $randomString);
        $response->assertStatus(404);
    }

    /** @test */
    public function contract__store__happyPath() {
        $contractData = $this->makeDemoContract(['consulting_hourly_rate' => 10.11])->toArray();

        $response = $this->callPost('/api/contracts', $contractData);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'consulting_hourly_rate' => 10.11,
            ])
            ->assertJsonStructure([
                'id',
                'project_id',
                'programming_hourly_rate',
                'sysadmin_hourly_rate',
                'consulting_hourly_rate',
                'activated_at',
//                'deactivated_at',       // null by default
                'valid_from_date',
//                'valid_until_date',     // null by default
                'created_at',
                'updated_at',
            ]);

        $id = $response->json('id');
        $response->assertHeader('Location', '/api/contracts/' . $id);
    }

    /** @test */
    public function contract__update__happyPath() {
        $contract = $this->createDemoContract(['consulting_hourly_rate' => 15.45]);
        $contractData = $this->getDataArrayWithoutMutations($contract);

        $this->assertDatabaseHas('contracts', $contractData);
        $contract->consulting_hourly_rate = 16.45;

        $response = $this->callPatch('/api/contracts/' . $contract->id, $contract->toArray());
        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'project_id',
                'programming_hourly_rate',
                'sysadmin_hourly_rate',
                'consulting_hourly_rate',
                'activated_at',
//                'deactivated_at',       // null by default
                'valid_from_date',
//                'valid_until_date',     // null by default
                'created_at',
                'updated_at',
            ])
            ->assertJsonFragment([
                'consulting_hourly_rate'  => 16.45,
            ]);
    }

    /** @test */
    public function contract__delete__happyPath() {
        $contract = $this->createDemoContract(['consulting_hourly_rate' => 9.50]);
        $contractData = $this->getDataArrayWithoutMutations($contract);
        $this->assertDatabaseHas('contracts', $contractData);

        $response = $this->callDelete('/api/contracts/' . $contract->id);
        $response->assertStatus(204);
    }

    /** @test */
    public function contract__delete__404_for_invalid_id() {
        $contract = $this->createDemoContract(['consulting_hourly_rate' => 10.23]);
        $nonExistingId = ++ $contract->id;
        $randomString = "contract";

        $response = $this->callDelete('/api/contracts/' . $nonExistingId);
        $response->assertStatus(404);

        $response = $this->callDelete('/api/contracts/' . $randomString);
        $response->assertStatus(404);
    }
}
