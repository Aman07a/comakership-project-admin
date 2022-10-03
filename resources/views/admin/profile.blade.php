@extends('layouts.dashboard')

@section('content')
    <style>
        .content {
            height: 100% !important;
        }
    </style>
    <div class="col-span-3 w-full h-screen">
        @if (session()->has('success'))
            <div class="flex justify-center">
                <div class="flex justify-center justify-between text-white shadow-inner rounded p-3 bg-green mt-6 h-12 -mb-1 w-69-percent">
                    <p class="self-center"><strong>Message:</strong> {{ session()->get('success') }}</p>
                    <strong class="text-xl cursor-pointer mb-1 leading-4">&times;</strong>
                </div>
            </div>
        @endif

        <form class="user-validation flex justify-center" action="{{ route('admin.profile.update') }}"
            method="POST">
            @csrf
            <div class="p-10 grid grid-cols-1 gap-5 w-3/4">
                <div class="font-bold text-xl mb-2">Profile: {{ $admin->name }}</div>
                <div class="flex">
                    <div class="md:w-1/2">
                        <label class="block normal-case tracking-wide text-gray-700 text-xs font-bold mb-2">
                            Name
                        </label>
                        <input type="name"
                            class="appearance-none block w-full bg-gray-200 text-black rounded py-3 px-4 leading-tight @error('name') is-invalid @enderror"
                            name="name" value="{{ $admin->name }}" placeholder="Enter your name" required
                            autocomplete="name" autofocus>

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="md:w-1/2 ml-3">
                        <label class="block normal-case tracking-wide text-gray-700 text-xs font-bold mb-2">
                            Email
                        </label>
                        <input type="email"
                            class="appearance-none block w-full bg-gray-200 text-black rounded py-3 px-4 leading-tight @error('email') is-invalid @enderror"
                            name="email" value="{{ $admin->email }}" placeholder="Enter your email" required
                            autocomplete="email">

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="flex">
                    <div class="md:w-1/2">
                        <label class="block normal-case tracking-wide text-gray-700 text-xs font-bold mb-2">
                            Phone number
                        </label>
                        <input type="text"
                            class="appearance-none block w-full bg-gray-200 text-black rounded py-3 px-4 leading-tight @error('name') is-invalid @enderror"
                            name="phone_number" value="{{ $admin->phone_number }}" placeholder="00-00000000" required
                            autocomplete="phone_number" autofocus>

                        @error('phone_number')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="md:w-1/2 ml-3">
                        <label class="block normal-case tracking-wide text-gray-700 text-xs font-bold mb-2">
                            Alternate Phone Number
                        </label>
                        <input type="text"
                            class="appearance-none block w-full bg-gray-200 text-black rounded py-3 px-4 leading-tight @error('alternate_phone_number') is-invalid @enderror"
                            name="alternate_phone_number" value="{{ $admin->alternate_phone_number }}"
                            placeholder="0000000000" autocomplete="alternate_phone_number">

                        @error('alternate_phone_number')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="flex">
                    <div class="md:w-1/2">
                        <label class="block normal-case tracking-wide text-gray-700 text-xs font-bold mb-2">
                            Password
                        </label>
                        <input type="password"
                            class="appearance-none block w-full bg-gray-200 text-black rounded py-3 px-4 leading-tight @error('password') is-invalid @enderror"
                            name="password" placeholder="**********" required>

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="md:w-1/2 ml-3">
                        <label class="block normal-case tracking-wide text-gray-700 text-xs font-bold mb-2">
                            Confirm Password
                        </label>
                        <input type="password"
                            class="appearance-none block w-full bg-gray-200 text-black rounded py-3 px-4 leading-tight @error('password') is-invalid @enderror"
                            name="password_confirmation" placeholder="**********" required>

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="flex">
                    <button type="submit"
                        class="text-white view-link font-medium rounded-lg text-sm w-full px-5 py-3 text-center">
                        Update Profile
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
