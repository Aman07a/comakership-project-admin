@extends('layouts.user_dashboard')

@section('content')
    <style>
        .user-content {
            align-items: flex-start !important;
            grid-template-rows: repeat(4, 1fr) !important;
        }
    </style>
    @if (session()->has('loggedin'))
        <div class="w-auto md:w-auto xl:w-auto p-6">
        </div>
        <div class="w-full md:w-1/2 xl:w-full p-6 mt-4">
            <div class="flex justify-between text-white shadow-inner rounded p-3 bg-green mb-6">
                <p class="self-center"><strong>Message:</strong> {{ session()->get('loggedin') }}</p>
                <strong class="text-xl cursor-pointer mb-1">&times;</strong>
            </div>
        </div>
        <div class="w-auto md:w-auto xl:w-auto p-6">
        </div>
    @endif
    <div class="w-full md:w-1/2 xl:w-full p-6">
        <div class="bg-blue rounded-lg shadow-xl p-5">
            <div class="flex flex-row items-center">
                <div class="flex-shrink pr-4">
                    <div class="rounded-full p-5 bg-dark-blue">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                </div>
                <div class="flex-1 text-right md:text-center">
                    <h2 class="font-bold text-xl text-white">Total Brokers</h2>
                    <p class="font-bold text-3xl text-white">{{ count($brokers) }}
                        <span><i class="fas fa-caret-up"></i></span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="w-full md:w-1/2 xl:w-full p-6">
        <div class="bg-green rounded-lg shadow-xl p-5">
            <div class="flex flex-row items-center">
                <div class="flex-shrink pr-4">
                    <div class="rounded-full p-5 bg-dark-green">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1 text-right md:text-center">
                    <h2 class="font-bold text-xl text-white">Active Brokers</h2>
                    <p class="font-bold text-3xl text-white">{{ count($activeBrokers) }}
                        <span><i class="fas fa-caret-up"></i></span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="w-full md:w-1/2 xl:w-full p-6">
        <div class="bg-red rounded-lg shadow-xl p-5">
            <div class="flex flex-row items-center">
                <div class="flex-shrink pr-4">
                    <div class="rounded-full p-5 bg-dark-red">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1 text-right md:text-center">
                    <h2 class="font-bold text-xl text-white">Inactive Brokers</h2>
                    <p class="font-bold text-3xl text-white">
                        {{ count($inActiveBrokers) }}
                        <span><i class="fas fa-caret-up"></i></span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="w-full md:w-1/2 xl:w-full p-6">
        <div class="bg-blue rounded-lg shadow-xl p-5">
            <div class="flex flex-row items-center">
                <div class="flex-shrink pr-4">
                    <div class="rounded-full p-5 bg-dark-blue">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                </div>
                <div class="flex-1 text-right md:text-center">
                    <h2 class="font-bold text-xl text-white">Total Properties</h2>
                    <p class="font-bold text-3xl text-white">
                        {{ count($properties) }}
                        <span><i class="fas fa-caret-up"></i></span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="w-full md:w-1/2 xl:w-full p-6">
        <div class="bg-green rounded-lg shadow-xl p-5">
            <div class="flex flex-row items-center">
                <div class="flex-shrink pr-4">
                    <div class="rounded-full p-5 bg-dark-green">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1 text-right md:text-center">
                    <h2 class="font-bold text-xl text-white">Active Properties</h2>
                    <p class="font-bold text-3xl text-white">
                        {{ count($activeProperties) }}
                        <span><i class="fas fa-caret-up"></i></span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="w-full md:w-1/2 xl:w-full p-6">
        <div class="bg-red rounded-lg shadow-xl p-5">
            <div class="flex flex-row items-center">
                <div class="flex-shrink pr-4">
                    <div class="rounded-full p-5 bg-dark-red">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1 text-right md:text-center">
                    <h2 class="font-bold text-xl text-white">Inactive Properties</h2>
                    <p class="font-bold text-3xl text-white">
                        {{ count($inActiveProperties) }}
                        <span><i class="fas fa-caret-up"></i></span>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
