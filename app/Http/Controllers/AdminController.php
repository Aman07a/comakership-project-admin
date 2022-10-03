<?php

namespace App\Http\Controllers;

use ZipArchive;
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
use App\Helpers\APIHelpers;
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
use Illuminate\Support\Facades\Hash;
use App\Console\Commands\DailyCronjob;
use App\Http\Controllers\XMLController;
use App\Http\Requests\SaveBrokerRequest;

class AdminController extends Controller
{
    /**
     * Dashboards
     */
    public function dashboard()
    {
        $users = User::all();
        $brokers = Broker::all();
        $properties = Property::all();
        $activeUsers = DB::table('users')->whereNull('deleted_at')->get();
        $inActiveUsers = DB::table('users')->whereNotNull('deleted_at')->get();
        $activeBrokers = DB::table('brokers')->whereNull('deleted_at')->get();
        $inActiveBrokers = DB::table('brokers')->whereNotNull('deleted_at')->get();
        $activeProperties = DB::table('properties')->whereNull('deleted_at')->get();
        $inActiveProperties = DB::table('properties')->whereNotNull('deleted_at')->get();
        return view('admin.index', compact('users', 'brokers', 'properties', 'activeUsers', 'inActiveUsers', 'activeBrokers', 'inActiveBrokers', 'activeProperties', 'inActiveProperties'));
    }

    public function userDashboard()
    {
        $users = User::where('is_admin', '!=', 1)->whereNull('blocked_at')->whereNull('deleted_at')->orderByDesc('id')->get()->all();
        return view('admin.user', compact('users'));
    }

    public function brokerDashboard()
    {
        $brokers = Broker::whereNull('blocked_at')->whereNull('deleted_at')->get();
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
        return view('admin.broker', compact('brokers', 'propertyList'));
    }

    public function propertyDashboard()
    {
        $brokers = Broker::whereNull('blocked_at')->whereNull('deleted_at')->orderByDesc('id')->paginate(24);
        /**
         * DONE: Fetch only total of properties from the same broker
         */
        if (!$brokers->isEmpty()) {
            foreach ($brokers as $broker) {
                $countProperties = DB::table('properties')->whereNull('deleted_at')->where('broker_id', $broker->id)->get()->count();
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
        return view('admin.property', compact('brokers', 'propertyList'));
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
            return view('admin.houses', compact('houses', 'broker', 'properties'));
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
                    return view('admin.view_house', compact('totalImages', 'totalProperties', 'house', 'no_image', 'frontYard', 'backYard'));
                } else {
                    return redirect()->route('properties');
                }
            }
        } else {
            return redirect()->route('properties');
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
        $tableBrokerPerson = DB::table('broker_people')->where('property_ID', '=', $property_info_ID)->get();
        $tableDepartment = DB::table('departments')->where('property_ID', '=', $property_info_ID)->get();

        // $tableClimatControl = DB::table('climat_controls')->where('property_ID', '=', $property_info_ID)->get();
        // $tableConstructions = DB::table('constructions')->where('property_ID', '=', $property_info_ID)->get();
        // $tableCurrent = DB::table('currents')->where('property_ID', '=', $property_info_ID)->get();
        // $tableDimensions = DB::table('dimensions')->where('property_ID', '=', $property_info_ID)->get();
        // $tableGarages = DB::table('garages')->where('property_ID', '=', $property_info_ID)->get();
        // $tableGardens = DB::table('gardens')->where('property_ID', '=', $property_info_ID)->get();
        // $tableLocalizationInfo = DB::table('localization_infos')->where('property_ID', '=', $property_info_ID)->get();
        // $tableThirdPartyMedias = DB::table('third_party_medias')->where('property_ID', '=', $property_info_ID)->get();

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
                    $formatted_address = "$addressFromLocationDetails->street_name $addressFromLocationDetails->house_number, $addressFromLocationDetails->zipcode $addressFromLocationDetails->locality";
                    $formattedStreetFromAddress["formatted_address"]  = $formatted_address;
                    $streetFromAddress["street"]  = $addressFromLocationDetails->street_name;
                    $houseNumberFromAddress["house_number"]  = $addressFromLocationDetails->house_number;
                    $zipcodeFromAddress["zipcode"] = $addressFromLocationDetails->zipcode;
                    $regionFromAddress["region"] = $addressFromLocationDetails->administrative_area_level_1;
                    $cityFromAddress["city"] = $addressFromLocationDetails->locality;
                    $countryFromAddress["country"] = $addressFromLocationDetails->country_name;

                    $mergedTotalAddress["address"] = (object)array_merge(
                        $formattedStreetFromAddress,
                        $streetFromAddress,
                        $houseNumberFromAddress,
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
                    $coordinates = "$GEOFromLocationDetails->latitude,$GEOFromLocationDetails->longitude";
                    $coordinatesFromGeo["coordinates"] = $coordinates;
                    $latitudeFromGEO["latitude"] = $GEOFromLocationDetails->latitude;
                    $longitudeFromGEO["longitude"] = $GEOFromLocationDetails->longitude;

                    $mergedTotalGEO["geo"] = (object)array_merge(
                        $coordinatesFromGeo,
                        $latitudeFromGEO,
                        $longitudeFromGEO
                    );
                }
            }

