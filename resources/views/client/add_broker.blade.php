@extends('layouts.app')

@section('content')
    <form class="broker-validation flex justify-center" action="{{ route('user.broker.store') }}" method="POST">
        @csrf
        <div class="p-10 grid grid-cols-1 gap-5 w-2/4">
            <div class="font-bold text-xl mb-2">Add Broker</div>
            <div class="flex">
                <div class="md:w-1/2">
                    <label class="block normal-case tracking-wide text-gray-700 text-xs font-bold mb-2">
                        Company name
                    </label>
                    <input
                        class="appearance-none block w-full bg-gray-200 text-black rounded py-3 px-4 leading-tight @error('name') is-invalid @enderror"
                        type="name" name="name" placeholder="Company name" required>
                </div>
                <div class="md:w-1/2 ml-3">
                    <label class="block normal-case tracking-wide text-gray-700 text-xs font-bold mb-2">
                        API Key
                    </label>
                    <input
                        class="appearance-none block w-full bg-gray-200 text-black rounded py-3 px-4 leading-tight @error('api_key') is-invalid @enderror"
                        type="text" name="api_key" placeholder="Kolibri API Key" required>
                </div>
            </div>
            <div class="flex">
                <div class="flex justify-center items-center w-full w-45">
                    <label for="dropzone-file"
                        class="flex flex-col justify-center items-center w-full h-40 bg-gray-50 rounded-lg border-2 border-gray-300 border-dashed cursor-pointer dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                        <div class="flex flex-col justify-center items-center pt-5 pb-6">
                            <svg class="mb-3 w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                </path>
                            </svg>
                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to
                                    upload</span> or drag and drop</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX.
                                800x400px)</p>
                        </div>
                        <input id="dropzone-file" type="file" class="hidden" name="image" />
                    </label>
                </div>
            </div>
            <div class="flex">
                <button type="submit"
                    class="text-white view-link font-medium rounded-lg text-sm w-full px-5 py-2.5 text-center">Save
                    Broker</button>
            </div>

        </div>
    </form>
@endsection
