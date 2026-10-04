<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Checklist questions</title>

{{-- If you already have an admin layout: delete the <html>/<head>/<body> wrapper,
     use @extends('layouts.admin') + @section('content'), and move <style>/<script> to @push. --}}

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
:root{
  --paper:#f5f6f2; --card:#ffffff; --ink:#1c2b2d; --muted:#6b7a7c; --line:#e0e5e0;
  --teal:#0f766e; --teal-soft:#d9f0ec; --amber:#b45309; --amber-soft:#fdf0d8;
  --red:#be3a34; --red-soft:#fbe4e2; --shadow:0 1px 2px rgba(28,43,45,.06),0 8px 24px rgba(28,43,45,.06);
}
@media (prefers-color-scheme: dark){
  :root{--paper:#121a1b; --card:#1a2527; --ink:#e8efee; --muted:#93a3a5; --line:#2a3a3c;
    --teal:#2dd4bf; --teal-soft:#123a36; --amber:#f5b04c; --amber-soft:#3b2c12;
    --red:#ff7b73; --red-soft:#3e1c1a; --shadow:0 8px 24px rgba(0,0,0,.35);}
}
*{box-sizing:border-box}
body{margin:0;background:var(--paper);color:var(--ink);font-family:'Manrope',system-ui,sans-serif;font-size:14px;line-height:1.5}
.wrap{max-width:1240px;margin:0 auto;padding:32px 20px 60px}

/* header */
.top{display:flex;flex-wrap:wrap;gap:16px;align-items:flex-end;justify-content:space-between;margin-bottom:22px}
.top h1{margin:0;font-size:28px;font-weight:800;letter-spacing:-.02em;display:flex;align-items:center;gap:12px}
.top h1 i{width:44px;height:44px;border-radius:12px;background:var(--teal);color:#fff;display:grid;place-items:center;font-size:22px;
  animation:pop .6s cubic-bezier(.2,1.4,.4,1) both}
.top p{margin:4px 0 0;color:var(--muted)}
.stats{display:flex;gap:10px;flex-wrap:wrap}
.stat{background:var(--card);border:1px solid var(--line);border-radius:12px;padding:10px 16px;min-width:110px}
.stat b{display:block;font-size:22px;font-weight:800}
.stat span{color:var(--muted);font-size:12px}
.stat.ok b{color:var(--teal)} .stat.off b{color:var(--amber)}

/* buttons */
.btn{border:0;border-radius:10px;padding:10px 16px;font:inherit;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:8px;
  transition:transform .15s,box-shadow .15s,background .15s}
.btn:active{transform:scale(.96)}
.btn:focus-visible,.icon-btn:focus-visible,.f-input:focus-visible,.field input:focus-visible,.field textarea:focus-visible{outline:2px solid var(--teal);outline-offset:2px}
.btn-primary{background:var(--teal);color:#fff}
.btn-primary:hover{box-shadow:0 6px 16px rgba(15,118,110,.35)}
.btn-ghost{background:transparent;color:var(--ink);border:1px solid var(--line)}
.btn-ghost:hover{background:var(--paper)}
.btn-danger{background:var(--red);color:#fff}
.btn[disabled]{opacity:.6;cursor:wait}

/* card + table */
.card{position:relative;background:var(--card);border:1px solid var(--line);border-radius:16px;box-shadow:var(--shadow);overflow:hidden}
.bar{height:3px;background:transparent;position:relative;overflow:hidden}
.card.loading .bar::after{content:"";position:absolute;inset:0;width:40%;background:var(--teal);animation:slide 1s infinite ease-in-out}
.toolbar{display:flex;gap:10px;align-items:center;justify-content:space-between;padding:14px 16px;border-bottom:1px solid var(--line);flex-wrap:wrap}
.search{position:relative;flex:1;min-width:220px;max-width:420px}
.search i{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted)}
.search input{width:100%;padding:10px 12px 10px 36px;border:1px solid var(--line);border-radius:10px;background:var(--paper);color:var(--ink);font:inherit}
.search .spin{left:auto;right:12px;display:none;animation:spin .7s linear infinite}
.card.loading .search .spin{display:block}
.table-scroll{overflow-x:auto;position:relative}
table{width:100%;border-collapse:collapse;min-width:900px}
thead th{text-align:left;padding:12px 14px;font-weight:700;color:var(--muted);font-size:12.5px;white-space:nowrap}
thead th i{margin-right:6px;color:var(--teal)}
thead tr.titles th{padding-bottom:6px}
thead tr.filters th{padding-top:0;padding-bottom:14px;border-bottom:1px solid var(--line);background:var(--card)}
.f-input{width:100%;padding:7px 8px;border:1px solid var(--line);border-radius:8px;background:var(--paper);color:var(--ink);font:inherit;font-size:13px;cursor:pointer;transition:border-color .15s}
.f-input:hover{border-color:var(--teal)}
.f-input.on{border-color:var(--teal);background:var(--teal-soft)}
tbody td{padding:14px;border-bottom:1px solid var(--line);vertical-align:middle}
tbody tr{transition:background .15s}
tbody tr:hover{background:color-mix(in srgb,var(--teal) 5%,transparent)}
tbody tr.enter{animation:rowIn .35s ease both}
tbody tr.removing{animation:rowOut .4s ease forwards}
tbody tr.is-hidden td:not(.actions){opacity:.5}
.table-scroll .veil{position:absolute;inset:0;background:color-mix(in srgb,var(--card) 70%,transparent);backdrop-filter:blur(1px);
  display:none;place-items:center;z-index:2}
.card.loading .veil{display:grid;animation:fade .2s}
.loader{width:34px;height:34px;border:3px solid var(--teal-soft);border-top-color:var(--teal);border-radius:50%;animation:spin .7s linear infinite}
.num{width:48px;font-weight:800;color:var(--muted)}
.qtext{max-width:420px;font-weight:600}
.pill{display:inline-flex;align-items:center;gap:6px;padding:3px 10px;border-radius:99px;font-size:12px;font-weight:700;background:var(--paper);border:1px solid var(--line)}
.status{display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:99px;font-size:12px;font-weight:700}
.status.on{background:var(--teal-soft);color:var(--teal)}
.status.off{background:var(--amber-soft);color:var(--amber)}
.status .dot{width:7px;height:7px;border-radius:50%;background:currentColor}
.status.on .dot{animation:pulse 1.8s infinite}
.actions{white-space:nowrap;text-align:right}
.icon-btn{width:34px;height:34px;border-radius:9px;border:1px solid var(--line);background:transparent;color:var(--ink);cursor:pointer;
  display:inline-grid;place-items:center;font-size:15px;transition:transform .15s,background .15s,color .15s,border-color .15s}
.icon-btn:hover{transform:translateY(-2px)}
.icon-btn.edit:hover{background:var(--teal-soft);color:var(--teal);border-color:var(--teal)}
.icon-btn.hide:hover{background:var(--amber-soft);color:var(--amber);border-color:var(--amber)}
.icon-btn.del:hover{background:var(--red-soft);color:var(--red);border-color:var(--red)}
.empty{text-align:center;padding:56px 20px;color:var(--muted)}
.empty i{font-size:42px;color:var(--teal);display:block;margin-bottom:8px;animation:float 3s ease-in-out infinite}
.empty b{display:block;color:var(--ink);font-size:16px}

/* pager */
.pager{display:flex;justify-content:space-between;align-items:center;padding:14px 16px;color:var(--muted);flex-wrap:wrap;gap:10px}
.pager .btns{display:flex;gap:8px}

/* modal */
.modal{position:fixed;inset:0;background:rgba(10,20,22,.5);backdrop-filter:blur(3px);display:none;place-items:center;padding:16px;z-index:50}
.modal.open{display:grid;animation:fade .2s}
.dialog{background:var(--card);border-radius:18px;width:100%;max-width:520px;padding:24px;box-shadow:0 24px 60px rgba(0,0,0,.3);animation:dialogIn .3s cubic-bezier(.2,1.2,.4,1)}
.dialog h2{margin:0 0 16px;font-size:20px;display:flex;align-items:center;gap:10px}
.dialog.small{max-width:410px;text-align:center}
.warn{width:64px;height:64px;border-radius:50%;background:var(--red-soft);color:var(--red);display:grid;place-items:center;font-size:30px;margin:0 auto 12px;animation:shake .5s .15s}
.dialog.small p{color:var(--muted);margin:6px 0 20px}
.dialog.small .q{display:block;margin:10px 0;padding:10px;border-radius:10px;background:var(--paper);color:var(--ink);font-weight:600}
.field{margin-bottom:14px}
.field label{display:block;font-weight:700;margin-bottom:5px;font-size:13px}
.field input[type=text],.field input[type=number],.field textarea{width:100%;padding:10px 12px;border:1px solid var(--line);border-radius:10px;background:var(--paper);color:var(--ink);font:inherit}
.field textarea{min-height:90px;resize:vertical}
.field .err{color:var(--red);font-size:12px;margin-top:4px;display:none}
.field.bad input,.field.bad textarea{border-color:var(--red)}
.field.bad .err{display:block;animation:fade .2s}
.grid2{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.switch{display:flex;align-items:center;gap:10px;cursor:pointer;font-weight:700}
.switch input{appearance:none;width:42px;height:24px;border-radius:99px;background:var(--line);position:relative;cursor:pointer;transition:background .2s;margin:0}
.switch input::after{content:"";position:absolute;top:3px;left:3px;width:18px;height:18px;border-radius:50%;background:#fff;transition:transform .2s}
.switch input:checked{background:var(--teal)}
.switch input:checked::after{transform:translateX(18px)}
.dialog .foot{display:flex;gap:10px;justify-content:flex-end;margin-top:18px}
.dialog.small .foot{justify-content:center}

/* toast */
#toasts{position:fixed;right:18px;bottom:18px;display:flex;flex-direction:column;gap:10px;z-index:100}
.toast{background:var(--ink);color:var(--paper);padding:12px 16px;border-radius:12px;display:flex;align-items:center;gap:10px;font-weight:600;
  box-shadow:0 10px 30px rgba(0,0,0,.25);animation:toastIn .35s cubic-bezier(.2,1.2,.4,1)}
.toast.out{animation:toastOut .3s forwards}
.toast i{font-size:18px;color:#5eead4}
.toast.error i{color:#ff9d96}

@keyframes spin{to{transform:rotate(360deg)}}
@keyframes slide{0%{left:-40%}100%{left:100%}}
@keyframes fade{from{opacity:0}to{opacity:1}}
@keyframes pop{from{transform:scale(.4) rotate(-20deg);opacity:0}to{transform:none;opacity:1}}
@keyframes rowIn{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:none}}
@keyframes rowOut{to{opacity:0;transform:translateX(40px);background:var(--red-soft)}}
@keyframes dialogIn{from{opacity:0;transform:translateY(20px) scale(.94)}to{opacity:1;transform:none}}
@keyframes toastIn{from{opacity:0;transform:translateX(40px)}to{opacity:1;transform:none}}
@keyframes toastOut{to{opacity:0;transform:translateX(40px)}}
@keyframes shake{0%,100%{transform:rotate(0)}20%{transform:rotate(-12deg)}40%{transform:rotate(10deg)}60%{transform:rotate(-6deg)}80%{transform:rotate(4deg)}}
@keyframes pulse{0%{box-shadow:0 0 0 0 currentColor}70%{box-shadow:0 0 0 6px transparent}100%{box-shadow:0 0 0 0 transparent}}
@keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-6px)}}
@media (prefers-reduced-motion:reduce){*{animation-duration:.01ms!important;animation-iteration-count:1!important;transition:none!important}}
@media (max-width:560px){.grid2{grid-template-columns:1fr}}
</style>
</head>
<body>
<div class="wrap">

  {{-- Header --}}
  <div class="top">
    <div>
      <h1><i class="bi bi-ui-checks"></i> Checklist questions</h1>
      <p>Add, edit, hide or remove the questions admins and staff answer.</p>
    </div>
    <div class="stats">
      <div class="stat"><b id="statTotal">{{ $stats['total'] }}</b><span>Total</span></div>
      <div class="stat ok"><b id="statActive">{{ $stats['active'] }}</b><span>Visible</span></div>
      <div class="stat off"><b id="statHidden">{{ $stats['hidden'] }}</b><span>Hidden</span></div>
    </div>
  </div>

  {{-- Table card --}}
  <div class="card" id="card">
    <div class="bar"></div>

    <div class="toolbar">
      <div class="search">
        <i class="bi bi-search"></i>
        <input type="search" id="search" placeholder="Search question text" value="{{ request('search') }}" autocomplete="off">
        <i class="bi bi-arrow-repeat spin"></i>
      </div>
      <div style="display:flex;gap:10px">
        <button class="btn btn-ghost" id="resetBtn"><i class="bi bi-x-circle"></i> Clear filters</button>
        <button class="btn btn-primary" id="addBtn"><i class="bi bi-plus-lg"></i> Add question</button>
      </div>
    </div>

    <div class="table-scroll">
      <div class="veil"><div class="loader"></div></div>
      <table>
        <thead>
          <tr class="titles">
            <th><i class="bi bi-sort-numeric-down"></i>Order</th>
            <th><i class="bi bi-chat-left-text"></i>Question</th>
            <th><i class="bi bi-tag"></i>Type</th>
            <th><i class="bi bi-calendar-check"></i>Frequency</th>
            <th><i class="bi bi-diagram-2"></i>Variant</th>
            <th><i class="bi bi-toggle-on"></i>Status</th>
            <th style="text-align:right"><i class="bi bi-lightning-charge"></i>Actions</th>
          </tr>
          <tr class="filters">
            <th></th>
            <th></th>
            <th>
              <select class="f-input" data-filter="type">
                <option value="">All types</option>
                @foreach($types as $t)
                  <option value="{{ $t }}" @selected(request('type') === $t)>{{ ucfirst($t) }}</option>
                @endforeach
              </select>
            </th>
            <th>
              <select class="f-input" data-filter="frequency">
                <option value="">All frequencies</option>
                @foreach($frequencies as $f)
                  <option value="{{ $f }}" @selected(request('frequency') === $f)>{{ ucfirst($f) }}</option>
                @endforeach
              </select>
            </th>
            <th>
              <select class="f-input" data-filter="variant">
                <option value="">All variants</option>
                @foreach($variants as $v)
                  <option value="{{ $v }}" @selected(request('variant') === $v)>{{ ucfirst($v) }}</option>
                @endforeach
              </select>
            </th>
            <th>
              <select class="f-input" data-filter="is_active">
                <option value="">All statuses</option>
                <option value="1" @selected(request('is_active') === '1')>Visible</option>
                <option value="0" @selected(request('is_active') === '0')>Hidden</option>
              </select>
            </th>
            <th></th>
          </tr>
        </thead>

        <tbody id="tableBody">
          @forelse($questions as $q)
            <tr class="enter {{ $q->is_active ? '' : 'is-hidden' }}" style="animation-delay:{{ $loop->index * 30 }}ms"
                data-id="{{ $q->id }}"
                data-question="{{ json_encode($q->only(['id','type','frequency','question_text','order','variant','is_active'])) }}">
              <td class="num">{{ $q->order }}</td>
              <td class="qtext">{{ $q->question_text }}</td>
              <td><span class="pill"><i class="bi bi-tag"></i>{{ $q->type }}</span></td>
              <td><span class="pill"><i class="bi bi-arrow-repeat"></i>{{ $q->frequency }}</span></td>
              <td>{!! $q->variant ? '<span class="pill"><i class="bi bi-diagram-2"></i>'.e($q->variant).'</span>' : '<span style="color:var(--muted)">None</span>' !!}</td>
              <td>
                @if($q->is_active)
                  <span class="status on"><span class="dot"></span>Visible</span>
                @else
                  <span class="status off"><span class="dot"></span>Hidden</span>
                @endif
              </td>
              <td class="actions">
                <button class="icon-btn edit" data-action="edit" title="Edit question"><i class="bi bi-pencil-square"></i></button>
                <button class="icon-btn hide" data-action="toggle" title="{{ $q->is_active ? 'Hide question' : 'Show question' }}">
                  <i class="bi {{ $q->is_active ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                </button>
                <button class="icon-btn del" data-action="delete" title="Remove question"><i class="bi bi-trash3"></i></button>
              </td>
            </tr>
          @empty
            <tr><td colspan="7">
              <div class="empty">
                <i class="bi bi-clipboard-x"></i>
                <b>No questions found</b>
                Change the filters or add a new question.
              </div>
            </td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="pager" id="pager">
      <span>Showing {{ $questions->firstItem() ?? 0 }} to {{ $questions->lastItem() ?? 0 }} of {{ $questions->total() }} questions</span>
      <div class="btns">
        <button class="btn btn-ghost" data-page="{{ $questions->currentPage() - 1 }}" @disabled($questions->onFirstPage())><i class="bi bi-chevron-left"></i> Previous</button>
        <button class="btn btn-ghost" data-page="{{ $questions->currentPage() + 1 }}" @disabled(!$questions->hasMorePages())>Next <i class="bi bi-chevron-right"></i></button>
      </div>
    </div>
  </div>
</div>

{{-- Add / edit modal --}}
<div class="modal" id="formModal" aria-hidden="true">
  <form class="dialog" id="qForm" novalidate>
    <h2><i class="bi bi-plus-circle" id="formIcon" style="color:var(--teal)"></i><span id="formTitle">Add question</span></h2>
    <input type="hidden" id="qId">

    <div class="field" data-field="question_text">
      <label for="question_text">Question</label>
      <textarea id="question_text" placeholder="e.g. Is the fire exit clear?"></textarea>
      <div class="err"></div>
    </div>

    <div class="grid2">
      <div class="field" data-field="type">
        <label for="type">Type</label>
        <input type="text" id="type" list="typeList" placeholder="e.g. safety">
        <div class="err"></div>
      </div>
      <div class="field" data-field="frequency">
        <label for="frequency">Frequency</label>
        <input type="text" id="frequency" list="freqList" placeholder="e.g. daily">
        <div class="err"></div>
      </div>
      <div class="field" data-field="variant">
        <label for="variant">Variant (optional)</label>
        <input type="text" id="variant" list="variantList" placeholder="e.g. standard">
        <div class="err"></div>
      </div>
      <div class="field" data-field="order">
        <label for="order">Order</label>
        <input type="number" id="order" min="0" placeholder="Auto">
        <div class="err"></div>
      </div>
    </div>

    <datalist id="typeList">@foreach($types as $t)<option value="{{ $t }}">@endforeach</datalist>
    <datalist id="freqList">@foreach($frequencies as $f)<option value="{{ $f }}">@endforeach</datalist>
    <datalist id="variantList">@foreach($variants as $v)<option value="{{ $v }}">@endforeach</datalist>

    <label class="switch"><input type="checkbox" id="is_active" checked> Visible to users</label>

    <div class="foot">
      <button type="button" class="btn btn-ghost" data-close>Cancel</button>
      <button type="submit" class="btn btn-primary" id="saveBtn"><i class="bi bi-check2"></i> Save question</button>
    </div>
  </form>
</div>

{{-- Delete confirmation modal --}}
<div class="modal" id="delModal" aria-hidden="true">
  <div class="dialog small">
    <div class="warn"><i class="bi bi-exclamation-triangle"></i></div>
    <h2 style="justify-content:center;margin:0">Remove this question?</h2>
    <span class="q" id="delText"></span>
    <p>This permanently deletes the question. If you only want to stop showing it, hide it instead.</p>
    <div class="foot">
      <button class="btn btn-ghost" data-close>Keep it</button>
      <button class="btn btn-danger" id="confirmDel"><i class="bi bi-trash3"></i> Remove question</button>
    </div>
  </div>
</div>

<div id="toasts"></div>

<script>
const BASE = @json(route('admin.checklist-questions.index'));
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
const $  = (s, r = document) => r.querySelector(s);
const $$ = (s, r = document) => [...r.querySelectorAll(s)];

const card = $('#card'), searchEl = $('#search');
let currentPage = 1, debounce, reqId = 0, deleteId = null;

/* ---------- helpers ---------- */
function toast(msg, type = 'success') {
  const t = document.createElement('div');
  t.className = 'toast ' + type;
  t.innerHTML = `<i class="bi ${type === 'error' ? 'bi-x-octagon' : 'bi-check-circle-fill'}"></i><span></span>`;
  t.querySelector('span').textContent = msg;
  $('#toasts').appendChild(t);
  setTimeout(() => { t.classList.add('out'); setTimeout(() => t.remove(), 300); }, 3200);
}

async function api(url, method = 'GET', body = null) {
  const res = await fetch(url, {
    method,
    headers: {
      'X-CSRF-TOKEN': CSRF,
      'Accept': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
      ...(body ? { 'Content-Type': 'application/json' } : {})
    },
    body: body ? JSON.stringify(body) : null
  });
  const data = await res.json().catch(() => ({}));
  if (!res.ok) { const e = new Error(data.message || 'Something went wrong'); e.status = res.status; e.errors = data.errors; throw e; }
  return data;
}

/* ---------- filtering + loading ---------- */
function buildUrl(page = 1) {
  const p = new URLSearchParams();
  if (searchEl.value.trim()) p.set('search', searchEl.value.trim());
  $$('[data-filter]').forEach(s => { if (s.value !== '') p.set(s.dataset.filter, s.value); });
  if (page > 1) p.set('page', page);
  const qs = p.toString();
  return BASE + (qs ? '?' + qs : '');
}

async function load(page = 1) {
  currentPage = page;
  const id = ++reqId;
  card.classList.add('loading');
  const url = buildUrl(page);
  try {
    const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
    if (!res.ok) throw new Error();
    const html = await res.text();
    if (id !== reqId) return; // a newer request replaced this one
    const doc = new DOMParser().parseFromString(html, 'text/html');
    $('#tableBody').innerHTML = doc.querySelector('#tableBody').innerHTML;
    $('#pager').innerHTML = doc.querySelector('#pager').innerHTML;
    ['statTotal', 'statActive', 'statHidden'].forEach(k => $('#' + k).textContent = doc.getElementById(k).textContent);
    history.replaceState(null, '', url);
    markActiveFilters();
  } catch (e) {
    if (id === reqId) toast('Could not load questions. Try again.', 'error');
  } finally {
    if (id === reqId) setTimeout(() => card.classList.remove('loading'), 250);
  }
}

function markActiveFilters() {
  $$('[data-filter]').forEach(s => s.classList.toggle('on', s.value !== ''));
}

searchEl.addEventListener('input', () => { clearTimeout(debounce); card.classList.add('loading'); debounce = setTimeout(() => load(1), 400); });
$$('[data-filter]').forEach(s => s.addEventListener('change', () => load(1)));
$('#resetBtn').addEventListener('click', () => {
  searchEl.value = ''; $$('[data-filter]').forEach(s => s.value = ''); load(1);
});
$('#pager').addEventListener('click', e => {
  const b = e.target.closest('[data-page]'); if (b && !b.disabled) load(+b.dataset.page);
});
markActiveFilters();

/* ---------- modals ---------- */
function openModal(el) { el.classList.add('open'); el.setAttribute('aria-hidden', 'false'); }
function closeModal(el) { el.classList.remove('open'); el.setAttribute('aria-hidden', 'true'); }
$$('.modal').forEach(m => {
  m.addEventListener('click', e => { if (e.target === m || e.target.closest('[data-close]')) closeModal(m); });
});
document.addEventListener('keydown', e => { if (e.key === 'Escape') $$('.modal.open').forEach(closeModal); });

/* ---------- add / edit ---------- */
const form = $('#qForm');
function clearErrors() { $$('.field', form).forEach(f => f.classList.remove('bad')); }

$('#addBtn').addEventListener('click', () => {
  form.reset(); clearErrors();
  $('#qId').value = '';
  $('#is_active').checked = true;
  $('#formTitle').textContent = 'Add question';
  $('#formIcon').className = 'bi bi-plus-circle';
  openModal($('#formModal'));
  setTimeout(() => $('#question_text').focus(), 50);
});

function openEdit(q) {
  form.reset(); clearErrors();
  $('#qId').value = q.id;
  $('#question_text').value = q.question_text ?? '';
  $('#type').value = q.type ?? '';
  $('#frequency').value = q.frequency ?? '';
  $('#variant').value = q.variant ?? '';
  $('#order').value = q.order ?? '';
  $('#is_active').checked = !!q.is_active;
  $('#formTitle').textContent = 'Edit question';
  $('#formIcon').className = 'bi bi-pencil-square';
  openModal($('#formModal'));
}

form.addEventListener('submit', async e => {
  e.preventDefault(); clearErrors();
  const id = $('#qId').value;
  const btn = $('#saveBtn'), old = btn.innerHTML;
  btn.disabled = true; btn.innerHTML = '<i class="bi bi-arrow-repeat" style="animation:spin .7s linear infinite"></i> Saving';
  const payload = {
    question_text: $('#question_text').value.trim(),
    type: $('#type').value.trim(),
    frequency: $('#frequency').value.trim(),
    variant: $('#variant').value.trim() || null,
    order: $('#order').value === '' ? null : +$('#order').value,
    is_active: $('#is_active').checked ? 1 : 0
  };
  try {
    const data = await api(id ? `${BASE}/${id}` : BASE, id ? 'PUT' : 'POST', payload);
    closeModal($('#formModal'));
    toast(data.message);
    load(currentPage);
  } catch (err) {
    if (err.status === 422 && err.errors) {
      Object.entries(err.errors).forEach(([k, msgs]) => {
        const f = $(`.field[data-field="${k}"]`, form);
        if (f) { f.classList.add('bad'); $('.err', f).textContent = msgs[0]; }
      });
    } else toast(err.message, 'error');
  } finally { btn.disabled = false; btn.innerHTML = old; }
});

/* ---------- row actions ---------- */
$('#tableBody').addEventListener('click', async e => {
  const btn = e.target.closest('[data-action]'); if (!btn) return;
  const row = btn.closest('tr');
  const q = JSON.parse(row.dataset.question);

  if (btn.dataset.action === 'edit') return openEdit(q);

  if (btn.dataset.action === 'toggle') {
    btn.disabled = true;
    try {
      const data = await api(`${BASE}/${q.id}/toggle`, 'PATCH');
      toast(data.message);
      load(currentPage);
    } catch (err) { toast(err.message, 'error'); btn.disabled = false; }
    return;
  }

  if (btn.dataset.action === 'delete') {
    deleteId = q.id;
    $('#delText').textContent = q.question_text;
    openModal($('#delModal'));
  }
});

$('#confirmDel').addEventListener('click', async () => {
  const btn = $('#confirmDel'), old = btn.innerHTML;
  btn.disabled = true; btn.innerHTML = '<i class="bi bi-arrow-repeat" style="animation:spin .7s linear infinite"></i> Removing';
  try {
    const data = await api(`${BASE}/${deleteId}`, 'DELETE');
    closeModal($('#delModal'));
    const row = $(`tr[data-id="${deleteId}"]`);
    if (row) row.classList.add('removing');
    toast(data.message);
    setTimeout(() => load(currentPage), 400);
  } catch (err) { toast(err.message, 'error'); }
  finally { btn.disabled = false; btn.innerHTML = old; }
});
</script>
</body>
</html>
