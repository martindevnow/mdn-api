<?php

namespace Tests\Feature\Projects;

use Martin\Projects\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectCrudApiTest extends TestCase
{
    use RefreshDatabase;

    private function makeDemoProject(array $overrides = []): Project {
        return factory(Project::class)->make($overrides);
    }

    private function createDemoProject(array $overrides = []): Project {
        $project = $this->makeDemoProject($overrides);
        $project->save();
        return $project->fresh();
    }

    private function getDataArrayWithoutMutations(Project $project): array {
        $projectDataArray = $project->toArray();
        unset($projectDataArray['started_at']);
        return $projectDataArray;
    }

    /** @test */
    public function project__index__happyPath() {
        $this->createDemoProject(['name' => 'TestProject']);

        $response = $this->callGet('/api/projects');
        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'TestProject'
            ])
            ->assertJsonStructure([
                [ // array of objects
                    'name',
                    'code',
                    'description',
                    'status',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]
            ]);
    }

    /** @test */
    public function project__show__happyPath() {
        $project = $this->createDemoProject(['name' => 'DemoProject']);

        $response = $this->callGet('/api/projects/' . $project->id);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $project->id
            ])
            ->assertJsonStructure([
                'id',
                'name',
                'code',
                'description',
                'status',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
    }

    /** @test */
    public function project__show__404_for_invalid_id() {
        $project = $this->createDemoProject(['name' => 'DemoProject3']);
        $nonExistingId = ++ $project->id;
        $randomString = $project->name;

        $response = $this->callGet('/api/projects/' . $nonExistingId);
        $response->assertStatus(404);

        $response = $this->callGet('/api/projects/' . $randomString);
        $response->assertStatus(404);
    }

    /** @test */
    public function project__store__happyPath() {
        $projectData = $this->makeDemoProject(['name' => 'ValidProjectName'])->toArray();

        $response = $this->callPost('/api/projects', $projectData);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'ValidProjectName',
            ])
            ->assertJsonStructure([
                'id',
                'name',
                'code',
                'description',
                'status',
                'created_at',
                'updated_at',
            ]);

        $id = $response->json('id');
        $response->assertHeader('Location', '/api/projects/' . $id);
    }

    /** @test */
    public function project__store__422_on_repeat_project_property() {
        $project1 = $this->createDemoProject(['code' => 'ValidProjectName'])->toArray();
        $project2Data = $this->makeDemoProject(['code' => 'ValidProjectName'])->toArray();

        $response = $this->callPost('/api/projects', $project2Data);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['code']
            ])
            ->assertJsonFragment([
                'code' => [
                    'The code has already been taken.',
                ],
            ]);
    }

    /** @test */
    public function project__update__happyPath() {
        $project = $this->createDemoProject(['name' => 'DemoProject']);
        $projectData = $this->getDataArrayWithoutMutations($project);

        $this->assertDatabaseHas('projects', $projectData);
        $project->name = 'RenamedProject';

        $response = $this->callPatch('/api/projects/' . $project->id, $project->toArray());
        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'code',
                'description',
                'status',
                'created_at',
                'updated_at',
            ])
            ->assertJsonFragment([
                'name'  => 'RenamedProject',
            ]);
    }

    /** @test */
    public function project__update__422_on_repeat_project_property() {
        $project1 = $this->createDemoProject(['code' => 'FavoriteProject']);
        $project2 = $this->createDemoProject(['code' => 'DemoProject']);

        $projectData1 = $this->getDataArrayWithoutMutations($project1);
        $projectData2 = $this->getDataArrayWithoutMutations($project2);

        $this->assertDatabaseHas('projects', $projectData1);
        $this->assertDatabaseHas('projects', $projectData2);

        $project1->code = $project2->code;

        $response = $this->callPatch('/api/projects/' . $project1->id, $project1->toArray());
        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['code']
            ])
            ->assertJsonFragment([
                'code' => [
                    'The code has already been taken.',
                ],
            ]);
    }

    /** @test */
    public function project__delete__happyPath() {
        $project = $this->createDemoProject(['name' => 'TerribleProject']);
        $projectData = $this->getDataArrayWithoutMutations($project);
        $this->assertDatabaseHas('projects', $projectData);

        $response = $this->callDelete('/api/projects/' . $project->id);
        $response->assertStatus(204);
    }

    /** @test */
    public function project__delete__404_for_invalid_id() {
        $project = $this->createDemoProject(['name' => 'DemoProject3']);
        $nonExistingId = ++ $project->id;
        $randomString = $project->name;

        $response = $this->callDelete('/api/projects/' . $nonExistingId);
        $response->assertStatus(404);

        $response = $this->callDelete('/api/projects/' . $randomString);
        $response->assertStatus(404);
    }
}
