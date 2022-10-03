@extends('layouts.dashboard')

@section('content')
    <div class="col-span-3">
        <div class="bg-white py-4 md:py-7 px-4 md:px-8 xl:px-10">
            <div class="sm:flex items-center justify-between">
                <div class="flex items-center">
                    <div class="rounded focus:outline-none focus:ring-2 focus:bg-indigo-50 focus:ring-indigo-800">
                        <div class="py-2 px-0 font-medium text-black text-xl">
                            <p>Archive: Properties Overview</p>
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
                                        Total properties</p>
                                </div>
                            </td>
                            <td>
                                <div class="col-span-1">
                                    <p class="text-base font-medium leading-none py-1 text-gray-700 dark:text-white mr-2">
                                        Broker</p>
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
                        @if (count($inActiveProperties) > 0)
                            @foreach ($inActiveProperties as $property)
                                <tr tabindex="0"
                                    class="focus:outline-none h-16 border border-dark dark:border-gray-600 rounded grid grid-cols-5 justify-items-center content-center">
                                    <td>
                                        <div class="col-span-1">
                                            <p
                                                class="text-base font-medium py-3 leading-none text-gray-700 dark:text-white mr-2">
                                                {{ $property->count }}
                                            </p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="col-span-1">
                                            <p
                                                class="text-base font-medium py-3 leading-none text-gray-700 dark:text-white mr-2">
                                                {{ $property->name }}
                                            </p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="col-span-1">
                                            <p
                                                class="text-base font-medium py-3 leading-none text-gray-700 dark:text-white mr-2">
                                                {{ $property->deleted_at }}
                                            </p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="col-span-1">
                                            {{-- TODO: restore properties --}}
                                            <form action="{{ route('properties.restore', $property->id) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="view-link font-medium text-white text-base leading-none py-3 px-5 rounded focus:outline-none"
                                                    onclick="return confirm('Are you sure?')">Restore</button>
                                            </form>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="col-span-1">
                                            {{-- TODO: hard delete properties --}}
                                            <form action="{{ route('properties.force_delete', $property->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="delete-link font-medium text-white text-base leading-none py-3 px-5 rounded focus:outline-none"
                                                    onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr tabindex="0"
                                class="focus:outline-none h-16 border border-dark dark:border-gray-600 rounded flex justify-around items-center">
                                <td>
                                    <div class="col-span-1">
                                        <p class="text-base font-medium leading-none text-gray-700 dark:text-white">No
                                            properties found</p>
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
