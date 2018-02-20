<?php

namespace Tests\Feature\Tracking;

use Martin\Tracking\Device;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeviceCrudApiTest extends TestCase
{
    use RefreshDatabase;

    private $mutatedMoneyFields = [
        'cost'
    ];

    private function makeDemoDevice(array $overrides = []): Device {
        return factory(Device::class)->make($overrides);
    }

    private function createDemoDevice(array $overrides = []): Device {
        $device = $this->makeDemoDevice($overrides);
        $device->save();
        return $device->fresh();
    }

    private function getDataArrayWithoutMutations(Device $device): array {
        $deviceDataArray = $device->toArray();
        unset($deviceDataArray['purchased_at']);
        return $deviceDataArray;
    }

    private function demutate(array $deviceData): array {
        if (! count($this->mutatedMoneyFields))
            return $deviceData;

        foreach ($this->mutatedMoneyFields as $field) {
            $deviceData[$field] = $deviceData[$field] / 100;
        }
        return $deviceData;
    }

    private function mutate(array $deviceData): array {
        if (! count($this->mutatedMoneyFields))
            return $deviceData;

        foreach ($this->mutatedMoneyFields as $field) {
            $deviceData[$field] = round($deviceData[$field] * 100);
        }
        return $deviceData;
    }

    /** @test */
    public function device__index__happyPath() {
        $this->createDemoDevice(['name' => 'TestDevice']);

        $response = $this->callGet('/api/devices');
        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'TestDevice'
            ])
            ->assertJsonStructure([
                [ // array of objects
                    'name',
                    'description',
                    'purchased_at',
                    'cost',
                    'notes',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]
            ]);
    }

    /** @test */
    public function device__show__happyPath() {
        $device = $this->createDemoDevice(['name' => 'DemoDevice']);

        $response = $this->callGet('/api/devices/' . $device->id);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $device->id
            ])
            ->assertJsonStructure([
                'id',
                'name',
                'description',
                'purchased_at',
                'cost',
                'notes',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
    }

    /** @test */
    public function device__show__404_for_invalid_id() {
        $device = $this->createDemoDevice(['name' => 'DemoDevice3']);
        $nonExistingId = ++ $device->id;
        $randomString = $device->name;

        $response = $this->callGet('/api/devices/' . $nonExistingId);
        $response->assertStatus(404);

        $response = $this->callGet('/api/devices/' . $randomString);
        $response->assertStatus(404);
    }

    /** @test */
    public function device__store__happyPath() {
        $deviceData = $this->makeDemoDevice(['name' => 'ValidDeviceName'])->toArray();

        $response = $this->callPost('/api/devices', $deviceData);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'ValidDeviceName',
            ])
            ->assertJsonStructure([
                'id',
                'name',
                'description',
                'purchased_at',
                'cost',
                'notes',
                'created_at',
                'updated_at',
            ]);

        $id = $response->json('id');
        $response->assertHeader('Location', '/api/devices/' . $id);
    }

    /** @test */
    public function device__update__happyPath() {
        $device = $this->createDemoDevice(['name' => 'DemoDevice']);
        $deviceData = $this->getDataArrayWithoutMutations($device);

        $this->assertDatabaseHas('devices', $this->mutate($deviceData));
        $device->name = 'RenamedDevice';

        $response = $this->callPatch('/api/devices/' . $device->id, $device->toArray());
        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'description',
                'purchased_at',
                'cost',
                'notes',
                'created_at',
                'updated_at',
            ])
            ->assertJsonFragment([
                'name'  => 'RenamedDevice',
            ]);
    }

    /** @test */
    public function device__delete__happyPath() {
        $device = $this->createDemoDevice(['name' => 'TerribleDevice']);
        $deviceData = $this->getDataArrayWithoutMutations($device);
        $this->assertDatabaseHas('devices', $this->mutate($deviceData));

        $response = $this->callDelete('/api/devices/' . $device->id);
        $response->assertStatus(204);
    }

    /** @test */
    public function device__delete__404_for_invalid_id() {
        $device = $this->createDemoDevice(['name' => 'DemoDevice3']);
        $nonExistingId = ++ $device->id;
        $randomString = $device->name;

        $response = $this->callDelete('/api/devices/' . $nonExistingId);
        $response->assertStatus(404);

        $response = $this->callDelete('/api/devices/' . $randomString);
        $response->assertStatus(404);
    }
}
