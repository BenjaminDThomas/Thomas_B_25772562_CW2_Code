<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Answer;

class ResponseController extends Controller
{
    public function store(Request $request)
    {
        Log::info('Incoming Request Data:', $request->all());

        $userId = auth()->id();
        $guestId = $userId ? null : 'guest_' . uniqid();

        $response = Response::create([
            'user_id' => $userId,
            'guest_id' => $guestId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Log::info("New Response Created: ID = {$response->id}");

        // Store answers
        if (!$request->has('answers') || empty($request->input('answers'))) {
            Log::error('No answers found in the request.');
            return redirect()->back()->with('error', 'No answers submitted.');
        }

        $answersData = [];

        foreach ($request->input('answers') as $questionId => $answer) {
            Log::info("Saving Answer - Response ID: {$response->id}, Question ID: $questionId, Answer: $answer");

            $answersData[] = [
                'response_id' => $response->id,
                'question_id' => $questionId,
                'user_id' => $userId,
                'guest_id' => $guestId,
                'answer' => $answer ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('answers')->insert($answersData);
        return redirect()->back()->with('success', 'Responses saved successfully!');
    }

    public function export()
    {
        // Get all responses
        $responses = DB::table('answers')
            ->select('response_id', 'user_id', 'question_id', 'answer')
            ->get()
            ->groupBy('response_id');

        $questionIds = DB::table('answers')->distinct()->pluck('question_id')->toArray();
        $headers = array_merge(['Response ID', 'User ID'], array_map(fn($q) => "Question $q", $questionIds));
        $output = fopen('php://output', 'w');
        fputcsv($output, $headers);

        foreach ($responses as $responseId => $answers) {
            $userId = $answers->first()->user_id ?? 'Anonymous';
            $row = array_fill(0, count($questionIds) + 2, '');
            $row[0] = $responseId;
            $row[1] = $userId;
            
            foreach ($answers as $answer) {
                $index = array_search($answer->question_id, $questionIds) + 2;
                $row[$index] = $answer->answer;
            }

            // Write row to CSV
            fputcsv($output, $row);
        }

        fclose($output);
        return response('', 200, ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename="responses.csv"']);
    }
}
