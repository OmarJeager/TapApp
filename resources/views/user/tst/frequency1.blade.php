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
