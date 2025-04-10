@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Manage My Questionnaires</h1>

    <!-- Button to navigate back to published questionnaires -->
    <a href="{{ route('questionnaires.index') }}" class="btn btn-info mb-3">Back to Published Questionnaires</a>

    <!-- Create Questionnaire Button -->
    <a href="{{ route('create') }}" class="btn btn-primary mb-3">Create New Questionnaire</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($questionnaires as $questionnaire)
            <tr>
                <td>{{ $questionnaire->title }}</td>
                <td>{{ $questionnaire->description }}</td>
                <td>
                    @if ($questionnaire->published)
                        <span class="badge bg-success">Published</span>
                    @else
                        <span class="badge bg-secondary">Not Published</span>
                    @endif
                </td>
                <td>
                    @if ($questionnaire->published)
                        <!-- Only show the Retrieve Responses button if it's published -->
                        <a href="{{ route('questionnaires.retrieveResponses', $questionnaire->id) }}" class="btn btn-primary btn-sm">Retrieve Responses</a>
                        
                        <!-- Export Responses link when responses are retrieved -->
                        <a href="{{ route('questionnaires.exportResponses', $questionnaire->id) }}" class="btn btn-success btn-sm">Export Responses to CSV</a>
                    @else
                        <!-- Show edit and delete if not published -->
                        <a href="{{ route('questionnaires.edit', $questionnaire->id) }}" class="btn btn-info btn-sm">Edit</a>

                        <form action="{{ route('questionnaires.destroy', $questionnaire->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
