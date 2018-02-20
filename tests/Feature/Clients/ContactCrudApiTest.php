<?php

namespace Tests\Feature\Contacts;

use Martin\Clients\Contact;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContactCrudApiTest extends TestCase
{
    use RefreshDatabase;

    private function makeDemoContact($overrides = []) {
        return factory(Contact::class)->make($overrides);
    }

    private function createDemoContact($overrides = []) {
        $contact = $this->makeDemoContact($overrides);
        $contact->save();
        return $contact->fresh();
    }


    /** @test */
    public function contact__index__happyPath() {
        $this->createDemoContact(['name' => 'TestContact']);

        $response = $this->callGet('/api/contacts');
        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'TestContact'
            ])
            ->assertJsonStructure([
                [ // array of objects
                    'name',
                    'email',
                    'client_id',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]
            ]);
    }

    /** @test */
    public function contact__show__happyPath() {
        $contact = $this->createDemoContact(['name' => 'DemoContact']);

        $response = $this->callGet('/api/contacts/' . $contact->id);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $contact->id
            ])
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                'client_id',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
    }

    /** @test */
    public function contact__show__404_for_invalid_id() {
        $contact = $this->createDemoContact(['name' => 'DemoContact3']);
        $nonExistingId = ++ $contact->id;
        $randomString = $contact->name;

        $response = $this->callGet('/api/contacts/' . $nonExistingId);
        $response->assertStatus(404);

        $response = $this->callGet('/api/contacts/' . $randomString);
        $response->assertStatus(404);
    }

    /** @test */
    public function contact__store__happyPath() {
        $contactData = $this->makeDemoContact(['name' => 'ValidContactName'])->toArray();

        $response = $this->callPost('/api/contacts', $contactData);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'ValidContactName',
            ])
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                'client_id',
                'created_at',
                'updated_at',
            ]);

        $id = $response->json('id');
        $response->assertHeader('Location', '/api/contacts/' . $id);
    }

    /** @test */
    public function contact__update__happyPath()
    {
        $contact = $this->createDemoContact(['name' => 'DemoContact']);
        $this->assertDatabaseHas('contacts', $contact->toArray());

        $contact->name = 'RenamedContact';

        $response = $this->callPatch('/api/contacts/' . $contact->id, $contact->toArray());
        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                'client_id',
                'created_at',
                'updated_at',
            ])
            ->assertJsonFragment([
                'name' => 'RenamedContact',
            ]);
    }

    /** @test */
    public function contact__delete__happyPath() {
        $contact = $this->createDemoContact(['name' => 'TerribleContact']);
        $this->assertDatabaseHas('contacts', $contact->toArray());

        $response = $this->callDelete('/api/contacts/' . $contact->id);
        $response->assertStatus(204);
    }

    /** @test */
    public function contact__delete__404_for_invalid_id() {
        $contact = $this->createDemoContact(['name' => 'DemoContact3']);
        $nonExistingId = ++ $contact->id;
        $randomString = $contact->name;

        $response = $this->callDelete('/api/contacts/' . $nonExistingId);
        $response->assertStatus(404);

        $response = $this->callDelete('/api/contacts/' . $randomString);
        $response->assertStatus(404);
    }
}
