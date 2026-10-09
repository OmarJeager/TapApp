<x-app-layout>

    <div class="ceo-users-page">

        {{-- ========================================================= --}}
        {{-- HEADER --}}
        {{-- ========================================================= --}}

        <div class="page-header">

            <div class="header-left">

                <div class="header-icon">
                    <i class="fa-solid fa-users-gear"></i>
                </div>

                <div>
                    <h1>
                        {{ __('User Management') }}
                    </h1>

                    <p>
                        {{ __('Manage all TapApp users, roles and accounts.') }}
                    </p>
                </div>

            </div>


            <a
                href="{{ route('ceo.users.create') }}"
                class="create-btn"
            >
                <i class="fa-solid fa-user-plus"></i>

                <span>
                    {{ __('Create User') }}
                </span>
            </a>

        </div>


        {{-- ========================================================= --}}
        {{-- ALERTS --}}
        {{-- ========================================================= --}}

        @if(session('success'))

            <div class="alert success-alert">

                <div class="alert-icon">
                    <i class="fa-solid fa-circle-check"></i>
                </div>

                <div>
                    <strong>
                        {{ __('Success') }}
                    </strong>

                    <p>
                        {{ session('success') }}
                    </p>
                </div>

                <button onclick="this.parentElement.remove()">
                    <i class="fa-solid fa-xmark"></i>
                </button>

            </div>

        @endif


        @if(session('error'))

            <div class="alert error-alert">

                <div class="alert-icon">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>

                <div>
                    <strong>
                        {{ __('Error') }}
                    </strong>

                    <p>
                        {{ session('error') }}
                    </p>
                </div>

                <button onclick="this.parentElement.remove()">
                    <i class="fa-solid fa-xmark"></i>
                </button>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- STATISTICS --}}
        {{-- ========================================================= --}}

        <div class="stats-grid">

            <div class="stat-card total">

                <div class="stat-icon">
                    <i class="fa-solid fa-users"></i>
                </div>

                <div>
                    <span>
                        {{ __('Total Users') }}
                    </span>

                    <strong>
                        {{ $totalUsers }}
                    </strong>
                </div>

            </div>


            <div class="stat-card ceo">

                <div class="stat-icon">
                    <i class="fa-solid fa-crown"></i>
                </div>

                <div>
                    <span>
                        {{ __('CEO') }}
                    </span>

                    <strong>
                        {{ $ceoCount }}
                    </strong>
                </div>

            </div>


            <div class="stat-card superadmin">

                <div class="stat-icon">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>

                <div>
                    <span>
                        {{ __('Super Admins') }}
                    </span>

                    <strong>
                        {{ $superadminCount }}
                    </strong>
                </div>

            </div>


            <div class="stat-card admin">

                <div class="stat-icon">
                    <i class="fa-solid fa-user-tie"></i>
                </div>

                <div>
                    <span>
                        {{ __('Admins') }}
                    </span>

                    <strong>
                        {{ $adminCount }}
                    </strong>
                </div>

            </div>


            <div class="stat-card normal-user">

                <div class="stat-icon">
                    <i class="fa-solid fa-user"></i>
                </div>

                <div>
                    <span>
                        {{ __('Users') }}
                    </span>

                    <strong>
                        {{ $userCount }}
                    </strong>
                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- FILTER BAR --}}
        {{-- ========================================================= --}}

        <div class="filter-card">

            <form
                method="GET"
                action="{{ route('ceo.users.index') }}"
            >

                <div class="search-box">

                    <i class="fa-solid fa-magnifying-glass"></i>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="{{ __('Search name, email, matricule or role...') }}"
                    >

                </div>


                <select name="role">

                    <option value="">
                        {{ __('All Roles') }}
                    </option>

                    <option
                        value="ceo"
                        @selected(request('role') === 'ceo')
                    >
                        CEO
                    </option>

                    <option
                        value="superadmin"
                        @selected(request('role') === 'superadmin')
                    >
                        Super Admin
                    </option>

                    <option
                        value="admin"
                        @selected(request('role') === 'admin')
                    >
                        Admin
                    </option>

                    <option
                        value="user"
                        @selected(request('role') === 'user')
                    >
                        User
                    </option>

                </select>


                <button class="filter-btn">

                    <i class="fa-solid fa-filter"></i>

                    {{ __('Filter') }}

                </button>


                <a
                    href="{{ route('ceo.users.index') }}"
                    class="reset-btn"
                >

                    <i class="fa-solid fa-rotate-left"></i>

                    {{ __('Reset') }}

                </a>

            </form>

        </div>


        {{-- ========================================================= --}}
        {{-- USERS --}}
        {{-- ========================================================= --}}

        <div class="users-card">

            <div class="table-title">

                <div>

                    <h2>
                        <i class="fa-solid fa-address-book"></i>

                        {{ __('All Users') }}
                    </h2>

                    <p>
                        {{ __('CEO account management') }}
                    </p>

                </div>

                <span class="user-count">
                    {{ $users->total() }}
                    {{ __('accounts') }}
                </span>

            </div>


            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>
                                {{ __('User') }}
                            </th>

                            <th>
                                {{ __('Matricule') }}
                            </th>

                            <th>
                                {{ __('Email') }}
                            </th>

                            <th>
                                {{ __('Role') }}
                            </th>

                            <th>
                                {{ __('Password') }}
                            </th>

                            <th>
                                {{ __('Created') }}
                            </th>
                            <th>
                                {{ __('Account Status') }}
                            </th>
                            <th>
                                {{ __('Actions') }}
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($users as $user)

                            <tr>

                                {{-- USER --}}

                                <td>

                                    <div class="user-cell">

                                        @if($user->profile_picture)

                                            <img
                                                src="{{ asset('storage/' . $user->profile_picture) }}"
                                                alt="{{ $user->name }}"
                                                class="avatar"
                                            >

                                        @else

                                            <div class="avatar default-avatar">

                                                <i class="fa-solid fa-user"></i>

                                            </div>

                                        @endif


                                        <div>

                                            <strong>
                                                {{ $user->name }}
                                            </strong>

                                            <small>
                                                ID #{{ $user->id }}
                                            </small>

                                        </div>

                                    </div>

                                </td>


                                {{-- MATRICULE --}}

                                <td>

                                    <span class="matricule">
                                        {{ $user->matricule }}
                                    </span>

                                </td>


                                {{-- EMAIL --}}

                                <td>

                                    <a
                                        href="mailto:{{ $user->email }}"
                                        class="email"
                                    >

                                        <i class="fa-solid fa-envelope"></i>

                                        {{ $user->email }}

                                    </a>

                                </td>


                                {{-- ROLE --}}

                                <td>

                                    @php

                                        $roleClass = match($user->role) {

                                            'ceo' => 'role-ceo',

                                            'superadmin' => 'role-superadmin',

                                            'admin' => 'role-admin',

                                            default => 'role-user',

                                        };

                                        $roleIcon = match($user->role) {

                                            'ceo' => 'fa-crown',

                                            'superadmin' => 'fa-shield-halved',

                                            'admin' => 'fa-user-tie',

                                            default => 'fa-user',

                                        };

                                    @endphp


                                    <span class="role-badge {{ $roleClass }}">

                                        <i class="fa-solid {{ $roleIcon }}"></i>

                                        {{ ucfirst($user->role) }}

                                    </span>

                                </td>


                                {{-- PASSWORD --}}

                                <td>

                                    <span class="password-protected">

                                        <i class="fa-solid fa-lock"></i>

                                        {{ __('Protected') }}

                                    </span>

                                </td>


                                {{-- CREATED --}}

                                <td>

                                    <span class="date">

                                        {{ $user->created_at?->format('d/m/Y') }}

                                    </span>

                                </td>
                                {{-- ACCOUNT STATUS --}}
