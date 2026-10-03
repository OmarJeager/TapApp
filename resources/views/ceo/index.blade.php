<x-app-layout>

    {{-- Header --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <h1>ceo</h1>
<h1>{{ auth()->user()->name }}</h1>
<h1>{{ auth()->user()->role }}</h1>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Success --}}
            @if (session('success'))
                <div class="mb-6 rounded-lg bg-green-100 p-4 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Error --}}
            @if (session('error'))
                <div class="mb-6 rounded-lg bg-red-100 p-4 text-red-700">
                    {{ session('error') }}
                </div>
            @endif


            {{-- Main Card --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-8">

                    {{-- Welcome --}}
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-8">
                        Welcome, {{ auth()->user()->name }}
                    </h3>


                    {{-- ========================================================= --}}
                    {{-- PROFILE --}}
                    {{-- ========================================================= --}}

                    <div class="flex flex-col items-center">

                        {{-- Profile Picture --}}
                        <div class="relative">

                            @if (auth()->user()->profile_picture)

                                <img
                                    src="{{ asset('storage/' . auth()->user()->profile_picture) }}?v={{ time() }}"
                                    alt="Profile Picture"
                                    class="h-40 w-40 rounded-full object-cover ring-4 ring-gray-200 dark:ring-gray-600"
                                >

                            @else

                                <div class="h-40 w-40 rounded-full bg-gray-200 dark:bg-gray-700
                                            flex items-center justify-center
                                            text-gray-500">
                                    No picture
                                </div>

                            @endif

                        </div>


                        {{-- Name --}}
                        <h4 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white">
                            {{ auth()->user()->name }}
                        </h4>


                        {{-- Choose Photo Button --}}
                        <button
                            type="button"
                            onclick="document.getElementById('picture').click()"
                            class="mt-5 rounded-full bg-blue-600 px-6 py-2.5
                                   text-sm font-semibold text-white
                                   hover:bg-blue-700 transition"
                        >
                            Change profile picture
                        </button>


                        {{-- Hidden File Input --}}
                        <input
                            type="file"
                            id="picture"
                            accept="image/jpeg,image/png,image/webp"
                            class="hidden"
                        >

                    </div>


                    {{-- ========================================================= --}}
                    {{-- SIGNATURE SECTION --}}
                    {{-- ========================================================= --}}

                    <div class="mt-12 border-t border-gray-200 dark:border-gray-700 pt-10">

                        <div class="max-w-2xl mx-auto">

                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                My Signature
                            </h3>

                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                Draw your signature below and save it to your account.
                            </p>


                            {{-- Existing Signature --}}
                            @if (auth()->user()->signature)

                                <div class="mt-6">

                                    <p class="mb-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Current signature
                                    </p>

                                    <div class="rounded-xl border border-gray-200
                                                dark:border-gray-600 bg-white p-4">

                                        <img
                                            src="{{ asset('storage/' . auth()->user()->signature) }}?v={{ time() }}"
                                            alt="Saved Signature"
                                            class="max-h-32 max-w-full object-contain"
                                        >

                                    </div>

                                </div>

                            @endif


                            {{-- Signature Form --}}
                            <form
                                method="POST"
                                action="{{ route('profile.signature.update') }}"
                                id="signatureForm"
                                class="mt-6"
                            >

                                @csrf

                                {{-- Canvas --}}
                                <div
                                    class="rounded-xl border-2 border-gray-300
                                           dark:border-gray-600 bg-white overflow-hidden"
                                >

                                    <canvas
                                        id="signatureCanvas"
                                        class="w-full h-52 cursor-crosshair"
                                    ></canvas>

                                </div>


                                {{-- Hidden Signature --}}
                                <input
                                    type="hidden"
                                    name="signature"
                                    id="signatureInput"
                                >


                                {{-- Validation Error --}}
                                @error('signature')
                                    <p class="mt-2 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror


                                {{-- Buttons --}}
                                <div class="mt-4 flex items-center gap-3">

                                    <button
                                        type="button"
                                        id="clearSignature"
                                        class="rounded-lg bg-gray-200
                                               px-5 py-2.5 text-sm font-semibold
                                               text-gray-700 hover:bg-gray-300
                                               transition"
                                    >
                                        Clear
                                    </button>


                                    <button
                                        type="submit"
                                        id="saveSignature"
                                        class="rounded-lg bg-blue-600
                                               px-6 py-2.5 text-sm font-semibold
                                               text-white hover:bg-blue-700
                                               transition"
                                    >
                                        Save Signature
                                    </button>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- CROP MODAL --}}
    {{-- ========================================================= --}}

    <div
        id="cropModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 p-4"
    >

        <div
            class="w-full max-w-lg rounded-2xl bg-white dark:bg-gray-800
                   shadow-2xl overflow-hidden"
        >

            {{-- Modal Header --}}
            <div class="flex items-center justify-between border-b
                        border-gray-200 dark:border-gray-700 px-5 py-4">

                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Edit profile picture
                </h3>

                <button
                    type="button"
                    onclick="closeCropper()"
                    class="text-2xl text-gray-500 hover:text-gray-800"
                >
                    &times;
                </button>

            </div>


            {{-- Image Area --}}
            <div class="bg-black p-4">

                <div class="crop-container">

                    <img
                        id="cropImage"
                        src=""
                        alt="Crop image"
                    >

                </div>

            </div>


            {{-- Controls --}}
            <div class="flex items-center justify-center gap-3 px-5 py-4">

                <button
                    type="button"
                    onclick="zoomOut()"
                    class="h-10 w-10 rounded-full bg-gray-200
                           hover:bg-gray-300 text-lg"
                >
                    −
                </button>


                <button
                    type="button"
                    onclick="zoomIn()"
                    class="h-10 w-10 rounded-full bg-gray-200
                           hover:bg-gray-300 text-lg"
                >
                    +
                </button>


                <button
                    type="button"
                    onclick="resetCropper()"
                    class="rounded-full bg-gray-200 px-4 py-2
                           text-sm hover:bg-gray-300"
                >
                    Reset
                </button>

            </div>


            {{-- Buttons --}}
            <div class="flex justify-end gap-3 border-t
                        border-gray-200 dark:border-gray-700
                        px-5 py-4">

                <button
                    type="button"
                    onclick="closeCropper()"
                    class="rounded-lg px-5 py-2.5 text-sm font-semibold
                           text-gray-700 bg-gray-200 hover:bg-gray-300"
                >
                    Cancel
                </button>


                <button
                    type="button"
                    onclick="savePicture()"
                    id="saveButton"
                    class="rounded-lg bg-blue-600 px-6 py-2.5
                           text-sm font-semibold text-white
                           hover:bg-blue-700"
                >
                    Save
                </button>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- CROP CSS --}}
    {{-- ========================================================= --}}

    <style>

        .crop-container {
            width: 100%;
            height: 450px;
            overflow: hidden;
        }

        .crop-container img {
            display: block;
            max-width: 100%;
        }

        #signatureCanvas {
            display: block;
            touch-action: none;
        }

    </style>


    {{-- ========================================================= --}}
    {{-- CROPPER.JS --}}
    {{-- ========================================================= --}}

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.css"
    >

    <script
        src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.js">
    </script>


    {{-- ========================================================= --}}
    {{-- JAVASCRIPT --}}
    {{-- ========================================================= --}}

    <script>

        /*
        |--------------------------------------------------------------------------
        | PROFILE PICTURE CROPPER
        |--------------------------------------------------------------------------
        */

        let cropper = null;

        const pictureInput = document.getElementById('picture');
        const cropModal = document.getElementById('cropModal');
        const cropImage = document.getElementById('cropImage');


        /*
        |--------------------------------------------------------------------------
        | Choose Image
        |--------------------------------------------------------------------------
        */

        pictureInput.addEventListener('change', function(event) {

            const file = event.target.files[0];

            if (!file) {
                return;
            }

            if (!file.type.startsWith('image/')) {

                alert('Please select an image.');

                pictureInput.value = '';

                return;
            }


            const imageURL = URL.createObjectURL(file);

            cropImage.src = imageURL;


            cropModal.classList.remove('hidden');
            cropModal.classList.add('flex');


            cropImage.onload = function() {

                if (cropper) {
                    cropper.destroy();
                }


                cropper = new Cropper(cropImage, {

                    aspectRatio: 1,

                    viewMode: 1,

                    dragMode: 'move',

                    autoCropArea: 0.85,

                    responsive: true,

                    background: false,

                    guides: false,

                    center: true,

                    highlight: false,

                    cropBoxMovable: false,

                    cropBoxResizable: false,

                    toggleDragModeOnDblclick: false,

                });

            };

        });


        /*
        |--------------------------------------------------------------------------
        | Zoom In
        |--------------------------------------------------------------------------
        */

        function zoomIn() {

            if (cropper) {
                cropper.zoom(0.1);
            }

        }


        /*
        |--------------------------------------------------------------------------
        | Zoom Out
        |--------------------------------------------------------------------------
        */

        function zoomOut() {

            if (cropper) {
                cropper.zoom(-0.1);
            }

        }


        /*
        |--------------------------------------------------------------------------
        | Reset
        |--------------------------------------------------------------------------
        */

        function resetCropper() {

            if (cropper) {
                cropper.reset();
            }

        }


        /*
        |--------------------------------------------------------------------------
        | Close Cropper
        |--------------------------------------------------------------------------
        */

        function closeCropper() {

            if (cropper) {

                cropper.destroy();

                cropper = null;
            }

            cropModal.classList.add('hidden');

            cropModal.classList.remove('flex');

            pictureInput.value = '';

        }


        /*
        |--------------------------------------------------------------------------
        | Save Profile Picture
        |--------------------------------------------------------------------------
        */

        function savePicture() {

            if (!cropper) {
                return;
            }


            const saveButton = document.getElementById('saveButton');

            saveButton.disabled = true;

            saveButton.innerText = 'Saving...';


            cropper.getCroppedCanvas({

                width: 500,

                height: 500,

                imageSmoothingEnabled: true,

                imageSmoothingQuality: 'high'

            }).toBlob(function(blob) {


                const formData = new FormData();

                formData.append(
                    'picture',
                    blob,
                    'profile-picture.jpg'
                );

                formData.append(
                    '_token',
                    '{{ csrf_token() }}'
                );


                fetch('{{ route('profile.upload-picture') }}', {

                    method: 'POST',

                    body: formData,

                    headers: {
                        'Accept': 'application/json'
                    }

                })

                .then(response => response.json())

                .then(data => {

                    if (data.success) {

                        closeCropper();

                        window.location.reload();

                    } else {

                        alert(data.message || 'Upload failed.');

                        saveButton.disabled = false;

                        saveButton.innerText = 'Save';

                    }

                })

                .catch(error => {

                    console.error(error);

                    alert('Something went wrong while uploading.');

                    saveButton.disabled = false;

                    saveButton.innerText = 'Save';

                });

            }, 'image/jpeg', 0.90);

        }


        /*
        |--------------------------------------------------------------------------
        | SIGNATURE PAD
        |--------------------------------------------------------------------------
        */

        const signatureCanvas =
            document.getElementById('signatureCanvas');

        const signatureInput =
            document.getElementById('signatureInput');

        const clearSignature =
            document.getElementById('clearSignature');

        const signatureForm =
            document.getElementById('signatureForm');


        const signatureContext =
            signatureCanvas.getContext('2d');


        let signatureDrawing = false;


        /*
        |--------------------------------------------------------------------------
        | Resize Signature Canvas
        |--------------------------------------------------------------------------
        */

        function resizeSignatureCanvas() {

            const rect =
                signatureCanvas.getBoundingClientRect();

            const ratio =
                window.devicePixelRatio || 1;


            signatureCanvas.width =
                rect.width * ratio;

            signatureCanvas.height =
                rect.height * ratio;


            signatureContext.setTransform(
                ratio,
                0,
                0,
                ratio,
                0,
                0
            );


            signatureContext.lineWidth = 2;

            signatureContext.lineCap = 'round';

            signatureContext.lineJoin = 'round';

            signatureContext.strokeStyle = '#000000';

        }


        resizeSignatureCanvas();


        /*
        |--------------------------------------------------------------------------
        | Get Mouse / Touch Position
        |--------------------------------------------------------------------------
        */

        function getSignaturePosition(event) {

            const rect =
                signatureCanvas.getBoundingClientRect();


            return {

                x: event.clientX - rect.left,

                y: event.clientY - rect.top

            };

        }


        /*
        |--------------------------------------------------------------------------
        | Start Drawing
        |--------------------------------------------------------------------------
        */

        signatureCanvas.addEventListener(
            'pointerdown',
            function(event) {

                signatureDrawing = true;


                const position =
                    getSignaturePosition(event);


                signatureContext.beginPath();

                signatureContext.moveTo(
                    position.x,
                    position.y
                );


                signatureCanvas.setPointerCapture(
                    event.pointerId
                );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Draw
        |--------------------------------------------------------------------------
        */

        signatureCanvas.addEventListener(
            'pointermove',
            function(event) {

                if (!signatureDrawing) {
                    return;
                }


                const position =
                    getSignaturePosition(event);


                signatureContext.lineTo(
                    position.x,
                    position.y
                );

                signatureContext.stroke();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Stop Drawing
        |--------------------------------------------------------------------------
        */

        signatureCanvas.addEventListener(
            'pointerup',
            function() {

                signatureDrawing = false;

            }
        );


        signatureCanvas.addEventListener(
            'pointercancel',
            function() {

                signatureDrawing = false;

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Clear Signature
        |--------------------------------------------------------------------------
        */

        clearSignature.addEventListener(
            'click',
            function() {

                signatureContext.clearRect(
                    0,
                    0,
                    signatureCanvas.width,
                    signatureCanvas.height
                );


                signatureInput.value = '';

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Save Signature
        |--------------------------------------------------------------------------
        */

        signatureForm.addEventListener(
            'submit',
            function(event) {

                /*
                |--------------------------------------------------------------
                | Check if canvas is empty
                |--------------------------------------------------------------
                */

                const canvasData =
                    signatureCanvas.toDataURL('image/png');


                /*
                |--------------------------------------------------------------
                | Save canvas image in hidden input
                |--------------------------------------------------------------
                */

                signatureInput.value = canvasData;

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Prevent Losing Signature on Resize
        |--------------------------------------------------------------------------
        */

        window.addEventListener(
            'resize',
            function() {

                /*
                 * Do not resize if the user has already drawn.
                 * Resizing a canvas clears its content.
                 */

                if (!signatureInput.value) {

                    resizeSignatureCanvas();

                }

            }
        );

    </script>

</x-app-layout>
