@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $questionnaire->title }}</h1>
    <p>{{ $questionnaire->description }}</p>

    <!-- Consent to Participate -->
    <form method="POST" action="{{ route('questionnaires.storeAnonymousResponse', $questionnaire->id) }}">
        @csrf

        @if(auth()->guest()) 
            <input type="hidden" name="guest_id" value="{{ uniqid('guest_', true) }}">
        @endif

        <div class="form-group">
            <label>
                <input type="checkbox" name="consent" value="1" required>
                I consent to participate in this questionnaire.
            </label>
        </div>

        <!-- Questions Section -->
        @foreach($questionnaire->questions as $index => $question)
            <p><strong>Question {{ $index + 1 }}:</strong> {{ $question->question_text }}</p>

            @if ($question->type == 'quantitative')
                <p>Rate from 1 to 6:</p>
                <div class="rating-options">
                    @for ($i = 1; $i <= 6; $i++)
                        <label>
                            <input type="radio" name="answers[{{ $question->id }}]" value="{{ $i }}" required>
                            {{ $i }}: {{ $i == 1 ? 'Lowest' : ($i == 6 ? 'Highest' : '') }}
                        </label><br>
                    @endfor
                </div>
            @else
                <p>Enter your answer here:</p>
                <textarea name="answers[{{ $question->id }}]" class="form-control" rows="3" required></textarea>
            @endif
        @endforeach

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Submit Questionnaire</button>

        <!-- Opt-Out Button -->
        <a href="{{ route('questionnaires.index') }}" class="btn btn-danger ml-3">Opt Out</a>
    </form>
</div>
@endsection
