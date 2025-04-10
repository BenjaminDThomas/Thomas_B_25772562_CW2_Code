<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Questionnaire;
use App\Models\Question;
use App\Mail\QuestionnaireResponseSubmitted;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class QuestionnaireTest extends TestCase
{
    use RefreshDatabase;

    public function test_questionnaire_creation()
    {
        // Create a user
        $user = User::factory()->create();
        $data = [
            'title' => 'Test Questionnaire',
            'description' => 'Test description',
            'questions' => [
                ['text' => 'Question 1', 'type' => 'qualitative'],
                ['text' => 'Question 2', 'type' => 'quantitative']
            ]
        ];

        // Create a questionnaire
        $response = $this->actingAs($user)->post('/questionnaires', $data);

        // Redirection
        $response->assertRedirect();
        $this->assertDatabaseHas('questionnaires', [
            'title' => 'Test Questionnaire',
            'description' => 'Test description',
        ]);
    }

    public function test_export_questionnaire_responses()
    {
        // Creates a user and a questionnaire
        $user = \App\Models\User::factory()->create();
        $questionnaire = \App\Models\Questionnaire::factory()->create([
            'user_id' => $user->id,
            'title' => 'Export Test',
            'description' => 'Should export responses'
        ]);

        // Requests the export
        $response = $this->actingAs($user)->get(route('responses.export', ['questionnaireId' => $questionnaire->id]));
        $this->assertStringContainsString('attachment', $response->headers->get('Content-Disposition'));

        // Check if file is a CSV
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
    }
    
    public function test_questionnaire_update()
    {
        // Create the user
        $user = \App\Models\User::factory()->create();

        // Create the questionnaire
        $questionnaire = \App\Models\Questionnaire::factory()->create([
            'user_id' => $user->id,
            'title' => 'Old Title',
            'description' => 'Old Description',
        ]);

        // Creates a question for the questionnaire
        $question = \App\Models\Question::create([
            'questionnaire_id' => $questionnaire->id,
            'question_text' => 'Old Question',
        ]);

        $updatedData = [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'publish' => true,
            'questions' => [
                $question->id => [
                    'text' => 'Updated Question Text',
                ],
            ],
        ];

        // Update the questionnaire
        $response = $this->actingAs($user)->put(
            route('questionnaires.update', $questionnaire->id),
            $updatedData
        );

        // Redirection after updating
        $response->assertRedirect(route('manage'));

        $this->assertDatabaseHas('questionnaires', [
            'id' => $questionnaire->id,
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'published' => true,
        ]);

        // Update the question
        $this->assertDatabaseHas('questions', [
            'id' => $question->id,
            'question_text' => 'Updated Question Text',
        ]);
    }

    public function test_questionnaire_deletion()
    {
        // Pre-established user and questionnaire
        $user = \App\Models\User::factory()->create();
        $questionnaire = \App\Models\Questionnaire::factory()->create([
            'user_id' => $user->id,
            'title' => 'Title to Delete',
            'description' => 'Description to Delete',
            'published' => false,
        ]);
    
        // Delete the questionnaire
        $response = $this->actingAs($user)->delete(route('questionnaires.destroy', $questionnaire->id));
    
        // Redirection after deletion
        $response->assertRedirect('/manage');
        $this->assertTrue(true);
    }
    
    // User registration
    public function test_user_registration()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];
        $response = $this->post('/register', $userData);

        // Check that the user is redirected
        $response->assertRedirect('/questionnaires');
        $this->assertDatabaseHas('users', [
            'email' => 'testuser@example.com',
        ]);

        // Check that the user is logged in
        $this->assertAuthenticatedAs(User::first());
    }

    // Test user login
    public function test_user_login()
    {
        // Create a user
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        // Login user's credentials
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        // Ensures the user is logged in
        $response->assertRedirect('/questionnaires');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_receives_email_when_submitting_questionnaire_response()
    {
        Mail::fake();

        // Create a user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create a questionnaire
        $questionnaire = Questionnaire::factory()->create([
            'user_id' => $user->id,
        ]);

        // Create a questionnaire question
        $question = $questionnaire->questions()->create([
            'question_text' => 'What is your favorite color?',
        ]);
        $responseData = [
            'answers' => [
                $question->id => 'Blue',
            ],
        ];
        $response = $this->actingAs($user)->post(
            route('questionnaires.submit', $questionnaire->id),
            $responseData
        );

        $response->assertRedirect();

        // Send Email
        Mail::assertSent(QuestionnaireResponseSubmitted::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    public function test_user_can_logout()
    {
        // Create a user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Logout request
        $response = $this->post(route('logout'));

        // Redirect User
        $response->assertRedirect('/');
        $this->assertGuest();
    }
}