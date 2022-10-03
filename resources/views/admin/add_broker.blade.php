@extends('layouts.dashboard')

@section('content')
    <style>
        .content {
            height: 100% !important;
        }
    </style>
    <div class="col-span-3 w-full h-screen">
        @if (session()->has('failed'))
            <div class="flex justify-center -mb-8 mt-4 p-2">
                <div class="flex justify-between text-white shadow-inner rounded p-3 bg-red mb-6 w-136">
                    <p class="self-center"><strong>Failed:</strong> {{ session()->get('failed') }}</p>
                    <strong class="text-xl cursor-pointer mb-1">&times;</strong>
                </div>
            </div>
        @endif
        
        <form class="broker-validation flex justify-center" action="{{ route('broker.store') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="p-10 grid grid-cols-1 gap-5 w-2/4">
                <div class="font-bold text-xl mb-2">Add broker</div>
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
                    <div class="md:w-1/2">
                        <label class="block normal-case tracking-wide text-gray-700 text-xs font-bold mb-2">
                            Image
                        </label>
                        <input
                            class="block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                            aria-describedby="file_input_help" id="file_input" type="file" name="image">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">SVG, PNG, JPG or GIF
                            (MAX. 800x400px).</p>
                    </div>
                    <div class="md:w-1/2 ml-3">
                        <label class="block normal-case tracking-wide text-gray-700 text-xs font-bold mb-2">
                            Select users
                        </label>
                        <select name="user"
                            class="appearance-none block w-full bg-gray-200 text-black rounded py-3 px-4 leading-tight">
                            @if (!empty($users))
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            @else
                                <option disabled>No users</option>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="flex">
                    <button type="submit"
                        class="text-white view-link font-medium rounded-lg text-sm w-full px-5 py-2.5 text-center">
                        Save Broker
                    </button>
                </div>

            </div>
        </form>
    </div>
@endsection
