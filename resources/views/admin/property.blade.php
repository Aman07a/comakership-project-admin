@extends('layouts.dashboard')

@section('content')

    <div class="col-span-3">
        <div class="bg-white py-4 md:py-7 px-4 md:px-8 xl:px-10">
            @if (session()->has('success'))
                <div class="flex justify-between text-white shadow-inner rounded p-3 bg-green mb-6">
                    <p class="self-center"><strong>Message:</strong> {{ session()->get('success') }}</p>
                    <strong class="text-xl cursor-pointer mb-1">&times;</strong>
                </div>
            @endif
            @if (session()->has('info'))
                <div class="flex justify-between text-white shadow-inner rounded p-3 bg-blue mb-6">
                    <p class="self-center"><strong>Info:</strong> {{ session()->get('info') }}</p>
                    <strong class="text-xl cursor-pointer mb-1">&times;</strong>
                </div>
            @endif
            @if (session()->has('warning'))
                <div class="flex justify-between text-white shadow-inner rounded p-3 bg-red mb-6">
                    <p class="self-center"><strong>Failed:</strong> {{ session()->get('warning') }}</p>
                    <strong class="text-xl cursor-pointer mb-1">&times;</strong>
                </div>
            @endif
            
            <div class="sm:flex items-center justify-between">
                <div class="flex items-center">
                    <div class="rounded focus:outline-none focus:ring-2 focus:bg-indigo-50 focus:ring-indigo-800">
                        <div class="py-2 px-0 font-medium text-black text-xl">
                            <p>Property Overview</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-7 overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    <thead class="thead-background">
                        <tr tabindex="0"
                            class="focus:outline-none h-16 border border-dark dark:border-gray-600 rounded grid grid-cols-6 justify-items-center content-center">
                            <td class="col-span-1">
                                <p class="text-base font-medium text-gray-700 dark:text-white mr-2">Total
                                    properties
                                </p>
                            </td>
                            <td class="col-span-1">
                                <p class="text-base font-medium text-gray-700 dark:text-white mr-2">Broker
                                </p>
                            </td>
                            <td class="col-span-1">
                                <p class="text-base font-medium text-gray-700 dark:text-white mr-2">Created
                                    at</p>
                            </td>
                            <td class="col-span-1">
                                <a class="text-base font-medium text-gray-700 dark:text-white mr-2">
                                    View
                                </a>
                            </td>
                            <td class="col-span-1">
                                <a class="text-base font-medium text-gray-700 dark:text-white mr-2">
                                    Store
                                </a>
                            </td>
                            <td class="col-span-1">
                                <a class="text-base font-medium text-gray-700 dark:text-white mr-2">
                                    Soft Delete
                                </a>
                            </td>
                        </tr>
                        <tr class="h-3"></tr>
                    </thead>
                    <tbody>
                        @if (count($brokers) > 0)
                            @foreach ($brokers as $broker)
                                <tr tabindex="0"
                                    class="focus:outline-none h-16 border border-dark dark:border-gray-600 rounded grid grid-cols-6 justify-items-center content-center">
                                    <td class="col-span-1">
                                        <p
                                            class="text-base font-medium leading-none py-1 text-gray-700 dark:text-white mr-2">
                                            @foreach ($propertyList as $list)
                                                @if ($list->id === $broker->id)
                                                    {{ $list->count }}
                                                @endif
                                            @endforeach
                                            <!-- find blade formatter , google & maak van de list ipv array een object zodat je met -> ipv array []  -->
                                        </p>
                                    </td>
                                    <td class="col-span-1">
                                        <p class="text-base font-medium leading-none py-1 text-gray-700 dark:text-white mr-2"
                                            id="{{ $broker->id }}">{{ $broker->name }}</p>
                                    </td>
                                    <td class="col-span-1">
                                        <p class="text-base font-medium text-gray-700 dark:text-white mr-2">
                                            {{ $broker->created_at }}
                                        </p>
                                    </td>
                                    <td class="col-span-1">
                                        <a href="{{ route('houses', $broker->api_key) }}"
                                            class="view-link font-medium text-white text-base leading-none py-3 px-5 rounded focus:outline-none">
                                            Houses</a>
                                    </td>
                                    <td class="col-span-1">
                                        <a href="{{ route('collections.save', $broker->api_key) }}"
                                            class="view-link font-medium text-white text-base leading-none py-3 px-5 rounded focus:outline-none">Store</a>
                                    </td>
                                    <td class="col-span-1">
                                        <a href="{{ route('properties.delete', $broker->id) }}"
                                            class="delete-link font-medium text-white text-base leading-none py-3 px-5 rounded focus:outline-none">Delete</a>
                                    </td>
                                </tr>
                                <tr class="h-3"></tr>
                            @endforeach
                        @else
                            <tr tabindex="0"
                                class="focus:outline-none h-16 border border-dark dark:border-gray-600 rounded flex justify-around items-center">
                                <td class="col-span-1">
                                    <p class="text-base font-medium leading-none text-gray-700 dark:text-white">No
                                        properties found</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <footer class="pagination">
                    @if (count($brokers) > 0)
                        {{ $brokers }}
                    @endif
                </footer>
            </div>
        </div>
    </div>

@endsection
