<?php

namespace Tests\Feature\Charges;

use Martin\Billing\Charge;
use Martin\Projects\Work;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChargeCrudApiTest extends TestCase
{
    use RefreshDatabase;

    private function makeDemoCharge(array $overrides = []): Charge {
        return factory(Charge::class)->make($overrides);
    }

    private function createDemoCharge(array $overrides = []): Charge {
        $charge = $this->makeDemoCharge($overrides);
        $charge->save();
        return $charge->fresh();
    }

    private function getRelationFields(array $relation): array {
        $relationArray = [];
        foreach ($relation as $field => $model) {
            $relationArray[$field . '_id'] = $model->id;
            $relationArray[$field . '_type'] = get_class($relation);
        }
        return $relationArray;
    }

    private function getDataArrayWithoutMutations(Charge $charge): array {
        $chargeDataArray = $charge->toArray();
        unset($chargeDataArray['billable_as_of']);
        unset($chargeDataArray['billed_at']);
        $chargeDataArray['rate'] *= 100;
        $chargeDataArray['quantity'] *= 100;
        $chargeDataArray['total_cost'] *= 100;
        return $chargeDataArray;
    }

    /** @test */
    public function charge__index__happyPath() {
        $chargeable = factory(Work::class)->create(['details' => 'TestWork']);
        $charge =  $this->createDemoCharge($this->getRelationFields(compact($chargeable)));

        $response = $this->callGet('/api/charges');
        $response->assertStatus(200)
            ->assertJsonFragment([
                'chargeable_id' => $charge->chargeable_id
            ])
            ->assertJsonStructure([
                [ // array of objects
                    'project_id',
                    'invoice_id',
                    'chargeable_id',
                    'chargeable_type',
                    'rate',
                    'quantity',
                    'total_cost',
                    'billable_as_of',
                    'billed_at',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]
            ]);
    }

    /** @test */
    public function charge__show__happyPath() {
        $chargeable = factory(Work::class)->create(['details' => 'TestWork']);
        $charge = $this->createDemoCharge($this->getRelationFields(compact($chargeable)));

        $response = $this->callGet('/api/charges/' . $charge->id);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $charge->id
            ])
            ->assertJsonStructure([
                'id',
                'project_id',
                'invoice_id',
                'chargeable_id',
                'chargeable_type',
                'rate',
                'quantity',
                'total_cost',
                'billable_as_of',
                'billed_at',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
    }

    /** @test */
    public function charge__show__404_for_invalid_id() {
        $chargeable = factory(Work::class)->create(['details' => 'TestWork']);
        $charge = $this->createDemoCharge($this->getRelationFields(compact($chargeable)));

        $nonExistingId = ++ $charge->id;
        $randomString = $charge->chargeable_type;

        $response = $this->callGet('/api/charges/' . $nonExistingId);
        $response->assertStatus(404);

        $response = $this->callGet('/api/charges/' . $randomString);
        $response->assertStatus(404);
    }

    /** @test */
    public function charge__store__happyPath() {
        $chargeable = factory(Work::class)->create(['details' => 'TestWork']);
        $chargeData = $this->makeDemoCharge($this->getRelationFields(compact($chargeable)))->toArray();

        $response = $this->callPost('/api/charges', $chargeData);

//        dd ($response->json());

        $response->assertStatus(201)
            ->assertJsonFragment([
                'chargeable_id' => $chargeData['chargeable_id'],
            ])
            ->assertJsonStructure([
                'id',
                'project_id',
                'invoice_id',
                'chargeable_id',
                'chargeable_type',
                'rate',
                'quantity',
                'total_cost',
                'billable_as_of',
                'billed_at',
                'created_at',
                'updated_at',
            ]);

        $id = $response->json('id');
        $response->assertHeader('Location', '/api/charges/' . $id);
    }

    /** @test */
    public function charge__update__happyPath() {
        $chargeable = factory(Work::class)->create(['details' => 'TestWork']);
        $charge = $this->createDemoCharge($this->getRelationFields(compact($chargeable)));

        $chargeData = $this->getDataArrayWithoutMutations($charge);

        $this->assertDatabaseHas('charges', $chargeData);
        $charge->cheque_number = 'RenamedCharge';

        $response = $this->callPatch('/api/charges/' . $charge->id, $charge->toArray());
        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'project_id',
                'invoice_id',
                'chargeable_id',
                'chargeable_type',
                'rate',
                'quantity',
                'total_cost',
                'billable_as_of',
                'billed_at',
                'created_at',
                'updated_at',
            ])
            ->assertJsonFragment([
                'chargeable_id'  => $charge->chargeable_id,
            ]);
    }

    /** @test */
    public function charge__delete__happyPath() {
        $chargeable = factory(Work::class)->create(['details' => 'TestWork']);
        $charge = $this->createDemoCharge($this->getRelationFields(compact($chargeable)));

        $chargeData = $this->getDataArrayWithoutMutations($charge);
        $this->assertDatabaseHas('charges', $chargeData);

        $response = $this->callDelete('/api/charges/' . $charge->id);
        $response->assertStatus(204);
    }

    /** @test */
    public function charge__delete__404_for_invalid_id() {
        $chargeable = factory(Work::class)->create(['details' => 'TestWork']);
        $charge = $this->createDemoCharge($this->getRelationFields(compact($chargeable)));

        $nonExistingId = ++ $charge->id;
        $randomString = $charge->chargeable_type;

        $response = $this->callDelete('/api/charges/' . $nonExistingId);
        $response->assertStatus(404);

        $response = $this->callDelete('/api/charges/' . $randomString);
        $response->assertStatus(404);
    }
}
