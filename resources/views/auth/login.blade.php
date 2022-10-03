@extends('layouts.app')

@section('content')
    <style>
        .content {
            height: 25em;
        }
    </style>


    <div class="notifications grid w-full max-w-lg justify-self-center">
        @if (session()->has('blocked'))
            <div class="flex justify-between text-white shadow-inner rounded p-3 bg-red mb-6">
                <p class="self-center"><strong>Blocked:</strong> {{ session()->get('blocked') }}</p>
                <strong class="text-xl cursor-pointer mb-1">&times;</strong>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="w-full max-w-lg justify-self-center">
            @csrf

            <div class="flex flex-wrap -mx-3 mb-4">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label for="email" class="block capitalize tracking-wide text-gray-700 text-xs font-bold mb-2">
                        {{ __('Email') }}
                    </label>

                    <input id="email" type="email"
                        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label for="password" class="block capitalize tracking-wide text-gray-700 text-xs font-bold mb-2">
                        {{ __('Password') }}
                    </label>

                    <input id="password" type="password"
                        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 @error('password') is-invalid @enderror"
                        name="password" required autocomplete="current-password">

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <button type="submit" class="w-full py-3 px-4 mb-3 rounded text-white view-link">
                        {{ __('Login') }}
                    </button>
                </div>

                @if (Route::has('password.request'))
                    <div class=" w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <a href="{{ route('password.request') }}"
                            class="text-center appearance-none block w-full py-3 px-4 h-12 rounded text-white view-link">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    </div>
                @endif
            </div>
        </form>
    </div>
@endsection
