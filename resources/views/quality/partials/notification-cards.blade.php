{{-- resources/views/quality/partials/notification-cards.blade.php --}}

@forelse ($checklists as $checklist)
    @php
        $record = $checklist->ppmRecord;
    @endphp

    <article class="nt-card" data-id="{{ $checklist->id }}" style="--i: {{ $loop->index }}">

        {{-- Top: asset + who/when --}}
        <header class="nt-card-head">
            <div class="nt-asset-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a4 4 0 0 0-5.4 5.4L3 18v3h3l6.3-6.3a4 4 0 0 0 5.4-5.4l-2.6 2.6-2.4-.6-.6-2.4z"/></svg>
            </div>
            <div class="nt-asset-text">
                <h4>{{ $record->asset_id ?? '—' }}</h4>
                <p>{{ $record->asset_description ?? 'No description' }}</p>
            </div>
            <span class="nt-pill nt-pill-wait">
                <i class="nt-dot"></i> Waiting
            </span>
        </header>

        {{-- Meta grid --}}
        <dl class="nt-meta">
            <div>
                <dt>Job ID</dt>
                <dd>{{ $record->job_id ?? '—' }}</dd>
            </div>
            <div>
                <dt>Week</dt>
                <dd>{{ $record->intervention_week ?? '—' }}</dd>
            </div>
            <div>
                <dt>Frequency</dt>
                <dd>{{ $record->frequency_label ?? '—' }}</dd>
            </div>
            <div>
                <dt>Completed by</dt>
                <dd>{{ $checklist->completedBy->name ?? $checklist->completed_by_matricule ?? '—' }}</dd>
            </div>
            <div>
                <dt>Completed on</dt>
                <dd>{{ optional($checklist->completed_at)->format('d M Y') ?? '—' }}</dd>
            </div>
            <div>
                <dt>Total time</dt>
                <dd>
                    {{ $checklist->total_time_minutes !== null ? $checklist->total_time_minutes . ' min' : '—' }}
                </dd>
            </div>
        </dl>

        {{-- Start / End time --}}
        <div class="nt-time">
            <div class="nt-time-box">
                <span class="nt-time-label">Start time</span>
                <strong>{{ optional($checklist->start_time)->format('d M Y, H:i') ?? '—' }}</strong>
            </div>
            <div class="nt-time-line"></div>
            <div class="nt-time-box">
                <span class="nt-time-label">End time</span>
                <strong>{{ optional($checklist->end_time)->format('d M Y, H:i') ?? '—' }}</strong>
            </div>
        </div>

        {{-- Questions --}}
        <details class="nt-questions">
            <summary>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                Questions &amp; answers ({{ $checklist->answers->count() }})
                <svg class="nt-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
            </summary>

            <ol class="nt-qlist">
                @forelse ($checklist->answers as $answer)
                    <li>
                        <p class="nt-q">{{ $answer->question->question_text ?? 'Question removed' }}</p>

                        <div class="nt-a">
                            @if (filled($answer->response))
                                <span class="nt-chip nt-chip-resp">{{ $answer->response }}</span>
                            @endif
                            @if (filled($answer->dpn))
                                <span class="nt-chip">DPN: {{ $answer->dpn }}</span>
                            @endif
                        </div>

                        @if (filled($answer->comment))
                            <p class="nt-note"><b>Comment:</b> {{ $answer->comment }}</p>
                        @endif
                        @if (filled($answer->observation))
                            <p class="nt-note"><b>Observation:</b> {{ $answer->observation }}</p>
                        @endif
                    </li>
                @empty
                    <li class="nt-note">No answers saved.</li>
                @endforelse
            </ol>
        </details>

        {{-- Action --}}
        <footer class="nt-card-foot">
            <a href="{{ route('quality.show', $record->id) }}" class="nt-btn nt-btn-ghost">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                Details
            </a>

            <button type="button" class="nt-btn nt-btn-verify" data-verify="{{ route('quality.notifications.verify', $checklist->id) }}">
                <span class="nt-spinner"></span>
                <svg class="nt-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                <span class="nt-btn-text">Verified</span>
            </button>
        </footer>
    </article>

@empty
    <div class="nt-empty">
        <div class="nt-empty-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>
        </div>
        <h4>All caught up</h4>
        <p>No completed checklist is waiting for verification.</p>
    </div>
@endforelse
