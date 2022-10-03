@extends('layouts.user_dashboard')

@section('content')
    <style>
        .content {
            height: 100% !important;
        }
    </style>
    <div class="col-span-3 w-full h-screen">
        <form class="broker-validation flex justify-center" action="{{ route('user.broker.update', $broker->api_key) }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            <div class="p-10 grid grid-cols-1 gap-5 w-2/4">
                <div class="font-bold text-xl mb-2">Edit broker</div>
                <div class="flex">
                    <div class="md:w-1/2 mr-2">
                        <label class="block normal-case tracking-wide text-gray-700 text-xs font-bold mb-2">
                            Company name
                        </label>
                        <input
                            class="appearance-none block w-full bg-gray-200 text-black rounded py-3 px-4 leading-tight @error('name') is-invalid @enderror"
                            type="name" name="name" value="{{ $broker->name }}" placeholder="Company name" required
                            autofocus>


                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
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
                </div>
                <div class="flex">
                    <button type="submit"
                        class="text-white view-link font-medium rounded-lg text-sm w-full px-3 py-2.5 text-center">
                        Update Broker
                    </button>
                </div>

            </div>
        </form>
    </div>
@endsection
