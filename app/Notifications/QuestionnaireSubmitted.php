<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuestionnaireSubmitted extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Questionnaire Submission Confirmation')
            ->line('Thank you for completing the questionnaire.')
            ->line('Your responses have been successfully recorded.')
            ->action('View Your Submission', url('/questionnaires'))
            ->line('Thank you for your participation!');
    }
}
