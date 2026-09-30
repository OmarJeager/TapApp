<style>
    .ppm-sheet {
        max-width: 850px;
        margin: 0 auto;
        background: #fff;
        border: 1px solid #333;
        padding: 24px 28px;
        font-family: Arial, Helvetica, sans-serif;
        color: #222;
    }

    .ppm-sheet .row {
        display: flex;
        padding: 6px 0;
        border-bottom: 1px solid #ddd;
        align-items: baseline;
    }

    .ppm-sheet .label,
    .ppm-sheet .label-sm {
        min-width: 190px;
        font-weight: 700;
        font-size: 14px;
        color: #111;
    }

    .ppm-sheet .label-sm {
        min-width: 150px;
        font-size: 12px;
    }

    .ppm-sheet .value {
        font-size: 14px;
        color: #222;
    }

    .ppm-sheet .value-mono {
        font-size: 14px;
        font-family: 'Courier New', monospace;
        color: #222;
    }

    .ppm-sheet .value-muted {
        color: #999;
    }

    .ppm-sheet .section-heading {
        font-weight: 700;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: .5px;
        margin: 10px 0 6px;
        color: #333;
    }

    .ppm-sheet .section-divider {
        border: none;
        border-top: 1px solid #333;
        margin: 14px 0;
    }

    .ppm-sheet .service-box {
        border: 1px solid #333;
        padding: 10px 16px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        row-gap: 6px;
        column-gap: 24px;
    }

    .ppm-sheet .field {
        display: flex;
        justify-content: space-between;
        border-bottom: 1px dotted #ccc;
        padding: 4px 0;
    }

    .ppm-sheet .field-label {
        font-weight: 700;
        font-size: 13px;
    }

    .ppm-sheet .field-value {
        font-size: 13px;
    }

    .ppm-sheet .field-value.small {
        font-size: 12px;
    }

    .ppm-sheet .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        column-gap: 24px;
    }

    /* ===== Header / Barcode area ===== */
    .ppm-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        border-bottom: 2px solid #111;
        padding-bottom: 12px;
        margin-bottom: 16px;
    }

    .ppm-header .barcode-block svg {
        height: 60px;
    }

    .ppm-header .plant-tag {
        font-weight: 700;
        font-size: 15px;
        color: #111;
        margin-top: 4px;
    }

    .ppm-jobid-row {
        display: flex;
        align-items: baseline;
        gap: 10px;
        margin-bottom: 14px;
    }

    .ppm-jobid-row .jobid-label {
        font-weight: 700;
        font-size: 20px;
        color: #111;
    }

    .ppm-jobid-row .jobid-value {
        font-weight: 700;
        font-size: 20px;
        font-family: 'Courier New', monospace;
        color: #111;
    }
</style>

