<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print PPM Tickets</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f1f5f9;
            margin: 0;
        }

    .ticket-select-box {
        max-width: 480px;
        margin: 30px auto;
        background: #fff;
        border-radius: 16px;
        padding: 28px 26px;
        box-shadow: 0 8px 24px rgba(0,0,0,.1);
        animation: fadeInUp .5s ease-out;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .ticket-select-box h2 {
        margin: 0 0 20px 0;
        font-size: 19px;
        color: #111827;
    }

    .ts-field {
        margin-bottom: 16px;
    }

    .ts-field label {
        display: block;
        font-weight: 600;
        font-size: 13px;
        color: #374151;
        margin-bottom: 6px;
    }

    .ts-field select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        font-size: 14px;
        background: #f9fafb;
        transition: border-color .2s ease;
    }

    .ts-field select:focus {
        outline: none;
        border-color: #2563eb;
        background: #fff;
    }

    .ts-field.hidden {
        display: none;
    }

    .ts-submit {
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 10px;
        background: #2563eb;
        color: #fff;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        transition: background .2s ease, transform .15s ease;
    }

    .ts-submit:hover:not(:disabled) {
        background: #1e40af;
        transform: translateY(-1px);
    }

    .ts-submit:disabled {
        background: #9ca3af;
        cursor: not-allowed;
    }
    </style>
</head>
<body>

<div class="ticket-select-box">

    <h2>Print PPM Tickets</h2>

    <form id="ticket-form" method="GET" action="{{ route('tickets.print') }}" target="_blank">

        <div class="ts-field">
            <label for="category">Category</label>
            <select id="category" name="category" required>
                <option value="" disabled selected>Select category</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}">{{ strtoupper($cat) }}</option>
                @endforeach
            </select>
        </div>

        <div class="ts-field hidden" id="week-field">
            <label for="week">Week Due</label>
            <select id="week" name="week" required>
                <option value="" disabled selected>Select week</option>
            </select>
        </div>

        {{-- Frequency selector only shown for TST --}}
        <div class="ts-field hidden" id="frequency-field">
            <label for="frequency">Frequency</label>
            <select id="frequency" name="frequency">
                <option value="" disabled selected>Select frequency</option>
            </select>
        </div>

        <button type="submit" class="ts-submit" id="submit-btn" disabled>
            Generate &amp; Print Tickets
        </button>

    </form>

</div>

<script>
    const categorySelect  = document.getElementById('category');
    const weekField       = document.getElementById('week-field');
    const weekSelect      = document.getElementById('week');
    const freqField       = document.getElementById('frequency-field');
    const freqSelect      = document.getElementById('frequency');
    const submitBtn       = document.getElementById('submit-btn');

    function resetSelect(select, placeholder) {
        select.innerHTML = `<option value="" disabled selected>${placeholder}</option>`;
    }

    function updateSubmitState() {
        const hasCategory = categorySelect.value !== '';
        const hasWeek      = weekSelect.value !== '';
        const isTst        = categorySelect.value === 'tst';
        const hasFreq       = !isTst || freqSelect.value !== '';

        submitBtn.disabled = !(hasCategory && hasWeek && hasFreq);
    }

    categorySelect.addEventListener('change', async function () {
        resetSelect(weekSelect, 'Select week');
        resetSelect(freqSelect, 'Select frequency');
        freqField.classList.add('hidden');
        weekField.classList.add('hidden');
        updateSubmitState();

        if (!categorySelect.value) return;

        const res = await fetch(`{{ route('tickets.weeks') }}?category=${categorySelect.value}`);
        const weeks = await res.json();

        weeks.forEach(w => {
            const opt = document.createElement('option');
            opt.value = w;
            opt.textContent = w;
            weekSelect.appendChild(opt);
        });

        weekField.classList.remove('hidden');
        updateSubmitState();
    });

    weekSelect.addEventListener('change', async function () {
        updateSubmitState();

        // Only TST needs a frequency selector
        if (categorySelect.value !== 'tst' || !weekSelect.value) {
            freqField.classList.add('hidden');
            updateSubmitState();
            return;
        }

        resetSelect(freqSelect, 'Select frequency');

        const res = await fetch(
            `{{ route('tickets.frequencies') }}?category=${categorySelect.value}&week=${weekSelect.value}`
        );
        const frequencies = await res.json();

        frequencies.forEach(f => {
            const opt = document.createElement('option');
            opt.value = f;
            opt.textContent = f;
            freqSelect.appendChild(opt);
        });

        freqField.classList.remove('hidden');
        updateSubmitState();
    });

    freqSelect.addEventListener('change', updateSubmitState);
</script>

</body>
</html>
