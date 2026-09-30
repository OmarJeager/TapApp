<x-app-layout>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <h2 class="text-2xl font-bold mb-6">
                    My Profile
                </h2>

                {{-- Current picture --}}
                <div class="mb-6">

                    @if(auth()->user()->picture)
                        <img
                            src="{{ asset('storage/' . auth()->user()->picture) }}"
                            alt="Profile Picture"
                            class="w-32 h-32 rounded-full object-cover border"
                        >
                    @else
                        <div class="w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center">
                            No Picture
                        </div>
                    @endif

                </div>

                {{-- Upload form --}}
                <form
                    method="POST"
                    action="{{ route('profile.update') }}"
                    enctype="multipart/form-data"
                >
                    @csrf

                    <div class="mb-4">

                        <label class="block font-medium mb-2">
                            Profile Picture
                        </label>

                        <input
                            type="file"
                            name="picture"
                            accept="image/*"
                            class="border rounded p-2 w-full"
                        >

                        @error('picture')
                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    <button
                        type="submit"
                        class="bg-blue-600 text-white px-5 py-2 rounded"
                    >
                        Save Picture
                    </button>

                </form>

                @if(session('success'))
                    <p class="text-green-600 mt-4">
                        {{ session('success') }}
                    </p>
                @endif

            </div>

        </div>
    </div>

</x-app-layout>
