<x-app-layout>

    <div class="user-form-page">

        <!-- Animated header with breadcrumb and action hint -->
        <div class="form-header animate-fade-down">
            <a href="{{ route('ceo.users.index') }}" class="back-btn" title="{{ __('Back to users list') }}">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1>
                    <i class="fa-solid fa-user-plus"></i>
                    {{ __('Create New User') }}
                </h1>
                <p class="subtitle">
                    <i class="fa-regular fa-circle-check"></i>
                    {{ __('Fill in the details to add a new member to the system.') }}
                </p>
            </div>
            <div class="header-badge">
                <i class="fa-regular fa-clock"></i>
                <span>{{ __('New account') }}</span>
            </div>
        </div>

        <!-- Error box with subtle shake animation -->
        @if($errors->any())
            <div class="errors-box animate-shake">
                <i class="fa-solid fa-circle-exclamation"></i>
                <div>
                    <strong>{{ __('Please fix the following errors') }}</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Main form card with icon enhancements and micro-interactions -->
        <form method="POST" action="{{ route('ceo.users.store') }}" enctype="multipart/form-data" class="user-form animate-scale-in">
            @csrf

            {{-- PROFILE --}}
            <div class="profile-upload">
                <div id="preview" class="preview">
                    <i class="fa-solid fa-user"></i>
                </div>
                <label for="profile_picture" class="upload-btn">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    {{ __('Choose Profile Picture') }}
                </label>
                <input id="profile_picture" type="file" name="profile_picture" accept="image/*" hidden>
                <small><i class="fa-regular fa-image"></i> JPG, PNG, WEBP — Max 4 MB</small>
            </div>

            <div class="fields-grid">
                {{-- NAME --}}
                <div class="field animate-fade-right delay-1">
                    <label>
                        <i class="fa-solid fa-user"></i>
                        {{ __('Full Name') }}
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="{{ __('e.g. John Doe') }}">
                </div>

                {{-- MATRICULE --}}
                <div class="field animate-fade-right delay-2">
                    <label>
                        <i class="fa-solid fa-id-card"></i>
                        {{ __('Matricule') }}
                    </label>
                    <input type="text" name="matricule" value="{{ old('matricule') }}" required placeholder="{{ __('Employee ID / Matricule') }}">
                </div>

                {{-- EMAIL --}}
                <div class="field animate-fade-left delay-1">
                    <label>
                        <i class="fa-solid fa-envelope"></i>
                        {{ __('Email Address') }}
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="user@example.com">
                </div>

                {{-- ROLE --}}
                <div class="field animate-fade-left delay-2 role-field">
                    <label>
                        <i class="fa-solid fa-shield-halved"></i>
                        {{ __('Role') }}
                    </label>
                    <select id="role-select" name="role" required>
                        <option value="">{{ __('— Select role —') }}</option>
                        <option value="ceo" data-quality="{{ __('Executive-level access with full strategic and system privileges.') }}" {{ old('role') == 'ceo' ? 'selected' : '' }}>👑 CEO — {{ __('Full access') }}</option>
                        <option value="superadmin" data-quality="{{ __('Advanced access with elevated controls and platform oversight.') }}" {{ old('role') == 'superadmin' ? 'selected' : '' }}>🛡️ Super Admin — {{ __('High access') }}</option>
                        <option value="quality" data-quality="{{ __('Quality assurance access with inspection and compliance responsibilities.') }}" {{ old('role') == 'quality' ? 'selected' : '' }}>🔍 Quality — {{ __('Quality assurance') }}</option>
                        <option value="admin" data-quality="{{ __('Operational access to manage teams, users, and business processes.') }}" {{ old('role') == 'admin' ? 'selected' : '' }}>⚙️ Admin — {{ __('Manage access') }}</option>
                        <option value="user" data-quality="{{ __('Standard access with limited permissions and everyday tasks.') }}" {{ old('role') == 'user' ? 'selected' : '' }}>👤 User — {{ __('Basic access') }}</option>
                    </select>

                    <div id="role-quality" class="role-quality" aria-live="polite">
                        <div class="role-quality-header">
                            <i class="fa-solid fa-layer-group"></i>
                            <span>{{ __('Role quality') }}</span>
                        </div>
                        <div class="role-quality-meta">
                            <span id="role-badge" class="role-badge pending">{{ __('Pending') }}</span>
                            <span id="role-description" class="role-description">{{ __('Select a role to view access level.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- PASSWORD --}}
                <div class="field animate-fade-right delay-3">
                    <label>
                        <i class="fa-solid fa-lock"></i>
                        {{ __('Password') }}
                    </label>
                    <input type="password" name="password" required placeholder="{{ __('Minimum 8 characters') }}">
                </div>

                {{-- CONFIRM --}}
                <div class="field animate-fade-left delay-3">
                    <label>
                        <i class="fa-solid fa-lock"></i>
                        {{ __('Confirm Password') }}
                    </label>
                    <input type="password" name="password_confirmation" required placeholder="{{ __('Repeat your password') }}">
                </div>
            </div>

            <div class="password-quality animate-fade-up" aria-live="polite">
                <div class="quality-header">
                    <i class="fa-solid fa-shield-check"></i>
                    <span>{{ __('Password quality') }}</span>
                </div>

                <ul class="quality-rules">
                    <li data-rule="length"><i class="fa-solid fa-circle"></i> {{ __('At least 8 characters') }}</li>
                    <li data-rule="case"><i class="fa-solid fa-circle"></i> {{ __('Uppercase and lowercase letters') }}</li>
                    <li data-rule="number"><i class="fa-solid fa-circle"></i> {{ __('At least one number') }}</li>
                    <li data-rule="special"><i class="fa-solid fa-circle"></i> {{ __('At least one special character') }}</li>
                </ul>

                <div id="password-strength" class="quality-status weak">{{ __('Weak') }}</div>
            </div>

            <!-- Enhanced form actions with icons and hover lift -->
            <div class="form-actions animate-fade-up">
                <a href="{{ route('ceo.users.index') }}" class="cancel-btn">
                    <i class="fa-solid fa-xmark"></i>
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="save-btn">
                    <i class="fa-solid fa-user-plus"></i>
                    {{ __('Create Account') }}
                    <i class="fa-solid fa-arrow-right-long"></i>
                </button>
            </div>
        </form>
    </div>

    <style>
        /* --- Base & animations --- */
        .user-form-page {
            max-width: 980px;
            margin: auto;
            padding: 35px;
            animation: appear .6s cubic-bezier(0.2, 0.9, 0.3, 1);
        }

        @keyframes appear {
            from { opacity: 0; transform: translateY(18px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeDown {
            from { opacity: 0; transform: translateY(-18px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeRight {
            from { opacity: 0; transform: translateX(-18px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes fadeLeft {
            from { opacity: 0; transform: translateX(18px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.97); }
            to { opacity: 1; transform: scale(1); }
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20% { transform: translateX(-6px); }
            40% { transform: translateX(6px); }
            60% { transform: translateX(-4px); }
            80% { transform: translateX(4px); }
        }

        .animate-fade-down { animation: fadeDown 0.6s ease forwards; }
        .animate-fade-up { animation: fadeUp 0.6s ease forwards; }
        .animate-fade-right { animation: fadeRight 0.5s ease forwards; }
        .animate-fade-left { animation: fadeLeft 0.5s ease forwards; }
        .animate-scale-in { animation: scaleIn 0.5s ease forwards; }
        .animate-shake { animation: shake 0.4s ease; }

        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }

        /* --- Header --- */
        .form-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
            flex-wrap: wrap;
            position: relative;
        }

        .back-btn {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            background: #f3f4f6;
            color: #374151;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        }
        .back-btn:hover {
            background: #e5e7eb;
            transform: translateX(-3px);
            color: #f97316;
        }

        .form-header h1 {
            margin: 0;
            color: #172033;
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .form-header h1 i {
            color: #f97316;
            font-size: 1.8rem;
            filter: drop-shadow(0 4px 6px rgba(249,115,22,0.2));
        }
        .subtitle {
            margin: 6px 0 0;
            color: #8b94a7;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .subtitle i {
            color: #f97316;
            font-size: 0.9rem;
        }

        .header-badge {
            margin-left: auto;
            background: #fff7ed;
            color: #c2410c;
            padding: 8px 18px;
            border-radius: 40px;
            font-size: 0.85rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            border: 1px solid #fed7aa;
        }
        .header-badge i {
            font-size: 1rem;
        }

        /* --- Form card --- */
        .user-form {
            background: white;
            padding: 35px;
            border-radius: 28px;
            border: 1px solid #edf0f5;
            box-shadow: 0 20px 50px rgba(20, 30, 50, 0.08);
            transition: box-shadow 0.3s;
        }
        .user-form:hover {
            box-shadow: 0 25px 60px rgba(20, 30, 50, 0.12);
        }

        /* --- Profile upload --- */
        .profile-upload {
            text-align: center;
            margin-bottom: 35px;
        }
        .preview {
            width: 120px;
            height: 120px;
            margin: auto;
            border-radius: 32px;
            background: linear-gradient(135deg, #ff8c00, #ff5e00);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            color: white;
            font-size: 42px;
            box-shadow: 0 18px 35px rgba(255, 100, 0, 0.3);
            transition: transform 0.3s, box-shadow 0.3s;
            border: 3px solid white;
            outline: 1px solid #ffe7d9;
        }
        .preview:hover {
            transform: scale(1.02);
            box-shadow: 0 20px 40px rgba(255, 100, 0, 0.4);
        }
        .preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .upload-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 18px;
            padding: 12px 24px;
            border-radius: 14px;
            background: #fff3e8;
            color: #e76d00;
            cursor: pointer;
            font-weight: 700;
            font-size: 0.95rem;
            transition: all 0.2s;
            border: 1px solid #ffd9b3;
            box-shadow: 0 4px 10px rgba(249,115,22,0.1);
        }
        .upload-btn:hover {
            background: #ffe7d9;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(249,115,22,0.15);
        }
        .upload-btn i {
            font-size: 1.1rem;
        }
        .profile-upload small {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 12px;
            color: #9ca3af;
            font-size: 0.85rem;
        }
        .profile-upload small i {
            color: #f97316;
            opacity: 0.7;
        }

        /* --- Fields grid --- */
        .fields-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }

        .field label {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            color: #374151;
            font-size: 0.9rem;
            font-weight: 700;
            letter-spacing: 0.3px;
        }
        .field label i {
            color: #f97316;
            width: 20px;
            font-size: 1rem;
            filter: drop-shadow(0 2px 4px rgba(249,115,22,0.2));
        }

        .field input,
        .field select {
            width: 100%;
            height: 52px;
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            padding: 0 18px;
            outline: none;
            background: #fafafa;
            font-size: 0.95rem;
            transition: all 0.25s;
            color: #1e293b;
        }
        .field select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23f97316' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            background-size: 16px;
        }
        .field input:focus,
        .field select:focus {
            border-color: #f97316;
            box-shadow: 0 0 0 5px rgba(249, 115, 22, 0.12);
            background: white;
        }
        .field input:hover,
        .field select:hover {
            border-color: #fdba74;
            background: #ffffff;
        }

        .role-field {
            position: relative;
        }

        .role-quality {
            margin-top: 12px;
            padding: 12px 14px;
            border-radius: 14px;
            background: linear-gradient(180deg, #fffaf5, #fff);
            border: 1px solid #fed7aa;
            box-shadow: 0 8px 18px rgba(249,115,22,0.05);
        }
        .role-quality-header {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 0.76rem;
            font-weight: 700;
            color: #c2410c;
            margin-bottom: 8px;
        }
        .role-quality-header i {
            color: #f97316;
            font-size: 0.8rem;
        }
        .role-quality-meta {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .role-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 92px;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.2px;
            text-transform: uppercase;
        }
        .role-badge.pending {
            background: #f3f4f6;
            color: #475467;
        }
        .role-badge.user {
            background: #ecfeff;
            color: #0f766e;
        }
        .role-badge.admin {
            background: #fff7ed;
            color: #c2410c;
        }
        .role-badge.superadmin {
            background: #f5f3ff;
            color: #6d28d9;
        }
        .role-badge.ceo {
            background: #fef3c7;
            color: #92400e;
        }
        .role-description {
            color: #475467;
            font-size: 0.8rem;
            line-height: 1.5;
        }

        /* --- Form actions --- */
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 16px;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #f1f5f9;
        }

        .cancel-btn, .save-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 28px;
            border-radius: 16px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.25s ease;
            letter-spacing: 0.3px;
        }

        .cancel-btn {
            color: #667085;
            background: #f2f4f7;
            border: 1px solid #e2e8f0;
        }
        .cancel-btn:hover {
            background: #e9edf2;
            color: #475467;
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(0,0,0,0.04);
        }
        .cancel-btn i {
            font-size: 1.1rem;
        }

        .save-btn {
            color: white;
            background: linear-gradient(135deg, #ff8c00, #ff5e00);
            box-shadow: 0 12px 28px rgba(255, 100, 0, 0.3);
            position: relative;
            overflow: hidden;
        }
        .save-btn::after {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(255,255,255,0.2), transparent);
            opacity: 0;
            transition: opacity 0.3s;
        }
        .save-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 18px 35px rgba(255, 100, 0, 0.4);
        }
        .save-btn:hover::after {
            opacity: 1;
        }
        .save-btn i:last-child {
            font-size: 0.9rem;
            transition: transform 0.2s;
        }
        .save-btn:hover i:last-child {
            transform: translateX(4px);
        }

        /* --- Error box --- */
        .errors-box {
            display: flex;
            gap: 14px;
            padding: 18px 22px;
            margin-bottom: 25px;
            color: #b42318;
            background: #fef3f2;
            border: 1px solid #fecdca;
            border-radius: 18px;
            animation: shake 0.5s ease;
            box-shadow: 0 8px 20px rgba(180, 35, 24, 0.08);
            align-items: flex-start;
        }
        .errors-box i {
            font-size: 1.5rem;
            color: #d92d20;
            margin-top: 2px;
        }
        .errors-box strong {
            display: block;
            margin-bottom: 6px;
            font-size: 0.95rem;
        }
        .errors-box ul {
            margin: 0;
            padding-left: 20px;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        /* --- Password quality --- */
        .password-quality {
            margin-top: 24px;
            padding: 18px 20px;
            background: linear-gradient(180deg, #fffaf5, #fff);
            border: 1px solid #fed7aa;
            border-radius: 18px;
            box-shadow: 0 10px 22px rgba(249,115,22,0.06);
        }
        .quality-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
            color: #c2410c;
            font-size: 0.9rem;
            font-weight: 700;
        }
        .quality-header i {
            color: #f97316;
        }
        .quality-rules {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            gap: 10px;
        }
        .quality-rules li {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #667085;
            font-size: 0.88rem;
            transition: color 0.2s ease, transform 0.2s ease;
        }
        .quality-rules li i {
            font-size: 0.7rem;
            color: #cbd5e1;
            transition: color 0.2s ease;
        }
        .quality-rules li.valid {
            color: #166534;
            transform: translateX(2px);
        }
        .quality-rules li.valid i {
            color: #22c55e;
        }
        .quality-status {
            display: inline-flex;
            margin-top: 14px;
            padding: 7px 12px;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.3px;
        }
        .quality-status.weak {
            background: #fef2f2;
            color: #b42318;
        }
        .quality-status.medium {
            background: #fff7ed;
            color: #c2410c;
        }
        .quality-status.strong {
            background: #ecfdf5;
            color: #166534;
        }

        /* --- Responsive --- */
        @media (max-width: 700px) {
            .user-form-page { padding: 18px; }
            .user-form { padding: 24px; }
            .fields-grid { grid-template-columns: 1fr; gap: 18px; }
            .form-header h1 { font-size: 1.5rem; }
            .header-badge { display: none; }
            .form-actions { flex-direction: column-reverse; }
            .cancel-btn, .save-btn { justify-content: center; width: 100%; }
            .preview { width: 100px; height: 100px; font-size: 34px; }
        }

        @media (max-width: 480px) {
            .form-header { flex-wrap: wrap; }
            .back-btn { width: 42px; height: 42px; }
        }
    </style>

    <script>
        const roleSelect = document.getElementById('role-select');
        const roleBadge = document.getElementById('role-badge');
        const roleDescription = document.getElementById('role-description');

        const roleMeta = {
            '': {
                label: 'Pending',
                description: 'Select a role to view access level.',
                className: 'pending'
            },
            user: {
                label: 'User',
                description: 'Standard access with limited permissions and everyday tasks.',
                className: 'user'
            },
            admin: {
                label: 'Admin',
                description: 'Operational access to manage teams, users, and business processes.',
                className: 'admin'
            },
            superadmin: {
                label: 'Super Admin',
                description: 'Advanced access with elevated controls and platform oversight.',
                className: 'superadmin'
            },
            ceo: {
                label: 'CEO',
                description: 'Executive-level access with full strategic and system privileges.',
                className: 'ceo'
            },
        };

        function updateRoleQuality(value) {
            const meta = roleMeta[value] || roleMeta[''];

            if (roleBadge) {
                roleBadge.textContent = meta.label;
                roleBadge.className = `role-badge ${meta.className}`;
            }

            if (roleDescription) {
                roleDescription.textContent = meta.description;
            }
        }

        if (roleSelect) {
            updateRoleQuality(roleSelect.value);
            roleSelect.addEventListener('change', function () {
                updateRoleQuality(this.value);
            });
        }

        const passwordInput = document.querySelector('input[name="password"]');
        const confirmationInput = document.querySelector('input[name="password_confirmation"]');
        const passwordRules = document.querySelectorAll('.quality-rules li');
        const strengthBadge = document.getElementById('password-strength');

        function updatePasswordQuality(value) {
            const checks = {
                length: value.length >= 8,
                case: /[a-z]/.test(value) && /[A-Z]/.test(value),
                number: /\d/.test(value),
                special: /[^A-Za-z0-9]/.test(value),
            };

            passwordRules.forEach((item) => {
                const key = item.dataset.rule;
                const isValid = Boolean(checks[key]);
                item.classList.toggle('valid', isValid);
                const icon = item.querySelector('i');
                icon.className = isValid ? 'fa-solid fa-check' : 'fa-solid fa-circle';
            });

            const validCount = Object.values(checks).filter(Boolean).length;

            if (validCount === 4) {
                strengthBadge.textContent = 'Strong';
                strengthBadge.className = 'quality-status strong';
            } else if (validCount >= 2) {
                strengthBadge.textContent = 'Medium';
                strengthBadge.className = 'quality-status medium';
            } else {
                strengthBadge.textContent = 'Weak';
                strengthBadge.className = 'quality-status weak';
            }
        }

        if (passwordInput) {
            passwordInput.addEventListener('input', function () {
                updatePasswordQuality(this.value);
            });
        }

        if (confirmationInput) {
            confirmationInput.addEventListener('input', function () {
                if (!this.value) return;
                this.style.borderColor = this.value === passwordInput?.value ? '#22c55e' : '#ef4444';
                this.style.boxShadow = this.value === passwordInput?.value ? '0 0 0 5px rgba(34, 197, 94, 0.12)' : '0 0 0 5px rgba(239, 68, 68, 0.12)';
            });
        }

        const profileInput = document.getElementById('profile_picture');
        if (profileInput) {
            profileInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('preview');
                    if (preview) {
                        preview.innerHTML = `<img src="${e.target.result}" alt="Profile preview" style="animation: scaleIn 0.3s ease;">`;
                    }
                };
                reader.readAsDataURL(file);
            });
        }
    </script>

</x-app-layout>