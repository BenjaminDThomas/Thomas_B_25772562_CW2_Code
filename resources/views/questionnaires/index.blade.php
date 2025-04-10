@extends('layouts.app')

@section('content')
<div class="container">
    <h1>All Published Questionnaires</h1>


    <a href="{{ route('questionnaires.index') }}" class="btn btn-info mb-3">View All Questionnaires</a>
    @auth
        <a href="{{ route('manage') }}" class="btn btn-secondary mb-3">Manage Your Questionnaires</a>
        <a href="{{ route('create') }}" class="btn btn-primary mb-3">Create New Questionnaire</a>
    @endauth

    @if ($questionnaires->isEmpty())
        <p class="text-muted">No published questionnaires available.</p>
    @else
        <div class="mt-3">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($questionnaires as $questionnaire)
                    <tr>
                        <td>{{ $questionnaire->title }}</td>
                        <td>{{ $questionnaire->description }}</td>
                        <td>
                            <a href="{{ route('questionnaires.show', $questionnaire->id) }}" class="btn btn-primary btn-sm">Participate</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
