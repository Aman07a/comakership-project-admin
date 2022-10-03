@extends('layouts.dashboard')

@section('content')
    <style>
        .content {
            height: 100% !important;
        }
    </style>
    <div class="col-span-3 w-full h-screen">
        <form class="user-validation flex justify-center" action="{{ route('user.update', $user->id) }}" method="POST">
            @csrf
            <div class="p-10 grid grid-cols-1 gap-5 w-2/4">
                <div class="font-bold text-xl mb-2">Edit User</div>
                <div class="flex">
                    <div class="md:w-1/2">
                        <label class="block normal-case tracking-wide text-gray-700 text-xs font-bold mb-2">
                            Name
                        </label>
                        <input type="name"
                            class="appearance-none block w-full bg-gray-200 text-black rounded py-3 px-4 leading-tight @error('name') is-invalid @enderror"
                            name="name" value="{{ $user->name }}" placeholder="Enter your name" required
                            autocomplete="name" autofocus>

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="md:w-1/2 px-3">
                        <label class="block normal-case tracking-wide text-gray-700 text-xs font-bold mb-2">
                            Email
                        </label>
                        <input type="email"
                            class="appearance-none block w-full bg-gray-200 text-black rounded py-3 px-4 leading-tight @error('email') is-invalid @enderror"
                            name="email" value="{{ $user->email }}" placeholder="Enter your email" required
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
                            Password
                        </label>
                        <input type="password"
                            class="appearance-none block w-full bg-gray-200 text-black rounded py-3 px-4 leading-tight @error('password') is-invalid @enderror"
                            name="password" placeholder="Enter your password" required autocomplete="password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="md:w-1/2 px-3">
                        <label class="block normal-case tracking-wide text-gray-700 text-xs font-bold mb-2">
                            Confirm password
                        </label>
                        <input type="password"
                            class="appearance-none block w-full bg-gray-200 text-black rounded py-3 px-4 leading-tight @error('password_confirmation') is-invalid @enderror"
                            name="password_confirmation" placeholder="Confirm your password" required>
                    </div>
                </div>
                <div class="flex">
                    <div class="md:w-1/2">
                        <label class="block normal-case tracking-wide text-gray-700 text-xs font-bold mb-2">
                            Roles (levels)
                        </label>
                        <select name="is_admin"
                            class="appearance-none block w-full bg-gray-200 text-black rounded py-3 px-4 leading-tight"
                            required>
                            <option value="0">User</option>
                            <option value="1">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="flex">
                    <button type="submit"
                        class="text-white view-link font-medium rounded-lg text-sm w-full px-5 py-2.5 text-center">
                        Update User
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
