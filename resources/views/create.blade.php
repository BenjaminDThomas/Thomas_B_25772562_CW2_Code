@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create New Questionnaire</h1>

    <form action="{{ route('questionnaires.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
            @error('title')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
            @error('description')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <input type="checkbox" id="published" name="published" value="1" {{ old('published') ? 'checked' : '' }}>
            <label for="published">Publish this questionnaire</label>
        </div>

        <div id="questions-container">
            <div class="question-group" id="question-0">
                <h4>Question 1</h4>

                <div class="form-group">
                    <label for="question-text-0">Question Text:</label>
                    <input type="text" id="question-text-0" name="questions[0][text]" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="question-type-0">Question Type:</label>
                    <select id="question-type-0" name="questions[0][type]" class="form-control" required>
                        <option value="quantitative">Quantitative (Rating 1-6)</option>
                        <option value="qualitative">Qualitative (Text Answer)</option>
                    </select>
                </div>
            </div>
        </div>

        <button type="button" id="add-question" class="btn btn-primary">Add Question</button>
        <button type="submit" class="btn btn-success">Create Questionnaire</button>
    </form>
    </div>
@endsection