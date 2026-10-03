@include('layouts.main')

@section('title', 'PNL Checklist')

@section('content')

    {{-- =========================
         PNL TITLE
    ========================= --}}

    <div class="pnl-title">

        <h1>PNL Checklist</h1>

    </div>


    {{-- =========================
         PPM RECORD
    ========================= --}}

    @include('components.ppm-record-card', [
        'record' => $ppmRecord
    ])
<div class="page-header">
<h1>TST Frequency 1</h1>
<div class="page-header">
@foreach($questions as $question)

    <div class="question-card">

        <h3>
            {{ $question->order }}.
            {{ $question->question_text }}
        </h3>

        <label>
            <input
                type="radio"
                name="responses[{{ $question->id }}]"
                value="OK"
            >
            OK
        </label>

        <label>
            <input
                type="radio"
                name="responses[{{ $question->id }}]"
                value="NOT_OK"
            >
            Not OK
        </label>

        <textarea
            name="comments[{{ $question->id }}]"
            placeholder="Comment..."
        ></textarea>

    </div>

@endforeach
