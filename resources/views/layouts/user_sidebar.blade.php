<div class="sidebar">
    <div class="overflow-y-auto py-2 px-3 rounded">
        <ul class="grid space-y-2">
            @if (auth()->check() && auth()->user()->is_admin)
                <li>
                    <a href="{{ url('dashboard/home') }}" class="flex flex-items p-2 text-lg font-semibold text-white">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                            <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                        </svg>
                        <span class="ml-3">Dashboard</span>
                    </a>
                </li>
            @else
                <li>
                    <a href="{{ url('/user/dashboard/home') }}"
                        class="flex flex-items p-2 text-lg font-semibold text-white">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                            <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                        </svg>
                        <span class="ml-3">Dashboard</span>
                    </a>
                </li>
            @endif
            @if (auth()->check() && auth()->user()->is_admin)
                <li>
                    <a href="{{ url('dashboard/users') }}"
                        class="flex items-center p-2 text-lg font-semibold text-white">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z">
                            </path>
                        </svg>
                        <span class="ml-3">Users</span>
                    </a>
                </li>
            @endif
            @if (auth()->check() && auth()->user()->is_admin)
                <li>
                    <a href="{{ url('dashboard/brokers') }}"
                        class="flex items-center p-2 text-lg font-semibold text-white">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z">
                            </path>
                        </svg>
                        <span class="ml-3">Brokers</span>
                    </a>
                </li>
            @else
                <li>
                    <a href="{{ url('/user/dashboard/broker') }}"
                        class="flex items-center p-2 text-lg font-semibold text-white">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z">
                            </path>
                        </svg>
                        <span class="ml-3">Brokers</span>
                    </a>
                </li>
            @endif
            @if (auth()->check() && auth()->user()->is_admin)
                <li>
                    <a href="{{ url('dashboard/properties') }}"
                        class="flex items-center p-2 text-lg font-semibold text-white">
                        <svg class=" w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                        </svg>
                        <span class="ml-3">Properties</span>
                    </a>
                </li>
            @else
                <li>
                    <a href="{{ url('/user/dashboard/properties') }}"
                        class="flex items-center p-2 text-lg font-semibold text-white">
                        <svg class=" w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                        </svg>
                        <span class="ml-3">Properties</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</div>
