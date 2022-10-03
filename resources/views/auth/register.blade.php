@extends('layouts.app')

@section('content')
    <style>
        .content {
            height: 30em;
        }
    </style>
    <form method="POST" action="{{ route('register') }}" class="w-full max-w-lg justify-self-center">
        @csrf

        <div class="flex flex-wrap -mx-3 mb-4 mt-5">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label for="name" class="block capitalize tracking-wide text-gray-700 text-xs font-bold mb-2">
                    {{ __('Name') }}
                </label>

                <input id="name" type="name"
                    class="mb-3 appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 @error('name') is-invalid @enderror"
                    name="name" required autocomplete="name" autofocus>

                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label for="email" class="block capitalize tracking-wide text-gray-700 text-xs font-bold mb-2">
                    {{ __('Email') }}
                </label>

                <input id="email" type="email"
                    class="mb-3 appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 @error('email') is-invalid @enderror"
                    name="email" required autocomplete="email" autofocus>

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label for="phone_number" class="block capitalize tracking-wide text-gray-700 text-xs font-bold mb-2">
                    {{ __('Phone Number') }}
                </label>

                <input id="phone_number" type="text"
                    class="mb-3 appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 @error('phone_number') is-invalid @enderror"
                    name="phone_number" placeholder="00-00000000" required autocomplete="phone_number" autofocus>

                @error('phone_number')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label for="alternate_phone_number"
                    class="block capitalize tracking-wide text-gray-700 text-xs font-bold mb-2">
                    {{ __('Alternate Phone Number') }}
                </label>

                <input id="alternate_phone_number" type="text"
                    class="mb-3 appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 @error('alternate_phone_number') is-invalid @enderror"
                    name="alternate_phone_number" placeholder="0000000000" autocomplete="alternate_phone_number" autofocus>

                @error('alternate_phone_number')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
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
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label for="password-confirm" class="block capitalize tracking-wide text-gray-700 text-xs font-bold mb-2">
                    {{ __('Confirm Password') }}
                </label>

                <input id="password-confirm" type="password"
                    class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 @error('password') is-invalid @enderror"
                    name="password_confirmation" required autocomplete="new-password">

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div> <!-- -->
        </div>
        <div class="flex flex-wrap -mx-3 mb-4">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <button type="submit" class="w-full py-3 px-4 mb-3 rounded text-white view-link">
                    {{ __('Register') }}
                </button>
            </div>

            @if (Route::has('password.request'))
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <a href="{{ route('login') }}"
                        class="text-center appearance-none block w-full py-3 px-4 h-12 rounded text-white view-link">
                        {{ __('Already have an account?') }}
                    </a>
                </div>
            @endif
        </div>
    </form>
@endsection
