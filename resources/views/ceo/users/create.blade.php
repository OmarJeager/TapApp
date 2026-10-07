<x-app-layout>

    <div class="user-form-page">

        <div class="form-header">

            <a
                href="{{ route('ceo.users.index') }}"
                class="back-btn"
            >
                <i class="fa-solid fa-arrow-left"></i>
            </a>

            <div>

                <h1>
                    <i class="fa-solid fa-user-plus"></i>

                    {{ __('Create User') }}
                </h1>

                <p>
                    {{ __('Create a new TapApp account.') }}
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
            action="{{ route('ceo.users.store') }}"
            enctype="multipart/form-data"
            class="user-form"
        >

            @csrf


            {{-- PROFILE --}}

            <div class="profile-upload">

                <div
                    id="preview"
                    class="preview"
                >

                    <i class="fa-solid fa-user"></i>

                </div>

                <label
                    for="profile_picture"
                    class="upload-btn"
                >

                    <i class="fa-solid fa-camera"></i>

                    {{ __('Choose Picture') }}

                </label>

                <input
                    id="profile_picture"
                    type="file"
                    name="profile_picture"
                    accept="image/*"
                    hidden
                >

                <small>
                    JPG, PNG, WEBP — Max 4 MB
                </small>

            </div>


            <div class="fields-grid">

                {{-- NAME --}}

                <div class="field">

                    <label>
                        <i class="fa-solid fa-user"></i>
                        {{ __('Full Name') }}
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        placeholder="{{ __('Enter full name') }}"
                    >

                </div>


                {{-- MATRICULE --}}

                <div class="field">

                    <label>
                        <i class="fa-solid fa-id-card"></i>
                        {{ __('Matricule') }}
                    </label>

                    <input
                        type="text"
                        name="matricule"
                        value="{{ old('matricule') }}"
                        required
                        placeholder="{{ __('Employee matricule') }}"
                    >

                </div>


                {{-- EMAIL --}}

                <div class="field">

                    <label>
                        <i class="fa-solid fa-envelope"></i>
                        {{ __('Email') }}
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        placeholder="user@example.com"
                    >

                </div>


                {{-- ROLE --}}

                <div class="field">

                    <label>
                        <i class="fa-solid fa-shield-halved"></i>
                        {{ __('Role') }}
                    </label>

                    <select
                        name="role"
                        required
                    >

                        <option value="">
                            {{ __('Select role') }}
                        </option>

                        <option value="ceo">
                            CEO
                        </option>

                        <option value="superadmin">
                            Super Admin
                        </option>

                        <option value="admin">
                            Admin
                        </option>

                        <option value="user">
                            User
                        </option>

                    </select>

                </div>


                {{-- PASSWORD --}}

                <div class="field">

                    <label>
                        <i class="fa-solid fa-lock"></i>
                        {{ __('Password') }}
                    </label>

                    <input
                        type="password"
                        name="password"
                        required
                        placeholder="{{ __('Minimum 8 characters') }}"
                    >

                </div>


                {{-- CONFIRM --}}

                <div class="field">

                    <label>
                        <i class="fa-solid fa-lock"></i>
                        {{ __('Confirm Password') }}
                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        required
                        placeholder="{{ __('Repeat password') }}"
                    >

                </div>

            </div>


            <div class="form-actions">

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

                    <i class="fa-solid fa-user-plus"></i>

                    {{ __('Create Account') }}

                </button>

            </div>

        </form>

    </div>


    <style>

        .user-form-page {

            max-width: 950px;

            margin: auto;

            padding: 35px;

            animation: appear .5s ease;

        }


        @keyframes appear {

            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }

        }


        .form-header {

            display: flex;

            align-items: center;

            gap: 15px;

            margin-bottom: 25px;

        }


        .back-btn {

            width: 45px;

            height: 45px;

            border-radius: 12px;

            background: #f3f4f6;

            color: #374151;

            display: flex;

            align-items: center;

            justify-content: center;

            text-decoration: none;

        }


        .form-header h1 {

            margin: 0;

            color: #172033;

        }


        .form-header h1 i {

            color: #f97316;

        }


        .form-header p {

            margin: 4px 0 0;

            color: #8b94a7;

        }


        .user-form {

            background: white;

            padding: 30px;

            border-radius: 22px;

            border: 1px solid #edf0f5;

            box-shadow:
                0 15px 45px rgba(20,30,50,.07);

        }


        .profile-upload {

            text-align: center;

            margin-bottom: 30px;

        }


        .preview {

            width: 110px;

            height: 110px;

            margin: auto;

            border-radius: 30px;

            background:
                linear-gradient(
                    135deg,
                    #ff8c00,
                    #ff5e00
                );

            display: flex;

            align-items: center;

            justify-content: center;

            overflow: hidden;

            color: white;

            font-size: 35px;

            box-shadow:
                0 15px 30px rgba(255,100,0,.25);

        }


        .preview img {

            width: 100%;

            height: 100%;

            object-fit: cover;

        }


        .upload-btn {

            display: inline-flex;

            margin-top: 15px;

            padding: 10px 15px;

            border-radius: 10px;

            background: #fff3e8;

            color: #e76d00;

            cursor: pointer;

            font-weight: 700;

        }


        .profile-upload small {

            display: block;

            margin-top: 7px;

            color: #9ca3af;

        }


        .fields-grid {

            display: grid;

            grid-template-columns:
                repeat(2, 1fr);

            gap: 20px;

        }


        .field label {

            display: block;

            margin-bottom: 7px;

            color: #374151;

            font-size: 13px;

            font-weight: 700;

        }


        .field label i {

            color: #f97316;

            width: 18px;

        }


        .field input,
        .field select {

            width: 100%;

            height: 48px;

            border: 1px solid #e5e7eb;

            border-radius: 11px;

            padding: 0 14px;

            outline: none;

            background: #fafafa;

        }


        .field input:focus,
        .field select:focus {

            border-color: #f97316;

            box-shadow:
                0 0 0 4px rgba(249,115,22,.1);

            background: white;

        }


        .form-actions {

            display: flex;

            justify-content: flex-end;

            gap: 12px;

            margin-top: 30px;

        }


        .cancel-btn,
        .save-btn {

            padding: 12px 20px;

            border-radius: 11px;

            text-decoration: none;

            border: 0;

            cursor: pointer;

            font-weight: 700;

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
                0 10px 25px rgba(255,100,0,.2);

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

            .user-form-page {

                padding: 18px;

            }

            .user-form {

                padding: 20px;

            }

            .fields-grid {

                grid-template-columns: 1fr;

            }

        }

    </style>


    <script>

        document
            .getElementById('profile_picture')
            .addEventListener('change', function(event) {

                const file = event.target.files[0];

                if (!file) return;

                const reader = new FileReader();

                reader.onload = function(e) {

                    document.getElementById('preview').innerHTML =
                        `<img src="${e.target.result}" alt="Preview">`;

                };

                reader.readAsDataURL(file);

            });

    </script>

</x-app-layout>