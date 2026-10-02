@forelse ($records as $i => $r)
    @php
        $c = $r->checklist;
        if (!$c) {
            $status = ['No checklist', 'badge-gray'];
        } elseif ($c->verified_by_matricule && $c->verified_by_quality_matricule) {
            $status = ['Verified', 'badge-green'];
        } else {
            $status = ['Not verified', 'badge-orange'];
        }
        $yr = $r->week_due ? substr((string) $r->week_due, 0, 4) : '—';
    @endphp
    <tr class="row-anim" style="--i: {{ $i }}">
        <td>{{ $yr }}</td>
        <td class="mono strong">{{ $r->job_id }}</td>
        <td class="mono">{{ $r->asset_id }}</td>
        <td class="desc" title="{{ $r->asset_description }}">{{ $r->asset_description }}</td>
        <td><span class="pill">{{ $r->intervention_week ?? '—' }}</span></td>
        <td>{{ $r->frequency ? $r->frequency . ' wk' : '—' }}</td>
        <td>
            {{ $c?->completedBy?->name ?? '—' }}
            @if ($c?->completed_at)
                <small>{{ $c->completed_at->format('d/m/Y') }}</small>
            @endif
        </td>
        <td>
            {{ $c?->verifiedBy?->name ?? '—' }}
            @if ($c?->verified_at)
                <small>{{ $c->verified_at->format('d/m/Y') }}</small>
            @endif
        </td>
        <td>
            {{ $c?->verifiedByQuality?->name ?? '—' }}
            @if ($c?->verified_quality_at)
                <small>{{ $c->verified_quality_at->format('d/m/Y') }}</small>
            @endif
        </td>
        <td><span class="badge {{ $status[1] }}">{{ $status[0] }}</span></td>
    </tr>
@empty
    <tr>
        <td colspan="10" class="empty">
            <div class="empty-icon">🔍</div>
            No records match your filters.
        </td>
    </tr>
@endforelse
