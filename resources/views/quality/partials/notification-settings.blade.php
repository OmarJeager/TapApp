{{-- resources/views/quality/partials/notification-settings.blade.php --}}
{{-- Include it inside the drawer of notification-bell.blade.php, right after </header>:
     @include('quality.partials.notification-settings')
--}}

@php
    $nsUser = auth()->user();
    $nsOn   = (bool) $nsUser->digest_enabled;
    $nsTime = substr((string) ($nsUser->digest_time ?: '08:00'), 0, 5);
@endphp

<section class="nts" id="ntsRoot">

    <button type="button" class="nts-toggle" id="ntsToggle" aria-expanded="false" aria-controls="ntsPanel">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg>
        <span id="ntsSummary">{{ $nsOn ? "Daily email at {$nsTime}" : 'Daily email is off' }}</span>
        <svg class="nts-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
    </button>

    <div class="nts-wrap" id="ntsPanel">
        <div class="nts-inner">

            <label class="nts-row">
                <span>Send me a daily email</span>
                <span class="nts-switch">
                    <input type="checkbox" id="ntsEnabled" {{ $nsOn ? 'checked' : '' }}>
                    <i></i>
                </span>
            </label>

            <label class="nts-row">
                <span>Send it at</span>
                <input type="time" id="ntsTime" class="nts-time" value="{{ $nsTime }}" {{ $nsOn ? '' : 'disabled' }}>
            </label>

            <p class="nts-hint">
                Sent to <strong>{{ $nsUser->email }}</strong> with every asset ID completed and waiting for verification.
            </p>

            <div class="nts-actions">
                <button type="button" class="nt-btn nt-btn-ghost" id="ntsTest">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2L11 13"/><path d="M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                    <span>Send test email</span>
                </button>
                <button type="button" class="nt-btn nts-save" id="ntsSave">
                    <span class="nt-spinner"></span>
                    <span class="nts-save-text">Save</span>
                </button>
            </div>
        </div>
    </div>
</section>

