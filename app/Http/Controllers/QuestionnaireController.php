<?php

namespace App\Http\Controllers;

use App\Mail\QuestionnaireResponseSubmitted;
use App\Notifications\QuestionnaireSubmitted;
use App\Exports\ResponsesExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Questionnaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class QuestionnaireController extends Controller
{
    // Show all questionnaires
    public function index()
    {
        $questionnaires = Questionnaire::where('published', true)->get();
        return view('questionnaires.index', compact('questionnaires'));
    }

    // Show the form to create a new questionnaire
    public function create()
    {
        return view('create');
    }

    // Store a newly created questionnaire in the database
    public function store(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'published' => 'nullable|boolean',
            'questions' => 'required|array',
            'questions.*.text' => 'required|string|max:255',
            'questions.*.type' => 'required|in:quantitative,qualitative',
        ]);

        // Create the questionnaire
        $questionnaire = Questionnaire::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'published' => $validatedData['published'] ?? false,
            'user_id' => auth()->id(),
        ]);

        // Create questions for the questionnaire
        foreach ($validatedData['questions'] as $questionData) {
            Question::create([
                'questionnaire_id' => $questionnaire->id,
                'question_text' => $questionData['text'],
                'type' => $questionData['type'],
                'options' => $questionData['type'] == 'quantitative' ? null : '',
            ]);
        }

        return redirect()->route('questionnaires.index')->with('success', 'Questionnaire created successfully!');
    }

    // Edit an existing questionnaire
    public function edit($id)
    {
        // Find the questionnaire by ID
        $questionnaire = Questionnaire::findOrFail($id);

        // Check if the current user is the owner of the questionnaire
        if ($questionnaire->user_id !== Auth::id()) {
            return redirect()->route('questionnaires.index')->with('error', 'You do not have permission to edit this questionnaire.');
        }

        return view('questionnaires.edit', compact('questionnaire'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'publish' => 'nullable|boolean',
            'questions' => 'required|array',
            'questions.*.text' => 'required|string|max:255',
        ]);

        // Find the questionnaire by ID
        $questionnaire = Questionnaire::findOrFail($id);

        // Check if the current user is the owner
        if ($questionnaire->user_id !== Auth::id()) {
            return redirect()->route('questionnaires.index')->with('error', 'You do not have permission to update this questionnaire.');
        }

        // Update the questionnaire
        $questionnaire->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'published' => $request->has('publish'),
        ]);

        // Update the questions
        foreach ($validated['questions'] as $questionId => $questionData) {
            $question = Question::findOrFail($questionId);

            // Update the question's text
            $question->update([
                'question_text' => $questionData['text'],
            ]);
        }

        return redirect()->route('manage')->with('success', 'Questionnaire updated successfully.');
    }

    // Delete the questionnaire from the database
    public function destroy($id)
    {
        // Find the questionnaire by ID
        $questionnaire = Questionnaire::findOrFail($id);
    
        // Check if the current user is the owner
        if ($questionnaire->user_id !== Auth::id()) {
            return redirect()->route('questionnaires.index')->with('error', 'You do not have permission to delete this questionnaire.');
        }
    
        // Prevent questionnaire deletion if the questionnaire is published
        if ($questionnaire->published) {
            return redirect()->route('questionnaires.index')->with('error', 'Published questionnaires cannot be deleted.');
        }
    
        $questionnaire->delete();
    
        return redirect()->route('manage')->with('success', 'Questionnaire successfully deleted.');
    }

    public function manage()
    {
        $questionnaires = Questionnaire::where('user_id', Auth::id())->get();
        return view('manage', compact('questionnaires'));
    }

    public function show($id)
    {
        $questionnaire = Questionnaire::findOrFail($id);
        $questionnaire->load('questions');

        return view('questionnaires.show', compact('questionnaire'));
    }

    public function exportResponses($id)
    {
        $questionnaire = Questionnaire::findOrFail($id);

        // Check export privilege
        if ($questionnaire->user_id !== auth()->id()) {
            return redirect()->route('manage')->with('error', 'You do not have permission to export this questionnaire.');
        }

        // Return the Excel file
        return Excel::download(new ResponsesExport($id), 'responses.xlsx');
    }

    // Response Controller
    public function retrieveResponses($questionnaireId)
    {
        $questionnaire = Questionnaire::findOrFail($questionnaireId);

        // Check if responses exist
        $responses = $questionnaire->responses()->with('answers')->paginate(10);
        $questionnaire->update(['published' => false]);
        \Log::info($responses);

        return view('exports.responses', compact('responses', 'questionnaire'));
    }

    public function participate($id)
    {
        $questionnaire = Questionnaire::findOrFail($id);

        if (!$questionnaire->published) {
            return redirect()->route('questionnaires.index')->with('error', 'This questionnaire is not published.');
        }

        return view('questionnaires.participate', compact('questionnaire'));
    }

    public function submit(Request $request, $id)
    {
        $validatedData = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|string',
        ]);
        $questionnaire = Questionnaire::findOrFail($id);

        // Check if user is logged in or a guest
        $userId = auth()->id();
        $guestId = null;

        if (!$userId) {
            if (!$request->session()->has('guest_id')) {
                $request->session()->put('guest_id', 'guest_' . \Str::uuid());
            }
            $guestId = $request->session()->get('guest_id');
        }
        $response = Response::create([
            'questionnaire_id' => $questionnaire->id,
            'user_id' => $userId,
            'guest_id' => $guestId,
        ]);

        // Save each answer
        foreach ($validatedData['answers'] as $question_id => $answer) {
            Answer::create([
                'response_id' => $response->id,
                'question_id' => $question_id,
                'answer' => $answer,
                'user_id' => $userId,
                'guest_id' => $guestId,
            ]);
        }

        // Send email using Mailable
        if ($userId) {
            Mail::to(auth()->user()->email)->send(new QuestionnaireResponseSubmitted($questionnaire));
        } else {
            Log::info("Guest response submitted.");
        }

        return redirect()->route('questionnaires.index')->with('success', 'Your answers have been submitted!');
    }

    public function showResponses($id)
    {
        // Find the questionnaire by ID
        $questionnaire = Questionnaire::findOrFail($id);
        $responses = $questionnaire->responses;
        return view('questionnaires.show', compact('questionnaire', 'responses'));
    }

    // Store anonymous responses
    public function storeAnonymousResponse(Request $request, $questionnaireId)
    {
        \Log::info('Storing anonymous response', ['request' => $request->all()]);
    
        try {
            // Find the questionnaire by ID
            $questionnaire = Questionnaire::findOrFail($questionnaireId);
    
            // Validate the incoming request
            $request->validate([
                'consent' => 'required|boolean',
                'answers' => 'required|array',
                'answers.*' => 'required|string',
            ]);
    
            // Create a new response
            $response = new Response();
            $response->questionnaire_id = $questionnaire->id;
            
            // If the user is not logged in, set the guest_id
            if ($request->has('guest_id')) {
                $response->guest_id = $request->input('guest_id');
            }
    
            // Save the response to the database
            $response->save();
    
            // Store each answer in the answers table
            foreach ($request->input('answers') as $questionId => $answerValue) {
                Answer::create([
                    'response_id' => $response->id,
                    'question_id' => $questionId,
                    'answer' => ctype_digit($answerValue) ? (int) $answerValue : $answerValue,
                    'guest_id' => $response->guest_id,
                    'user_id' => auth()->check() ? auth()->id() : null,
                ]);
            }
    
            \Log::info('Response saved successfully', ['response' => $response]);
    
            return redirect()->route('questionnaires.index')->with('success', 'Your response has been recorded.');
        } catch (\Exception $e) {
            \Log::error('Error storing response', ['error' => $e->getMessage()]);
            return back()->withErrors('An error occurred while submitting your response.');
        }
    }    
}
    
