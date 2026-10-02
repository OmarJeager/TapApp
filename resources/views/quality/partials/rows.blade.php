@forelse($ppm_records as $record)
    @php $checklist = $record->checklist; @endphp
    <tr class="hover:bg-gray-50">
        <td class="px-4 py-3">{{ $record->id }}</td>
        <td class="px-4 py-3">{{ $record->job_id }}</td>
        <td class="px-4 py-3">{{ $record->asset_id }}</td>
        <td class="px-4 py-3">{{ $record->week_due }}</td>
        <td class="px-4 py-3">{{ $record->frequency }}</td>
        <td class="px-4 py-3">{{ $checklist?->completedBy?->name ?? '—' }}</td>
        <td class="px-4 py-3">
            @if($checklist)
                @if($checklist->status_admin === 'verified')
                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">Verified</span>
                @else
                    <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-800">Not verified</span>
                @endif
            @else
                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">No checklist</span>
            @endif
        </td>
        <td class="px-4 py-3">
            <div class="flex items-center gap-2">
                @if($checklist)
                    <form method="POST" action="{{ route('superadmin.toggle', $checklist) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="whitespace-nowrap rounded border px-3 py-1 text-xs font-medium
                            {{ $checklist->status_admin === 'verified'
                                ? 'border-red-500 text-red-600 hover:bg-red-50'
                                : 'border-green-500 text-green-600 hover:bg-green-50' }}">
                            {{ $checklist->status_admin === 'verified' ? 'Mark not verified' : 'Mark verified' }}
                        </button>
                    </form>
                @endif
                <a href="{{ route('quality.show', $record) }}"
                   class="whitespace-nowrap rounded bg-blue-600 px-3 py-1 text-xs font-medium text-white hover:bg-blue-700">Show more</a>
            </div>
        </td>
    </tr>
@empty
    <tr><td colspan="8" class="px-4 py-6 text-center text-gray-500">No records.</td></tr>
@endforelse
