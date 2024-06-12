<?php

namespace Tests\Feature;

use App\Traits\FeatureTestTrait;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubjectTest extends TestCase
{
    use RefreshDatabase;
    use FeatureTestTrait;

    /**---------------------------------------------------------------------------------------------------------------------------------**/

    public function test_get_all_subjects_successfully(): void
    {

        $response = $this->authorized_user()->get('/api/subjects');
        $response->assertStatus(200);
    }
    public function test_get_subject_by_id_successfully(): void
    {
        $subject = Subject::factory()->create();
        $response = $this->authorized_user()->get('/api/subjects/' . $subject->id);
        $response->assertStatus(200);
    }

    public function test_creat_subject_successfully(): void
    {

        $subject = Subject::factory()->create();
        $subjectArray = $subject->toArray();
        $response = $this->authorized_user()->post('/api/subjects', $subjectArray);
        $response->assertStatus(201);
        $this->assertDatabaseHas(
            'subjects',
            $subjectArray
        );
    }

    public function test_create_subject_faild(): void
    {
        $subject = Subject::factory()->create();
        $subjectArray = $subject->toArray();
        $response = $this->unauthorized_user()->post('/api/subjects', $subjectArray);
        $response->assertStatus(403);
    }
    public function test_delete_subject_successfully(): void
    {
        $subject = Subject::factory()->create();
        $response = $this->authorized_user()->delete('/api/subjects/' . $subject->id);
        $response->assertStatus(204);
    }

    public function test_delete_subject_faild(): void
    {
        $subject = Subject::factory()->create();
        $subjectArray = $subject->toArray();
        $response = $this->unauthorized_user()->delete('/api/subjects/' . $subject->id);
        $response->assertStatus(403);
    }


    public function test_update_subject_successfully()
    {
        $subject = Subject::factory()->create();
        $subjectNewData = [
            'title' => "Math"
        ];

        $response = $this->authorized_user()->put('/api/subjects/' . $subject->id, $subjectNewData);
        $response->assertstatus(200);
        $this->assertDatabaseHas(
            'subjects',
            $subjectNewData

        );
    }
}
