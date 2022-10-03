@extends('layouts.dashboard')

@section('content')
    <style>
        .content {
            height: 100% !important;
        }
    </style>
    <div class="col-span-3 w-full h-screen">
        <form class="user-validation flex justify-center" action="{{ route('properties.update', $property->id) }}"
            method="POST">
            @csrf
            <div class="p-10 grid grid-cols-1 gap-5 w-2/4">
                <div class="font-bold text-xl mb-2">Edit Properties</div>
                <div class="flex">
                    <div class="md:w-1/2 mr-2">
                        <label class="block normal-case tracking-wide text-gray-700 text-xs font-bold mb-2">
                            Select broker (relationships)
                        </label>
                        <select name="broker_id"
                            class="appearance-none block w-full bg-gray-200 text-black rounded py-3 px-4 leading-tight"
                            required>
                            @foreach ($brokers as $broker)
                                <option value="{{ $broker->id }}">{{ $broker->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:w-1/2 ml-2">
                        <button type="submit"
                            class="mt-6 text-white view-link font-medium rounded-lg text-sm w-full px-5 py-3 text-center">
                            Update Properties
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
