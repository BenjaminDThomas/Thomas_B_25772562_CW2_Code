<tbody>
    @foreach($responses as $response)
    <tr>
        <td>{{ $response->id }}</td>
        <td>{{ $response->user->name ?? 'Anonymous' }}</td>
        <td>
            @foreach ($response->answers as $answer)
                {{ $answer->question->question_text }}: {{ $answer->answer }} <br>
            @endforeach
        </td>
    </tr>
    @endforeach
</tbody>
