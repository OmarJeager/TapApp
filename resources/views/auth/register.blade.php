<x-guest-layout>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />

            <x-text-input
                id="name"
                class="block mt-1 w-full"
                type="text"
                name="name"
                :value="old('name')"
                required
                autofocus
                autocomplete="name"
            />

            <x-input-error
                :messages="$errors->get('name')"
                class="mt-2"
            />
        </div>

        <!-- Matricule -->
        <div class="mt-4">
            <x-input-label for="matricule" :value="__('Matricule')" />

            <x-text-input
                id="matricule"
                class="block mt-1 w-full"
                type="text"
                name="matricule"
                :value="old('matricule')"
                required
                autocomplete="off"
            />

            <x-input-error
                :messages="$errors->get('matricule')"
                class="mt-2"
            />
        </div>

        <!-- Email -->
        <div class="mt-4 email-wrapper">

            <x-input-label for="email" :value="__('Email')" />

            <div class="email-input-container">

                <x-text-input
                    id="email"
                    class="block mt-1 w-full"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required
                    autocomplete="username"
                    spellcheck="false"
                />

                <!-- Email Suggestions -->
                <div
                    id="emailSuggestions"
                    class="email-suggestions"
                    role="listbox"
                    aria-label="Email suggestions"
                ></div>

            </div>

            <x-input-error
                :messages="$errors->get('email')"
                class="mt-2"
            />

        </div>

        <!-- Password -->
        <div class="mt-4">

            <x-input-label for="password" :value="__('Password')" />

            <x-text-input
                id="password"
                class="block mt-1 w-full"
                type="password"
                name="password"
                required
                autocomplete="new-password"
            />

            <x-input-error
                :messages="$errors->get('password')"
                class="mt-2"
            />

        </div>

        <!-- Confirm Password -->
        <div class="mt-4">

            <x-input-label
                for="password_confirmation"
                :value="__('Confirm Password')"
            />

            <x-text-input
                id="password_confirmation"
                class="block mt-1 w-full"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
            />

            <x-input-error
                :messages="$errors->get('password_confirmation')"
                class="mt-2"
            />

        </div>

        <!-- Buttons -->
        <div class="flex items-center justify-end mt-4">

            <a
                class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                href="{{ route('login') }}"
            >
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>

        </div>

    </form>


    <!-- =============================== -->
    <!-- EMAIL AUTOCOMPLETE STYLES -->
    <!-- =============================== -->

    <style>

        .email-wrapper {
            position: relative;
        }

        .email-input-container {
            position: relative;
        }

        /* Suggestions container */
        .email-suggestions {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            width: 100%;
            z-index: 9999;

            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 10px;

            box-shadow:
                0 10px 25px rgba(0, 0, 0, 0.10),
                0 4px 10px rgba(0, 0, 0, 0.05);

            overflow: hidden;

            display: none;

            opacity: 0;
            transform: translateY(-8px) scale(0.98);

            transition:
                opacity 0.18s ease,
                transform 0.18s ease;
        }

        .email-suggestions.show {
            display: block;
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        /* Individual suggestion */
        .email-suggestion {
            display: flex;
            align-items: center;
            gap: 10px;

            width: 100%;
            padding: 12px 14px;

            background: white;
            border: none;

            cursor: pointer;

            font-size: 14px;
            color: #374151;

            text-align: left;

            transition:
                background 0.15s ease,
                padding-left 0.15s ease;
        }

        .email-suggestion:hover,
        .email-suggestion.active {
            background: #fff7ed;
            color: #ea580c;
            padding-left: 18px;
        }

        /* Email icon */
        .email-suggestion-icon {
            width: 30px;
            height: 30px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 8px;

            background: #fff7ed;
            color: #ea580c;

            font-size: 14px;

            flex-shrink: 0;
        }

        .email-suggestion.active .email-suggestion-icon,
        .email-suggestion:hover .email-suggestion-icon {
            background: #ea580c;
            color: white;
        }

        /* Highlight typed text */
        .email-highlight {
            font-weight: 700;
            color: #111827;
        }

        .email-suggestion:hover .email-highlight,
        .email-suggestion.active .email-highlight {
            color: #ea580c;
        }

        /* Keyboard hint */
        .email-key-hint {
            margin-left: auto;

            font-size: 11px;
            color: #9ca3af;

            padding: 3px 7px;

            border: 1px solid #e5e7eb;
            border-radius: 5px;

            background: #f9fafb;
        }

        /* Dark mode */
        @media (prefers-color-scheme: dark) {

            .email-suggestions {
                background: #1f2937;
                border-color: #374151;

                box-shadow:
                    0 10px 25px rgba(0, 0, 0, 0.35),
                    0 4px 10px rgba(0, 0, 0, 0.25);
            }

            .email-suggestion {
                background: #1f2937;
                color: #e5e7eb;
            }

            .email-suggestion:hover,
            .email-suggestion.active {
                background: #431407;
                color: #fb923c;
            }

            .email-suggestion-icon {
                background: #431407;
                color: #fb923c;
            }

            .email-suggestion:hover .email-suggestion-icon,
            .email-suggestion.active .email-suggestion-icon {
                background: #ea580c;
                color: white;
            }

            .email-highlight {
                color: #f3f4f6;
            }

            .email-suggestion:hover .email-highlight,
            .email-suggestion.active .email-highlight {
                color: #fb923c;
            }

            .email-key-hint {
                background: #111827;
                border-color: #374151;
                color: #9ca3af;
            }
        }

        /* Mobile */
        @media (max-width: 640px) {

            .email-suggestion {
                padding: 11px 12px;
            }

            .email-key-hint {
                display: none;
            }

        }

    </style>


    <!-- =============================== -->
    <!-- EMAIL AUTOCOMPLETE JAVASCRIPT -->
    <!-- =============================== -->

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const emailInput = document.getElementById('email');
            const suggestionsBox = document.getElementById('emailSuggestions');

            if (!emailInput || !suggestionsBox) {
                return;
            }

            /*
             * Supported email domains
             */
            const domains = [
                'gmail.com',
                'yahoo.com',
                'outlook.com',
                'hotmail.com',
                'icloud.com'
            ];

            let selectedIndex = -1;
            let currentSuggestions = [];


            /*
             * Normalize common email typos
             */
            const typoCorrections = {
                'gmial.com': 'gmail.com',
                'gmal.com': 'gmail.com',
                'gmai.com': 'gmail.com',
                'gmail.con': 'gmail.com',

                'yaho.com': 'yahoo.com',
                'yhoo.com': 'yahoo.com',
                'yahoo.con': 'yahoo.com',

                'outlok.com': 'outlook.com',
                'outloo.com': 'outlook.com',
                'outlook.con': 'outlook.com',

                'hotmai.com': 'hotmail.com',
                'hotmial.com': 'hotmail.com',
                'hotmail.con': 'hotmail.com',

                'iclud.com': 'icloud.com',
                'icloud.con': 'icloud.com'
            };


            /*
             * Get email username
             */
            function getUsername(value) {

                const atIndex = value.indexOf('@');

                if (atIndex === -1) {
                    return value;
                }

                return value.substring(0, atIndex);
            }


            /*
             * Get domain part
             */
            function getDomain(value) {

                const atIndex = value.indexOf('@');

                if (atIndex === -1) {
                    return '';
                }

                return value.substring(atIndex + 1).toLowerCase();
            }


            /*
             * Highlight matching text
             */
            function highlightDomain(domain, typedDomain) {

                if (!typedDomain) {
                    return domain;
                }

                const escaped = typedDomain.replace(
                    /[.*+?^${}()|[\]\\]/g,
                    '\\$&'
                );

                const regex = new RegExp(
                    '^(' + escaped + ')',
                    'i'
                );

                return domain.replace(
                    regex,
                    '<span class="email-highlight">$1</span>'
                );
            }


            /*
             * Build suggestion list
             */
            function updateSuggestions() {

                const value = emailInput.value.trim();

                /*
                 * No @ = no suggestions
                 */
                if (!value.includes('@')) {

                    hideSuggestions();

                    return;
                }


                const username = getUsername(value);
                const typedDomain = getDomain(value);


                /*
                 * Don't show suggestions without username
                 */
                if (!username) {

                    hideSuggestions();

                    return;
                }


                /*
                 * If the domain is already complete,
                 * don't show suggestions.
                 */
                if (
                    domains.includes(typedDomain) &&
                    value.endsWith('@' + typedDomain)
                ) {

                    hideSuggestions();

                    return;
                }


                /*
                 * Typo correction
                 */
                if (typoCorrections[typedDomain]) {

                    const correctedDomain =
                        typoCorrections[typedDomain];

                    currentSuggestions = [
                        correctedDomain
                    ];

                } else {

                    /*
                     * Filter domains
                     *
                     * Example:
                     * @g -> gmail.com
                     * @y -> yahoo.com
                     */
                    currentSuggestions = domains.filter(
                        function (domain) {

                            return domain
                                .toLowerCase()
                                .startsWith(typedDomain);

                        }
                    );

                }


                /*
                 * If no matching domain,
                 * show all domains when only @ is typed.
                 */
                if (
                    typedDomain === '' &&
                    currentSuggestions.length === 0
                ) {

                    currentSuggestions = [...domains];

                }


                renderSuggestions(typedDomain);

            }


            /*
             * Render dropdown
             */
            function renderSuggestions(typedDomain) {

                suggestionsBox.innerHTML = '';

                selectedIndex = -1;


                if (currentSuggestions.length === 0) {

                    hideSuggestions();

                    return;
                }


                currentSuggestions.forEach(
                    function (domain, index) {

                        const button =
                            document.createElement('button');

                        button.type = 'button';

                        button.className =
                            'email-suggestion';

                        button.setAttribute(
                            'role',
                            'option'
                        );


                        /*
                         * Icon
                         */
                        const icon =
                            document.createElement('span');

                        icon.className =
                            'email-suggestion-icon';

                        icon.innerHTML = '✉';


                        /*
                         * Email text
                         */
                        const text =
                            document.createElement('span');

                        text.innerHTML =
                            '@' +
                            highlightDomain(
                                domain,
                                typedDomain
                            );


                        /*
                         * Keyboard hint
                         */
                        const hint =
                            document.createElement('span');

                        hint.className =
                            'email-key-hint';

                        hint.textContent =
                            index === 0
                                ? 'Enter'
                                : '';


                        button.appendChild(icon);
                        button.appendChild(text);

                        if (index === 0) {
                            button.appendChild(hint);
                        }


                        /*
                         * Mouse selection
                         */
                        button.addEventListener(
                            'mousedown',
                            function (event) {

                                event.preventDefault();

                                selectDomain(index);

                            }
                        );


                        suggestionsBox.appendChild(button);

                    }
                );


                showSuggestions();

            }


            /*
             * Show dropdown
             */
            function showSuggestions() {

                suggestionsBox.style.display = 'block';

                requestAnimationFrame(
                    function () {

                        suggestionsBox.classList.add('show');

                    }
                );

            }


            /*
             * Hide dropdown
             */
            function hideSuggestions() {

                suggestionsBox.classList.remove('show');

                setTimeout(
                    function () {

                        if (
                            !suggestionsBox.classList.contains('show')
                        ) {

                            suggestionsBox.style.display =
                                'none';

                        }

                    },
                    180
                );

                selectedIndex = -1;

            }


            /*
             * Select domain
             */
            function selectDomain(index) {

                if (
                    index < 0 ||
                    index >= currentSuggestions.length
                ) {
                    return;
                }


                const username =
                    getUsername(emailInput.value);

                const domain =
                    currentSuggestions[index];


                emailInput.value =
                    username + '@' + domain;


                /*
                 * Move cursor to end
                 */
                emailInput.focus();

                emailInput.setSelectionRange(
                    emailInput.value.length,
                    emailInput.value.length
                );


                hideSuggestions();

            }


            /*
             * Keyboard navigation
             */
            emailInput.addEventListener(
                'keydown',
                function (event) {

                    /*
                     * Only handle keyboard if
                     * suggestions are visible.
                     */
                    if (
                        suggestionsBox.style.display !==
                        'block'
                    ) {
                        return;
                    }


                    /*
                     * Arrow Down
                     */
                    if (event.key === 'ArrowDown') {

                        event.preventDefault();

                        if (
                            currentSuggestions.length === 0
                        ) {
                            return;
                        }

                        selectedIndex++;

                        if (
                            selectedIndex >=
                            currentSuggestions.length
                        ) {

                            selectedIndex = 0;

                        }

                        updateActiveSuggestion();

                    }


                    /*
                     * Arrow Up
                     */
                    else if (event.key === 'ArrowUp') {

                        event.preventDefault();

                        if (
                            currentSuggestions.length === 0
                        ) {
                            return;
                        }

                        selectedIndex--;

                        if (selectedIndex < 0) {

                            selectedIndex =
                                currentSuggestions.length - 1;

                        }

                        updateActiveSuggestion();

                    }


                    /*
                     * Enter
                     */
                    else if (event.key === 'Enter') {

                        if (selectedIndex >= 0) {

                            event.preventDefault();

                            selectDomain(selectedIndex);

                        } else if (
                            currentSuggestions.length === 1
                        ) {

                            event.preventDefault();

                            selectDomain(0);

                        }

                    }


                    /*
                     * Tab
                     */
                    else if (event.key === 'Tab') {

                        if (
                            selectedIndex >= 0
                        ) {

                            selectDomain(selectedIndex);

                        } else if (
                            currentSuggestions.length === 1
                        ) {

                            selectDomain(0);

                        }

                    }


                    /*
                     * Escape
                     */
                    else if (event.key === 'Escape') {

                        hideSuggestions();

                    }

                }
            );


            /*
             * Update active suggestion
             */
            function updateActiveSuggestion() {

                const items =
                    suggestionsBox.querySelectorAll(
                        '.email-suggestion'
                    );


                items.forEach(
                    function (item, index) {

                        item.classList.toggle(
                            'active',
                            index === selectedIndex
                        );

                    }
                );


                /*
                 * Scroll selected item
                 * into view.
                 */
                if (selectedIndex >= 0) {

                    items[selectedIndex]
                        ?.scrollIntoView({
                            block: 'nearest'
                        });

                }

            }


            /*
             * Update while typing
             */
            emailInput.addEventListener(
                'input',
                function () {

                    updateSuggestions();

                }
            );


            /*
             * Focus
             */
            emailInput.addEventListener(
                'focus',
                function () {

                    if (emailInput.value.includes('@')) {

                        updateSuggestions();

                    }

                }
            );


            /*
             * Click outside
             */
            document.addEventListener(
                'click',
                function (event) {

                    if (
                        !emailInput.contains(event.target) &&
                        !suggestionsBox.contains(event.target)
                    ) {

                        hideSuggestions();

                    }

                }
            );

        });

    </script>

</x-guest-layout>
