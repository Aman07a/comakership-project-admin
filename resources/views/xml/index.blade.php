<!DOCTYPE html>
<html>

<head>
    <title>API Overview | Kolibri</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">
    @toastr_css
</head>

<body>

    <nav class="navbar navbar-light navbar-expand-lg mb-5" style="background-color: #e3f2fd;">
        <div class="container">
            <a class="navbar-brand mr-auto" href="#">Eazlee</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    @auth
        <div class="container">
            <!--main content start-->
            <section id="main-content">
                <section class="wrapper">
                    <h1><strong>XML Overview</strong></h1>
                    <!--overview start-->
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3><i class="fa fa-flag-o red"></i>AreaTotals Overview</h3>
                                </div>
                                <div class="panel-body">
                                    <table class="table bootstrap-datatable countries">
                                        <thead>
                                            <tr>
                                                <th>EffectiveArea</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($areaTotals) > 0)
                                                @foreach ($areaTotals as $areaTotal)
                                                    <tr>
                                                        <td>{{ $areaTotal->effectiveArea }}m2</td>
                                                        <td>
                                                            <a href="#">Edit</a>
                                                            <a href="#">Delete</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <h4>No AreaTotals found</h4>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3><i class="fa fa-flag-o red"></i>Counts Overview</h3>
                                </div>
                                <div class="panel-body">
                                    <table class="table bootstrap-datatable countries">
                                        <thead>
                                            <tr>
                                                <th>CountOfBathrooms</th>
                                                <th>CountOfBedrooms</th>
                                                <th>CountOfFloors</th>
                                                <th>CountOfGarages</th>
                                                <th>CountOfGardens</th>
                                                <th>CountOfKitchens</th>
                                                <th>CountOfRooms</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($counts) > 0)
                                                @foreach ($counts as $count)
                                                    <tr>
                                                        <td>{{ $count->countOfBathrooms }}</td>
                                                        <td>{{ $count->countOfBedrooms }}</td>
                                                        <td>{{ $count->countOfFloors }}</td>
                                                        <td>{{ $count->countOfGarages }}</td>
                                                        <td>{{ $count->countOfGardens }}</td>
                                                        <td>{{ $count->countOfKitchens }}</td>
                                                        <td>{{ $count->countOfRooms }}</td>
                                                        <td>
                                                            <a href="#">Edit</a>
                                                            <a href="#">Delete</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <h4>No Counts found</h4>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3><i class="fa fa-flag-o red"></i>Evaluations Overview</h3>
                                </div>
                                <div class="panel-body">
                                    <table class="table bootstrap-datatable countries">
                                        <thead>
                                            <tr>
                                                <th>CommunalAreas</th>
                                                <th>SecurityMeasures</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($evaluations) > 0)
                                                @foreach ($evaluations as $evaluation)
                                                    <tr>
                                                        <td>{{ $evaluation->communalAreas }}</td>
                                                        <td>{{ $evaluation->securityMeasures }}</td>
                                                        <td>
                                                            <a href="#">Edit</a>
                                                            <a href="#">Delete</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <h4>No Evaluations found</h4>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3><i class="fa fa-flag-o red"></i>Facilities Overview</h3>
                                </div>
                                <div class="panel-body">
                                    <table class="table bootstrap-datatable countries">
                                        <thead>
                                            <tr>
                                                <th>AirTreatments</th>
                                                <th>AirTreatmentsOffice</th>
                                                <th>Balcony</th>
                                                <th>CompanyListings</th>
                                                <th>Electricity</th>
                                                <th>Fencing</th>
                                                <th>FirePlace</th>
                                                <th>Garage</th>
                                                <th>Garden</th>
                                                <th>HorseTroughIndoor</th>
                                                <th>HorseTroughOutdoor</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($facilities) > 0)
                                                @foreach ($facilities as $facility)
                                                    <tr>
                                                        <td>{{ $facility->airTreatments }}</td>
                                                        <td>{{ $facility->airTreatmentsOffice }}</td>
                                                        <td>{{ $facility->balcony }}</td>
                                                        <td>{{ $facility->companyListings }}</td>
                                                        <td>{{ $facility->electricity }}</td>
                                                        <td>{{ $facility->fencing }}</td>
                                                        <td>{{ $facility->firePlace }}</td>
                                                        <td>{{ $facility->garage }}</td>
                                                        <td>{{ $facility->garden }}</td>
                                                        <td>{{ $facility->horseTroughIndoor }}</td>
                                                        <td>{{ $facility->horseTroughOutdoor }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <h4>No Facilities found</h4>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="panel-body">
                                    <table class="table bootstrap-datatable countries">
                                        <thead>
                                            <tr>
                                                <th>HorseTroughDrainage</th>
                                                <th>HorseWalker</th>
                                                <th>IndustrialFacilities</th>
                                                <th>Installations</th>
                                                <th>InternetConnection</th>
                                                <th>LeisureFacilities</th>
                                                <th>LocalSewer</th>
                                                <th>MilkingSystemTypes</th>
                                                <th>Office</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($facilities) > 0)
                                                @foreach ($facilities as $facility)
                                                    <tr>
                                                        <td>{{ $facility->horseTroughDrainage }}</td>
                                                        <td>{{ $facility->horseWalker }}</td>
                                                        <td>{{ $facility->industrialFacilities }}</td>
                                                        <td>{{ $facility->installations }}</td>
                                                        <td>{{ $facility->internetConnection }}</td>
                                                        <td>{{ $facility->leisureFacilities }}</td>
                                                        <td>{{ $facility->localSewer }}</td>
                                                        <td>{{ $facility->milkingSystemTypes }}</td>
                                                        <td>{{ $facility->office }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <h4>No Facilities found</h4>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="panel-body">
                                    <table class="table bootstrap-datatable countries">
                                        <thead>
                                            <tr>
                                                <th>OfficeFacilities</th>
                                                <th>OfficeFacilitiesOffice</th>
                                                <th>PhoneLine</th>
                                                <th>PoultryHousing</th>
                                                <th>SewerConnection</th>
                                                <th>SocialPropertyFacilities</th>
                                                <th>Structures</th>
                                                <th>Terrain</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($facilities) > 0)
                                                @foreach ($facilities as $facility)
                                                    <tr>
                                                        <td>{{ $facility->officeFacilities }}</td>
                                                        <td>{{ $facility->officeFacilitiesOffice }}</td>
                                                        <td>{{ $facility->phoneLine }}</td>
                                                        <td>{{ $facility->poultryHousing }}</td>
                                                        <td>{{ $facility->sewerConnection }}</td>
                                                        <td>{{ $facility->socialPropertyFacilities }}</td>
                                                        <td>{{ $facility->structures }}</td>
                                                        <td>{{ $facility->terrain }}</td>
                                                        <td>
                                                            <a href="#">Edit</a>
                                                            <a href="#">Delete</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <h4>No Facilities found</h4>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3><i class="fa fa-flag-o red"></i>Location Overview</h3>
                                </div>
                                <div class="panel-body">
                                    <table class="table bootstrap-datatable countries">
                                        <thead>
                                            <tr>
                                                <th>Street</th>
                                                <th>HouseNumber</th>
                                                <th>PostalCode</th>
                                                <th>District</th>
                                                <th>City</th>
                                                <th>Region</th>
                                                <th>SubRegion</th>
                                                <th>CountryCode</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($locations) > 0)
                                                @foreach ($locations as $location)
                                                    <tr>
                                                        <td>{{ $location->street }}</td>
                                                        <td>{{ $location->houseNumber }}</td>
                                                        <td>{{ $location->postalCode }}</td>
                                                        <td>{{ $location->district }}</td>
                                                        <td>{{ $location->city }}</td>
                                                        <td>{{ $location->region }}</td>
                                                        <td>{{ $location->subRegion }}</td>
                                                        <td>{{ $location->countryCode }}</td>
                                                        <td>
                                                            <a href="#">Edit</a>
                                                            <a href="#">Delete</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <h4>No Locations found</h4>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3><i class="fa fa-flag-o red"></i>Location Details Overview</h3>
                                </div>
                                <div class="panel-body">
                                    <table class="table bootstrap-datatable countries">
                                        <thead>
                                            <tr>
                                                <th>FormattedAddress</th>
                                                <th>StreetAddress</th>
                                                <th>PostalCode</th>
                                                <th>Region</th>
                                                <th>City</th>
                                                <th>Country</th>
                                                <th>Latitude</th>
                                                <th>Longitude</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($locationDetails) > 0)
                                                @foreach ($locationDetails as $locationDetail)
                                                    <tr>
                                                        <td>{{ $locationDetail->formattedAddress }}</td>
                                                        <td>{{ $locationDetail->streetAddress }}</td>
                                                        <td>{{ $locationDetail->postalCode }}</td>
                                                        <td>{{ $locationDetail->region }}</td>
                                                        <td>{{ $locationDetail->city }}</td>
                                                        <td>{{ $locationDetail->country }}</td>
                                                        <td>{{ $locationDetail->latitude }}</td>
                                                        <td>{{ $locationDetail->longitude }}</td>
                                                        <td>
                                                            <a href="#">Edit</a>
                                                            <a href="#">Delete</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <h4>No Location Details found</h4>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3><i class="fa fa-flag-o red"></i>Offer Overview</h3>
                                </div>
                                <div class="panel-body">
                                    <table class="table bootstrap-datatable countries">
                                        <thead>
                                            <tr>
                                                <th>Acceptance</th>
                                                <th>AcceptanceDate</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($offers) > 0)
                                                @foreach ($offers as $offer)
                                                    <tr>
                                                        <td>{{ $offer->acceptance }}</td>
                                                        <td>{{ $offer->acceptanceDate }}</td>
                                                        <td>
                                                            <a href="#">Edit</a>
                                                            <a href="#">Delete</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <h4>No Offer found</h4>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3><i class="fa fa-flag-o red"></i>Property Info Overview</h3>
                                </div>
                                <div class="panel-body">
                                    <table class="table bootstrap-datatable countries">
                                        <thead>
                                            <tr>
                                                <th>PropertyID</th>
                                                <th>PropertyStatus</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($propertyInfos) > 0)
                                                @foreach ($propertyInfos as $propertyInfo)
                                                    <tr>
                                                        <td>{{ $propertyInfo->propertyID }}</td>
                                                        <td>{{ $propertyInfo->propertyStatus }}</td>
                                                        <td>
                                                            <a href="#">Edit</a>
                                                            <a href="#">Delete</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <h4>No Property Info found</h4>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3><i class="fa fa-flag-o red"></i>Surroundings Overview</h3>
                                </div>
                                <div class="panel-body">
                                    <table class="table bootstrap-datatable countries">
                                        <thead>
                                            <tr>
                                                <th>Location</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($surroundings) > 0)
                                                @foreach ($surroundings as $surrounding)
                                                    <tr>
                                                        <td>{{ $surrounding->location }}</td>
                                                        <td>
                                                            <a href="#">Edit</a>
                                                            <a href="#">Delete</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <h4>No Surroundings found</h4>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3><i class="fa fa-flag-o red"></i>Type Overview</h3>
                                </div>
                                <div class="panel-body">
                                    <table class="table bootstrap-datatable countries">
                                        <thead>
                                            <tr>
                                                <th>IsResidential</th>
                                                <th>ForPermanentResidence</th>
                                                <th>PropertyType</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($types) > 0)
                                                @foreach ($types as $type)
                                                    <tr>
                                                        <td>{{ $type->isResidential }}</td>
                                                        <td>{{ $type->forPermanentResidence }}</td>
                                                        <td>{{ $type->propertyType }}</td>
                                                        <td>
                                                            <a href="#">Edit</a>
                                                            <a href="#">Delete</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <h4>No Type found</h4>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!--/col-->
                </section>
            </section>
            <!--main content end-->
        </div>
    @endauth

    @jquery
    @toastr_js
    @toastr_render
</body>

</html>
