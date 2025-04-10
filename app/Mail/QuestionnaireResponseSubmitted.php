<?php

namespace App\Mail;

use App\Models\Questionnaire;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuestionnaireResponseSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $questionnaire;

    public function __construct(Questionnaire $questionnaire)
    {
        $this->questionnaire = $questionnaire;
    }

    public function build()
    {
        return $this->subject('Your questionnaire response has been submitted')
                    ->view('emails.questionnaire_response_submitted')
                    ->with([
                        'questionnaire' => $this->questionnaire,
                    ]);
    }
}
