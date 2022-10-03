@extends('layouts.dashboard')

@section('content')
    <style>
        .content {
            height: 100% !important;
        }
    </style>
    <div class="col-span-3 w-full h-screen">
        @if (session()->has('message_sent'))
            <div class="flex justify-center">
                <div class="flex justify-center justify-between text-white shadow-inner rounded p-3 bg-green mt-6 h-12 -mb-4 mb-2 w-43-7-percent">
                    <p class="self-center"><strong>Google Mail:</strong> {{ session()->get('message_sent') }}</p>
                    <strong class="text-xl cursor-pointer mb-1 leading-4">&times;</strong>
                </div>
            </div>
        @endif

        <form class="validation flex justify-center" action="{{ route('admin.email.send') }}" method="POST">
            @csrf
            <div class="p-10 grid grid-cols-1 gap-5 w-2/4">
                <div class="font-bold text-xl mb-2">Contact Us</div>
                <div class="flex">
                    <div class="md:w-1/2">
                        <label class="block normal-case tracking-wide text-gray-700 text-xs font-bold mb-2">
                            Name
                        </label>
                        <input
                            class="appearance-none block w-full bg-gray-200 text-black rounded py-3 px-4 leading-tight @error('name') is-invalid @enderror"
                            type="name" name="name" placeholder="Enter name" required>
                    </div>
                    <div class="md:w-1/2 ml-3">
                        <label class="block normal-case tracking-wide text-gray-700 text-xs font-bold mb-2">
                            Email
                        </label>
                        <input
                            class="appearance-none block w-full bg-gray-200 text-black rounded py-3 px-4 leading-tight @error('email') is-invalid @enderror"
                            type="email" name="email" placeholder="test@gmail.com" required>
                    </div>
                </div>
                <div class="flex">
                    <div class="md:w-1/2">
                        <label class="block normal-case tracking-wide text-gray-700 text-xs font-bold mb-2">
                            Phone Number
                        </label>
                        <input
                            class="appearance-none block w-full bg-gray-200 text-black rounded py-3 px-4 leading-tight @error('phone_number') is-invalid @enderror"
                            type="text" name="phone_number" placeholder="06-00000000">
                    </div>
                    <div class="md:w-1/2 ml-3">
                        <label class="block normal-case tracking-wide text-gray-700 text-xs font-bold mb-2">
                            Alternate Phone Number
                        </label>
                        <input
                            class="appearance-none block w-full bg-gray-200 text-black rounded py-3 px-4 leading-tight @error('alternate_phone_number') is-invalid @enderror"
                            type="text" name="alternate_phone_number" placeholder="00000000000">
                    </div>
                </div>
                <div class="flex">
                    <div class="md:w-full">
                        <label class="block normal-case tracking-wide text-gray-700 text-xs font-bold mb-2">
                            Subject
                        </label>
                        <input
                            class="appearance-none block w-full bg-gray-200 text-black rounded py-3 px-4 leading-tight @error('subject') is-invalid @enderror"
                            type="text" name="subject" placeholder="Enter subject" required>
                    </div>
                </div>
                <div class="flex">
                    <div class="md:w-full">
                        <label class="block normal-case tracking-wide text-gray-700 text-xs font-bold mb-2">
                            Message
                        </label>
                        <textarea
                            class="appearance-none block w-full h-24 bg-gray-200 text-black resize-y rounded-md py-3 px-4 leading-tight @error('message') is-invalid @enderror"
                            name="message" placeholder="Enter message" required></textarea>
                    </div>
                </div>
                <div class="flex">
                    <button type="submit"
                        class="text-white view-link font-medium rounded-lg text-sm w-full px-5 py-2.5 text-center">
                        Submit
                    </button>
                </div>

            </div>
        </form>
    </div>
@endsection
