<?php

namespace App\Http\Controllers;

use App\Models\Type;
use App\Models\User;
use App\Models\Offer;
use App\Models\Agency;
use App\Models\Broker;
use App\Models\Counts;
use App\Models\Current;
use App\Models\Garages;
use App\Models\Gardens;
use App\Models\Location;
use App\Models\Property;
use App\Models\AreaTotals;
use App\Models\Department;
use App\Models\Dimensions;
use App\Models\Facilities;
use App\Models\Financials;
use App\Models\Attachments;
use App\Models\Evaluations;
use App\Models\BrokerPerson;
use App\Models\Construction;
use App\Models\Descriptions;
use App\Models\PropertyInfo;
use App\Models\Surroundings;
use Illuminate\Http\Request;
use App\Models\ClimatControl;
use App\Models\LocationDetails;
use App\Models\LocalizationInfo;
use App\Models\ThirdPartyMedias;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function userDashboard()
    {
        $auth_user = auth()->user()->id;
        $brokers = Broker::where('user_id', $auth_user)->get();
        if (!$brokers->isEmpty()) {
            $brokers = $brokers;
        } else {
            $brokers = array();
        }

        $broker_id = Broker::where('user_id', $auth_user)->value('id');
        $properties = Property::where('broker_id', '=', $broker_id)->get();
        if (!$properties->isEmpty()) {
            $properties = $properties;
        } else {
            $properties = array();
        }

        $activeBrokers = DB::table('brokers')->where('user_id', $auth_user)->whereNull('deleted_at')->get();
        $inActiveBrokers = DB::table('brokers')->where('user_id', $auth_user)->whereNotNull('deleted_at')->get();
        $activeProperties = DB::table('properties')->where('broker_id', $broker_id)->whereNull('deleted_at')->get();
        $inActiveProperties = DB::table('properties')->where('broker_id', $broker_id)->whereNotNull('deleted_at')->get();

        return view('client.home', compact('brokers', 'properties', 'activeBrokers', 'inActiveBrokers', 'activeProperties', 'inActiveProperties'));
    }

    public function brokerDashboard()
    {

        $brokers = Broker::whereNull('deleted_at')->where('user_id', auth()->user()->id)->get();
        if (!$brokers->isEmpty()) {
            foreach ($brokers as $broker) {
                $countProperties = Property::select('id')->whereNull('deleted_at')->where('broker_id', '=', $broker->id)->get()->count();
                $addArrayToProperties[] = [
                    'id' => $broker->id,
                    'count' => $countProperties,
                ];
                $propertyList = json_decode(json_encode($addArrayToProperties));
            }
        } else {
            $brokers = array();
            $propertyList = array();
        }
        return view('client.broker', compact('brokers', 'propertyList'));
    }

    public function propertyDashboard()
    {
        $brokers = Broker::whereNull('deleted_at')->where('user_id', auth()->user()->id)->get();

        if (!$brokers->isEmpty()) {
            foreach ($brokers as $broker) {
                $countProperties = DB::table('properties')->where('broker_id', $broker->id)->whereNull('deleted_at')->get()->count();
                $addArrayToProperties[] = [
                    'id' => $broker->id,
                    'count' => $countProperties,
                    'api' => $broker->api_key,
                ];
                $propertyList = json_decode(json_encode($addArrayToProperties));
            }
        } else {
            $brokers = array();
            $propertyList = array();
        }
        return view('client.property', compact('brokers', 'propertyList'));
    }

    /**
     * Global Scope: Houses Overview
     */
    public function houseDashboard($api_key)
    {
        $brokers = Broker::where('api_key', $api_key)->whereNull('deleted_at')->get();
        foreach ($brokers as $broker) {
            $houses = Property::where('broker_id', $broker->id)->whereNull('deleted_at')->paginate(24);
            $properties = Property::where('broker_id', $broker->id)->whereNull('deleted_at')->get();
            return view('client.houses', compact('houses', 'broker', 'properties'));
        }
    }

    /**
     * Specific Scope: View house
     */
    public function viewHouse($property_ID)
    {
        $frontYard = array();
        $backYard = array();

        if (Property::where('property_info_ID', $property_ID)->whereNull('deleted_at')->exists()) {
            $houses = Property::where('property_info_ID', $property_ID)->whereNull('deleted_at')->get();
            foreach ($houses as $house) {
                if (isset($house->property_info_ID) && !empty($house->property_info_ID)) {
                    $broker = Broker::where('id', $house->broker_id)->value('id');
                    $totalProperties = $this->collectDataForOnlyOneHouse($house->property_info_ID);

                    foreach ($totalProperties as $getProperties) {
                        foreach ($getProperties as $properties) {
                            if (isset($properties["results"])) {
                                if (isset($properties["results"]["images"]) && is_array($properties["results"]["images"])) {
                                    $totalImages = $properties["results"]["images"];
                                }
                                if (isset($properties["results"]["gardens"]) && is_array($properties["results"]["gardens"])) {
                                    if (count($properties["results"]["gardens"]) > 1) {
                                        $frontYard = $properties["results"]["gardens"][0];
                                        $backYard = $properties["results"]["gardens"][1];
                                    }

                                    if (count($properties["results"]["gardens"]) == 1) {
                                        $frontYard = $properties["results"]["gardens"][0];
                                        $backYard = array();
                                    }
                                }
                                $totalProperties = $properties["results"];
                            }
                        }
                    }
                    $no_image = asset('images/no_image.png');
                    return view('client.view_house', compact('totalImages', 'totalProperties', 'house', 'broker', 'no_image', 'frontYard', 'backYard'));
                } else {
                    return redirect()->route('user.properties');
                }
            }
        } else {
            return redirect()->route('user.properties');
        }
    }

    public static function collectDataForOnlyOneHouse($property_info_ID)
    {
        $tableAreaTotals = DB::table('area_totals')->where('property_ID', '=', $property_info_ID)->get();
        $tableAttachments = DB::table('attachments')->where('property_ID', '=', $property_info_ID)->get();
        $tableCounts = DB::table('counts')->where('property_ID', '=', $property_info_ID)->get();
        $tableDescriptions = DB::table('descriptions')->where('property_ID', '=', $property_info_ID)->get();
        $tableEvaluations = DB::table('evaluations')->where('property_ID', '=', $property_info_ID)->get();
        $tableFacilities = DB::table('facilities')->where('property_ID', '=', $property_info_ID)->get();
        $tableFinancials = DB::table('financials')->where('property_ID', '=', $property_info_ID)->get();
        $tableLocation = DB::table('locations')->where('property_ID', '=', $property_info_ID)->get();
        $tableLocationDetails = DB::table('location_details')->where('property_ID', '=', $property_info_ID)->get();
        $tableOffer = DB::table('offers')->where('property_ID', '=', $property_info_ID)->get();
        $tablePropertyInfo = DB::table('property_infos')->where('property_ID', '=', $property_info_ID)->get();
        $tableSurroundings = DB::table('surroundings')->where('property_ID', '=', $property_info_ID)->get();
        $tableType = DB::table('types')->where('property_ID', '=', $property_info_ID)->get();

        $tableAgency = DB::table('agencies')->where('property_ID', '=', $property_info_ID)->get();
        $tableDepartment = DB::table('departments')->where('property_ID', '=', $property_info_ID)->get();
        $tableBrokerPerson = DB::table('broker_people')->where('property_ID', '=', $property_info_ID)->get();

        $tableClimatControl = DB::table('climat_controls')->where('property_ID', '=', $property_info_ID)->get();
        $tableConstructions = DB::table('constructions')->where('property_ID', '=', $property_info_ID)->get();
        $tableCurrent = DB::table('currents')->where('property_ID', '=', $property_info_ID)->get();
        $tableDimensions = DB::table('dimensions')->where('property_ID', '=', $property_info_ID)->get();
        $tableGarages = DB::table('garages')->where('property_ID', '=', $property_info_ID)->get();
        $tableGardens = DB::table('gardens')->where('property_ID', '=', $property_info_ID)->get();
        $tableLocalizationInfo = DB::table('localization_infos')->where('property_ID', '=', $property_info_ID)->get();
        $tableThirdPartyMedias = DB::table('third_party_medias')->where('property_ID', '=', $property_info_ID)->get();

        /**
         * Garages
         */
        $totalGardenFromGardens = array();
        foreach ($tableGardens as $key => $value) {
            $totalGardenFromGardens[0]["gardens"][] =
                (object)array_merge(
                    (array)$value,
                );
        }

        /**
         * Add arrays to images and map
         */
        $totalImagesFromAttachments = array();
        foreach ($tableAttachments as $key => $value) {
            $totalImagesFromAttachments["images"][] = (object)array_merge(
                (array)$value,
            );
        }
        /**
         * Foreach: LocationDetails
         */
        $totalMapFromLocationDetails = array();
        foreach ($tableLocationDetails as $key => $value) {
            $totalMapFromLocationDetails[] = (object)array_merge(
                (array)$value,
            );
            $mergedLocationDetails["address"] = $totalMapFromLocationDetails;
            /**
             * Merged: Address
             */
            foreach ($mergedLocationDetails as $totalAddressFromLocationDetails) {
                foreach ($totalAddressFromLocationDetails as $addressFromLocationDetails) {
                    $streetFromAddress["formatted_address"]  = "$addressFromLocationDetails->street_name $addressFromLocationDetails->house_number, $addressFromLocationDetails->zipcode $addressFromLocationDetails->locality";
                    $streetFromAddress["street"]  = $addressFromLocationDetails->street_name;
                    $streetFromAddress["house_number"]  = $addressFromLocationDetails->house_number;
                    $zipcodeFromAddress["zipcode"] = $addressFromLocationDetails->zipcode;
                    $regionFromAddress["region"] = $addressFromLocationDetails->administrative_area_level_1;
                    $cityFromAddress["city"] = $addressFromLocationDetails->locality;
                    $countryFromAddress["country"] = $addressFromLocationDetails->country_name;

                    $mergedTotalAddress["address"] = (object)array_merge(
                        $streetFromAddress,
                        $zipcodeFromAddress,
                        $regionFromAddress,
                        $cityFromAddress,
                        $countryFromAddress
                    );
                }
            }
            /**
             * Merged: GEO
             */
            foreach ($mergedLocationDetails as $totalGEOFromLocationDetails) {
                foreach ($totalGEOFromLocationDetails as $GEOFromLocationDetails) {
                    $latitudeFromGEO["latitude"] = $GEOFromLocationDetails->latitude;
                    $longitudeFromGEO["longitude"] = $GEOFromLocationDetails->longitude;

                    $mergedTotalGEO["geo"] = (object)array_merge(
                        $latitudeFromGEO,
                        $longitudeFromGEO
                    );
                }
            }
            /**
             * Merged: Map
             */
            $mergedMapFromLocationDetails["map"] = (object)array_merge((array)$mergedTotalAddress, (array)$mergedTotalGEO);
        }
        /**
         * Foreach: totalResults
         */
        $totalResults = array();
        $totalContacts = array();
        foreach ($tableAreaTotals as $key => $value) {
            foreach ($tableAgency as $agency) {
                $totalContacts["agency"] = $agency;
            }
            foreach ($tableBrokerPerson as $brokerperson) {
                $totalContacts["broker_person"] = $brokerperson;
            }
            foreach ($tableDepartment as $department) {
                $totalContacts["department"] = $department;
            }
            $totalResponse = (object)array_merge(
                (array)$totalContacts,
            );
            $totalResults = (object)array_merge(
                (array)$value,
                (array)$totalImagesFromAttachments,
                (array)$tableClimatControl[$key],
                (array)$tableConstructions[$key],
                (array)$tableCounts[$key],
                (array)$tableCurrent[$key],
                (array)$tableDescriptions[$key],
                (array)$tableDimensions[$key],
                (array)$tableEvaluations[$key],
                (array)$tableFacilities[$key],
                (array)$tableFinancials[$key],
                (array)$tableGarages[$key],
                (array)$totalGardenFromGardens[$key],
                (array)$tableLocalizationInfo[$key],
                (array)$tableLocation[$key],
                (array)$mergedMapFromLocationDetails,
                (array)$tableOffer[$key],
                (array)$tablePropertyInfo[$key],
                (array)$tableSurroundings[$key],
                (array)$tableThirdPartyMedias[$key],
                (array)$tableType[$key],
            );
            $convertResponseToArray = json_decode(json_encode($totalResponse), true);
            $convertResultsToArray = json_decode(json_encode($totalResults), true);
        }
        /**
         ** Merged: Tables
         */
        $mergedResponseTables["response"] = array_merge($convertResponseToArray);
        $mergedTables["results"] = array_merge($convertResultsToArray);
        $mergedTotalTables["properties"][] = array_merge($mergedResponseTables, $mergedTables);

        if (!empty($mergedTotalTables)) {
            return $mergedTotalTables;
        } else {
            return back();
        }
    }
    /**
     * Soft Delete data (inactivation)
     */
    public function softDeleteBroker($api_key)
    {
        Broker::where('api_key', $api_key)->whereNull('deleted_at')->delete();
        return redirect()->route('user.brokers')->with('success', 'Broker has been deleted.');
    }

    public function softDeleteProperties($id)
    {
        if (Property::withTrashed()->where('broker_id', $id)->get()) {
            $properties = Property::withTrashed()->where('broker_id', $id)->get();
            foreach ($properties as $property) {
                AreaTotals::where('property_ID', $property->property_info_ID)->delete();
                Attachments::where('property_ID', $property->property_info_ID)->delete();
                Counts::where('property_ID', $property->property_info_ID)->delete();
                Descriptions::where('property_ID', $property->property_info_ID)->delete();
                Evaluations::where('property_ID', $property->property_info_ID)->delete();
                Facilities::where('property_ID', $property->property_info_ID)->delete();
                Financials::where('property_ID', $property->property_info_ID)->delete();
                Location::where('property_ID', $property->property_info_ID)->delete();
                LocationDetails::where('property_ID', $property->property_info_ID)->delete();
                Offer::where('property_ID', $property->property_info_ID)->delete();
                PropertyInfo::where('property_ID', $property->property_info_ID)->delete();
                Surroundings::where('property_ID', $property->property_info_ID)->delete();
                Type::where('property_ID', $property->property_info_ID)->delete();

                Agency::where('property_ID', $property->property_info_ID)->delete();
                BrokerPerson::where('property_ID', $property->property_info_ID)->delete();
                Department::where('property_ID', $property->property_info_ID)->delete();

                ClimatControl::where('property_ID', $property->property_info_ID)->delete();
                Construction::where('property_ID', $property->property_info_ID)->delete();
                Current::where('property_ID', $property->property_info_ID)->delete();
                Dimensions::where('property_ID', $property->property_info_ID)->delete();
                Garages::where('property_ID', $property->property_info_ID)->delete();
                Gardens::where('property_ID', $property->property_info_ID)->delete();
                LocalizationInfo::where('property_ID', $property->property_info_ID)->delete();
                ThirdPartyMedias::where('property_ID', $property->property_info_ID)->delete();
            }
            Property::withTrashed()->where('broker_id', $id)->delete();
        }
        if (Property::onlyTrashed()->where('broker_id', $id)->get()) {
            return redirect()->route('user.properties')->with('success', 'Properties have been deleted.');
        }
    }

    public function softDeleteHouse($id)
    {
        if (Property::withTrashed()->where('id', $id)->get()) {
            $properties = Property::withTrashed()->where('id', $id)->get();
            foreach ($properties as $property) {
                AreaTotals::where('property_ID', $property->property_info_ID)->delete();
                Attachments::where('property_ID', $property->property_info_ID)->delete();
                Counts::where('property_ID', $property->property_info_ID)->delete();
                Descriptions::where('property_ID', $property->property_info_ID)->delete();
                Evaluations::where('property_ID', $property->property_info_ID)->delete();
                Facilities::where('property_ID', $property->property_info_ID)->delete();
                Financials::where('property_ID', $property->property_info_ID)->delete();
                Location::where('property_ID', $property->property_info_ID)->delete();
                LocationDetails::where('property_ID', $property->property_info_ID)->delete();
                Offer::where('property_ID', $property->property_info_ID)->delete();
                PropertyInfo::where('property_ID', $property->property_info_ID)->delete();
                Surroundings::where('property_ID', $property->property_info_ID)->delete();
                Type::where('property_ID', $property->property_info_ID)->delete();

                Agency::where('property_ID', $property->property_info_ID)->delete();
                BrokerPerson::where('property_ID', $property->property_info_ID)->delete();
                Department::where('property_ID', $property->property_info_ID)->delete();

                ClimatControl::where('property_ID', $property->property_info_ID)->delete();
                Construction::where('property_ID', $property->property_info_ID)->delete();
                Current::where('property_ID', $property->property_info_ID)->delete();
                Dimensions::where('property_ID', $property->property_info_ID)->delete();
                Garages::where('property_ID', $property->property_info_ID)->delete();
                Gardens::where('property_ID', $property->property_info_ID)->delete();
                LocalizationInfo::where('property_ID', $property->property_info_ID)->delete();
                ThirdPartyMedias::where('property_ID', $property->property_info_ID)->delete();
            }
            Property::withTrashed()->where('id', $id)->delete();
        }
        if (Property::onlyTrashed()->where('id', $id)->get()) {
            return redirect()->route('user.properties')->with('success', 'House has been deleted.');
        }
    }
}
