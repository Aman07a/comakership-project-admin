<?php

namespace App\Http\Controllers;

use ZipArchive;
use Carbon\Carbon;
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
use App\Helpers\ArrayHelpers;
use App\Models\ClimatControl;
use App\Models\LocationDetails;
use App\Models\LocalizationInfo;
use App\Models\ThirdPartyMedias;
use App\Models\RealEstateProperty;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class XMLController extends Controller
{
    public static function mergeAllData($api_key)
    {
        if (Broker::where('api_key', $api_key)->whereNull('deleted_at')->get()) {
            ini_set('max_execution_time', 1800);

            /**
             ** Finished Code Cleaning:
             */
            $getAreaTotals = AreaTotals::getAreaTotalsFromXMLObjects($api_key);
            $getAttachments = Attachments::getAttachmentsFromXMLObjects($api_key);
            $getCounts = Counts::getCountsFromXMLObjects($api_key);
            $getDescriptions = Descriptions::getDescriptionsFromXMLObjects($api_key);
            $getEvaluations = Evaluations::getEvaluationsFromXMLObjects($api_key);
            $getFacilities = Facilities::getFacilitiesFromXMLObjects($api_key);
            $getFinancials = Financials::getFinancialsFromXMLObjects($api_key);
            $getLocations = Location::getLocationsFromXMLObjects($api_key);
            $getLocationDetails = LocationDetails::getLocationDetailsFromXMLObjects($api_key);
            $getOffers = Offer::getOffersFromXMLObjects($api_key);
            $getPropertyInfo = PropertyInfo::getPropertyInfoFromXMLObjects($api_key);
            $getSurroundings = Surroundings::getSurroundingsFromXMLObjects($api_key);
            $getTypes = Type::getTypesFromXMLObjects($api_key);

            $getAgencies = Agency::getAgenciesFromXMLObjects($api_key);
            $getBrokerPeople = BrokerPerson::getBrokerPeopleFromXMLObjects($api_key);
            $getDepartments = Department::getDepartmentsFromXMLObjects($api_key);

            // $getClimatControls = ClimatControl::getClimatControlsFromXMLObjects($api_key);
            // $getConstructions = Construction::getConstructionsFromXMLObjects($api_key);
            // $getCurrents = Current::getCurrentsFromXMLObjects($api_key);
            // $getDimensions = Dimensions::getDimensionsFromXMLObjects($api_key);
            // $getGarages = Garages::getGaragesFromXMLObjects($api_key);
            // $getGardens = Gardens::getGardensFromXMLObjects($api_key);
            // $getLocalizationInfos = LocalizationInfo::getLocalizationInfoFromXMLObjects($api_key);
            // $getThirdPartyMedias = ThirdPartyMedias::getThirdPartyMediasFromXMLObjects($api_key);

            $getProperties = Property::getPropertiesFromXMLObjects($api_key);
        }
    }

    public static function mergeDailyData($api_key)
    {
        if (DB::table('brokers')->where('api_key', $api_key)->whereNotNull('deleted_at')->exists()) {
            $softDeleteData = DB::table('brokers')->whereNotNull('deleted_at')->where('api_key', $api_key)->latest()->limit(1)->get();
            foreach ($softDeleteData as $isDeleted) {
                Broker::create([
                    'name' => $isDeleted->name,
                    'api_key' => $isDeleted->api_key,
                    'user_id' => $isDeleted->user_id,
                ]);
            }
        }

        if (DB::table('brokers')->where('api_key',  $api_key)->whereNull('deleted_at')->exists()) {
            $newBroker = DB::table('brokers')->where('api_key', $api_key)->whereNull('deleted_at')->get();
            foreach ($newBroker as $broker) {
                ini_set('max_execution_time', 2800);

                AreaTotals::getAreaTotalsFromXMLObjects($broker->api_key);
                Attachments::getAttachmentsFromXMLObjects($broker->api_key);
                Counts::getCountsFromXMLObjects($broker->api_key);
                Descriptions::getDescriptionsFromXMLObjects($broker->api_key);
                Evaluations::getEvaluationsFromXMLObjects($broker->api_key);
                Facilities::getFacilitiesFromXMLObjects($broker->api_key);
                Financials::getFinancialsFromXMLObjects($broker->api_key);
                Location::getLocationsFromXMLObjects($broker->api_key);
                LocationDetails::getLocationDetailsFromXMLObjects($broker->api_key);
                Offer::getOffersFromXMLObjects($broker->api_key);
                PropertyInfo::getPropertyInfoFromXMLObjects($broker->api_key);
                Surroundings::getSurroundingsFromXMLObjects($broker->api_key);
                Type::getTypesFromXMLObjects($broker->api_key);

                Agency::getAgenciesFromXMLObjects($broker->api_key);
                BrokerPerson::getBrokerPeopleFromXMLObjects($broker->api_key);
                Department::getDepartmentsFromXMLObjects($broker->api_key);

                // ClimatControl::getClimatControlsFromXMLObjects($api_key);
                // Construction::getConstructionsFromXMLObjects($api_key);
                // Current::getCurrentsFromXMLObjects($api_key);
                // Dimensions::getDimensionsFromXMLObjects($api_key);
                // Garages::getGaragesFromXMLObjects($api_key);
                // Gardens::getGardensFromXMLObjects($api_key);
                // LocalizationInfo::getLocalizationInfoFromXMLObjects($api_key);
                // ThirdPartyMedias::getThirdPartyMediasFromXMLObjects($api_key);

                Property::getPropertiesFromXMLObjects($broker->api_key);

                if (DB::table('properties')->where('broker_id', $broker->id)->whereNull('deleted_at')->exists()) {
                    $properties = DB::table('properties')->where('broker_id', $broker->id)->whereNull('deleted_at')->get();
                    echo "Message: Broker and properties have been created" . ". " .
                        "API: " . $broker->api_key . ". " .
                        "Total properties: " . $properties->count() . ". " .
                        "Date: " . Carbon::now()->format('Y-m-d H:i:s') . ".";
                }
            }
        }
    }

    public static function softDeleteData($api_key)
    {
        $broker_id = DB::table('brokers')->where('api_key', $api_key)->whereNull('deleted_at')->value('id');
        if (!empty(DB::table('properties')->where('broker_id', $broker_id)->whereNull('deleted_at')->exists())) {
            if (DB::table('properties')->where('broker_id', $broker_id)->whereNull('deleted_at')->get()) {
                $properties = DB::table('properties')->where('broker_id', $broker_id)->whereNull('deleted_at')->get();
                foreach ($properties as $property) {
                    AreaTotals::where('property_ID', $property->property_info_ID)->whereNull('deleted_at')->delete();
                    Attachments::where('property_ID', $property->property_info_ID)->whereNull('deleted_at')->delete();
                    Counts::where('property_ID', $property->property_info_ID)->whereNull('deleted_at')->delete();
                    Descriptions::where('property_ID', $property->property_info_ID)->whereNull('deleted_at')->delete();
                    Evaluations::where('property_ID', $property->property_info_ID)->whereNull('deleted_at')->delete();
                    Facilities::where('property_ID', $property->property_info_ID)->whereNull('deleted_at')->delete();
                    Financials::where('property_ID', $property->property_info_ID)->whereNull('deleted_at')->delete();
                    Location::where('property_ID', $property->property_info_ID)->whereNull('deleted_at')->delete();
                    LocationDetails::where('property_ID', $property->property_info_ID)->whereNull('deleted_at')->delete();
                    Offer::where('property_ID', $property->property_info_ID)->whereNull('deleted_at')->delete();
                    PropertyInfo::where('property_ID', $property->property_info_ID)->whereNull('deleted_at')->delete();
                    Surroundings::where('property_ID', $property->property_info_ID)->whereNull('deleted_at')->delete();
                    Type::where('property_ID', $property->property_info_ID)->whereNull('deleted_at')->delete();

                    Agency::where('property_ID', $property->property_info_ID)->whereNull('deleted_at')->delete();
                    BrokerPerson::where('property_ID', $property->property_info_ID)->whereNull('deleted_at')->delete();
                    Department::where('property_ID', $property->property_info_ID)->whereNull('deleted_at')->delete();

                    // ClimatControl::where('property_ID', $property->property_info_ID)->whereNull('deleted_at')->delete();
                    // Construction::where('property_ID', $property->property_info_ID)->whereNull('deleted_at')->delete();
                    // Current::where('property_ID', $property->property_info_ID)->whereNull('deleted_at')->delete();
                    // Dimensions::where('property_ID', $property->property_info_ID)->whereNull('deleted_at')->delete();
                    // Garages::where('property_ID', $property->property_info_ID)->whereNull('deleted_at')->delete();
                    // Gardens::where('property_ID', $property->property_info_ID)->whereNull('deleted_at')->delete();
                    // LocalizationInfo::where('property_ID', $property->property_info_ID)->whereNull('deleted_at')->delete();
                    // ThirdPartyMedias::where('property_ID', $property->property_info_ID)->whereNull('deleted_at')->delete();
                }
                Property::where('broker_id', $broker_id)->whereNull('deleted_at')->delete();
                Broker::where('id', $broker_id)->whereNull('deleted_at')->delete();
            }
            if (DB::table('properties')->where('broker_id', $broker_id)->whereNotNull('deleted_at')->exists()) {
                $brokerIsDeleted = DB::table('brokers')->where('id', $broker_id)->whereNull('deleted_at')->get();
                return $brokerIsDeleted;
            }
        } else {
            return back();
        }
    }

    public static function setDataToIsDeleted($api_key)
    {
        if (DB::table('brokers')->where('api_key', '=', $api_key)->exists()) {
            $zip = new ZipArchive();
            $publicDir = public_path();
            $zipFileName = "$api_key.zip";
            $path = $publicDir . '/documents/temp/' . $zipFileName;

            if ($zip->open($path) != true) {
                echo '<p>Can\'t open zip archive!</p>';
                return false;
            }

            if (file_exists($path)) {
                $totalPropertyInfoArray = [];
                for ($idx = 0; $path = $zip->statIndex($idx); $idx++) {
                    if (!is_dir($path['name'])) {
                        $contents = $zip->getFromIndex($idx);
                        $xmlObject = simplexml_load_string($contents, "SimpleXMLElement", LIBXML_NOCDATA);
                        $json = json_encode($xmlObject, JSON_PRETTY_PRINT);
                        $propertyData = json_decode($json, true);
                        if (count($propertyData["RealEstateProperty"])) {
                            if ($propertyData["RealEstateProperty"]["PropertyInfo"]["Status"] == "WITHDRAWN") {
                                continue;
                            }
                            foreach ($propertyData["RealEstateProperty"] as $key => $pData) {
                                switch ($key) {
                                    case "PropertyInfo":
                                        if (array_key_exists("ID", $pData)) {
                                            $totalPropertyInfoArray['property_ID'] = $pData["ID"] ?? null;
                                        }
                                        $propertyInfoArrays = $totalPropertyInfoArray;
                                        unset($totalPropertyInfoArray);
                                        break;
                                }
                            }
                            $propertyIDArrays[] = $propertyInfoArrays;
                        }
                    }
                }


                /**
                 ** Done: Updated Other Components: softDeletes
                 */
                foreach ($propertyIDArrays as $property) {
                    if (Property::where('property_info_ID', '=', $property['property_ID'])->exists()) {
                        Property::where('property_info_ID', '=', $property['property_ID'])->whereNull('deleted_at')->update(['deleted_at' => Carbon::now()->format('Y-m-d H:i:s')]);
                        AreaTotals::where('property_ID', '=', $property['property_ID'])->whereNull('deleted_at')->update(['deleted_at' => Carbon::now()->format('Y-m-d H:i:s')]);
                        Attachments::where('property_ID', '=', $property['property_ID'])->whereNull('deleted_at')->update(['deleted_at' => Carbon::now()->format('Y-m-d H:i:s')]);
                        Counts::where('property_ID', '=', $property['property_ID'])->whereNull('deleted_at')->update(['deleted_at' => Carbon::now()->format('Y-m-d H:i:s')]);
                        Descriptions::where('property_ID', '=', $property['property_ID'])->whereNull('deleted_at')->update(['deleted_at' => Carbon::now()->format('Y-m-d H:i:s')]);
                        Evaluations::where('property_ID', '=', $property['property_ID'])->whereNull('deleted_at')->update(['deleted_at' => Carbon::now()->format('Y-m-d H:i:s')]);
                        Facilities::where('property_ID', '=', $property['property_ID'])->whereNull('deleted_at')->update(['deleted_at' => Carbon::now()->format('Y-m-d H:i:s')]);
                        Financials::where('property_ID', '=', $property['property_ID'])->whereNull('deleted_at')->update(['deleted_at' => Carbon::now()->format('Y-m-d H:i:s')]);
                        Location::where('property_ID', '=', $property['property_ID'])->whereNull('deleted_at')->update(['deleted_at' => Carbon::now()->format('Y-m-d H:i:s')]);
                        LocationDetails::where('property_ID', '=', $property['property_ID'])->whereNull('deleted_at')->update(['deleted_at' => Carbon::now()->format('Y-m-d H:i:s')]);
                        Offer::where('property_ID', '=', $property['property_ID'])->whereNull('deleted_at')->update(['deleted_at' => Carbon::now()->format('Y-m-d H:i:s')]);
                        Surroundings::where('property_ID', '=', $property['property_ID'])->whereNull('deleted_at')->update(['deleted_at' => Carbon::now()->format('Y-m-d H:i:s')]);
                        Type::where('property_ID', '=', $property['property_ID'])->whereNull('deleted_at')->update(['deleted_at' => Carbon::now()->format('Y-m-d H:i:s')]);

                        Agency::where('property_ID', '=', $property['property_ID'])->whereNull('deleted_at')->update(['deleted_at' => Carbon::now()->format('Y-m-d H:i:s')]);
                        BrokerPerson::where('property_ID', '=', $property['property_ID'])->whereNull('deleted_at')->update(['deleted_at' => Carbon::now()->format('Y-m-d H:i:s')]);
                        Department::where('property_ID', '=', $property['property_ID'])->whereNull('deleted_at')->update(['deleted_at' => Carbon::now()->format('Y-m-d H:i:s')]);

                        // ClimatControl::where('property_ID', '=', $property['property_ID'])->whereNull('deleted_at')->update(['deleted_at' => Carbon::now()->format('Y-m-d H:i:s')]);
                        // Construction::where('property_ID', '=', $property['property_ID'])->whereNull('deleted_at')->update(['deleted_at' => Carbon::now()->format('Y-m-d H:i:s')]);
                        // Current::where('property_ID', '=', $property['property_ID'])->whereNull('deleted_at')->update(['deleted_at' => Carbon::now()->format('Y-m-d H:i:s')]);
                        // Dimensions::where('property_ID', '=', $property['property_ID'])->whereNull('deleted_at')->update(['deleted_at' => Carbon::now()->format('Y-m-d H:i:s')]);
                        // Garages::where('property_ID', '=', $property['property_ID'])->whereNull('deleted_at')->update(['deleted_at' => Carbon::now()->format('Y-m-d H:i:s')]);
                        // Gardens::where('property_ID', '=', $property['property_ID'])->whereNull('deleted_at')->update(['deleted_at' => Carbon::now()->format('Y-m-d H:i:s')]);
                        // LocalizationInfo::where('property_ID', '=', $property['property_ID'])->whereNull('deleted_at')->update(['deleted_at' => Carbon::now()->format('Y-m-d H:i:s')]);
                        // ThirdPartyMedias::where('property_ID', '=', $property['property_ID'])->whereNull('deleted_at')->update(['deleted_at' => Carbon::now()->format('Y-m-d H:i:s')]);
                    }
                }
            }

            /**
             ** Done: Updated Broker: softDeletes
             */
            foreach (Broker::where('api_key', $api_key)->whereNull('deleted_at')->get() as $isDeleted) {
                Broker::where('api_key', $api_key)->whereNull('deleted_at')->update([
                    'name' => $isDeleted->name,
                    'api_key' => $isDeleted->api_key,
                    'deleted_at' => Carbon::now()->format('Y-m-d H:i:s')
                ]);
            }
        }
    }

    public function showAllData($api_key)
    {
        /**
         * Max Excution Time: 1800
         */
        ini_set('max_execution_time', 1800);

        /**
         * Memory Limit
         */
        ini_set('memory_limit', '512M');

        /**
         * Validation: Brokers => api_key
         */
        $broker_id = DB::table('brokers')->where('api_key', '=', $api_key)->value('id');

        /**
         * Validation: Properties => broker_id
         */
        $properties = DB::table('properties')->where('broker_id', '=', $broker_id)->whereNull('deleted_at')->orderByDesc('id')->get();

        /**
         ** Array variables
         */
        $merged = [];

        /**
         * Migrations
         */
        foreach ($properties as $property) {
            $property_ID = $property->property_info_ID;

            $tableAreaTotals = DB::table('area_totals')->where('property_ID', $property_ID)->whereNull('deleted_at')->get();
            $tableAttachments = DB::table('attachments')->where('property_ID', $property_ID)->whereNull('deleted_at')->get();
            $tableCounts = DB::table('counts')->where('property_ID', $property_ID)->whereNull('deleted_at')->get();
            $tableDescriptions = DB::table('descriptions')->where('property_ID', $property_ID)->whereNull('deleted_at')->get();
            $tableEvaluations = DB::table('evaluations')->where('property_ID', $property_ID)->whereNull('deleted_at')->get();
            $tableFacilities = DB::table('facilities')->where('property_ID', $property_ID)->whereNull('deleted_at')->get();
            $tableFinancials = DB::table('financials')->where('property_ID', $property_ID)->whereNull('deleted_at')->get();
            $tableLocation = DB::table('locations')->where('property_ID', $property_ID)->whereNull('deleted_at')->get();
            $tableLocationDetails = DB::table('location_details')->where('property_ID', $property_ID)->whereNull('deleted_at')->get();
            $tableOffer = DB::table('offers')->where('property_ID', $property_ID)->whereNull('deleted_at')->get();
            $tablePropertyInfo = DB::table('property_infos')->where('property_ID', $property_ID)->whereNull('deleted_at')->get();
            $tableSurroundings = DB::table('surroundings')->where('property_ID', $property_ID)->whereNull('deleted_at')->get();
            $tableType = DB::table('types')->where('property_ID', $property_ID)->whereNull('deleted_at')->get();

            $tableAgency = DB::table('agencies')->where('property_ID', $property_ID)->whereNull('deleted_at')->get();
            $tableBrokerPerson = DB::table('broker_people')->where('property_ID', $property_ID)->whereNull('deleted_at')->get();
            $tableDepartment = DB::table('departments')->where('property_ID', $property_ID)->whereNull('deleted_at')->get();

            // $tableClimatControl = DB::table('climat_controls')->where('property_ID', $property_ID)->get();
            // $tableConstructions = DB::table('constructions')->where('property_ID', $property_ID)->get();
            // $tableCurrent = DB::table('currents')->where('property_ID', $property_ID)->get();
            // $tableDimensions = DB::table('dimensions')->where('property_ID', $property_ID)->get();
            // $tableGarages = DB::table('garages')->where('property_ID', $property_ID)->get();
            // $tableGardens = DB::table('gardens')->where('property_ID', $property_ID)->get();
            // $tableLocalizationInfo = DB::table('localization_infos')->where('property_ID', $property_ID)->get();
            // $tableThirdPartyMedias = DB::table('third_party_medias')->where('property_ID', $property_ID)->get();

            /**
             * Garages
             */
            // $totalGardenFromGardens = array();
            // foreach ($tableGardens as $key => $value) {
            //     $totalGardenFromGardens[0]["gardens"][] =
            //         (object)array_merge(
            //             (array)$value,
            //         );
            // }

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
                        $streetFromAddress["street"]  = "$addressFromLocationDetails->street_name$addressFromLocationDetails->house_number";
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
                    // (array)$tableClimatControl[$key],
                    // (array)$tableConstructions[$key],
                    (array)$tableCounts[$key],
                    // (array)$tableCurrent[$key],
                    (array)$tableDescriptions[$key],
                    // (array)$tableDimensions[$key],
                    (array)$tableEvaluations[$key],
                    (array)$tableFacilities[$key],
                    (array)$tableFinancials[$key],
                    // (array)$tableGarages[$key],
                    // (array)$totalGardenFromGardens[$key],
                    // (array)$tableLocalizationInfo[$key],
                    (array)$tableLocation[$key],
                    (array)$mergedMapFromLocationDetails,
                    (array)$tableOffer[$key],
                    (array)$tablePropertyInfo[$key],
                    (array)$tableSurroundings[$key],
                    // (array)$tableThirdPartyMedias[$key],
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
        }

        /**
         * DONE: Sorting results on DESCENDING
         */
        if (!empty($mergedTotalTables)) {
            $returnTotalTables = $this->paginate($mergedTotalTables);
            return $returnTotalTables;
        } else {
            return back();
        }
    }

    public function paginate($items, $perPage = 60, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items["properties"]);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public static function saveAllData($api_key)
    {
        if (Broker::where('api_key', $api_key)->whereNull('deleted_at')->get()) {
            ini_set('max_execution_time', 1800);
            $broker_id = Broker::where('api_key', $api_key)->whereNull('deleted_at')->value('id');

            /**
             ** Finished Code Cleaning:
             */
            $getAreaTotals = AreaTotals::getAreaTotalsFromXMLObjects($api_key);
            $getAttachments = Attachments::getAttachmentsFromXMLObjects($api_key);
            $getCounts = Counts::getCountsFromXMLObjects($api_key);
            $getDescriptions = Descriptions::getDescriptionsFromXMLObjects($api_key);
            $getEvaluations = Evaluations::getEvaluationsFromXMLObjects($api_key);
            $getFacilities = Facilities::getFacilitiesFromXMLObjects($api_key);
            $getFinancials = Financials::getFinancialsFromXMLObjects($api_key);
            $getLocations = Location::getLocationsFromXMLObjects($api_key);
            $getLocationDetails = LocationDetails::getLocationDetailsFromXMLObjects($api_key);
            $getOffers = Offer::getOffersFromXMLObjects($api_key);
            $getPropertyInfo = PropertyInfo::getPropertyInfoFromXMLObjects($api_key);
            $getSurroundings = Surroundings::getSurroundingsFromXMLObjects($api_key);
            $getTypes = Type::getTypesFromXMLObjects($api_key);

            $getAgencies = Agency::getAgenciesFromXMLObjects($api_key);
            $getBrokerPeople = BrokerPerson::getBrokerPeopleFromXMLObjects($api_key);
            $getDepartments = Department::getDepartmentsFromXMLObjects($api_key);

            // $getClimatControls = ClimatControl::getClimatControlsFromXMLObjects($api_key);
            // $getConstructions = Construction::getConstructionsFromXMLObjects($api_key);
            // $getCurrents = Current::getCurrentsFromXMLObjects($api_key);
            // $getDimensions = Dimensions::getDimensionsFromXMLObjects($api_key);
            // $getGarages = Garages::getGaragesFromXMLObjects($api_key);
            // $getGardens = Gardens::getGardensFromXMLObjects($api_key);
            // $getLocalizationInfos = LocalizationInfo::getLocalizationInfoFromXMLObjects($api_key);
            // $getThirdPartyMedias = ThirdPartyMedias::getThirdPartyMediasFromXMLObjects($api_key);

            $getProperties = Property::getPropertiesFromXMLObjects($api_key);

            if (Property::where('broker_id', $broker_id)->whereNull('deleted_at')->exists() === true) {
                if (User::where('id', Auth::id())->value('is_admin') === 1) {
                    return redirect()->back()->with('success', 'Properties have been saved.');
                }
                if (User::where('id', Auth::id())->value('is_admin') === 0) {
                    return redirect()->back()->with('success', 'Properties have been saved.');
                }
            }
        }
    }
}