<div class="ppm-sheet asset-card">

    {{-- =========================
         HEADER: BARCODE + PLANT
    ========================== --}}

    <div class="ppm-header">
        <div class="barcode-block">
            @if($record->job_id)
                {!! DNS1D::getBarcodeSVG($record->job_id, 'C128', 2, 60) !!}
            @endif
        </div>

        <div class="plant-tag">
            {{ $record->plant_name ?? 'Morocco III' }}
        </div>
    </div>

    <div class="ppm-jobid-row">
        <span class="jobid-label">PPM Job ID</span>
        <span class="jobid-value">{{ $record->job_id ?? 'N/A' }}</span>
    </div>


    {{-- =========================
         TOP INFORMATION
    ========================== --}}

    <div class="row" style="justify-content: space-between;">

        <div style="display:flex; gap:40px; flex-wrap:wrap;">

            <span>
                <span class="label">Week Due</span>

                <span class="value-mono">
                    {{ $record->week_due ?? 'N/A' }}
                </span>
            </span>

            <span>
                <span class="label" style="min-width:auto;">
                    PPM ID
                </span>

                <span class="value-mono">
                    {{ $record->ppm_id ?? 'N/A' }}
                </span>
            </span>

        </div>

        <span>

            <span class="label" style="min-width:auto;">
                Risk ID
            </span>

            <span class="value-mono">
                {{ $record->risk_id ?? '0' }}
            </span>

        </span>

    </div>


    {{-- =========================
         DESCRIPTION
    ========================== --}}

    <div class="row">

        <span class="label">
            Brief Description
        </span>

        <span class="value">
            {{ $record->brief_description ?? 'N/A' }}
        </span>

    </div>


    <div class="row">

        <span class="label">
            Default PPM Category
        </span>

        <span class="value value-muted">
            —
        </span>

    </div>


    <hr class="section-divider">


    {{-- =========================
         ASSET
    ========================== --}}

    <div class="section-heading">
        Asset
    </div>


    <div class="row">

        <span class="label">
            Asset
        </span>

        <span class="value value-mono">
            {{ $record->asset_id ?? 'N/A' }}
        </span>

    </div>


    <div class="row">

        <span class="label">
            Asset Description
        </span>

        <span class="value">
            {{ $record->asset_description ?? 'N/A' }}
        </span>

    </div>


    <div class="row">

        <span class="label">
            Sub Assembly
        </span>

        <span class="value value-muted">
            —
        </span>

    </div>


    <div class="row">

        <span class="label">
            Position
        </span>

        <span class="value">
            {{ $record->position_3 ?? $record->asset_position ?? 'N/A' }}
        </span>

    </div>


    <div class="row">

        <span class="label">
            Plant Group
        </span>

        <span class="value">
            {{ $record->plant_group ?? 'N/A' }}
        </span>

    </div>


    <div class="row">

        <span class="label">
            System
        </span>

        <span class="value">
            {{ $record->system ?? 'N/A' }}
        </span>

    </div>


    <div class="row" style="justify-content:space-between;">

        <span>

            <span class="label" style="min-width:auto;">
                Original Asset ID
            </span>

            <span class="value value-mono">
                {{ $record->asset_id ?? 'N/A' }}
            </span>

        </span>


        <span>

            <span class="label" style="min-width:auto;">
                Serial Number
            </span>

            <span class="value value-mono">
                {{ $record->manufacturer_serial_number ?? 'N/A' }}
            </span>

        </span>

    </div>


    {{-- =========================
         MAINTENANCE SERVICE
    ========================== --}}

    <div
        class="section-heading"
        style="margin-top:18px;"
    >
        Maintenance Service
    </div>


    <div class="service-box">

        <div class="field">

            <span class="field-label">
                Principal Engineer
            </span>

            <span class="field-value">
                {{ $record->trade ?? 'N/A' }}
            </span>

        </div>


        <div class="field">

            <span class="field-label">
                Trade
            </span>

            <span class="field-value">
                {{ $record->trade ?? 'N/A' }}
            </span>

        </div>


        <div class="field">

            <span class="field-label">
                Frequency (Weeks)
            </span>

            <span class="field-value">
                {{ $record->frequency ?? 'N/A' }}
            </span>

        </div>


        <div class="field">

            <span class="field-label">
                Date Time Created
            </span>

            <span class="field-value small">

                @if($record->date_time_created)

                    {{ \Carbon\Carbon::parse($record->date_time_created)->format('d/m/Y H:i:s') }}

                @else

                    N/A

                @endif

            </span>

        </div>


        <div class="field">

            <span class="field-label">
                FMEA
            </span>

            <span class="field-value">
                {{ $record->risk_id ?? '24' }}
            </span>

        </div>


        <div class="field">

            <span class="field-label">
                Est. Duration
            </span>

            <span class="field-value">
                {{ $record->est_duration ?? 'N/A' }}
            </span>

        </div>

    </div>


    {{-- =========================
         ADDITIONAL DETAILS
    ========================== --}}

    @if(
        $record->job_id ||
        $record->est_resource_minutes ||
        $record->est_resource_time ||
        $record->frequency_text ||
        $record->position_2
    )

        <hr
            class="section-divider"
            style="margin-top:20px;"
        >

        <div
            class="section-heading"
            style="font-size:12px;"
        >
            Additional Details
        </div>


        <div class="grid-2">

            @if($record->job_id)

                <div class="row">

                    <span class="label-sm">
                        Job ID
                    </span>

                    <span class="value value-mono">
                        {{ $record->job_id }}
                    </span>

                </div>

            @endif


            @if($record->est_resource_minutes)

                <div class="row">

                    <span class="label-sm">
                        Est. Resource (min)
                    </span>

                    <span class="value">
                        {{ $record->est_resource_minutes }}
                    </span>

                </div>

            @endif


            @if($record->est_resource_time)

                <div class="row">

                    <span class="label-sm">
                        Est. Resource Time
                    </span>

                    <span class="value">
                        {{ $record->est_resource_time }}
                    </span>

                </div>

            @endif


            @if($record->frequency_text)

                <div class="row">

                    <span class="label-sm">
                        Frequency Text
                    </span>

                    <span class="value">
                        {{ $record->frequency_text }}
                    </span>

                </div>

            @endif


            @if($record->position_2)

                <div class="row">

                    <span class="label-sm">
                        Position 2
                    </span>

                    <span class="value">
                        {{ $record->position_2 }}
                    </span>

                </div>

            @endif

        </div>

    @endif

</div>
