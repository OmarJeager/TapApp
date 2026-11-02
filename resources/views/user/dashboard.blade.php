<x-app-layout>
    <x-slot name="header">
        <div class="dash-header">
            <div>
                <span class="eyebrow">TAP APP / WORKSPACE</span>
                <h1>{{ __('Dashboard') }}</h1>
                <p>Your personal workspace and account settings.</p>
            </div>
            <div class="header-date">
                <span class="status-dot"></span>
                <span>Account overview</span>
            </div>
        </div>
    </x-slot>

    <div class="dashboard-shell">
        <div class="dashboard-container">

            @if (session('success'))
                <div class="notice notice-success" role="status">
                    <span class="notice-icon">✓</span>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="notice-close" aria-label="Dismiss" onclick="this.parentElement.remove()">×</button>
                </div>
            @endif

            @if (session('error'))
                <div class="notice notice-error" role="alert">
                    <span class="notice-icon">!</span>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="notice-close" aria-label="Dismiss" onclick="this.parentElement.remove()">×</button>
                </div>
            @endif

            @if ($errors->has('signature'))
                <div class="notice notice-error" role="alert">
                    <span class="notice-icon">!</span>
                    <div>{{ $errors->first('signature') }}</div>
                </div>
            @endif

            <section class="welcome-panel">
                <div class="welcome-copy">
                    <span class="welcome-kicker"><span class="welcome-kicker-dot"></span> YOUR TAP APP SPACE</span>
                    <h2>Welcome back,<br><strong>{{ auth()->user()->name }}</strong></h2>
                    <p>Everything you need to access your workspace is right here.</p>
                    <div class="welcome-actions">
                        <a href="{{ route('user.index') }}" class="action-btn action-home">
                            <span class="action-icon">⌂</span>
                            <span>Home</span>
                            <span class="action-arrow">↗</span>
                        </a>
                        <a href="{{ route('scan.index') }}" class="action-btn action-scan">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 8V5a1 1 0 0 1 1-1h3M16 4h3a1 1 0 0 1 1 1v3M20 16v3a1 1 0 0 1-1 1h-3M8 20H5a1 1 0 0 1-1-1v-3"/><path d="M7 7h4v4H7zM13 7h4M13 11h4M7 13h4v4H7zM15 14v3"/></svg>
                            <span>Scan</span>
                            <span class="action-arrow">↗</span>
                        </a>
                    </div>
                </div>
                <div class="welcome-art" aria-hidden="true">
                    <div class="art-orbit orbit-one"></div>
                    <div class="art-orbit orbit-two"></div>
                    <div class="art-core">
                        <span class="art-core-mark">T</span>
                        <span class="art-core-label">TAP APP</span>
                    </div>
                    <span class="art-chip chip-one">MAINTENANCE</span>
                    <span class="art-chip chip-two">WORKSPACE</span>
                    <span class="art-spark spark-one">✦</span>
                    <span class="art-spark spark-two">✧</span>
                </div>
            </section>

            <div class="section-heading">
                <div>
                    <span class="eyebrow">PERSONAL SETTINGS</span>
                    <h2>Your profile</h2>
                    <p>Manage your profile picture and saved signature.</p>
                </div>
                <span class="section-count">01 / 02</span>
            </div>

            <section class="profile-grid">
                <article class="surface-card profile-card">
                    <div class="card-topline">
                        <div class="card-icon icon-purple">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 21a8 8 0 0 0-16 0M12 13a5 5 0 1 0 0-10 5 5 0 0 0 0 10Z"/></svg>
                        </div>
                        <span class="card-tag">PROFILE</span>
                    </div>

                    <div class="profile-content">
                        <div class="avatar-wrap">
                            @if (auth()->user()->profile_picture)
                                <img
                                    id="currentProfilePicture"
                                    class="profile-avatar"
                                    src="{{ asset('storage/' . auth()->user()->profile_picture) }}?v={{ time() }}"
                                    alt="Profile picture"
                                >
                            @else
                                <div id="currentProfilePicture" class="profile-avatar avatar-placeholder">
                                    {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <span class="avatar-online" title="Account active"></span>
                        </div>

                        <h3>{{ auth()->user()->name }}</h3>
                        <p class="profile-caption">Your account profile</p>

                        <button type="button" class="primary-btn" id="choosePictureButton">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 16V4m-4 4 4-4 4 4M4 16v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3"/></svg>
                            Change profile picture
                        </button>
                        <input type="file" id="picture" accept="image/jpeg,image/png,image/webp" hidden>
                        <p class="helper-text">JPG, PNG or WEBP · Square crop recommended</p>
                    </div>
                </article>

                <article class="surface-card signature-card">
                    <div class="card-topline">
                        <div class="card-icon icon-cyan">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m4 16 4-1 9-9a2.1 2.1 0 0 0-3-3l-9 9-1 4ZM13 5l3 3M4 20h16"/></svg>
                        </div>
                        <span class="card-tag">IDENTITY</span>
                    </div>

                    <div class="signature-intro">
                        <h3>My signature</h3>
                        <p>Draw your signature in the space below. It will be saved securely to your account.</p>
                    </div>

                    @if (auth()->user()->signature)
                        <div class="saved-signature">
                            <div class="saved-signature-heading">
                                <span>Saved signature</span>
                                <span class="saved-pill"><i></i> On file</span>
                            </div>
                            <div class="saved-signature-preview">
                                <img
                                    src="{{ asset('storage/' . auth()->user()->signature) }}?v={{ time() }}"
                                    alt="Current saved signature"
                                >
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.signature.update') }}" id="signatureForm" class="signature-form">
                        @csrf
                        <div class="canvas-heading">
                            <label for="signatureCanvas">Draw your signature</label>
                            <span>Use your mouse or touch screen</span>
                        </div>
                        <div class="canvas-frame" id="canvasFrame">
                            <canvas id="signatureCanvas" aria-label="Signature drawing area"></canvas>
                            <div class="canvas-placeholder" id="canvasPlaceholder">
                                <span class="placeholder-pen">✎</span>
                                <span>Sign here</span>
                            </div>
                            <span class="canvas-baseline"></span>
                        </div>
                        <input type="hidden" name="signature" id="signatureInput">
                        @error('signature')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                        <div class="signature-footer">
                            <span class="signature-hint"><span class="hint-dot"></span> Your signature is ready when saved</span>
                            <div class="signature-actions">
                                <button type="button" id="clearSignature" class="secondary-btn">Clear</button>
                                <button type="submit" id="saveSignature" class="primary-btn save-signature">
                                    Save signature <span>→</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </article>
            </section>

            <footer class="dashboard-footer">
                <span><span class="footer-mark">T</span> TAP APP <span class="footer-separator">·</span> Maintenance Management</span>
                <span>Personal workspace</span>
            </footer>
        </div>
    </div>

    {{-- Profile picture crop modal --}}
    <div id="cropModal" class="crop-modal" aria-hidden="true">
        <div class="crop-backdrop" data-close-crop></div>
        <section class="crop-dialog" role="dialog" aria-modal="true" aria-labelledby="cropTitle">
            <header class="crop-header">
                <div>
                    <span class="eyebrow">PROFILE IMAGE</span>
                    <h2 id="cropTitle">Adjust your picture</h2>
                </div>
                <button type="button" class="modal-close" id="closeCropButton" aria-label="Close">×</button>
            </header>
            <div class="crop-stage">
                <div class="crop-container"><img id="cropImage" src="" alt="Image to crop"></div>
            </div>
            <div class="crop-tools">
                <button type="button" class="crop-tool" id="zoomOutButton" aria-label="Zoom out">−</button>
                <button type="button" class="crop-tool" id="zoomInButton" aria-label="Zoom in">+</button>
                <button type="button" class="crop-reset" id="resetCropButton">Reset crop</button>
                <span class="crop-tip">Drag to reposition · Scroll to zoom</span>
            </div>
            <footer class="crop-footer">
                <button type="button" class="secondary-btn" id="cancelCropButton">Cancel</button>
                <button type="button" class="primary-btn" id="saveButton">Save picture <span>→</span></button>
            </footer>
            <p class="crop-error" id="cropError" role="alert"></p>
        </section>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.css">
    <style>
        :root {
            --tap-ink: #172033;
            --tap-muted: #778198;
            --tap-line: #e8ebf2;
            --tap-purple: #f28a16;
            --tap-purple-dark: #d96b08;
            --tap-cyan: #ffb347;
            --tap-bg: #f5f6fb;
            --tap-card: #fff;
        }
        .dashboard-shell { min-height: calc(100vh - 70px); padding: 34px 22px 28px; color: var(--tap-ink); background: radial-gradient(ellipse at 8% 0%, rgba(242,138,22,.09), transparent 30%), radial-gradient(ellipse at 95% 25%, rgba(255,179,71,.09), transparent 27%), var(--tap-bg); }
        .dashboard-container { width: min(1180px, 100%); margin: 0 auto; }
        .dash-header { display:flex; justify-content:space-between; align-items:center; gap:20px; }
        .dash-header h1 { margin:4px 0 2px; color:#202940; font-size:23px; font-weight:800; letter-spacing:-.6px; }
        .dash-header p { margin:0; color:#8992a5; font-size:12px; }
        .eyebrow { display:block; color:#c47a2b; font-size:9px; font-weight:800; letter-spacing:2px; }
        .header-date { display:flex; align-items:center; gap:9px; padding:10px 14px; border:1px solid #e9eaf2; border-radius:30px; background:#fff; color:#6f7890; font-size:11px; font-weight:700; }
        .status-dot,.welcome-kicker-dot { width:7px; height:7px; border-radius:50%; background:#35c99a; box-shadow:0 0 0 4px rgba(53,201,154,.12); }
        .notice { display:flex; align-items:center; gap:12px; margin-bottom:16px; padding:14px 16px; border:1px solid transparent; border-radius:13px; font-size:13px; animation:rise-in .45s ease both; }
        .notice-success { color:#176b50; border-color:#c9efdf; background:#edfbf4; }
        .notice-error { color:#a33b4b; border-color:#f5d4da; background:#fff1f3; }
        .notice-icon { display:grid; width:25px; height:25px; flex:none; place-items:center; border-radius:50%; background:rgba(0,0,0,.06); font-weight:900; }
        .notice-close { margin-left:auto; border:0; background:transparent; color:inherit; font-size:22px; cursor:pointer; }
        .welcome-panel { position:relative; display:flex; min-height:290px; align-items:center; justify-content:space-between; overflow:hidden; margin-bottom:48px; padding:42px 55px; border-radius:25px; color:#fff; background:linear-gradient(112deg,#302316 0%,#8a4b12 53%,#f28a16 100%); box-shadow:0 22px 48px rgba(52,45,113,.18); isolation:isolate; animation:rise-in .65s cubic-bezier(.2,.75,.25,1) both; }
        .welcome-panel::before,.welcome-panel::after { position:absolute; z-index:-1; content:""; border:1px solid rgba(255,255,255,.1); border-radius:50%; }
        .welcome-panel::before { width:480px; height:480px; right:8%; top:-290px; }
        .welcome-panel::after { width:360px; height:360px; right:13%; bottom:-280px; }
        .welcome-copy { position:relative; z-index:2; max-width:570px; }
        .welcome-kicker { display:flex; align-items:center; gap:10px; color:#ffe0b5; font-size:9px; font-weight:800; letter-spacing:2px; }
        .welcome-kicker-dot { background:#72e5c0; }
        .welcome-copy h2 { margin:18px 0 10px; color:#fff; font-size:clamp(29px,4vw,43px); line-height:1.13; font-weight:400; letter-spacing:-1.5px; }
        .welcome-copy h2 strong { color:#ffe0b5; font-weight:800; }
        .welcome-copy p { margin:0; color:#f2d8ba; font-size:13px; }
        .welcome-actions { display:flex; flex-wrap:wrap; gap:12px; margin-top:28px; }
        .action-btn { position:relative; display:inline-flex; min-width:142px; align-items:center; justify-content:center; gap:10px; padding:12px 16px; overflow:hidden; border:1px solid rgba(255,255,255,.18); border-radius:11px; color:#fff; text-decoration:none; font-size:12px; font-weight:800; transition:transform .25s,box-shadow .25s,background .25s; }
        .action-btn:hover { transform:translateY(-4px); box-shadow:0 10px 24px rgba(10,8,40,.25); }
        .action-home { background:linear-gradient(135deg,#ffad42,#e8790c); }
        .action-scan { background:rgba(255,255,255,.1); backdrop-filter:blur(8px); }
        .action-btn svg { width:18px; height:18px; fill:none; stroke:currentColor; stroke-width:1.8; stroke-linecap:round; stroke-linejoin:round; }
        .action-icon { font-size:22px; line-height:15px; }
        .action-arrow { margin-left:10px; opacity:.7; transition:transform .25s; }
        .action-btn:hover .action-arrow { transform:translate(3px,-3px); }
        .welcome-art { position:relative; width:300px; height:230px; flex:none; margin-right:5%; }
        .art-orbit { position:absolute; top:50%; left:50%; border:1px solid rgba(214,209,255,.23); border-radius:50%; transform:translate(-50%,-50%); }
        .orbit-one { width:205px; height:205px; animation:orbit 22s linear infinite; }
        .orbit-two { width:270px; height:150px; transform:translate(-50%,-50%) rotate(-35deg); border-style:dashed; animation:orbit 32s linear infinite reverse; }
        .art-core { position:absolute; top:50%; left:50%; display:flex; width:112px; height:112px; flex-direction:column; align-items:center; justify-content:center; border:1px solid rgba(255,255,255,.4); border-radius:31px; background:linear-gradient(145deg,rgba(255,255,255,.24),rgba(255,255,255,.06)); box-shadow:0 18px 45px rgba(15,10,60,.24),inset 0 1px rgba(255,255,255,.25); transform:translate(-50%,-50%) rotate(-8deg); backdrop-filter:blur(10px); }
        .art-core-mark { color:#fff; font-size:51px; line-height:1; font-weight:900; }
        .art-core-label { margin-top:4px; color:#ffe7c8; font-size:7px; font-weight:800; letter-spacing:2px; }
        .art-chip { position:absolute; padding:8px 11px; border:1px solid rgba(255,255,255,.2); border-radius:8px; background:rgba(255,255,255,.12); color:#e5e1ff; font-size:7px; font-weight:800; letter-spacing:1.4px; backdrop-filter:blur(8px); }
        .chip-one { top:18px; right:5px; transform:rotate(7deg); }
        .chip-two { bottom:17px; left:3px; transform:rotate(-7deg); }
        .art-spark { position:absolute; color:#ffe0a6; font-size:24px; animation:float 4s ease-in-out infinite; }
        .spark-one { top:25px; left:28px; }.spark-two { right:22px; bottom:30px; animation-delay:1.2s; }
        .section-heading { display:flex; justify-content:space-between; align-items:flex-end; gap:20px; margin-bottom:19px; }
        .section-heading h2 { margin:5px 0 3px; color:#222b40; font-size:22px; font-weight:800; letter-spacing:-.7px; }
        .section-heading p { margin:0; color:#8b94a6; font-size:12px; }
        .section-count { padding:7px 11px; border:1px solid #e5e7f0; border-radius:8px; color:#9299aa; background:#fff; font-size:9px; font-weight:800; letter-spacing:1px; }
        .profile-grid { display:grid; grid-template-columns:minmax(260px,.78fr) minmax(0,1.55fr); gap:20px; align-items:stretch; }
        .surface-card { position:relative; overflow:hidden; padding:25px; border:1px solid #eaecf3; border-radius:19px; background:var(--tap-card); box-shadow:0 8px 28px rgba(38,45,75,.045); transition:transform .3s,box-shadow .3s; animation:rise-in .7s ease both; }
        .surface-card:hover { transform:translateY(-4px); box-shadow:0 16px 35px rgba(38,45,75,.08); }
        .signature-card { animation-delay:.1s; }
        .card-topline { display:flex; align-items:center; justify-content:space-between; }
        .card-icon { display:grid; width:38px; height:38px; place-items:center; border-radius:12px; }
        .card-icon svg { width:19px; height:19px; fill:none; stroke:currentColor; stroke-width:1.7; stroke-linecap:round; stroke-linejoin:round; }
        .icon-purple { color:#e47b12; background:#fff1df; }.icon-cyan { color:#d97706; background:#fff3df; }
        .card-tag { color:#a0a7b6; font-size:8px; font-weight:900; letter-spacing:1.7px; }
        .profile-content { display:flex; flex-direction:column; align-items:center; padding:25px 8px 12px; text-align:center; }
        .avatar-wrap { position:relative; width:116px; height:116px; padding:5px; border:1px solid #ffead1; border-radius:50%; background:linear-gradient(140deg,#fff0df,#fff); box-shadow:0 8px 22px rgba(242,138,22,.14); }
        .profile-avatar { display:grid; width:100%; height:100%; place-items:center; border-radius:50%; object-fit:cover; background:#e9e7f8; color:#b85e08; font-size:39px; font-weight:800; }
        .avatar-placeholder { background:linear-gradient(145deg,#ffead1,#ffe1b8); }
        .avatar-online { position:absolute; right:5px; bottom:8px; width:15px; height:15px; border:3px solid #fff; border-radius:50%; background:#37c99b; }
        .profile-content h3 { margin:16px 0 3px; color:#263047; font-size:17px; font-weight:800; }
        .profile-caption { margin:0; color:#9aa1b0; font-size:11px; }
        .primary-btn,.secondary-btn { display:inline-flex; align-items:center; justify-content:center; gap:9px; min-height:42px; padding:0 17px; border:0; border-radius:10px; cursor:pointer; font-size:11px; font-weight:800; text-decoration:none; transition:transform .2s,box-shadow .2s,background .2s; }
        .primary-btn { color:#fff; background:linear-gradient(120deg,#f59a2a,#df760d); box-shadow:0 7px 16px rgba(242,138,22,.22); }
        .primary-btn:hover { transform:translateY(-2px); box-shadow:0 10px 22px rgba(217,107,8,.30); }
        .primary-btn:disabled { opacity:.65; cursor:wait; transform:none; }
        .primary-btn svg { width:16px; height:16px; fill:none; stroke:currentColor; stroke-width:1.8; stroke-linecap:round; stroke-linejoin:round; }
        .profile-content .primary-btn { margin-top:20px; }
        .helper-text { margin:11px 0 0; color:#a2a9b7; font-size:9px; }
        .signature-intro { margin:22px 0 18px; }
        .signature-intro h3 { margin:0 0 6px; color:#263047; font-size:17px; font-weight:800; }
        .signature-intro p { max-width:520px; margin:0; color:#929aab; font-size:11px; line-height:1.7; }
        .saved-signature { margin:0 0 19px; padding:12px; border:1px solid #e9edf2; border-radius:12px; background:#fbfcfe; }
        .saved-signature-heading { display:flex; align-items:center; justify-content:space-between; margin-bottom:9px; color:#707a8f; font-size:10px; font-weight:800; }
        .saved-pill { display:inline-flex; align-items:center; gap:5px; color:#b96b13; font-size:9px; }
        .saved-pill i { width:6px; height:6px; border-radius:50%; background:#f59e0b; }
        .saved-signature-preview { display:flex; min-height:65px; align-items:center; justify-content:center; padding:8px; border:1px dashed #e2e6ed; border-radius:8px; background:#fff; }
        .saved-signature-preview img { max-width:100%; max-height:75px; object-fit:contain; }
        .signature-form { margin-top:3px; }
        .canvas-heading { display:flex; align-items:center; justify-content:space-between; gap:10px; margin-bottom:9px; }
        .canvas-heading label { color:#59647a; font-size:10px; font-weight:800; }
        .canvas-heading span { color:#a4abba; font-size:9px; }
        .canvas-frame { position:relative; height:145px; overflow:hidden; border:1px solid #e3e7ef; border-radius:12px; background:linear-gradient(#fff,#fcfcff); }
        #signatureCanvas { position:relative; z-index:2; display:block; width:100%; height:100%; touch-action:none; cursor:crosshair; }
        .canvas-placeholder { position:absolute; z-index:1; top:50%; left:50%; display:flex; align-items:center; gap:8px; color:#c4c9d4; font-size:12px; pointer-events:none; transform:translate(-50%,-50%); transition:opacity .2s; }
        .canvas-placeholder.is-hidden { opacity:0; }
        .placeholder-pen { font-size:22px; }
        .canvas-baseline { position:absolute; right:22px; bottom:29px; left:22px; border-bottom:1px dashed #e8ebf1; pointer-events:none; }
        .signature-footer { display:flex; align-items:center; justify-content:space-between; gap:14px; margin-top:14px; }
        .signature-hint { display:flex; align-items:center; gap:7px; color:#a0a8b7; font-size:9px; }
        .hint-dot { width:6px; height:6px; border-radius:50%; background:#39c99a; }
        .signature-actions { display:flex; gap:8px; }
        .secondary-btn { color:#8a6a48; border:1px solid #e5e8ef; background:#fff; }
        .secondary-btn:hover { background:#f5f6fa; transform:translateY(-1px); }
        .save-signature { min-width:142px; }
        .save-signature span { font-size:17px; font-weight:400; }
        .field-error { margin:8px 0 0; color:#d34d62; font-size:10px; }
        .dashboard-footer { display:flex; justify-content:space-between; gap:15px; margin-top:28px; padding:17px 2px 0; border-top:1px solid #e7e9f0; color:#a1a8b6; font-size:9px; }
        .footer-mark { display:inline-grid; width:18px; height:18px; place-items:center; margin-right:5px; border-radius:5px; color:#fff; background:#e8790c; font-size:10px; font-weight:900; }
        .footer-separator { padding:0 5px; color:#c4c8d2; }
        .crop-modal { position:fixed; z-index:1000; inset:0; display:none; align-items:center; justify-content:center; padding:20px; }
        .crop-modal.is-open { display:flex; }
        .crop-backdrop { position:absolute; inset:0; background:rgba(16,19,35,.72); backdrop-filter:blur(7px); animation:fade-in .2s ease; }
        .crop-dialog { position:relative; z-index:1; width:min(560px,100%); overflow:hidden; border:1px solid rgba(255,255,255,.5); border-radius:20px; background:#fff; box-shadow:0 30px 90px rgba(0,0,0,.3); animation:modal-in .3s cubic-bezier(.2,.8,.2,1); }
        .crop-header { display:flex; align-items:center; justify-content:space-between; padding:20px 23px; }
        .crop-header h2 { margin:4px 0 0; color:#252d43; font-size:18px; font-weight:800; }
        .modal-close { width:33px; height:33px; border:0; border-radius:50%; color:#778198; background:#f2f3f8; font-size:23px; cursor:pointer; }
        .crop-stage { padding:15px; background:#171927; }
        .crop-container { width:100%; height:min(52vh,390px); overflow:hidden; }
        .crop-container img { display:block; max-width:100%; }
        .crop-tools { display:flex; align-items:center; gap:9px; padding:15px 22px; }
        .crop-tool { width:34px; height:34px; border:1px solid #e7e9ef; border-radius:9px; color:#555f76; background:#f7f8fb; font-size:20px; cursor:pointer; }
        .crop-reset { padding:8px 12px; border:1px solid #e7e9ef; border-radius:9px; color:#8a6a48; background:#fff; font-size:10px; font-weight:800; cursor:pointer; }
        .crop-tip { margin-left:auto; color:#a0a7b5; font-size:9px; }
        .crop-footer { display:flex; justify-content:flex-end; gap:9px; padding:15px 22px 20px; border-top:1px solid #edf0f4; }
        .crop-error { display:none; margin:0; padding:0 22px 14px; color:#c83f55; font-size:11px; }
        .crop-error.is-visible { display:block; }
        @keyframes rise-in { from { opacity:0; transform:translateY(17px); } to { opacity:1; transform:translateY(0); } }
        @keyframes fade-in { from { opacity:0; } to { opacity:1; } }
        @keyframes modal-in { from { opacity:0; transform:translateY(15px) scale(.98); } to { opacity:1; transform:translateY(0) scale(1); } }
        @keyframes float { 0%,100% { transform:translateY(0); } 50% { transform:translateY(-9px); } }
        @keyframes orbit { to { rotate:360deg; } }
        @media (max-width:850px) { .welcome-panel { padding:35px; } .welcome-art { width:230px; margin-right:0; transform:scale(.85); } .profile-grid { grid-template-columns:1fr; } .profile-card { min-height:330px; } }
        @media (max-width:600px) {
            .dashboard-shell { padding:22px 13px; }
            .dash-header { align-items:flex-start; }
            .header-date { padding:8px 10px; font-size:9px; }
            .welcome-panel { min-height:0; padding:29px 23px; border-radius:19px; }
            .welcome-copy h2 { font-size:31px; }
            .welcome-copy p { max-width:290px; line-height:1.7; }
            .welcome-art { position:absolute; right:-85px; bottom:-68px; opacity:.25; transform:scale(.8); }
            .welcome-actions { gap:8px; }
            .action-btn { min-width:0; padding:11px 13px; }
            .section-heading h2 { font-size:20px; }
            .surface-card { padding:19px; }
            .signature-footer { align-items:flex-start; flex-direction:column; }
            .signature-actions { width:100%; }
            .signature-actions button { flex:1; }
            .canvas-heading { align-items:flex-start; flex-direction:column; gap:4px; }
            .dashboard-footer { flex-direction:column; }
            .crop-modal { padding:10px; }
            .crop-dialog { border-radius:15px; }
            .crop-header { padding:16px; }
            .crop-tools { flex-wrap:wrap; padding:12px 16px; }
            .crop-tip { width:100%; margin:2px 0 0; }
            .crop-footer { padding:13px 16px 16px; }
        }
        @media (prefers-reduced-motion:reduce) { *,*::before,*::after { scroll-behavior:auto!important; animation-duration:.01ms!important; animation-iteration-count:1!important; transition-duration:.01ms!important; } }

        /* Mobile-first refinements: phones, small phones and touch screens */
        html, body { max-width:100%; overflow-x:hidden; }
        .dashboard-shell, .dashboard-container { min-width:0; }
        .dashboard-container *, .crop-dialog * { box-sizing:border-box; }
        .dash-header, .section-heading, .card-topline, .signature-footer,
        .dashboard-footer { min-width:0; }
        .welcome-copy, .profile-content, .signature-card { min-width:0; }
        .profile-grid > * { min-width:0; }
        .crop-container img { max-width:100%; }

        @media (max-width: 768px) {
            .dashboard-shell { padding:24px 16px 30px; }
            .dashboard-container { width:100%; }
            .dash-header { flex-wrap:wrap; }
            .header-date { white-space:nowrap; }
            .welcome-panel { gap:12px; }
            .welcome-copy { max-width:100%; }
            .profile-grid { grid-template-columns:minmax(0,1fr); }
            .surface-card { width:100%; }
            .profile-card { min-height:0; }
            .profile-content { padding:22px 8px 10px; }
            .signature-card { min-height:0; }
            .canvas-frame { height:clamp(135px, 28vw, 190px); }
            .crop-dialog { width:min(560px, calc(100vw - 28px)); max-height:calc(100dvh - 28px); overflow-y:auto; }
            .crop-container { height:min(48dvh, 340px); }
        }

        @media (max-width: 480px) {
            .dashboard-shell { padding:16px 11px 24px; }
            .dash-header { gap:10px; }
            .dash-header h1 { font-size:20px; }
            .dash-header p { font-size:11px; line-height:1.5; }
            .header-date { padding:7px 9px; font-size:9px; }
            .welcome-panel {
                display:block;
                min-height:0;
                margin-bottom:30px;
                padding:25px 19px;
                border-radius:17px;
            }
            .welcome-kicker { font-size:8px; letter-spacing:1.4px; }
            .welcome-copy h2 { margin:14px 0 9px; font-size:clamp(27px, 8vw, 33px); letter-spacing:-.8px; }
            .welcome-copy p { max-width:100%; font-size:12px; }
            .welcome-art { right:-105px; bottom:-88px; opacity:.18; pointer-events:none; }
            .welcome-actions { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:9px; margin-top:21px; }
            .action-btn { width:100%; min-width:0; min-height:44px; gap:7px; padding:10px 9px; border-radius:10px; font-size:11px; }
            .action-arrow { margin-left:auto; }
            .section-heading { align-items:flex-start; gap:8px; margin-bottom:14px; }
            .section-heading h2 { font-size:19px; }
            .section-heading p { max-width:260px; font-size:11px; line-height:1.5; }
            .section-count { flex:none; padding:6px 8px; font-size:8px; }
            .profile-grid { gap:13px; }
            .surface-card { padding:17px 15px; border-radius:15px; }
            .card-tag { font-size:7px; letter-spacing:1.1px; }
            .avatar-wrap { width:100px; height:100px; }
            .profile-content h3 { overflow-wrap:anywhere; font-size:16px; }
            .profile-caption { overflow-wrap:anywhere; }
            .profile-content .primary-btn { width:100%; min-height:45px; }
            .signature-intro { margin:18px 0 15px; }
            .signature-intro h3 { font-size:16px; }
            .signature-intro p { font-size:11px; }
            .saved-signature-heading { flex-wrap:wrap; gap:6px; }
            .canvas-heading { flex-direction:column; align-items:flex-start; gap:5px; }
            .canvas-heading span { font-size:9px; }
            .canvas-frame { height:145px; }
            .canvas-placeholder { max-width:90%; font-size:10px; white-space:nowrap; }
            .signature-footer { flex-direction:column; align-items:stretch; gap:12px; }
            .signature-hint { line-height:1.5; }
            .signature-actions { display:grid; grid-template-columns:minmax(0,.8fr) minmax(0,1.2fr); width:100%; gap:8px; }
            .signature-actions button { min-width:0; min-height:44px; padding:0 9px; font-size:10px; }
            .save-signature { min-width:0; }
            .dashboard-footer { gap:7px; margin-top:22px; font-size:9px; line-height:1.6; }
            .crop-modal { align-items:center; padding:8px; }
            .crop-dialog { width:100%; max-height:calc(100dvh - 16px); border-radius:14px; }
            .crop-header { padding:14px 15px; }
            .crop-header h2 { font-size:16px; }
            .crop-stage { padding:9px; }
            .crop-container { height:min(42dvh, 270px); }
            .crop-tools { gap:7px; padding:10px 14px; }
            .crop-footer { display:grid; grid-template-columns:1fr 1fr; gap:8px; padding:12px 14px 14px; }
            .crop-footer button { min-height:42px; padding:0 8px; font-size:10px; }
            .crop-error { padding:0 14px 12px; }
        }

        @media (max-width: 360px) {
            .dashboard-shell { padding-right:8px; padding-left:8px; }
            .welcome-panel { padding:22px 15px; }
            .welcome-copy h2 { font-size:26px; }
            .welcome-actions { grid-template-columns:1fr; }
            .action-btn { justify-content:flex-start; padding-left:14px; }
            .surface-card { padding:15px 12px; }
            .signature-actions { grid-template-columns:1fr; }
            .header-date { font-size:8px; }
            .section-count { display:none; }
        }

        @media (hover:none) and (pointer:coarse) {
            .surface-card:hover, .action-btn:hover, .primary-btn:hover { transform:none; box-shadow:initial; }
            .action-btn, .primary-btn, .secondary-btn, .notice-close, .modal-close, .crop-tool {
                -webkit-tap-highlight-color:transparent;
                touch-action:manipulation;
            }
            #signatureCanvas { touch-action:none; }
        }

        @media (max-height: 520px) and (orientation:landscape) {
            .crop-modal { align-items:flex-start; overflow-y:auto; }
            .crop-dialog { margin:auto; }
            .crop-container { height:48vh; }
        }

    </style>

    <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Profile picture cropper
            const pictureInput = document.getElementById('picture');
            const choosePictureButton = document.getElementById('choosePictureButton');
            const cropModal = document.getElementById('cropModal');
            const cropImage = document.getElementById('cropImage');
            const saveButton = document.getElementById('saveButton');
            const cropError = document.getElementById('cropError');
            let cropper = null;
            let activeObjectUrl = null;

            function showCropError(message) {
                cropError.textContent = message;
                cropError.classList.add('is-visible');
            }

            function closeCropper() {
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
                cropModal.classList.remove('is-open');
                cropModal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
                pictureInput.value = '';
                if (activeObjectUrl) {
                    URL.revokeObjectURL(activeObjectUrl);
                    activeObjectUrl = null;
                }
                cropImage.removeAttribute('src');
                cropError.textContent = '';
                cropError.classList.remove('is-visible');
                saveButton.disabled = false;
                saveButton.innerHTML = 'Save picture <span>→</span>';
            }

            function openCropper(file) {
                if (typeof Cropper === 'undefined') {
                    alert('The image editor could not be loaded. Check your internet connection and try again.');
                    return;
                }
                activeObjectUrl = URL.createObjectURL(file);
                cropImage.onload = function () {
                    if (cropper) cropper.destroy();
                    cropper = new Cropper(cropImage, {
                        aspectRatio: 1,
                        viewMode: 1,
                        dragMode: 'move',
                        autoCropArea: 0.88,
                        responsive: true,
                        background: false,
                        guides: false,
                        center: true,
                        highlight: false,
                        cropBoxMovable: false,
                        cropBoxResizable: false,
                        toggleDragModeOnDblclick: false
                    });
                };
                cropImage.src = activeObjectUrl;
                cropModal.classList.add('is-open');
                cropModal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            }

            choosePictureButton.addEventListener('click', () => pictureInput.click());
            pictureInput.addEventListener('change', function (event) {
                const file = event.target.files && event.target.files[0];
                if (!file) return;
                if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
                    alert('Please choose a JPG, PNG or WEBP image.');
                    pictureInput.value = '';
                    return;
                }
                if (file.size > 8 * 1024 * 1024) {
                    alert('Please choose an image smaller than 8 MB.');
                    pictureInput.value = '';
                    return;
                }
                openCropper(file);
            });

            document.getElementById('closeCropButton').addEventListener('click', closeCropper);
            document.getElementById('cancelCropButton').addEventListener('click', closeCropper);
            document.querySelector('[data-close-crop]').addEventListener('click', closeCropper);
            document.getElementById('zoomInButton').addEventListener('click', () => cropper && cropper.zoom(0.1));
            document.getElementById('zoomOutButton').addEventListener('click', () => cropper && cropper.zoom(-0.1));
            document.getElementById('resetCropButton').addEventListener('click', () => cropper && cropper.reset());
            document.addEventListener('keydown', event => {
                if (event.key === 'Escape' && cropModal.classList.contains('is-open')) closeCropper();
            });

            saveButton.addEventListener('click', function () {
                if (!cropper) return;
                const canvas = cropper.getCroppedCanvas({
                    width: 600,
                    height: 600,
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: 'high'
                });
                if (!canvas) {
                    showCropError('Could not crop this image. Please try another image.');
                    return;
                }
                saveButton.disabled = true;
                saveButton.textContent = 'Saving...';
                canvas.toBlob(function (blob) {
                    if (!blob) {
                        showCropError('Could not prepare the image. Please try again.');
                        saveButton.disabled = false;
                        saveButton.innerHTML = 'Save picture <span>→</span>';
                        return;
                    }
                    const formData = new FormData();
                    formData.append('picture', blob, 'profile-picture.jpg');
                    formData.append('_token', @json(csrf_token()));

                    fetch(@json(route('profile.upload-picture')), {
                        method: 'POST',
                        body: formData,
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(async response => {
                        const data = await response.json().catch(() => ({}));
                        if (!response.ok) throw new Error(data.message || 'Upload failed. Please try again.');
                        return data;
                    })
                    .then(data => {
                        if (data.success) {
                            closeCropper();
                            window.location.reload();
                        } else {
                            throw new Error(data.message || 'Upload failed.');
                        }
                    })
                    .catch(error => {
                        showCropError(error.message || 'Something went wrong while uploading.');
                        saveButton.disabled = false;
                        saveButton.innerHTML = 'Try again <span>→</span>';
                    });
                }, 'image/jpeg', 0.92);
            });

            // Signature pad: high-DPI canvas, pointer/touch support and resize preservation
            const canvas = document.getElementById('signatureCanvas');
            const context = canvas.getContext('2d');
            const signatureInput = document.getElementById('signatureInput');
            const clearButton = document.getElementById('clearSignature');
            const signatureForm = document.getElementById('signatureForm');
            const placeholder = document.getElementById('canvasPlaceholder');
            let drawing = false;
            let hasInk = false;
            let strokes = [];
            let currentStroke = [];

            function canvasPoint(event) {
                const rect = canvas.getBoundingClientRect();
                return { x: event.clientX - rect.left, y: event.clientY - rect.top };
            }

            function drawAllStrokes() {
                const rect = canvas.getBoundingClientRect();
                context.clearRect(0, 0, canvas.width, canvas.height);
                context.lineWidth = 2.4;
                context.lineCap = 'round';
                context.lineJoin = 'round';
                context.strokeStyle = '#20243a';
                strokes.forEach(stroke => {
                    if (!stroke.length) return;
                    context.beginPath();
                    context.moveTo(stroke[0].x, stroke[0].y);
                    stroke.slice(1).forEach(point => context.lineTo(point.x, point.y));
                    if (stroke.length === 1) context.lineTo(stroke[0].x + .1, stroke[0].y + .1);
                    context.stroke();
                });
                placeholder.classList.toggle('is-hidden', hasInk);
            }

            function resizeCanvas() {
                const rect = canvas.getBoundingClientRect();
                if (!rect.width || !rect.height) return;
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                const previous = canvas.width && canvas.height ? context.getImageData(0, 0, canvas.width, canvas.height) : null;
                canvas.width = Math.round(rect.width * ratio);
                canvas.height = Math.round(rect.height * ratio);
                context.setTransform(ratio, 0, 0, ratio, 0, 0);
                context.lineWidth = 2.4;
                context.lineCap = 'round';
                context.lineJoin = 'round';
                context.strokeStyle = '#20243a';
                if (hasInk) drawAllStrokes();
            }

            canvas.addEventListener('pointerdown', function (event) {
                event.preventDefault();
                drawing = true;
                hasInk = true;
                currentStroke = [canvasPoint(event)];
                strokes.push(currentStroke);
                placeholder.classList.add('is-hidden');
                canvas.setPointerCapture(event.pointerId);
                drawAllStrokes();
            });
            canvas.addEventListener('pointermove', function (event) {
                if (!drawing) return;
                currentStroke.push(canvasPoint(event));
                drawAllStrokes();
            });
            function stopDrawing() { drawing = false; currentStroke = []; }
            canvas.addEventListener('pointerup', stopDrawing);
            canvas.addEventListener('pointercancel', stopDrawing);
            canvas.addEventListener('lostpointercapture', stopDrawing);

            clearButton.addEventListener('click', function () {
                strokes = [];
                currentStroke = [];
                hasInk = false;
                signatureInput.value = '';
                context.clearRect(0, 0, canvas.width, canvas.height);
                placeholder.classList.remove('is-hidden');
            });

            signatureForm.addEventListener('submit', function (event) {
                if (!hasInk || strokes.length === 0) {
                    event.preventDefault();
                    alert('Please draw your signature before saving.');
                    return;
                }
                signatureInput.value = canvas.toDataURL('image/png');
            });

            resizeCanvas();
            if ('ResizeObserver' in window) {
                const observer = new ResizeObserver(() => resizeCanvas());
                observer.observe(canvas.parentElement);
            } else {
                window.addEventListener('resize', resizeCanvas);
            }
        });
    </script>
</x-app-layout>
