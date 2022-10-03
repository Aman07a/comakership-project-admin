@extends('layouts.dashboard')

@section('content')
    <div class="col-span-3">
        <div class="bg-white py-4 md:py-7 px-4 md:px-8 xl:px-10">
            <div class="sm:flex items-center justify-between">
                <div class="flex items-center">
                    <div class="rounded focus:outline-none focus:ring-2 focus:bg-indigo-50 focus:ring-indigo-800">
                        <div class="py-2 px-0 font-medium text-black text-xl">
                            <p>User Overview</p>
                        </div>
                    </div>
                </div>
                <a href="{{ url('dashboard/add/user') }}" class="text-sm font-medium leading-none text-white">
                    <button
                        class="mt-4 sm:mt-0 inline-flex items-start justify-start px-6 py-3 view-link focus:outline-none rounded font-medium">
                        Add User
                    </button>
                </a>
            </div>
            <div class="mt-7 overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    <thead class="thead-background">
                        <tr tabindex="0"
                            class="focus:outline-none h-16 border border-dark dark:border-gray-600 rounded grid grid-cols-5 justify-items-center content-center">
                            <td class="col-span-1">
                                <p class="text-base font-medium leading-none py-1 text-gray-700 dark:text-white mr-2">
                                    Name</p>
                            </td>
                            <td class="col-span-1">
                                <p class="text-base font-medium leading-none py-1 text-gray-700 dark:text-white mr-2">
                                    Email</p>
                            </td>
                            <td class="col-span-1">
                                <p class="text-base font-medium leading-none py-1 text-gray-700 dark:text-white mr-2">
                                    Created at</p>
                            </td>
                            <td class="col-span-1">
                                <a class="text-base font-medium leading-none py-1 text-gray-700 dark:text-white mr-2">
                                    Edit</a>
                            </td>
                                                        <td class="col-span-1">
                                <a class="text-base font-medium leading-none py-1 text-gray-700 dark:text-white mr-2">
                                    Soft Delete</a>
                            </td>
                        </tr>
                        <tr class="h-3"></tr>
                    </thead>
                    <tbody>
                        @if (count($users) > 0)
                            @foreach ($users as $user)
                                <tr tabindex="0"
                                    class="focus:outline-none h-16 border border-dark dark:border-gray-600 rounded grid grid-cols-5 justify-items-center content-center">
                                    <td class="col-span-1">
                                        <p
                                            class="text-base font-medium leading-none py-1 text-gray-700 dark:text-white mr-2">
                                            {{ $user->name }}</p>
                                    </td>
                                    <td class="col-span-1">
                                        <p
                                            class="text-base font-medium leading-none py-1 text-gray-700 dark:text-white mr-2">
                                            {{ $user->email }}</p>
                                    </td>
                                    <td class="col-span-1">
                                        <button
                                            class="text-base font-medium leading-none py-1 text-gray-700 dark:text-white mr-2">{{ $user->created_at->format('jS F Y') }}</button>
                                    </td>
                                    <td class="col-span-1">
                                        <a href="{{ route('user.edit', $user->id) }}"
                                            class="view-link font-medium text-white text-base leading-none py-3 px-5 rounded focus:outline-none">Edit</a>
                                    </td>
                                    <td class="col-span-1">
                                        <a href="{{ route('user.delete', $user->id) }}"
                                            class="delete-link font-medium text-white text-base leading-none py-3 px-5 rounded focus:outline-none">Delete</a>
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
                                            users found</p>
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
