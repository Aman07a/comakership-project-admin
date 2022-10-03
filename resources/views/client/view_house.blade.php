@extends('layouts.house_dashboard')

@section('content')
    <div class="snap-y snap-mandatory no-scroll w-screen overflow-scroll scroll-content overflow-x-hidden">
        <div class="snap-start w-screen no-scroll grid scroll-main">
            <div class="images">
                <div class="carousel-container overflow-hidden w-full">
                    <div class="carousel" id="carousel-1" auto-scroll="7000">
                        @foreach ($totalImages as $image)
                            <section class="carousel-screen">
                                <img class="border-none object-cover h-full w-full"
                                    src="{{ $image['URL_normalized_file'] }}">
                            </section>
                        @endforeach
                        <section class="circle-container">
                            <div class="circle"></div>
                            <div class="circle"></div>
                            <div class="circle"></div>
                            <div class="circle"></div>
                            <div class="circle"></div>
                            <div class="circle"></div>
                            <div class="circle"></div>
                            <div class="circle"></div>
                            <div class="circle"></div>
                            <div class="circle"></div>
                        </section>
                        <div class="left-arrow">
                            <span class="chevron left"></span>
                        </div>
                        <div class="right-arrow">
                            <span class="chevron right"></span>
                        </div>
                    </div>

                </div>
                <div class="small overflow-hidden">
                    @if (isset($totalImages[2]))
                        <img class="h-full w-40" src="{{ $totalImages[2]['URL_normalized_file'] }}">
                    @else
                        <img class="h-full w-full" src="{{ $no_image }}">
                    @endif
                    @if (isset($totalImages[3]))
                        <img class="h-full w-full" src="{{ $totalImages[3]['URL_normalized_file'] }}">
                    @else
                        <img class="h-full w-full" src="{{ $no_image }}">
                    @endif
                    @if (isset($totalImages[4]))
                        <img class="h-full w-full" src="{{ $totalImages[4]['URL_normalized_file'] }}">
                    @else
                        <img class="h-full w-full" src="{{ $no_image }}">
                    @endif
                    @if (isset($totalImages[5]))
                        <img class="h-full w-full" src="{{ $totalImages[5]['URL_normalized_file'] }}">
                    @else
                        <img class="h-full w-full" src="{{ $no_image }}">
                    @endif
                    @if (isset($totalImages[6]))
                        <img class="h-full w-full" src="{{ $totalImages[6]['URL_normalized_file'] }}">
                    @else
                        <img class="h-full w-full" src="{{ $no_image }}">
                    @endif
                    @if (isset($totalImages[7]))
                        <img class="h-full w-full" src="{{ $totalImages[7]['URL_normalized_file'] }}">
                    @else
                        <img class="h-full w-full" src="{{ $no_image }}">
                    @endif
                </div>
                <div class="maps overflow-hidden">
                    <iframe class="w-full h-full" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
                        src="https://maps.google.com/maps?width=100%25&amp;height=100%&amp;hl=nl&amp;q={{ $totalProperties['map']['address']['formatted_address'] }}+&amp;t=&amp;z=13&amp;ie=UTF8&amp;iwloc=B&amp;output=embed">
                    </iframe>
                </div>
            </div>
            <div class="information px-4 py-2">
                <p class="text-3xl">Adres: {{ $totalProperties['map']['address']['formatted_address'] }}.</p>
                @if ($totalProperties['rent_price_type'] == 'PRICE_PER_MONTH')
                    <p class="text-2xl">Prijs per maand: &euro;{{ $totalProperties['rent_price'] }},-</p>
                    <p class="text-2xl">Status:
                        @if ($totalProperties['status'] == 'AVAILABLE')
                            {{ 'Beschikbaar.' }}
                        @endif
                        @if ($totalProperties['status'] == 'RENTED')
                            {{ 'Verhuurd.' }}
                        @endif
                        @if ($totalProperties['status'] == 'RENTED_UNDER_CONDITIONS')
                            {{ 'Verhuurd onder voorwaarden.' }}
                        @endif
                    </p>
                @endif
            </div>
        </div>
        <div class="snap-start w-screen no-scroll grid scroll-description">
            <div class="description px-4 py-2 text-lg overflow-scroll overflow-x-hidden">
                {{ $totalProperties['description_nl'] }}
            </div>
            <div class="contact">
                <form class="flex h-full px-4 py-2" action="{{ route('admin.email.send') }}" method="POST">
                    @csrf
                    <div class="w-full">
                        <div class="font-bold text-xl mb-2">Formulier</div>
                        <div class="flex">
                            <div class="md:w-1/2 mb-4">
                                <label class="block normal-case tracking-wide text-base font-medium mb-2">
                                    Naam
                                </label>
                                <input
                                    class="appearance-none block w-full bg-gray-200 text-black rounded py-3 px-4 leading-tight @error('name') is-invalid @enderror"
                                    type="name" name="name" placeholder="Voer naam in" required>
                            </div>
                            <div class="md:w-1/2 ml-3 mb-4">
                                <label class="block normal-case tracking-wide text-base font-medium mb-2">
                                    Email
                                </label>
                                <input
                                    class="appearance-none block w-full bg-gray-200 text-black rounded py-3 px-4 leading-tight @error('email') is-invalid @enderror"
                                    type="email" name="email" placeholder="test@gmail.com" required>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="md:w-1/2 mb-4">
                                <label class="block normal-case tracking-wide text-base font-medium mb-2">
                                    Telefoonnummer
                                </label>
                                <input
                                    class="appearance-none block w-full bg-gray-200 text-black rounded py-3 px-4 leading-tight @error('phone_number') is-invalid @enderror"
                                    type="text" name="phone_number" placeholder="06-00000000">
                            </div>
                            <div class="md:w-1/2 ml-3 mb-4">
                                <label class="block normal-case tracking-wide text-base font-medium mb-2">
                                    Alternatieve telefoonnumer
                                </label>
                                <input
                                    class="appearance-none block w-full bg-gray-200 text-black rounded py-3 px-4 leading-tight @error('alternate_phone_number') is-invalid @enderror"
                                    type="text" name="alternate_phone_number" placeholder="00000000000">
                            </div>
                        </div>
                        <div class="flex">
                            <div class="md:w-full mb-4">
                                <label class="block normal-case tracking-wide text-base font-medium mb-2">
                                    Onderwerp
                                </label>
                                <input
                                    class="appearance-none block w-full bg-gray-200 text-black rounded py-3 px-4 leading-tight @error('subject') is-invalid @enderror"
                                    type="text" name="subject" placeholder="Voer onderwerp in" required>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="md:w-full mb-4">
                                <label class="block normal-case tracking-wide text-base font-medium mb-2">
                                    Bericht
                                </label>
                                <textarea
                                    class="appearance-none block w-full h-24 bg-gray-200 text-black resize-y rounded-md py-3 px-4 leading-tight @error('message') is-invalid @enderror"
                                    name="message" placeholder="Voer bericht in" required></textarea>
                            </div>
                        </div>
                        <div class="flex">
                            <button type="submit"
                                class="text-white bg-submit font-medium rounded-lg text-sm w-full px-5 py-2.5 text-center">
                                Verzenden
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="types px-4">
                <div class="basic-types grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 text-lg">
                    <div class="basic-type h-6">
                        Beschikbaar per:
                        @if (!empty($totalProperties['available_from_date']))
                            {{ $totalProperties['available_from_date'] }}
                        @else
                            {{ 'Nog niet bekend' }}
                        @endif

                    </div>
                    <div class="basic-type h-6">
                        Beschikbaar tot:
                        @if (!empty($totalProperties['available_from_date']))
                            {{ $totalProperties['available_from_date'] }}
                        @else
                            {{ 'Nog niet bekend' }}
                        @endif

                    </div>
                    <div class="basic-type h-6">Oppervlakte:
                        @if (!empty($frontYard['area']))
                            {{ $frontYard['area'] }}
                        @else
                            {{ 'Nog niet bekend' }}
                        @endif
                    </div>
                    <div class="basic-type h-6">Kamers:
                        @if (!empty($totalProperties['count_of_rooms']))
                            {{ $totalProperties['count_of_rooms'] }}
                        @else
                            {{ 'Nog niet bekend' }}
                        @endif
                    </div>
                    <div class="basic-type h-6">Badkamers:
                        @if (!empty($totalProperties['count_of_bathrooms']))
                            {{ $totalProperties['count_of_bathrooms'] }}
                        @else
                            {{ 'Nog niet bekend' }}
                        @endif
                    </div>
                    <div class="basic-type h-6">Slaapkamers:
                        @if (!empty($totalProperties['count_of_bedrooms']))
                            {{ $totalProperties['count_of_bedrooms'] }}
                        @else
                            {{ 'Nog niet bekend' }}
                        @endif
                    </div>
                    <div class="basic-type h-6">Toiletten:
                        @if (!empty($totalProperties['count_of_toilettes']))
                            {{ $totalProperties['count_of_toilettes'] }}
                        @else
                            {{ 'Nog niet bekend' }}
                        @endif
                    </div>
                    <div class="basic-type h-6">Energie label:
                        @if (!empty($totalProperties['energy_class']))
                            {{ $totalProperties['energy_class'] }}
                        @else
                            {{ 'Nog niet bekend' }}
                        @endif
                    </div>
                    <div class="other-type h-6">Huisdieren:
                        @if (!empty($totalProperties['pets']))
                            {{ $totalProperties['pets'] }}
                        @else
                            {{ 'Nog niet bekend' }}
                        @endif
                    </div>
                    <div class="other-type h-6">Soort tuin:
                        @if (empty($frontYard) && is_array($frontYard))
                            {{ 'Nog niet bekend' }}
                        @else
                            {{ $frontYard['type'] }}
                        @endif
                    </div>
                    <div class="other-type h-6">Interieur:
                        @if (!empty($totalProperties['interior']))
                            {{ $totalProperties['interior'] }}
                        @else
                            {{ 'Nog niet bekend' }}
                        @endif
                    </div>
                    <div class="other-type h-6">Borg:
                        @if (!empty($totalProperties['deposit']))
                            &euro;{{ $totalProperties['deposit'] }},-
                        @else
                            {{ 'Nog niet bekend' }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
