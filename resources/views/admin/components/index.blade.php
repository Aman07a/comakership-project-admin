@extends('layouts.dashboard')

@section('content')
    <div class="col-span-3">
        <div class="bg-white py-4 md:py-7 px-4 md:px-8 xl:px-10">
            <div class="sm:flex items-center justify-between">
                <div class="flex items-center">
                    <div class="rounded focus:outline-none focus:ring-2 focus:bg-indigo-50 focus:ring-indigo-800">
                        <div class="py-2 px-0 font-medium text-black text-xl">
                            <p>Component Overview</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-7">
                <section class="grid grid-cols-3 gap-4">
                    @if (count($components))
                        @foreach ($components as $component)
                            <x-property.index :component="$component" />
                        @endforeach
                    @endif
                </section>
            </div>
        </div>
    </div>
@endsection
