<?php

namespace App\Exports;

use App\Models\Answer;
use App\Models\Question;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ResponsesExport implements FromCollection, WithHeadings
{
    protected $questionnaireId;

    public function __construct($questionnaireId)
    {
        $this->questionnaireId = $questionnaireId;
    }

    public function collection()
    {
        // Get all questions for the questionnaire
        $questions = Question::where('questionnaire_id', $this->questionnaireId)->get();
        $questionTexts = $questions->pluck('question_text')->toArray();
    
        // Get all responses with their answers
        $responses = \App\Models\Response::where('questionnaire_id', $this->questionnaireId)
            ->with(['answers.question', 'user'])
            ->get();
    
        $exportData = [];
    
        foreach ($responses as $response) {
            $row = [
                'Response ID' => $response->id,
                'User' => $response->user ? $response->user->name : 'Anonymous',
            ];
    
            // Fill all questions with empty strings
            foreach ($questionTexts as $questionText) {
                $row[$questionText] = '';
            }
    
            foreach ($response->answers as $answer) {
                $questionText = $answer->question->question_text;
                $row[$questionText] = $answer->answer;
            }
    
            $exportData[] = $row;
        }
    
        return collect($exportData);
    }    

    public function headings(): array
    {
        $questions = Question::where('questionnaire_id', $this->questionnaireId)->get();
        
        // Return the column headings
        return array_merge(['Response ID', 'User'], $questions->pluck('question_text')->toArray());
    }
}