<style>
    .nts { background: var(--nt-card); border-bottom: 1px solid var(--nt-line); }
    .nts-toggle { width: 100%; display: flex; align-items: center; gap: 10px; padding: 13px 22px; border: 0; background: transparent; color: var(--nt-ink); font-size: 14px; font-weight: 600; cursor: pointer; text-align: left; transition: background .2s; }
    .nts-toggle:hover { background: var(--nt-bg); }
    .nts-toggle:focus-visible { outline: 3px solid rgba(36, 87, 214, .45); outline-offset: -3px; }
    .nts-toggle svg { width: 19px; height: 19px; color: var(--nt-accent); flex: none; }
    .nts-toggle .nts-chevron { margin-left: auto; color: var(--nt-muted); transition: transform .25s; }
    .nts-toggle[aria-expanded="true"] .nts-chevron { transform: rotate(180deg); }

    /* smooth open / close */
    .nts-wrap { display: grid; grid-template-rows: 0fr; transition: grid-template-rows .3s ease; }
    .nts-wrap.open { grid-template-rows: 1fr; }
    .nts-inner { overflow: hidden; padding: 0 22px; }
    .nts-wrap.open .nts-inner { padding-bottom: 18px; }

    .nts-row { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 10px 0; font-size: 14px; color: var(--nt-ink); border-top: 1px solid var(--nt-line); }
    .nts-row:first-child { border-top: 0; }

    .nts-switch { position: relative; width: 44px; height: 26px; flex: none; }
    .nts-switch input { position: absolute; inset: 0; width: 100%; height: 100%; opacity: 0; margin: 0; cursor: pointer; z-index: 1; }
    .nts-switch i { position: absolute; inset: 0; border-radius: 999px; background: var(--nt-line); transition: background .25s; }
    .nts-switch i::after { content: ""; position: absolute; top: 3px; left: 3px; width: 20px; height: 20px; border-radius: 50%; background: #fff; box-shadow: 0 1px 3px rgba(0, 0, 0, .3); transition: transform .25s cubic-bezier(.22, 1, .36, 1); }
    .nts-switch input:checked + i { background: var(--nt-ok); }
    .nts-switch input:checked + i::after { transform: translateX(18px); }
    .nts-switch input:focus-visible + i { outline: 3px solid rgba(36, 87, 214, .45); outline-offset: 2px; }

    .nts-time { padding: 8px 10px; border-radius: 9px; border: 1px solid var(--nt-line); background: var(--nt-bg); color: var(--nt-ink); font-size: 15px; font-weight: 600; transition: opacity .2s, border-color .2s; }
    .nts-time:focus { outline: none; border-color: var(--nt-accent); box-shadow: 0 0 0 3px rgba(36, 87, 214, .2); }
    .nts-time:disabled { opacity: .45; cursor: not-allowed; }

    .nts-hint { margin: 6px 0 14px; font-size: 13px; color: var(--nt-muted); line-height: 1.5; word-break: break-word; }
    .nts-hint strong { color: var(--nt-ink); }

    .nts-actions { display: flex; justify-content: flex-end; gap: 10px; flex-wrap: wrap; }
    .nts-save { background: var(--nt-accent); color: #fff; }
    .nts-save:hover { box-shadow: 0 6px 16px rgba(36, 87, 214, .4); transform: translateY(-1px); }
    .nts-save .nt-spinner, #ntsTest .nt-spinner { display: none; }
    .nts-save.loading, #ntsTest.loading { pointer-events: none; opacity: .8; }
    .nts-save.loading .nt-spinner { display: inline-block; }
    #ntsTest.loading svg { animation: nt-spin .8s linear infinite; }

    @media (prefers-reduced-motion: reduce) {
        .nts-wrap, .nts-switch i, .nts-switch i::after { transition-duration: .01s !important; }
    }
</style>

<script>
(function () {
    const toggle   = document.getElementById('ntsToggle');
    const panel    = document.getElementById('ntsPanel');
    const enabled  = document.getElementById('ntsEnabled');
    const timeEl   = document.getElementById('ntsTime');
    const summary  = document.getElementById('ntsSummary');
    const saveBtn  = document.getElementById('ntsSave');
    const testBtn  = document.getElementById('ntsTest');
    const toastEl  = document.getElementById('ntToast');

    const URL_SAVE = @json(route('quality.notifications.settings'));
    const URL_TEST = @json(route('quality.notifications.test'));
    const CSRF     = @json(csrf_token());

    function toast(msg, isError) {
        toastEl.textContent = msg;
        toastEl.classList.toggle('error', !!isError);
        toastEl.classList.add('show');
        setTimeout(() => toastEl.classList.remove('show'), 2600);
    }

    function post(url, payload) {
        return fetch(url, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify(payload || {}),
        }).then(async res => ({ ok: res.ok, data: await res.json().catch(() => ({})) }));
    }

    toggle.addEventListener('click', function () {
        const open = panel.classList.toggle('open');
        toggle.setAttribute('aria-expanded', open);
    });

    enabled.addEventListener('change', function () {
        timeEl.disabled = !enabled.checked;
    });

    saveBtn.addEventListener('click', async function () {
        if (enabled.checked && !timeEl.value) {
            toast('Choose a time first', true);
            timeEl.focus();
            return;
        }

        saveBtn.classList.add('loading');
        try {
            const { ok, data } = await post(URL_SAVE, {
                enabled: enabled.checked,
                time: timeEl.value || '08:00',
            });
            if (!ok || !data.success) throw new Error();

            summary.textContent = enabled.checked ? 'Daily email at ' + timeEl.value : 'Daily email is off';
            toast(data.message);
        } catch (e) {
            toast('Could not save. Try again.', true);
        } finally {
            saveBtn.classList.remove('loading');
        }
    });

    testBtn.addEventListener('click', async function () {
        testBtn.classList.add('loading');
        try {
            const { ok, data } = await post(URL_TEST);
            toast(data.message || (ok ? 'Test email sent' : 'Could not send'), !ok);
        } catch (e) {
            toast('Could not send. Check your connection.', true);
        } finally {
            testBtn.classList.remove('loading');
        }
    });
})();
</script>
