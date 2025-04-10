@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $questionnaire->title }} - Responses</h1>

    <!-- Navigation Buttons -->
    <div class="mb-3">
        <a href="{{ route('questionnaires.index') }}" class="btn btn-secondary">Back to Questionnaires</a>
        <a href="{{ route('questionnaires.exportResponses', $questionnaire->id) }}" class="btn btn-primary">Export to CSV</a>
    </div>

    @if($responses->isNotEmpty())
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Response ID</th>
                    <th>User</th>
                    @foreach ($questionnaire->questions as $question)
                        <th>{{ $question->question_text }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($responses as $response)
                    <tr>
                        <td>{{ $response->id }}</td>
                        <td>{{ $response->user_id ? 'User ' . $response->user_id : 'Anonymous' }}</td>

                        @foreach ($questionnaire->questions as $question)
                            <td>
                                @php
                                    $userAnswer = $response->answers->firstWhere('question_id', $question->id);
                                @endphp
                                {{ $userAnswer ? nl2br(e($userAnswer->answer)) : 'No answer' }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>

        </table>

        <div class="d-flex justify-content-center">
            {{ $responses->links() }}
        </div>
    @else
        <p class="alert alert-info">No responses found for this questionnaire.</p>
    @endif
</div>
@endsection