            /**
             * Merged: Maps
             */
            foreach ($mergedLocationDetails as $totalGEOFromLocationDetails) {
                foreach ($totalGEOFromLocationDetails as $GEOFromLocationDetails) {
                    $address_and_coordinates = $formatted_address . "+/@" . $coordinates;
                    $addressFromGoogleMaps["google_maps_address"]  = str_replace(" ", "+", $formatted_address);
                    $addressAndCoordinatesFromGoogleMaps["google_maps_address_and_coordinates"]  = str_replace(" ", "+", $address_and_coordinates);

                    $mergedTotalGoogleMaps["google_maps"] = (object)array_merge(
                        $addressFromGoogleMaps,
                        $addressAndCoordinatesFromGoogleMaps
                    );
                }
            }
            /**
             * Merged: Map
             */
            $mergedMapFromLocationDetails["map"] = (object)array_merge((array)$mergedTotalAddress, (array)$mergedTotalGEO, (array)$mergedTotalGoogleMaps);
            // dump($mergedMapFromLocationDetails);
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

        if (!empty($mergedTotalTables)) {
            return $mergedTotalTables;
        } else {
            return back();
        }
    }

    /**
     * Add data
     */
    public function addUser()
    {
        return view('admin.add_user');
    }

    public function addBroker()
    {
        $users = DB::table('users')->where('is_admin', '=', 0)->get();
        return view('admin.add_broker', compact('users'));
    }

    /**
     * Store data & Request data
     */
    public function storeUser(Request $req)
    {

        if (!User::where('name', $req->name)->whereNull('deleted_at')->exists()) {
            if (!User::where('email', $req->api_key)->whereNull('deleted_at')->exists()) {
                $user = new User();
                $user->name = $req->name;
                $user->email = $req->email;
                if ($req->password === $req->password_confirmation) {
                    $user->password = Hash::make($req->password);
                }
                $user->is_admin = $req->is_admin;
                $user->save();
                return redirect()->route('users')->with('success', 'User has been created.');
            } else {
                return redirect()->back()->with("failed", "Email already exists.");
            }
        } else {
            return redirect()->back()->with("failed", "Name already exists.");
        }
    }

    public function storeBroker(SaveBrokerRequest $req)
    {
        /**
         * Validate: image for broker
         */
        if (!empty($req->image)) {
            $req->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $original_name = $req->file('image')->getClientOriginalName();
            $changed_name = preg_replace('/\.[^.]+$/', '', $original_name);
            $imageName = $changed_name . '.' . $req->image->extension();
            $req->image->move(storage_path('images/brokers/' . date('Y-m-d') . '/'), $imageName);
            $image = date('Y-m-d') . '/' . $imageName;
        }

        /**
         * Doing: Saving API data to Broker()
         */
        $broker = new Broker();
        $broker->name = $req->name;
        $broker->api_key = $req->api_key;
        $broker->user_id = $req->user;
        $broker->image = $image ?? null;

        /**
         ** Environment (.ENV) Variables
         */
        $api_http = env('API_HTTP_URL');
        $api_version = env('API_VERSION');
        $api_zip = env('API_ZIP_URL');

        // if (!Broker::where('name', $broker->name)->whereNull('deleted_at')->exists()) {
        //     if (!Broker::where('api_key', $broker->api_key)->whereNull('deleted_at')->exists()) {
        //         $broker->save();
        //         return redirect()->route('brokers')->with('success', 'API-Key has been saved.');
        //     } else {
        //         return redirect()->back()->with("failed", "Broker API-Key already exists.");
        //     }
        // } else {
        //     return redirect()->back()->with("failed", "Broker's name already exists.");
        // }

        // Broker Key
        $broker_key = $req->api_key;
        // Create new zip object
        $zip = new ZipArchive();
        // Store the public path
        $publicDir = public_path();
        // Define the file name. Give it a unique name to avoid overriding.
        $zipFileName = "$broker_key.zip";
        // Define the file path
        $filePath = $publicDir . '/documents/temp/' . $zipFileName;

        // Check if the ZipArchive can't open the ZIP File
        if ($zip->open($filePath) != true) {
            echo '<p>Can\'t open zip archive!</p>';
            return false;
        }

        if (file_exists($filePath)) {
            /**
             ** Overwriting: if .ZIP File Exist
             */
            if ($zip->open($filePath, (ZipArchive::OVERWRITE))) {
                if ($zip->open($filePath, (ZipArchive::CREATE))) {
                    /**
                     ** Get broker_id from tables('brokers')
                     */
                    $broker_id = DB::table('brokers')->where('api_key', $req->api_key)->value('id');
                    /**
                     * Done: Truncating other components/{$property_ID}
                     */
                    $totalProperties = DB::table('properties')->where('broker_id', $broker_id)->get();
                    foreach ($totalProperties as $properties) {
                        if (!empty(DB::table('area_totals')->where('property_ID', $properties->property_info_ID)->get())) {
                            DB::table('area_totals')->where('property_ID', $properties->property_info_ID)->delete();
                        }
                        if (!empty(DB::table('attachments')->where('property_ID', $properties->property_info_ID)->get())) {
                            DB::table('attachments')->where('property_ID', $properties->property_info_ID)->delete();
                        }
                        if (!empty(DB::table('counts')->where('property_ID', $properties->property_info_ID)->get())) {
                            DB::table('counts')->where('property_ID', $properties->property_info_ID)->delete();
                        }
                        if (!empty(DB::table('descriptions')->where('property_ID', $properties->property_info_ID)->get())) {
                            DB::table('descriptions')->where('property_ID', $properties->property_info_ID)->delete();
                        }
                        if (!empty(DB::table('evaluations')->where('property_ID', $properties->property_info_ID)->get())) {
                            DB::table('evaluations')->where('property_ID', $properties->property_info_ID)->delete();
                        }
                        if (!empty(DB::table('facilities')->where('property_ID', $properties->property_info_ID)->get())) {
                            DB::table('facilities')->where('property_ID', $properties->property_info_ID)->delete();
                        }
                        if (!empty(DB::table('financials')->where('property_ID', $properties->property_info_ID)->get())) {
                            DB::table('financials')->where('property_ID', $properties->property_info_ID)->delete();
                        }
                        if (!empty(DB::table('locations')->where('property_ID', $properties->property_info_ID)->get())) {
                            DB::table('locations')->where('property_ID', $properties->property_info_ID)->delete();
                        }
                        if (!empty(DB::table('location_details')->where('property_ID', $properties->property_info_ID)->get())) {
                            DB::table('location_details')->where('property_ID', $properties->property_info_ID)->delete();
                        }
                        if (!empty(DB::table('offers')->where('property_ID', $properties->property_info_ID)->get())) {
                            DB::table('offers')->where('property_ID', $properties->property_info_ID)->delete();
                        }
                        if (!empty(DB::table('property_infos')->where('property_ID', $properties->property_info_ID)->get())) {
                            DB::table('property_infos')->where('property_ID', $properties->property_info_ID)->delete();
                        }
                        if (!empty(DB::table('surroundings')->where('property_ID', $properties->property_info_ID)->get())) {
                            DB::table('surroundings')->where('property_ID', $properties->property_info_ID)->delete();
                        }
                        if (!empty(DB::table('types')->where('property_ID', $properties->property_info_ID)->get())) {
                            DB::table('types')->where('property_ID', $properties->property_info_ID)->delete();
                        }
                        if (!empty(DB::table('agencies')->where('property_ID', $properties->property_info_ID)->get())) {
                            DB::table('agencies')->where('property_ID', $properties->property_info_ID)->delete();
                        }
                        if (!empty(DB::table('broker_people')->where('property_ID', $properties->property_info_ID)->get())) {
                            DB::table('broker_people')->where('property_ID', $properties->property_info_ID)->delete();
                        }
                        if (!empty(DB::table('departments')->where('property_ID', $properties->property_info_ID)->get())) {
                            DB::table('departments')->where('property_ID', $properties->property_info_ID)->delete();
                        }
                        if (!empty(DB::table('climat_controls')->where('property_ID', $properties->property_info_ID)->get())) {
                            DB::table('climat_controls')->where('property_ID', $properties->property_info_ID)->delete();
                        }
                        if (!empty(DB::table('constructions')->where('property_ID', $properties->property_info_ID)->get())) {
                            DB::table('constructions')->where('property_ID', $properties->property_info_ID)->delete();
                        }
                        if (!empty(DB::table('currents')->where('property_ID', $properties->property_info_ID)->get())) {
                            DB::table('currents')->where('property_ID', $properties->property_info_ID)->delete();
                        }
                        if (!empty(DB::table('dimensions')->where('property_ID', $properties->property_info_ID)->get())) {
                            DB::table('dimensions')->where('property_ID', $properties->property_info_ID)->delete();
                        }
                        if (!empty(DB::table('garages')->where('property_ID', $properties->property_info_ID)->get())) {
                            DB::table('garages')->where('property_ID', $properties->property_info_ID)->delete();
                        }
                        if (!empty(DB::table('gardens')->where('property_ID', $properties->property_info_ID)->get())) {
                            DB::table('gardens')->where('property_ID', $properties->property_info_ID)->delete();
                        }
                        if (!empty(DB::table('localization_infos')->where('property_ID', $properties->property_info_ID)->get())) {
                            DB::table('localization_infos')->where('property_ID', $properties->property_info_ID)->delete();
                        }
                        if (!empty(DB::table('third_party_medias')->where('property_ID', $properties->property_info_ID)->get())) {
                            DB::table('third_party_medias')->where('property_ID', $properties->property_info_ID)->delete();
                        }
                    }
                    /**
                     ** Flushing/Truncating properties/{$broker_id}
                     */
                    if (!empty(DB::table('properties')->where('broker_id', $broker_id)->get())) {
                        DB::table('properties')->where('broker_id', $broker_id)->delete();
                    }
                    /**
                     ** Flushing/Truncating brokers/{user_id}
                     */
                    if (!empty(DB::table('brokers')->where('user_id', $broker->user_id)->get())) {
                        DB::table('brokers')->where('user_id', $broker->user_id)->delete();
                    }
                    /**
                     ** DONE: Save the Broker()
                     */
                    $broker->save();
                    /**
                     ** Adding all components to database when broker is created
                     */
                    XMLController::mergeAllData($broker->api_key);
                    /**
                     ** Redirecting the route to Broker Overview
                     */
                    return redirect()->route('brokers')->with('success', 'Broker has been overwritten and saved.');
                }
            }
        } else {
            /**
             * Max Excution Time: 1800
             */
            ini_set('max_execution_time', 1800);
            /**
             * Memory Limit
             */
            ini_set('memory_limit', '512M');
            /**
             * DONE: Downloading .ZIP File
             */
            $url = $api_http . $api_version . '/' . $broker_key . '/' . $api_zip;
            $checkFileUrl = file_get_contents($url);
            file_put_contents(public_path("documents/temp/$broker_key.zip"), $checkFileUrl);
            /**
             * DONE: Save the Broker()
             */
            $broker->save();
            /**
             * Saving all the properties from the objects to the database, when the broker is created
             */
            XMLController::mergeAllData($broker->api_key);
            /**
             * Activate DailyCronjob
             */
            // DailyCronjob::handle($broker->api_key);
            /**
             ** Redirecting the route to Broker Overview
             */
            return redirect()->route('brokers')->with('success', 'Broker has been saved.');
        }
    }

    /**
     * Edit data
     */
    public function editUser($id)
    {
        $user = User::find($id);
        return view('admin.edit_user', compact('user'));
    }

    public function editBroker($api_key)
    {
        $brokers = Broker::where('api_key', $api_key)->get();
        $users = User::all();
        foreach ($brokers as $broker) {
            return view('admin.edit_broker', compact('broker', 'users'));
        }
    }

    /**
     * Update & Validate data
     */
    public function updateUser(Request $req, $id)
    {
        $user = User::find($id);
        $user->name = $req->name;
        $user->email = $req->email;
        if ($req->password === $req->password_confirmation) {
            $user->password = Hash::make($req->password);
        }
        $user->is_admin = $req->is_admin;
        $user->save();
        return redirect()->route('users')->with('success', 'User has been updated.');
    }

    public function updateBroker(Request $req, $api_key)
    {
        /**
         ** DONE: To upload image with Broker (model) to database
         */
        if (!empty($req->image)) {
            $req->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $original_name = $req->file('image')->getClientOriginalName();
            $changed_name = preg_replace('/\.[^.]+$/', '', $original_name);
            $imageName = $changed_name . '.' . $req->image->extension();
            $req->image->move(storage_path('images/brokers/' . date('Y-m-d') . '/'), $imageName);
            $image = date('Y-m-d') . '/' .  $imageName;
        }

        /**
         * Broker
         */
        $brokers = Broker::where('api_key', $api_key)->get();
        foreach ($brokers as $broker) {
            $broker->name = $req->name;
            $broker->user_id = $req->user_id;
            $broker->image = $image ?? null;
        }
        $broker->save();

        return redirect()->route('brokers')->with('success', 'Broker has been updated.');
    }

    /**
     * Soft Delete data (inactivation)
     */
    public function softDeleteUser(User $id)
    {
        $user = $id;
        $user->delete();
        return redirect()->route('users')->with('success', 'User has been soft deleted.');
    }

    public function softDeleteBroker($api_key)
    {
        $brokers = Broker::where('api_key', $api_key)->get();
        foreach ($brokers as $broker) {
            $broker->delete();
            return redirect()->route('brokers')->with('success', 'Broker has been soft deleted.');
        }
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

                // ClimatControl::where('property_ID', $property->property_info_ID)->delete();
                // Construction::where('property_ID', $property->property_info_ID)->delete();
                // Current::where('property_ID', $property->property_info_ID)->delete();
                // Dimensions::where('property_ID', $property->property_info_ID)->delete();
                // Garages::where('property_ID', $property->property_info_ID)->delete();
                // Gardens::where('property_ID', $property->property_info_ID)->delete();
                // LocalizationInfo::where('property_ID', $property->property_info_ID)->delete();
                // ThirdPartyMedias::where('property_ID', $property->property_info_ID)->delete();
            }
            Property::withTrashed()->where('broker_id', $id)->delete();
        }
        if (Property::onlyTrashed()->where('broker_id', $id)->get()) {
            return redirect()->route('properties')->with('success', 'Properties have been soft deleted.');
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

                // ClimatControl::where('property_ID', $property->property_info_ID)->delete();
                // Construction::where('property_ID', $property->property_info_ID)->delete();
                // Current::where('property_ID', $property->property_info_ID)->delete();
                // Dimensions::where('property_ID', $property->property_info_ID)->delete();
                // Garages::where('property_ID', $property->property_info_ID)->delete();
                // Gardens::where('property_ID', $property->property_info_ID)->delete();
                // LocalizationInfo::where('property_ID', $property->property_info_ID)->delete();
                // ThirdPartyMedias::where('property_ID', $property->property_info_ID)->delete();
            }
            Property::withTrashed()->where('id', $id)->delete();
        }
        if (Property::onlyTrashed()->where('id', $id)->get()) {
            return redirect()->route('properties')->with('success', 'House has been soft deleted.');
        }
    }

    /**
     ** Archives 
     */
    public function archiveDashboard()
    {
        $inActiveUsers = User::onlyTrashed()->get();
        $inActiveBrokers = Broker::onlyTrashed()->get();
        $inActiveProperties = Property::onlyTrashed()->get();
        return view('admin.archives.overview', compact('inActiveUsers', 'inActiveBrokers', 'inActiveProperties'));
    }

    public function archiveDashboard_User()
    {
        if (!empty(DB::table('users')->whereNotNull('deleted_at')->get())) {
            $inActiveUsers = User::onlyTrashed()->get();
        } else {
            $inActiveUsers = array();
        }

        return view('admin.archives.user', compact('inActiveUsers'));
    }

    public function archiveDashboard_Broker()
    {
        $brokers = DB::table('brokers')
            ->select('brokers.user_id', 'users.id', 'brokers.api_key', 'brokers.deleted_at', 'brokers.name', 'users.name as user_name')
            ->join('users', 'brokers.user_id', '=', 'users.id')
            ->whereNotNull('brokers.deleted_at')
            ->get();

        if (!empty($brokers)) {
            $inActiveBrokers = $brokers;
        } else {
            $inActiveBrokers = array();
        }
        return view('admin.archives.broker', compact('inActiveBrokers'));
    }

    public function archiveDashboard_Properties()
    {
        $brokers = Broker::whereNull('blocked_at')->whereNull('deleted_at')->orderByDesc('id')->paginate(24);
        foreach ($brokers as $broker) {
            $getProperties =
                DB::table('brokers')
                ->select('brokers.id', 'properties.broker_id', 'brokers.name', 'properties.deleted_at')
                ->join('properties', 'brokers.id', '=', 'properties.broker_id')
                ->whereNotNull('properties.deleted_at')
                ->where('broker_id', '=', $broker->id)
                ->get();
            $convertProperties = json_decode(json_encode($getProperties));
            if (!empty($convertProperties)) {
                $countProperties = count($convertProperties);
                foreach ($convertProperties as $properties) {
                    $addArrayToProperties = [
                        'id' => $properties->id,
                        'broker_id' => $properties->broker_id,
                        'name' => $properties->name,
                        'count' => $countProperties,
                        'deleted_at' => $properties->deleted_at,
                    ];
                }
                $combineProperties[] = $addArrayToProperties;
                $inActiveProperties = json_decode(json_encode($combineProperties));
            }
        }
        if (!empty($inActiveProperties)) {
            return view('admin.archives.properties', compact('inActiveProperties'));
        } else {
            $inActiveProperties = array();
            return view('admin.archives.properties', compact('inActiveProperties'));
        }
    }

    public function archiveDashboard_Houses()
    {
        $brokers = Broker::whereNull('blocked_at')->whereNull('deleted_at')->orderByDesc('id')->get();
        foreach ($brokers as $broker) {
            $getHouses =
                DB::table('properties')
                ->select(
                    'properties.id',
                    'properties.broker_id as property_broker_id',
                    'brokers.id as broker_id',
                    'brokers.name',
                    'properties.street',
                    'properties.house_number',
                    'properties.zipcode',
                    'properties.city',
                    'properties.deleted_at'
                )
                ->join('brokers', 'properties.broker_id', '=', 'brokers.id')
                ->whereNotNull('properties.deleted_at')
                ->where('properties.broker_id', '=', $broker->id)
                ->get();
            $convertHouses = json_decode(json_encode($getHouses));
            if (!empty($convertHouses)) {
                foreach ($convertHouses as $houses) {
                    $addArrayToHouses[] = [
                        'id' => $houses->id,
                        'broker_id' => $houses->broker_id,
                        'name' => $houses->name,
                        'formatted_address' => $houses->street . ' ' .
                            $houses->house_number . ', ' .
                            $houses->zipcode . ' ' .
                            $houses->city,
                        'deleted_at' => $houses->deleted_at,
                    ];
                }
                $combineHouses = $addArrayToHouses;
                $inActiveHouses = json_decode(json_encode($combineHouses));
            }
        }
        if (!empty($inActiveHouses)) {
            return view('admin.archives.houses', compact('inActiveHouses'));
        } else {
            $inActiveHouses = array();
            return view('admin.archives.houses', compact('inActiveHouses'));
        }
    }

    /**
     ** Done: Restore data in Archives
     */
    public function restoreUser($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();
        return redirect()->route('users')->with('info', 'User has been restored.');
    }

    public function restoreBroker($api_key)
    {
        Broker::onlyTrashed()->where('api_key', $api_key)->restore();
        return redirect()->route('brokers')->with('info', 'Broker has been restored.');
    }

    public function restoreProperties($id)
    {
        if (Property::onlyTrashed()->where('broker_id', $id)->get()) {
            $properties = Property::onlyTrashed()->where('broker_id', $id)->get();
            foreach ($properties as $property) {
                AreaTotals::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                Attachments::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                Counts::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                Descriptions::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                Evaluations::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                Facilities::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                Financials::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                Location::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                LocationDetails::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                Offer::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                PropertyInfo::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                Surroundings::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                Type::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();

                Agency::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                BrokerPerson::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                Department::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();

                // ClimatControl::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                // Construction::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                // Current::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                // Dimensions::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                // Garages::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                // Gardens::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                // LocalizationInfo::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                // ThirdPartyMedias::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
            }
            Property::onlyTrashed()->where('broker_id', $id)->restore();
        }
        if (Property::withTrashed()->where('broker_id', $id)->get()) {
            return redirect()->route('properties')->with('info', 'Properties have been restored.');
        }
    }

    public function restoreHouse($id)
    {
        if (Property::onlyTrashed()->where('id', $id)->get()) {
            $properties = Property::onlyTrashed()->where('id', $id)->get();
            foreach ($properties as $property) {
                AreaTotals::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                Attachments::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                Counts::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                Descriptions::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                Evaluations::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                Facilities::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                Financials::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                Location::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                LocationDetails::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                Offer::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                PropertyInfo::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                Surroundings::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                Type::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();

                Agency::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                BrokerPerson::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                Department::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();

                // ClimatControl::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                // Construction::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                // Current::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                // Dimensions::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                // Garages::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                // Gardens::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                // LocalizationInfo::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
                // ThirdPartyMedias::onlyTrashed()->where('property_ID', $property->property_info_ID)->restore();
            }
            Property::onlyTrashed()->where('id', $id)->restore();
        }
        if (Property::withTrashed()->where('id', $id)->get()) {
            return redirect()->route('properties')->with('info', 'House has been restored.');
        }
    }

    /**
     ** Done: Hard Delete data in Archives
     */
    public function forceDeleteUser($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->forceDelete();
        return redirect()->route('users')->with('success', 'User has been hard deleted.');
    }

    public function forceDeleteBroker($api_key)
    {
        Broker::onlyTrashed()->where('api_key', $api_key)->forceDelete();
        return redirect()->route('brokers')->with('success', 'Broker has been hard deleted.');
    }

    public function forceDeleteProperties($id)
    {
        if (Property::onlyTrashed()->where('broker_id', $id)->get()) {
            $properties = Property::onlyTrashed()->where('broker_id', $id)->get();
            foreach ($properties as $property) {
                AreaTotals::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                Attachments::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                Counts::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                Descriptions::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                Evaluations::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                Facilities::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                Financials::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                Location::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                LocationDetails::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                Offer::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                PropertyInfo::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                Surroundings::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                Type::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();

                Agency::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                BrokerPerson::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                Department::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();

                // ClimatControl::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                // Construction::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                // Current::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                // Dimensions::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                // Garages::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                // Gardens::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                // LocalizationInfo::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                // ThirdPartyMedias::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
            }
            Property::onlyTrashed()->where('broker_id', $id)->forceDelete();
        }
        if (Property::withTrashed()->where('broker_id', $id)->get()) {
            return redirect()->route('properties')->with('success', 'Properties have been hard deleted.');
        }
    }

    public function forceDeleteHouse($id)
    {
        if (Property::onlyTrashed()->where('id', $id)->get()) {
            $properties = Property::onlyTrashed()->where('id', $id)->get();
            foreach ($properties as $property) {
                AreaTotals::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                Attachments::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                Counts::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                Descriptions::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                Evaluations::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                Facilities::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                Financials::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                Location::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                LocationDetails::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                Offer::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                PropertyInfo::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                Surroundings::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                Type::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();

                Agency::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                BrokerPerson::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                Department::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();

                // ClimatControl::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                // Construction::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                // Current::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                // Dimensions::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                // Garages::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                // Gardens::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                // LocalizationInfo::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
                // ThirdPartyMedias::onlyTrashed()->where('property_ID', $property->property_info_ID)->forceDelete();
            }
            Property::onlyTrashed()->where('id', $id)->forceDelete();
        }
        if (Property::withTrashed()->where('id', $id)->get()) {
            return redirect()->route('properties')->with('success', 'House has been hard deleted.');
        }
    }
}
