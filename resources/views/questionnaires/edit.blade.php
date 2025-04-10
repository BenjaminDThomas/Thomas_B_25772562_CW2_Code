@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Questionnaire</h1>
    <form action="{{ route('questionnaires.update', $questionnaire->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Title -->
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $questionnaire->title) }}" required>
        </div>

        <!-- Description -->
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control" required>{{ old('description', $questionnaire->description) }}</textarea>
        </div>

        <!-- Publish Checkbox -->
        <div class="form-group">
            <label for="publish">Publish</label>
            <input type="checkbox" name="publish" id="publish" value="1" {{ old('publish', $questionnaire->published) ? 'checked' : '' }}>
        </div>

        <!-- Questions -->
        <h3>Questions</h3>
        @foreach ($questionnaire->questions as $index => $question)
            <div class="form-group">
                <label for="question_{{ $question->id }}">Question {{ $index + 1 }}</label>
                <input type="text" name="questions[{{ $question->id }}][text]" class="form-control" value="{{ old('questions.' . $question->id . '.text', $question->question_text) }}" required>
            </div>
        @endforeach

        <button type="submit" class="btn btn-primary mt-3">Update Questionnaire</button>

        <!-- Cancel Button -->
        <a href="{{ route('manage') }}" class="btn btn-secondary mt-3">Cancel</a>
    </form>
</div>
@endsection