<td>
    @if($user->is_active)
        <span class="account-status active-status">
            <i class="fa-solid fa-circle-check"></i>
            {{ __('Active') }}
        </span>
    @else
        <span class="account-status inactive-status">
            <i class="fa-solid fa-ban"></i>
            {{ __('Deactivated') }}
        </span>
    @endif
</td>

                                {{-- ACTIONS --}}

                                <td>

                                    <div class="actions">
                                        @if($user->id !== auth()->id())
    <form
        method="POST"
        action="{{ route('ceo.users.toggle-status', $user) }}"
        onsubmit="return confirm(
            '{{ $user->is_active
                ? __('Deactivate this account?')
                : __('Activate this account?') }}'
        )"
    >
        @csrf
        @method('PATCH')

        <button
            type="submit"
            class="action {{ $user->is_active ? 'deactivate' : 'activate' }}"
            title="{{ $user->is_active ? __('Deactivate') : __('Activate') }}"
        >
            <i class="fa-solid {{ $user->is_active ? 'fa-user-slash' : 'fa-user-check' }}"></i>

            <span class="action-label">
                {{ $user->is_active ? __('Deactivate') : __('Activate') }}
            </span>
        </button>
    </form>
@endif
                                        <a
                                            href="{{ route('ceo.users.edit', $user) }}"
                                            class="action edit"
                                            title="{{ __('Edit') }}"
                                        >

                                            <i class="fa-solid fa-pen"></i>
                                            <span class="action-label">{{ __('Edit') }}</span>

                                        </a>


                                        @if($user->id !== auth()->id())
