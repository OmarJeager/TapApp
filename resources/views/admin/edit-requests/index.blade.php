<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Requests</title>
<style>
    body { font-family: Arial, sans-serif; background: #f5f6f8; margin: 0; padding: 20px; color: #222; }
    .container { max-width: 1000px; margin: 0 auto; }
    h1 { font-size: 24px; margin-bottom: 20px; }

    .flash { padding: 12px 16px; border-radius: 6px; margin-bottom: 16px; font-weight: bold; }
    .flash.success { color: #155724; background: #d4edda; border: 1px solid #c3e6cb; }
    .flash.error   { color: #721c24; background: #f8d7da; border: 1px solid #f5c6cb; }

    .card { background: #fff; border-radius: 10px; padding: 20px; margin-bottom: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,.08); }
    .card-head { display: flex; justify-content: space-between; flex-wrap: wrap; gap: 10px; margin-bottom: 12px; }
    .badge { background: #fff3cd; color: #856404; padding: 4px 10px; border-radius: 20px; font-size: 13px; font-weight: bold; }

    .info { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; font-size: 14px; margin-bottom: 14px; }
    .info strong { display: block; color: #666; font-size: 12px; text-transform: uppercase; }

    .reason { background: #f0f2f5; padding: 10px 14px; border-radius: 6px; font-size: 14px; margin-bottom: 16px; }

    .actions { display: flex; gap: 10px; flex-wrap: wrap; align-items: flex-start; }
    .btn { border: 0; border-radius: 8px; padding: 10px 18px; font-weight: bold; font-size: 14px; cursor: pointer; color: #fff; }
    .btn-accept { background: #2e7d32; }
    .btn-reject { background: #c62828; }
    .btn-cancel { background: #eee; color: #333; }
    .btn:hover { opacity: .9; }

    .reject-box { display: none; width: 100%; margin-top: 12px; }
    .reject-box.open { display: block; }
    .reject-box textarea { width: 100%; box-sizing: border-box; padding: 10px; border: 1px solid #999;
                           border-radius: 6px; font-family: inherit; font-size: 14px; min-height: 80px; }
    .reject-box .row { margin-top: 8px; display: flex; gap: 8px; }

    .empty { text-align: center; padding: 40px; color: #777; background: #fff; border-radius: 10px; }
    .error-inline { color: #b00000; font-size: 13px; margin-top: 4px; }
</style>
</head>
<body>
<div class="container">

    <h1>Pending Edit Requests</h1>

    @if (session('status'))
        <div class="flash success">{{ session('status') }}</div>
    @endif

    @if (session('error'))
        <div class="flash error">{{ session('error') }}</div>
    @endif

    @forelse ($requests as $req)
        @php $record = $req->checklist?->ppmRecord; @endphp

        <div class="card">
            <div class="card-head">
                <strong>Request #{{ $req->id }}</strong>
                <span class="badge">Pending</span>
            </div>

            <div class="info">
                <div><strong>PPM ID</strong>{{ $record->ppm_id ?? '-' }}</div>
                <div><strong>Asset ID</strong>{{ $record->asset_id ?? '-' }}</div>
                <div><strong>Job ID</strong>{{ $record->job_id ?? '-' }}</div>
                <div><strong>Week Due</strong>{{ $record->week_due ?? '-' }}</div>
                <div><strong>Requested by</strong>{{ $req->requested_by_matricule }}</div>
                <div><strong>Requested at</strong>{{ $req->created_at->format('Y-m-d H:i') }}</div>
            </div>

            <div class="info">
                <div style="grid-column: 1 / -1;">
                    <strong>Asset description</strong>{{ $record->asset_description ?? '-' }}
                </div>
            </div>

            <div class="reason">
                <strong>Reason for edit:</strong>
                {{ $req->request_reason ?: 'No reason given.' }}
            </div>

            <div class="actions">
                {{-- ACCEPT --}}
                <form method="POST" action="{{ route('admin.edit-requests.approve', $req) }}">
                    @csrf
                    <button type="submit" class="btn btn-accept">✔ Accept</button>
                </form>

                {{-- REJECT (opens reason box) --}}
                <button type="button" class="btn btn-reject"
                        onclick="document.getElementById('reject-{{ $req->id }}').classList.add('open'); this.style.display='none';">
                    ✖ Reject
                </button>

                <form method="POST" action="{{ route('admin.edit-requests.reject', $req) }}"
                      class="reject-box {{ $errors->has('admin_note') && old('request_id') == $req->id ? 'open' : '' }}"
                      id="reject-{{ $req->id }}">
                    @csrf
                    <input type="hidden" name="request_id" value="{{ $req->id }}">
                    <textarea name="admin_note" placeholder="Reason for rejection (required)" required>{{ old('request_id') == $req->id ? old('admin_note') : '' }}</textarea>

                    @if ($errors->has('admin_note') && old('request_id') == $req->id)
                        <div class="error-inline">{{ $errors->first('admin_note') }}</div>
                    @endif

                    <div class="row">
                        <button type="submit" class="btn btn-reject">Confirm rejection</button>
                        <button type="button" class="btn btn-cancel"
                                onclick="document.getElementById('reject-{{ $req->id }}').classList.remove('open'); this.closest('.actions').querySelector('.btn-reject').style.display='';">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @empty
        <div class="empty">No pending edit requests. ✅</div>
    @endforelse

</div>
</body>
</html>
