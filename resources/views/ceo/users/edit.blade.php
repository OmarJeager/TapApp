<x-app-layout>

    <div class="edit-user-page">

        <div class="edit-header">

            <a
                href="{{ route('ceo.users.index') }}"
                class="back-btn"
            >
                <i class="fa-solid fa-arrow-left"></i>
            </a>

            <div>

                <span class="eyebrow">
                    {{ __('CEO USER MANAGEMENT') }}
                </span>

                <h1>
                    {{ __('Edit User') }}
                </h1>

                <p>
                    {{ __('Update account information, role, password and profile picture.') }}
                </p>

            </div>

        </div>


        @if($errors->any())

            <div class="errors-box">

                <i class="fa-solid fa-circle-exclamation"></i>

                <div>

                    <strong>
                        {{ __('Please fix the following errors') }}
                    </strong>

                    <ul>

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            </div>

        @endif


        <form
            method="POST"
            action="{{ route('ceo.users.update', $user) }}"
            enctype="multipart/form-data"
            class="edit-card"
        >

            @csrf

            @method('PUT')


            {{-- PROFILE SECTION --}}

            <div class="profile-section">

                <div class="large-avatar">

                    @if($user->profile_picture)

                        <img
                            id="profilePreview"
                            src="{{ asset('storage/' . $user->profile_picture) }}"
                            alt="{{ $user->name }}"
                        >

                    @else

                        <div
                            id="profilePreview"
                            class="avatar-placeholder"
                        >

                            <i class="fa-solid fa-user"></i>

                        </div>

                    @endif

                </div>


                <div class="profile-info">

                    <h2>
                        {{ $user->name }}
                    </h2>

                    <p>
                        {{ $user->email }}
                    </p>

                    <div class="profile-actions">

                        <label
                            for="profile_picture"
                            class="picture-btn"
                        >

                            <i class="fa-solid fa-camera"></i>

                            {{ __('Change Picture') }}

                        </label>

                        <input
                            type="file"
                            id="profile_picture"
                            name="profile_picture"
                            accept="image/*"
                            hidden
                        >


                        @if($user->profile_picture)

                            <button
                                type="button"
                                class="remove-picture-btn"
                                onclick="deletePicture()"
                            >

                                <i class="fa-solid fa-trash"></i>

                                {{ __('Remove') }}

                            </button>

                        @endif

                    </div>

                </div>

            </div>


            <div class="divider"></div>


            {{-- INFORMATION --}}

            <div class="section-title">

                <i class="fa-solid fa-id-card"></i>

                <div>

                    <h3>
                        {{ __('Account Information') }}
                    </h3>

                    <p>
                        {{ __('Personal and account details') }}
                    </p>

                </div>

            </div>


            <div class="fields">

                <div class="field">

                    <label>
                        {{ __('Full Name') }}
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        required
                    >

                </div>


                <div class="field">

                    <label>
                        {{ __('Matricule') }}
                    </label>

                    <input
                        type="text"
                        name="matricule"
                        value="{{ old('matricule', $user->matricule) }}"
                        required
                    >

                </div>


                <div class="field">

                    <label>
                        {{ __('Email Address') }}
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        required
                    >

                </div>


                <div class="field">

                    <label>
                        {{ __('Role') }}
                    </label>

                    <select name="role">

                        <option
                            value="ceo"
                            @selected($user->role === 'ceo')
                        >
                            CEO
                        </option>

                        <option
                            value="superadmin"
                            @selected($user->role === 'superadmin')
                        >
                            Super Admin
                        </option>
                         <option
                            value="quality"
                            @selected($user->role === 'quality')
                        >
                            Quality
                        </option>
                        <option
                            value="admin"
                            @selected($user->role === 'admin')
                        >
                            Admin
                        </option>

                        <option
                            value="user"
                            @selected($user->role === 'user')
                        >
                            User
                        </option>

                    </select>

                </div>

            </div>


            <div class="divider"></div>


            {{-- PASSWORD --}}

            <div class="section-title">

                <i class="fa-solid fa-key"></i>

                <div>

                    <h3>
                        {{ __('Security') }}
                    </h3>

                    <p>
                        {{ __('Change the password only when necessary.') }}
                    </p>

                </div>

            </div>


            <div class="password-warning">

                <i class="fa-solid fa-shield-halved"></i>

                <span>
                    {{ __('The current password is protected and cannot be displayed. Leave these fields empty to keep the existing password.') }}
                </span>

            </div>


            <div class="fields">

                <div class="field">

                    <label>
                        {{ __('New Password') }}
                    </label>

                    <input
                        type="password"
                        name="password"
                        placeholder="{{ __('Leave empty to keep current password') }}"
                    >

                </div>


                <div class="field">

                    <label>
                        {{ __('Confirm New Password') }}
                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        placeholder="{{ __('Repeat new password') }}"
                    >

                </div>

            </div>


            {{-- ACTIONS --}}

            <div class="form-footer">

                <a
                    href="{{ route('ceo.users.index') }}"
                    class="cancel-btn"
                >
                    {{ __('Cancel') }}
                </a>

                <button
                    type="submit"
                    class="save-btn"
                >

                    <i class="fa-solid fa-floppy-disk"></i>

                    {{ __('Save Changes') }}

                </button>

            </div>

        </form>

    </div>


    {{-- DELETE PICTURE FORM --}}

    <form
        id="deletePictureForm"
        method="POST"
        action="{{ route('ceo.users.picture.destroy', $user) }}"
        style="display:none;"
    >

        @csrf
        @method('DELETE')

    </form>


    <style>

        .edit-user-page {

            max-width: 1000px;

            margin: auto;

            padding: 35px;

            animation: editAppear .5s ease;

        }


        @keyframes editAppear {

            from {

                opacity: 0;

                transform:
                    translateY(15px);

            }

            to {

                opacity: 1;

                transform:
                    translateY(0);

            }

        }


        .edit-header {

            display: flex;

            align-items: center;

            gap: 16px;

            margin-bottom: 25px;

        }


        .back-btn {

            width: 45px;

            height: 45px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 12px;

            background: #f3f4f6;

            color: #344054;

            text-decoration: none;

        }


        .eyebrow {

            font-size: 10px;

            font-weight: 800;

            color: #f97316;

            letter-spacing: 1px;

        }


        .edit-header h1 {

            margin: 3px 0;

            font-size: 29px;

            color: #172033;

        }


        .edit-header p {

            margin: 0;

            color: #8b94a7;

        }


        .edit-card {

            background: white;

            border: 1px solid #edf0f5;

            border-radius: 23px;

            padding: 30px;

            box-shadow:
                0 15px 45px rgba(20,30,50,.07);

        }


        .profile-section {

            display: flex;

            align-items: center;

            gap: 25px;

        }


        .large-avatar {

            width: 120px;

            height: 120px;

            border-radius: 30px;

            overflow: hidden;

            flex-shrink: 0;

            box-shadow:
                0 12px 30px rgba(20,30,50,.12);

        }


        .large-avatar img {

            width: 100%;

            height: 100%;

            object-fit: cover;

        }


        .avatar-placeholder {

            width: 100%;

            height: 100%;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 40px;

            color: white;

            background:
                linear-gradient(
                    135deg,
                    #ff8c00,
                    #ff5e00
                );

        }


        .profile-info h2 {

            margin: 0;

            color: #172033;

        }


        .profile-info p {

            margin: 5px 0 15px;

            color: #8992a5;

        }


        .profile-actions {

            display: flex;

            gap: 10px;

            flex-wrap: wrap;

        }


        .picture-btn,
        .remove-picture-btn {

            padding: 9px 13px;

            border-radius: 10px;

            border: 0;

            cursor: pointer;

            font-weight: 700;

        }


        .picture-btn {

            background: #fff4e8;

            color: #e66c00;

        }


        .remove-picture-btn {

            background: #fff1f2;

            color: #dc2626;

        }


        .divider {

            height: 1px;

            background: #edf0f5;

            margin: 30px 0;

        }


        .section-title {

            display: flex;

            align-items: center;

            gap: 13px;

            margin-bottom: 20px;

        }


        .section-title > i {

            width: 42px;

            height: 42px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 12px;

            background: #fff4e8;

            color: #f97316;

        }


        .section-title h3 {

            margin: 0;

            color: #172033;

        }


        .section-title p {

            margin: 3px 0 0;

            color: #929aab;

            font-size: 12px;

        }


        .fields {

            display: grid;

            grid-template-columns:
                repeat(2, 1fr);

            gap: 20px;

        }


        .field label {

            display: block;

            margin-bottom: 7px;

            color: #344054;

            font-weight: 700;

            font-size: 13px;

        }


        .field input,
        .field select {

            width: 100%;

            height: 48px;

            border: 1px solid #e4e7ec;

            border-radius: 11px;

            padding: 0 14px;

            background: #fafafa;

            outline: none;

        }


        .field input:focus,
        .field select:focus {

            border-color: #f97316;

            box-shadow:
                0 0 0 4px rgba(249,115,22,.10);

            background: white;

        }


        .password-warning {

            display: flex;

            align-items: center;

            gap: 10px;

            padding: 13px 15px;

            border-radius: 12px;

            background: #fffbeb;

            border: 1px solid #fde68a;

            color: #92400e;

            margin-bottom: 20px;

            font-size: 13px;

        }


        .form-footer {

            display: flex;

            justify-content: flex-end;

            gap: 12px;

            margin-top: 30px;

        }


        .cancel-btn,
        .save-btn {

            padding: 12px 20px;

            border-radius: 11px;

            font-weight: 700;

            text-decoration: none;

            border: 0;

            cursor: pointer;

        }


        .cancel-btn {

            color: #667085;

            background: #f2f4f7;

        }


        .save-btn {

            color: white;

            background:
                linear-gradient(
                    135deg,
                    #ff8c00,
                    #ff5e00
                );

            box-shadow:
                0 10px 25px rgba(255,100,0,.22);

        }


        .errors-box {

            display: flex;

            gap: 12px;

            padding: 15px;

            margin-bottom: 20px;

            color: #b42318;

            background: #fef3f2;

            border: 1px solid #fecdca;

            border-radius: 13px;

        }


        @media(max-width:700px) {

            .edit-user-page {

                padding: 18px;

            }

            .edit-card {

                padding: 20px;

            }

            .profile-section {

                align-items: flex-start;

                flex-direction: column;

            }

            .fields {

                grid-template-columns: 1fr;

            }

            .form-footer {

                flex-direction: column;

            }

            .cancel-btn,
            .save-btn {

                text-align: center;

            }

        }

    </style>


    <script>

        /*
        |--------------------------------------------------------------------------
        | Picture preview
        |--------------------------------------------------------------------------
        */

        document
            .getElementById('profile_picture')
            .addEventListener('change', function(event) {

                const file = event.target.files[0];

                if (!file) return;

                const reader = new FileReader();

                reader.onload = function(e) {

                    const preview =
                        document.getElementById('profilePreview');

                    if (preview.tagName === 'IMG') {

                        preview.src = e.target.result;

                    } else {

                        preview.outerHTML =
                            `<img
                                id="profilePreview"
                                src="${e.target.result}"
                                style="width:100%;height:100%;object-fit:cover;"
                            >`;

                    }

                };

                reader.readAsDataURL(file);

            });


        /*
        |--------------------------------------------------------------------------
        | Delete picture
        |--------------------------------------------------------------------------
        */

        function deletePicture()
        {
            if (
                confirm(
                    @json(__('Are you sure you want to remove this profile picture?'))
                )
            ) {

                document
                    .getElementById('deletePictureForm')
                    .submit();

            }
        }

    </script>

</x-app-layout>