    <style>
        .content {
            height: 100% !important;
        }
    </style>
    <div class="col-span-3 w-full h-screen">
        <div class="flex justify-center">
            <div class="p-10 grid grid-cols-1 gap-5 w-3/4">
                <div class="font-bold text-xl mb-2">Contact Message: {{ $data['name'] }}</div>
                <div class="flex">
                    <div class="md:w-1/2">
                        <label class="block normal-case tracking-wide text-gray-700 text-xs font-bold mb-2">
                            Name: {{ $data['name'] }}
                        </label>
                    </div>
                    <div class="md:w-1/2 ml-3">
                        <label class="block normal-case tracking-wide text-gray-700 text-xs font-bold mb-2">
                            Email: {{ $data['email'] }}
                        </label>
                    </div>
                </div>
                <div class="flex">
                    @if (!empty($data['phone']))
                        <div class="md:w-1/2">
                            <label class="block normal-case tracking-wide text-gray-700 text-xs font-bold mb-2">
                                Phone Number: {{ $data['phone'] }}
                            </label>
                        </div>
                    @endif
                    @if (!empty($data['alternate_phone']))
                        <div class="md:w-1/2">
                            <label class="block normal-case tracking-wide text-gray-700 text-xs font-bold mb-2">
                                Alternate Phone Number: {{ $data['alternate_phone'] }}
                            </label>
                        </div>
                    @endif
                </div>
                <div class="flex">
                    <div class="md:w-full">
                        <label class="block normal-case tracking-wide text-gray-700 text-xs font-bold mb-2">
                            Message: {{ $data['message'] }}
                        </label>
                    </div>
                </div>

            </div>
        </div>
    </div>
