@extends('layouts.mobile_dashboard')

@section('content')
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
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1 text-right md:text-center">
                    <h2 class="font-bold text-xl text-white">Total Brokers</h2>
                    <p class="font-bold text-3xl text-white">{{ $brokers->count() }}
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
                    <p class="font-bold text-3xl text-white">{{ $activeBrokers->count() }}
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
                    <p class="font-bold text-3xl text-white">{{ $inActiveBrokers->count() }}
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
                        <svg class="flex-shrink-0 w-8 h-8 text-white transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                            fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                        </svg>
                    </div>
                </div>
                <div class="flex-1 text-right md:text-center">
                    <h2 class="font-bold text-xl text-white">Total Properties</h2>
                    <p class="font-bold text-3xl text-white">{{ $properties->count() }}
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
                    <p class="font-bold text-3xl text-white">{{ $activeProperties->count() }}
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
                    <p class="font-bold text-3xl text-white">{{ $inActiveProperties->count() }}
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
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1 text-right md:text-center">
                    <h2 class="font-bold text-xl text-white">Total Users</h2>
                    <p class="font-bold text-3xl text-white">{{ $users->count() }}
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
                    <h2 class="font-bold text-xl text-white">Active Users</h2>
                    <p class="font-bold text-3xl text-white">{{ $activeUsers->count() }}
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
                    <h2 class="font-bold text-xl text-white">Inactive Users</h2>
                    <p class="font-bold text-3xl text-white">{{ $inActiveUsers->count() }}
                        <span><i class="fas fa-caret-up"></i></span>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
