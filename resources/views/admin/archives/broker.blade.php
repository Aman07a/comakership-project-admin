@extends('layouts.dashboard')

@section('content')
    <div class="col-span-3">
        <div class="bg-white py-4 md:py-7 px-4 md:px-8 xl:px-10">
            <div class="sm:flex items-center justify-between">
                <div class="flex items-center">
                    <div class="rounded focus:outline-none focus:ring-2 focus:bg-indigo-50 focus:ring-indigo-800">
                        <div class="py-2 px-0 font-medium text-black text-xl">
                            <p>Archive: Broker Overview</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-7 overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    <thead class="thead-background">
                        <tr tabindex="0"
                            class="focus:outline-none h-16 border border-dark dark:border-gray-600 rounded grid grid-cols-5 justify-items-center content-center">
                            <td>
                                <div class="col-span-1">
                                    <p class="text-base font-medium leading-none py-1 text-gray-700 dark:text-white mr-2">
                                        Name</p>
                                </div>
                            </td>
                            <td>
                                <div class="col-span-1">
                                    <p class="text-base font-medium leading-none py-1 text-gray-700 dark:text-white mr-2">
                                        User</p>
                                </div>
                            </td>
                            <td>
                                <div class="col-span-1">
                                    <p class="text-base font-medium leading-none py-1 text-gray-700 dark:text-white mr-2">
                                        Deleted at</p>
                                </div>
                            </td>
                            <td>
                                <div class="col-span-1">
                                    <a
                                        class="text-base font-medium leading-none py-1 text-gray-700 dark:text-white mr-2">Restore</a>
                                </div>
                            </td>
                            <td>
                                <div class="col-span-1">
                                    <a class="text-base font-medium leading-none py-1 text-gray-700 dark:text-white mr-2">Hard
                                        Delete</a>
                                </div>
                            </td>
                        </tr>
                        <tr class="h-3"></tr>
                    </thead>
                    <tbody>
                        @if (count($inActiveBrokers) > 0)
                            @foreach ($inActiveBrokers as $broker)
                                <tr tabindex="0"
                                    class="focus:outline-none h-16 border border-dark dark:border-gray-600 rounded grid grid-cols-5 justify-items-center content-center">
                                    <td>
                                        <div class="col-span-1">
                                            <p
                                                class="text-base font-medium leading-8 py-1 text-gray-700 dark:text-white mr-2">
                                                {{ $broker->name }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="col-span-1">
                                            <p
                                                class="text-base font-medium leading-8 py-1 text-gray-700 dark:text-white mr-2">
                                                {{ $broker->user_name }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="col-span-1">
                                            <button
                                                class="text-base font-medium leading-8 py-1 text-gray-700 dark:text-white mr-2">{{ $broker->deleted_at }}</button>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="col-span-1">
                                            {{-- TODO: restore broker --}}
                                            <form action="{{ route('broker.restore', $broker->api_key) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="view-link font-medium text-white text-base leading-none py-3 px-5 rounded focus:outline-none"
                                                    onclick="return confirm('Are you sure?')">Restore</button>
                                            </form>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="col-span-1">
                                            {{-- TODO: hard delete broker --}}
                                            <form action="{{ route('broker.force_delete', $broker->api_key) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="delete-link font-medium text-white text-base leading-none py-3 px-5 rounded focus:outline-none"
                                                    onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="h-3"></tr>
                            @endforeach
                        @else
                            <tr tabindex="0"
                                class="focus:outline-none h-16 border border-dark dark:border-gray-600 rounded flex justify-around items-center">
                                <td>
                                    <div class="col-span-1">
                                        <p class="text-base font-medium leading-none text-gray-700 dark:text-white">No
                                            brokers found</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
