<!-- Scripts -->
<script src="{{ asset('js/app.js') }}" defer></script>
<link rel="stylesheet" href="https://unpkg.com/flowbite@1.3.4/dist/flowbite.min.css" />

<!-- Fonts -->
<link rel="dns-prefetch" href="//fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

<!-- Styles -->
<link href="{{ asset('css/app.css') }}" rel="stylesheet">

<!-- Custom Styles -->
<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
<link href="{{ asset('css/dashboard-grid.css') }}" rel="stylesheet">
<link href="{{ asset('css/overflow.css') }}" rel="stylesheet">

<div>
    {{-- <x-message :message="'Message'" /> --}}
    <x-message data-id="23" class="h-16">
        Message from the slot
    </x-message>
</div>
