<?php

namespace Tests\Feature\Tracking;

use Martin\Tracking\Software;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SoftwareCrudApiTest extends TestCase
{
    use RefreshDatabase;

    private $mutatedMoneyFields = [
        'amount_usd' => 100,
        'amount_cad' => 100,
        'usd_to_cad_rate' => 100000,
    ];

    private function makeDemoSoftware(array $overrides = []): Software {
        return factory(Software::class)->make($overrides);
    }

    private function createDemoSoftware(array $overrides = []): Software {
        $software = $this->makeDemoSoftware($overrides);
        $software->save();
        return $software->fresh();
    }

    private function getDataArrayWithoutMutations(Software $software): array {
        $softwareDataArray = $software->toArray();
        unset($softwareDataArray['purchased_at']);
        unset($softwareDataArray['cancelled_at']);
        return $softwareDataArray;
    }

    private function demutate(array $softwareData): array {
        if (! count($this->mutatedMoneyFields))
            return $softwareData;

        foreach ($this->mutatedMoneyFields as $field => $amount) {
            $softwareData[$field] = $softwareData[$field] / $amount;
        }
        return $softwareData;
    }

    private function mutate(array $softwareData): array {
        if (! count($this->mutatedMoneyFields))
            return $softwareData;

        foreach ($this->mutatedMoneyFields as $field => $amount) {
            $softwareData[$field] = round($softwareData[$field] * $amount);
        }
        return $softwareData;
    }

    /** @test */
    public function software__index__happyPath() {
        $this->createDemoSoftware(['name' => 'TestSoftware']);

        $response = $this->callGet('/api/softwares');
        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'TestSoftware'
            ])
            ->assertJsonStructure([
                [ // array of objects
                    'name',
                    'description',
                    'purchased_at',
//                    'cancelled_at',     // nullable
                    'purchased_from',
//                    'license_information',      // nullable
                    'amount_cad',
                    'usd_to_cad_rate',
                    'amount_usd',
                    'billing_cycle',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]
            ]);
    }

    /** @test */
    public function software__show__happyPath() {
        $software = $this->createDemoSoftware(['name' => 'DemoSoftware']);

        $response = $this->callGet('/api/softwares/' . $software->id);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $software->id
            ])
            ->assertJsonStructure([
                'id',
                'name',
                'description',
                'purchased_at',
//                'cancelled_at',     // nullable
                'purchased_from',
//                'license_information',      // nullable
                'amount_cad',
                'usd_to_cad_rate',
                'amount_usd',
                'billing_cycle',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
    }

    /** @test */
    public function software__show__404_for_invalid_id() {
        $software = $this->createDemoSoftware(['name' => 'DemoSoftware3']);
        $nonExistingId = ++ $software->id;
        $randomString = $software->name;

        $response = $this->callGet('/api/softwares/' . $nonExistingId);
        $response->assertStatus(404);

        $response = $this->callGet('/api/softwares/' . $randomString);
        $response->assertStatus(404);
    }

    /** @test */
    public function software__store__happyPath() {
        $softwareData = $this->makeDemoSoftware(['name' => 'ValidSoftwareName'])->toArray();

        $response = $this->callPost('/api/softwares', $softwareData);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'ValidSoftwareName',
            ])
            ->assertJsonStructure([
                'id',
                'name',
                'description',
                'purchased_at',
//                'cancelled_at',     // nullable
                'purchased_from',
//                'license_information',      // nullable
                'amount_cad',
                'usd_to_cad_rate',
                'amount_usd',
                'billing_cycle',
                'created_at',
                'updated_at',
            ]);

        $id = $response->json('id');
        $response->assertHeader('Location', '/api/softwares/' . $id);
    }

    /** @test */
    public function software__update__happyPath() {
        $software = $this->createDemoSoftware(['name' => 'DemoSoftware']);
        $softwareData = $this->getDataArrayWithoutMutations($software);

        $this->assertDatabaseHas('softwares', $this->mutate($softwareData));
        $software->name = 'RenamedSoftware';

        $response = $this->callPatch('/api/softwares/' . $software->id, $software->toArray());
        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'description',
                'purchased_at',
//                'cancelled_at',     // nullable
                'purchased_from',
//                'license_information',      // nullable
                'amount_cad',
                'usd_to_cad_rate',
                'amount_usd',
                'billing_cycle',
                'created_at',
                'updated_at',
            ])
            ->assertJsonFragment([
                'name'  => 'RenamedSoftware',
            ]);
    }

    /** @test */
    public function software__delete__happyPath() {
        $software = $this->createDemoSoftware(['name' => 'TerribleSoftware']);
        $softwareData = $this->getDataArrayWithoutMutations($software);
        $this->assertDatabaseHas('softwares', $this->mutate($softwareData));

        $response = $this->callDelete('/api/softwares/' . $software->id);
        $response->assertStatus(204);
    }

    /** @test */
    public function software__delete__404_for_invalid_id() {
        $software = $this->createDemoSoftware(['name' => 'DemoSoftware3']);
        $nonExistingId = ++ $software->id;
        $randomString = $software->name;

        $response = $this->callDelete('/api/softwares/' . $nonExistingId);
        $response->assertStatus(404);

        $response = $this->callDelete('/api/softwares/' . $randomString);
        $response->assertStatus(404);
    }
}
