@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Questionnaire: {{ $questionnaire->title }}</h1>

    <form method="POST" action="{{ route('responses.store', $questionnaire->id) }}">
        @csrf

        <!-- Consent Checkbox -->
        <div class="form-group">
            <label for="consent">
                <input type="checkbox" name="consent" id="consent" value="1">
                I consent to participate in this questionnaire.
            </label>
        </div>

        @foreach ($questionnaire->questions as $question)
        <div class="form-group">
            <label>{{ $question->question_text }}</label>

            @if ($question->type == 'quantitative')
                <!-- Quantitative questions (Rating 1-6) -->
                <div class="rating">
                    @for ($i = 1; $i <= 6; $i++)
                        <label>
                            <input type="radio" name="responses[{{ $question->id }}]" value="{{ $i }}">
                            {{ $i }}
                        </label>
                    @endfor
                    <div class="rating-description">
                        <p>1 is the lowest and 6 is the highest</p>
                    </div>
                </div>
            @elseif ($question->type == 'qualitative')
                <!-- Qualitative questions (Text) -->
                <textarea name="responses[{{ $question->id }}]" class="form-control" placeholder="Your answer here" rows="3"></textarea>
            @endif
        </div>
        @endforeach

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Submit Responses</button>

        <!-- Opt-Out Button -->
        <a href="{{ route('questionnaires.index') }}" class="btn btn-danger">Opt-Out</a>
    </form>
</div>
@endsection