<form
    method="POST"
    action="{{ route('ceo.users.destroy', $user) }}"
    class="delete-form"
>
    @csrf
    @method('DELETE')

    <input
        type="password"
        name="security_code"
        placeholder="CEO security code"
        aria-label="CEO security code"
        required
        autocomplete="off"
        class="security-code-input"
    >

    @error('security_code')
        <span class="text-red-600 text-xs">
            {{ $message }}
        </span>
    @enderror

    <button
        type="submit"
        class="action delete"
        onclick="return confirm('Permanently delete this account?')"
    >
        <i class="fa-solid fa-trash"></i>
        <span class="action-label">{{ __('Delete') }}</span>
    </button>
</form>

                                        @else

                                            <span
                                                class="self-badge"
                                                title="{{ __('Your account') }}"
                                            >
                                                <i class="fa-solid fa-user-check"></i>
                                            </span>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="empty-state"
                                >

                                    <div>

                                        <i class="fa-solid fa-users-slash"></i>

                                        <h3>
                                            {{ __('No users found') }}
                                        </h3>

                                        <p>
                                            {{ __('Try changing your search or filters.') }}
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- PAGINATION --}}

            @if($users->hasPages())

                <div class="pagination-wrapper">

                    {{ $users->links() }}

                </div>

            @endif

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- CSS --}}
    {{-- ============================================================= --}}

    <style>

        * {
            box-sizing: border-box;
        }

        .ceo-users-page {

            padding: 30px;

            max-width: 1600px;

            margin: auto;

            min-height: 100vh;

            background:
                radial-gradient(
                    circle at top right,
                    rgba(255, 153, 0, .10),
                    transparent 35%
                );

            animation: pageAppear .6s ease;

        }


        @keyframes pageAppear {

            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }

        }


        /* HEADER */

        .page-header {

            display: flex;

            justify-content: space-between;

            align-items: center;

            gap: 20px;

            margin-bottom: 30px;

        }


        .header-left {

            display: flex;

            align-items: center;

            gap: 18px;

        }


        .header-icon {

            width: 64px;

            height: 64px;

            border-radius: 20px;

            display: flex;

            align-items: center;

            justify-content: center;

            background:
                linear-gradient(
                    135deg,
                    #ff8c00,
                    #ff5e00
                );

            color: white;

            font-size: 27px;

            box-shadow:
                0 12px 30px rgba(255, 120, 0, .28);

            animation: floatIcon 3s ease-in-out infinite;

        }


        @keyframes floatIcon {

            0%, 100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }

        }


        .page-header h1 {

            margin: 0;

            font-size: 30px;

            font-weight: 800;

            color: #172033;

        }


        .page-header p {

            margin: 5px 0 0;

            color: #7b8498;

        }


        .create-btn {

            display: inline-flex;

            align-items: center;

            gap: 9px;

            padding: 13px 20px;

            border-radius: 13px;

            background: linear-gradient(
                135deg,
                #ff8c00,
                #ff5c00
            );

            color: white;

            text-decoration: none;

            font-weight: 700;

            box-shadow:
                0 10px 25px rgba(255, 100, 0, .25);

            transition: .25s ease;

        }


        .create-btn:hover {

            transform: translateY(-3px);

            box-shadow:
                0 15px 30px rgba(255, 100, 0, .35);

        }


        /* ALERT */

        .alert {

            display: flex;

            align-items: center;

            gap: 14px;

            padding: 15px 18px;

            margin-bottom: 22px;

            border-radius: 15px;

            animation: alertIn .4s ease;

        }


        @keyframes alertIn {

            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }

        }


        .alert p {

            margin: 3px 0 0;

        }


        .alert button {

            margin-left: auto;

            border: 0;

            background: transparent;

            cursor: pointer;

            font-size: 18px;

        }


        .success-alert {

            background: #ecfdf3;

            color: #087443;

            border: 1px solid #b7f0cf;

        }


        .error-alert {

            background: #fff1f2;

            color: #be123c;

            border: 1px solid #fecdd3;

        }


        .alert-icon {

            font-size: 23px;

        }


        /* STATS */

        .stats-grid {

            display: grid;

            grid-template-columns:
                repeat(5, 1fr);

            gap: 16px;

            margin-bottom: 24px;

        }


        .stat-card {

            position: relative;

            overflow: hidden;

            display: flex;

            align-items: center;

            gap: 15px;

            padding: 20px;

            border-radius: 20px;

            background: white;

            border: 1px solid #edf0f5;

            box-shadow:
                0 10px 35px rgba(25, 35, 55, .06);

            transition: .3s ease;

        }


        .stat-card:hover {

            transform: translateY(-5px);

            box-shadow:
                0 18px 40px rgba(25, 35, 55, .11);

        }


        .stat-icon {

            width: 48px;

            height: 48px;

            flex-shrink: 0;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 15px;

            background: #fff4e7;

            color: #f97316;

            font-size: 20px;

        }


        .stat-card span {

            display: block;

            font-size: 12px;

            color: #8b94a7;

            font-weight: 600;

        }


        .stat-card strong {

            display: block;

            margin-top: 4px;

            font-size: 25px;

            color: #182033;

        }


        /* FILTER */

        .filter-card {

            padding: 18px;

            background: white;

            border-radius: 18px;

            border: 1px solid #edf0f5;

            margin-bottom: 20px;

            box-shadow:
                0 8px 25px rgba(25, 35, 55, .05);

        }


        .filter-card form {

            display: flex;

            align-items: center;

            gap: 12px;

        }


        .search-box {

            flex: 1;

            position: relative;

        }


        .search-box i {

            position: absolute;

            left: 15px;

            top: 50%;

            transform: translateY(-50%);

            color: #9ca3af;

        }


        .search-box input,
        .filter-card select {

            width: 100%;

            height: 46px;

            border: 1px solid #e4e8ef;

            border-radius: 11px;

            outline: none;

            padding: 0 14px;

            background: #fafbfc;

            transition: .2s;

        }


        .search-box input {

            padding-left: 43px;

        }


        .search-box input:focus,
        .filter-card select:focus {

            border-color: #ff8c00;

            box-shadow:
                0 0 0 4px rgba(255, 140, 0, .10);

            background: white;

        }


        .filter-card select {

            max-width: 180px;

        }


        .filter-btn,
        .reset-btn {

            height: 46px;

            padding: 0 16px;

            border-radius: 11px;

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 7px;

            text-decoration: none;

            cursor: pointer;

            font-weight: 700;

        }


        .filter-btn {

            border: 0;

            color: white;

            background: #172033;

        }


        .reset-btn {

            color: #697386;

            background: #f3f5f8;

        }


        /* TABLE */

        .users-card {

            background: white;

            border-radius: 22px;

            border: 1px solid #edf0f5;

            overflow: hidden;

            box-shadow:
                0 10px 40px rgba(25, 35, 55, .06);

        }


        .table-title {

            padding: 23px 25px;

            display: flex;

            align-items: center;

            justify-content: space-between;

            border-bottom: 1px solid #edf0f5;

        }


        .table-title h2 {

            margin: 0;

            font-size: 19px;

            color: #172033;

        }


        .table-title h2 i {

            color: #ff8500;

            margin-right: 7px;

        }


        .table-title p {

            margin: 5px 0 0;

            color: #929aab;

            font-size: 13px;

        }


        .user-count {

            padding: 8px 12px;

            border-radius: 10px;

            background: #fff4e7;

            color: #e66b00;

            font-weight: 700;

            font-size: 13px;

        }


        .table-wrapper {

            overflow-x: auto;

        }


        table {

            width: 100%;

            border-collapse: collapse;

            min-width: 1050px;

        }


        th {

            padding: 15px 20px;

            text-align: left;

            font-size: 11px;

            text-transform: uppercase;

            letter-spacing: .5px;

            color: #8992a5;

            background: #fafbfc;

        }


        td {

            padding: 17px 20px;

            border-top: 1px solid #f0f2f5;

            color: #424b5e;

        }


        tbody tr {

            transition: .2s ease;

        }


        tbody tr:hover {

            background: #fffaf5;

        }


        /* USER */

        .user-cell {

            display: flex;

            align-items: center;

            gap: 12px;

        }


        .avatar {

            width: 45px;

            height: 45px;

            object-fit: cover;

            border-radius: 14px;

            border: 2px solid #fff;

            box-shadow:
                0 4px 12px rgba(0,0,0,.12);

        }


        .default-avatar {

            display: flex;

            align-items: center;

            justify-content: center;

            background: linear-gradient(
                135deg,
                #f97316,
                #fb923c
            );

            color: white;

        }


        .user-cell strong {

            display: block;

            color: #20283a;

        }


        .user-cell small {

            color: #9aa2b2;

            font-size: 11px;

        }


        .matricule {

            font-family: monospace;

            background: #f3f5f8;

            padding: 6px 9px;

            border-radius: 8px;

            font-size: 12px;

        }


        .email {

            color: #566176;

            text-decoration: none;

            font-size: 13px;

        }


        .email:hover {

            color: #f97316;

        }


        .email i {

            margin-right: 5px;

        }


        /* ROLES */

        .role-badge {

            display: inline-flex;

            align-items: center;

            gap: 6px;

            padding: 7px 10px;

            border-radius: 9px;

            font-size: 11px;

            font-weight: 800;

        }


        .role-ceo {

            background: #fff7d6;

            color: #9a6900;

        }


        .role-superadmin {

            background: #f1eaff;

            color: #7541c8;

        }


        .role-admin {

            background: #e7f1ff;

            color: #2463b5;

        }


        .role-user {

            background: #eafaf2;

            color: #16804d;

        }


        .password-protected {

            display: inline-flex;

            align-items: center;

            gap: 6px;

            color: #8b94a7;

            font-size: 12px;

        }


        .date {

            font-size: 12px;

            color: #8b94a7;

        }


        /* ACTIONS */

        .actions {

            display: flex;

            align-items: center;

            gap: 7px;

        }


        .action {

            display: inline-flex;

            align-items: center;

            gap: 5px;

            padding: 7px 10px;

            border-radius: 9px;

            border: 0;

            cursor: pointer;

            transition: .2s;

            text-decoration: none;

            font-size: 12px;

            font-weight: 600;

            white-space: nowrap;

        }


        .action:hover {

            transform: translateY(-2px);

        }


        .edit {

            color: #2563eb;

            background: #eff6ff;

        }


        .delete {

            color: #dc2626;

            background: #fef2f2;

        }


        .self-badge {

            width: 34px;

            height: 34px;

            display: flex;

            align-items: center;

            justify-content: center;

            color: #16a34a;

            background: #f0fdf4;

            border-radius: 9px;

        }


        .action-label {

            display: inline;

        }


        /* EMPTY */

        .empty-state {

            height: 300px;

            text-align: center;

        }


        .empty-state i {

            font-size: 45px;

            color: #d4d9e2;

        }


        .empty-state h3 {

            margin-bottom: 5px;

            color: #3e4759;

        }


        .empty-state p {

            color: #9aa2b2;

        }


        /* MOBILE */

        @media(max-width: 1100px) {

            .stats-grid {

                grid-template-columns:
                    repeat(3, 1fr);

            }

        }


        @media(max-width: 750px) {

            .ceo-users-page {

                padding: 18px;

            }


            .page-header {

                align-items: flex-start;

                flex-direction: column;

            }


            .create-btn {

                width: 100%;

                justify-content: center;

            }


            .stats-grid {

                grid-template-columns:
                    repeat(2, 1fr);

            }


            .filter-card form {

                flex-direction: column;

            }


            .search-box,
            .filter-card select,
            .filter-btn,
            .reset-btn {

                max-width: none;

                width: 100%;

            }

        }


        @media(max-width: 480px) {

            .stats-grid {

                grid-template-columns: 1fr;

            }


            .page-header h1 {

                font-size: 24px;

            }

        }

.account-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 10px;
    border-radius: 9px;
    font-size: 11px;
    font-weight: 800;
    white-space: nowrap;
}

.active-status {
    color: #15803d;
    background: #dcfce7;
}

.inactive-status {
    color: #b91c1c;
    background: #fee2e2;
}

.action.activate {
    color: #15803d;
    background: #dcfce7;
}

.action.deactivate {
    color: #b45309;
    background: #ffedd5;
}

.security-code-input {
    width: 145px;
    min-width: 120px;
    padding: 8px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 12px;
    outline: none;
}

.security-code-input:focus {
    border-color: #f97316;
    box-shadow: 0 0 0 3px rgba(249, 115, 22, .12);
}

.delete-form {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 6px;
}
    </style>

</x-app-layout>