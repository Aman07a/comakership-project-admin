@extends('layouts.dashboard')

@section('content')
    <style>
        .content {
            align-items: flex-start !important;
            grid-template-rows: repeat(4, 1fr) !important;
        }
    </style>
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
                    <h2 class="font-bold text-xl text-white">Softdelete Users</h2>
                    <p class="font-bold text-3xl text-white">{{ $inActiveUsers->count() }}
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
                    <h2 class="font-bold text-xl text-white">Softdelete Brokers</h2>
                    <p class="font-bold text-3xl text-white">{{ $inActiveBrokers->count() }}
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
                    <h2 class="font-bold text-xl text-white">Softdelete Properties</h2>
                    <p class="font-bold text-3xl text-white">{{ $inActiveProperties->count() }}
                        <span><i class="fas fa-caret-up"></i></span>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
