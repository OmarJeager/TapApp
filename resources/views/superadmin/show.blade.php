<x-app-layout>
    @php
        $checklist = $record->checklist;
        $badge = fn ($ok) => $ok
            ? 'bg-green-100 text-green-800'
            : 'bg-red-100 text-red-800';
    @endphp

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Asset {{ $record->asset_id }} — {{ $record->asset_description }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto space-y-6 sm:px-6 lg:px-8">

            <a href="{{ route('superadmin.index') }}"
               class="inline-block rounded bg-gray-200 px-3 py-1 text-sm hover:bg-gray-300">← Back</a>

            <div class="grid gap-6 md:grid-cols-2">
                {{-- PPM record info --}}
                <div class="overflow-hidden bg-white shadow sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <tbody class="divide-y divide-gray-100">
                            <tr><th class="w-1/3 bg-gray-50 px-4 py-2 text-left">PPM ID</th><td class="px-4 py-2">{{ $record->ppm_id }}</td></tr>
                            <tr><th class="bg-gray-50 px-4 py-2 text-left">Job ID</th><td class="px-4 py-2">{{ $record->job_id }}</td></tr>
                            <tr><th class="bg-gray-50 px-4 py-2 text-left">Week Due</th><td class="px-4 py-2">{{ $record->week_due }}</td></tr>
                            <tr><th class="bg-gray-50 px-4 py-2 text-left">Frequency</th><td class="px-4 py-2">{{ $record->frequency_text }}</td></tr>
                            <tr><th class="bg-gray-50 px-4 py-2 text-left">Trade</th><td class="px-4 py-2">{{ $record->trade }}</td></tr>
                            <tr><th class="bg-gray-50 px-4 py-2 text-left">Serial Number</th><td class="px-4 py-2">{{ $record->manufacturer_serial_number }}</td></tr>
                            <tr><th class="bg-gray-50 px-4 py-2 text-left">Position</th><td class="px-4 py-2">{{ $record->position }}</td></tr>
                        </tbody>
                    </table>
                </div>

                {{-- Checklist info --}}
                <div class="overflow-hidden bg-white shadow sm:rounded-lg">
                    @if($checklist)
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <tbody class="divide-y divide-gray-100">
                            <tr><th class="w-1/3 bg-gray-50 px-4 py-2 text-left">Start</th><td class="px-4 py-2">{{ $checklist->start_time }}</td></tr>
                            <tr><th class="bg-gray-50 px-4 py-2 text-left">End</th><td class="px-4 py-2">{{ $checklist->end_time }}</td></tr>
                            <tr><th class="bg-gray-50 px-4 py-2 text-left">Total time</th><td class="px-4 py-2">{{ $checklist->total_time_minutes }} min</td></tr>
                            <tr>
                                <th class="bg-gray-50 px-4 py-2 text-left">Completed by</th>
                                <td class="px-4 py-2">{{ $checklist->completedBy?->name ?? '—' }} ({{ $checklist->completed_at?->format('Y-m-d') }})</td>
                            </tr>
                            <tr>
                                <th class="bg-gray-50 px-4 py-2 text-left">Verified by (admin)</th>
                                <td class="px-4 py-2">{{ $checklist->verifiedBy?->name ?? '—' }} ({{ $checklist->verified_at?->format('Y-m-d') }})</td>
                            </tr>
                            <tr>
                                <th class="bg-gray-50 px-4 py-2 text-left">Verified by (quality)</th>
                                <td class="px-4 py-2">{{ $checklist->verifiedByQuality?->name ?? '—' }} ({{ $checklist->verified_quality_at?->format('Y-m-d') }})</td>
                            </tr>
                            <tr>
                                <th class="bg-gray-50 px-4 py-2 text-left">Admin status</th>
                                <td class="px-4 py-2">
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $badge($checklist->status_admin === 'verified') }}">
                                        {{ $checklist->status_admin === 'verified' ? 'Verified' : 'Not verified' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-gray-50 px-4 py-2 text-left">Quality status</th>
                                <td class="px-4 py-2">
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $badge($checklist->status_quality === 'verified') }}">
                                        {{ $checklist->status_quality === 'verified' ? 'Verified' : 'Not verified' }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    @else
                        <p class="p-4 text-gray-500">No checklist has been filled for this record.</p>
                    @endif
                </div>
            </div>

            {{-- Answers --}}
            <div class="overflow-x-auto bg-white shadow sm:rounded-lg">
                <h3 class="px-4 pt-4 text-lg font-semibold">Checklist answers</h3>
                <table class="mt-2 min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Type</th>
                            <th class="px-4 py-2 text-left">Question</th>
                            <th class="px-4 py-2 text-left">Response</th>
                            <th class="px-4 py-2 text-left">Comment</th>
                            <th class="px-4 py-2 text-left">DPN</th>
                            <th class="px-4 py-2 text-left">Observation</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                    @forelse($answers as $answer)
                        <tr>
                            <td class="px-4 py-2">{{ $answer->question->type }}</td>
                            <td class="px-4 py-2">{{ $answer->question->question_text }}</td>
                            <td class="px-4 py-2">
                                @if($answer->response === 'ok')
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">OK</span>
                                @elseif($answer->response === 'not_ok')
                                    <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-800">NOT OK</span>
                                @else
                                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">{{ $answer->comment }}</td>
                            <td class="px-4 py-2">{{ $answer->dpn }}</td>
                            <td class="px-4 py-2">{{ $answer->observation }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">No answers yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
