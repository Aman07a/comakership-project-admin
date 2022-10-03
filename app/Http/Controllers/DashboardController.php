<?php

namespace App\Http\Controllers;

use ZipArchive;
use App\Models\Type;
use App\Models\Offer;
use App\Models\Broker;
use App\Models\Counts;
use App\Models\Location;
use App\Models\Property;
use App\Models\AreaTotals;
use App\Models\Facilities;
use App\Helpers\APIHelpers;
use App\Models\Evaluations;
use App\Models\PropertyInfo;
use Illuminate\Http\Request;
use App\Models\LocationDetails;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Console\Commands\DailyCronjob;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\SaveBrokerRequest;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function dashboard(Request $req)
    {
        if (Auth::check()) {
            return view('dashboard');
        }
        return redirect()->route('login')->with('success', 'You are not allowed to access.');
    }

    public function store(Request $req)
    {

        $broker = new Broker;
        $property = new Property;

        $broker->name = $req->name;
        $broker->api_key = $req->api_key;

        $broker->save();

        $path = Storage::path('public/properties.zip');
        $zip = new ZipArchive;

        if ($zip->open($path) !== true) {
            echo '<p>Can\'t open zip archive!</p>';
            return false;
        }

        // for( $i = 0; $i < $zip->numFiles; $i++ ){
        //     $stat = $zip->statIndex( $i );
        //     var_dump( basename( $stat['name'] ) . PHP_EOL );
        // }


        for ($idx = 0; $path = $zip->statIndex($idx); $idx++) {
            $directory = \dirname($path['name']);
            if (!\is_dir($path['name'])) {
                // file contents
                $contents = $zip->getFromIndex($idx);
                $xmlObject = simplexml_load_string($contents, "SimpleXMLElement", LIBXML_NOCDATA);
                $json = json_encode($xmlObject, JSON_PRETTY_PRINT);
                $propertyData = json_decode($json, true);

                if (count($propertyData['RealEstateProperty']) > 0) {

                    $dataArray = array();
                    $attachments = array();

                    foreach ($propertyData['RealEstateProperty'] as $k => $data) {

                        // dump($k);
                        if ($k === "AreaTotals") {
                            $dataArray[$k] = [
                                "EffectiveArea" => $data['EffectiveArea'] ?? null,
                            ];
                        }

                        if ($k === "Attachments") {
                            $dataArray[$k] = [
                                'Attachment' => $data['Attachment'] ?? null,
                            ];
                        }

                        if ($k === "Cadastre") {

                            $dataArray[$k] = [
                                'CadastrallInformations' => $data['CadastrallInformations'] ?? null,
                                'CadastrallDescription' => $data['CadastrallDescription'] ?? null,
                            ];
                        }

                        if ($k === "ClimatControl") {

                            $dataArray[$k] = [
                                'EnergyCertificate' => $data['EnergyCertificate'] ?? null,
                                'Heating' => $data['Heating'] ?? null,
                                'Ventilation' => $data['Ventilation'] ?? null,
                            ];
                        }

                        if ($k === "Construction") {

                            $dataArray[$k] = [
                                'ConstructionPeriod' => $data['ConstructionPeriod'] ?? null,
                                'ConstructionOptions' => $data['ConstructionOptions'] ?? null,
                                'ConstructionYearFrom' => $data['ConstructionYearFrom'] ?? null,
                                'ConstructionYearTo' => $data['ConstructionYearTo'] ?? null,
                                'ConstructionYearTo' => $data['ConstructionYearTo'] ?? null,
                                'IsNewEstate' => $data['IsNewEstate'] ?? null,
                                'Windows' => $data['Windows'] ?? null,
                            ];
                        }

                        if ($k === "Contact") {

                            $dataArray[$k] = [
                                'Agency' => $data['Agency'] ?? null,
                                'Department' => $data['Department'] ?? null,
                                'Person' => $data['Person'] ?? null,
                            ];
                        }

                        if ($k === "Counts") {

                            $dataArray[$k] = [
                                'CountOfBathrooms' => $data['CountOfBathrooms'] ?? null,
                                'CountOfFloors' => $data['CountOfFloors'] ?? null,
                                'CountOfKitchens' => $data['CountOfKitchens'] ?? null,
                                'CountOfRooms' => $data['CountOfRooms'] ?? null,
                            ];
                        }

                        if ($k === "Current") {

                            $dataArray[$k] = [
                                'ForTakeOverItems' => $data['ForTakeOverItems'] ?? null,
                                'Pavement' => $data['Pavement'] ?? null,
                                'PercentageRented' => $data['PercentageRented'] ?? null,
                                'SectorTypes' => $data['SectorTypes'] ?? null,
                            ];
                        }

                        if ($k === "Descriptions") {

                            $dataArray[$k] = [
                                'AdText' => $data['AdText'] ?? null,
                            ];
                        }

                        if ($k === "Dimensions") {
                            $dataArray[$k] = [
                                'Content' => $data['Content'] ?? null,
                                'Land' => $data['Land'] ?? null,
                            ];
                        }

                        if ($k === "Evaluations") {
                            $dataArray[$k] = [
                                'CommunalAreas' => $data['CommunalAreas'] ?? null,
                                'SecurityMeasures' => $data['SecurityMeasures'] ?? null,
                                'ComfortQuality' => $data['ComfortQuality'] ?? null,
                            ];
                        }

                        if ($k === "Facilities") {
                            $dataArray[$k] = [
                                'AirTreatments' => $data['AirTreatments'] ?? null,
                                'AirTreatmentsOffice' => $data['AirTreatmentsOffice'] ?? null,
                                'Balcony' => $data['Balcony'] ?? null,
                                'CompanyListings' => $data['CompanyListings'] ?? null,
                                'Electricity' => $data['Electricity'] ?? null,
                                'Fencing' => $data['Fencing'] ?? null,
                                'FirePlace' => $data['FirePlace'] ?? null,
                                'Garden' => $data['Garden'] ?? null,
                                'HorseTroughIndoor' => $data['HorseTroughIndoor'] ?? null,
                                'HorseTroughOutdoor' => $data['HorseTroughOutdoor'] ?? null,
                                'HorseTroughDrainage' => $data['HorseTroughDrainage'] ?? null,
                                'HorseWalker' => $data['HorseWalker'] ?? null,
                                'IndustrialFacilities' => $data['IndustrialFacilities'] ?? null,
                                'Installations' => $data['Installations'] ?? null,
                                'InternetConnection' => $data['InternetConnection'] ?? null,
                                'LeisureFacilities' => $data['LeisureFacilities'] ?? null,
                                'LocalSewer' => $data['LocalSewer'] ?? null,
                                'MilkingSystemTypes' => $data['MilkingSystemTypes'] ?? null,
                                'Office' => $data['Office'] ?? null,
                                'OfficeFacilities' => $data['OfficeFacilities'] ?? null,
                                'OfficeFacilitiesOffice' => $data['OfficeFacilitiesOffice'] ?? null,
                                'ParkingTypes' => $data['ParkingTypes'] ?? null,
                                'PhoneLine' => $data['PhoneLine'] ?? null,
                                'PoultryHousing' => $data['PoultryHousing'] ?? null,
                                'SewerConnection' => $data['SewerConnection'] ?? null,
                                'SocialPropertyFacilities' => $data['SocialPropertyFacilities'] ?? null,
                                'Structures' => $data['Structures'] ?? null,
                                'Terrain' => $data['Terrain'] ?? null,
                                'Upholstered' => $data['Upholstered'] ?? null,
                                'UpholsteredType' => $data['UpholsteredType'] ?? null,
                            ];
                        }

                        if ($k === "Financials") {
                            $dataArray[$k] = [
                                'Commissions' => $data['Commissions'] ?? null,
                                'Deposit' => $data['Deposit'] ?? null,
                                'Indications' => $data['Indications'] ?? null,
                                'RentPrice' => $data['RentPrice'] ?? null,
                                'RentPriceType' => $data['RentPriceType'] ?? null,
                                'RentSpecification' => $data['RentSpecification'] ?? null,
                                'ServiceCosts' => $data['ServiceCosts'] ?? null,
                                'PriceCode' => $data['PriceCode'] ?? null,
                            ];
                        }

                        if ($k === "Floors") {
                            $dataArray[$k] = [
                                'Floor' => $data['Floor'] ?? null,
                            ];
                        }

                        if ($k === "Legal") {
                            $dataArray[$k] = [
                                'BusinessRights' => $data['BusinessRights'] ?? null,
                                'ProductionRights' => $data['ProductionRights'] ?? null,
                            ];
                        }

                        if ($k === "LocalizationInfo") {
                            $dataArray[$k] = [
                                'Culture' => $data['Culture'] ?? null,
                                'Currency' => $data['Currency'] ?? null,
                                'Language' => $data['Language'] ?? null,
                            ];
                        }

                        if ($k === "Location") {
                            $dataArray[$k] = [
                                'Floor' => $data['Floor'] ?? null,
                                'FloorNumber' => $data['FloorNumber'] ?? null,
                                'Address' => $data['Address'] ?? null,
                            ];
                        }

                        if ($k === "LocationDetails") {
                            $dataArray[$k] = [
                                'GeoAddressDetails' => $data['GeoAddressDetails'] ?? null,
                            ];
                        }

                        if ($k === "Offer") {
                            $dataArray[$k] = [
                                'Acceptance' => $data['Acceptance'] ?? null,
                                'AcceptanceDate' => $data['AcceptanceDate'] ?? null,
                                'AcceptanceDescription' => $data['AcceptanceDescription'] ?? null,
                                'IsForRent' => $data['IsForRent'] ?? null,
                                'IsIncentive' => $data['IsIncentive'] ?? null,
                                'LinkedObject' => $data['LinkedObject'] ?? null,
                            ];
                        }

                        if ($k === "PropertyInfo") {
                            $dataArray[$k] = [
                                'CreationDateTime' => $data['CreationDateTime'] ?? null,
                                'ForeignAgencyID' => $data['ForeignAgencyID'] ?? null,
                                'ForeignID' => $data['ForeignID'] ?? null,
                                'ID' => $data['ID'] ?? null,
                                'MandateDate' => $data['MandateDate'] ?? null,
                                'ModificationDateTime' => $data['ModificationDateTime'] ?? null,
                                'Origin' => $data['Origin'] ?? null,
                                'Status' => $data['Status'] ?? null,
                                'ExclusiveStatus' => $data['ExclusiveStatus'] ?? null,
                            ];
                        }

                        if ($k === "Surroundings") {
                            $dataArray[$k] = [
                                'Location' => $data['Location'] ?? null,
                            ];
                        }

                        if ($k === "ThirdPartyMedias") {
                            $dataArray[$k] = [];
                        }

                        if ($k === "Type") {
                            $dataArray[$k] = [
                                'IsResidential' => $data['IsResidential'] ?? null,
                                'ForPermanentResidence' => $data['ForPermanentResidence'] ?? null,
                                'PropertyTypes' => $data['PropertyTypes'] ?? null,
                                'ForeignPropertyTags' => $data['ForeignPropertyTags'] ?? null,
                            ];
                        }
                    }
                    dump($dataArray);
                }
            }
        }

        foreach ($propertyData as $data) {
            //api key for each field for now
            // dump($data);
            $property->api_key = $req->api_key;
            // $property->effective_area = $data['AreaTotals']['EffectiveArea'];

            $property->save();
        }

        $zip->close();
    }

    /**
     * Add data
     */
    public function addBroker()
    {
        return view('client/add_broker');
    }

    /**
     * Store data & Request data
     */
    public function storeBroker(SaveBrokerRequest $req)
    {
        /**
         * Validate: image for broker
         */
        $req->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $original_name = $req->file('image')->getClientOriginalName();
        $changed_name = preg_replace('/\.[^.]+$/', '', $original_name);
        $imageName = $changed_name . '.' . $req->image->extension();
        $req->image->move(storage_path('images/brokers/' . date('Y-m-d') . '/'), $imageName);
        $image = date('Y-m-d') . '/' . $imageName;

        /**
         * Doing: Saving API data to Broker()
         */
        $broker = new Broker();
        $broker->name = $req->name;
        $broker->api_key = $req->api_key;
        $broker->user_id = Auth::id();
        $broker->image = $image;

        /**
         ** Environment (.ENV) Variables
         */
        $api_http = env('API_HTTP_URL');
        $api_version = env('API_VERSION');
        $api_zip = env('API_ZIP_URL');

        /**
         ** Development: Test Variables
         */
        $api_http_development = "http://development.kolibri24.com/";
        $api_files = "files";
        $dev_api_key = env('DEV_API_KEY');

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

        /**
         * DONE: Check if .ZIP File Exists
         */
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
                    return redirect()->route('user.brokers')->with('success', 'Broker has been overwritten and saved.');
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
            // $url = $api_http_development . $api_files . '/' . $dev_api_key . '.' . $api_zip;
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
             * Activate Schedule
             */
            // Artisan::call('schedule:run');
            /**
             ** Redirecting the route to Broker Overview
             */
            return redirect()->route('user.brokers')->with('success', 'Broker has been saved.');
        }
    }


    /**
     * Edit data
     */
    public function editBroker($api_key)
    {
        $brokers = Broker::where('api_key', $api_key)->get();
        foreach ($brokers as $broker) {
            return view('client.edit_broker', compact('broker'));
        }
    }

    /**
     * Update & Validate data
     */
    public function updateBroker(Request $req, $api_key)
    {
        /**
         ** DONE: To upload image with Broker (model) to database
         */
        $req->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $original_name = $req->file('image')->getClientOriginalName();
        $changed_name = preg_replace('/\.[^.]+$/', '', $original_name);
        $imageName = $changed_name . '.' . $req->image->extension();
        $req->image->move(storage_path('images/brokers/' . date('Y-m-d') . '/'), $imageName);
        $image = date('Y-m-d') . '/' .  $imageName;

        /**
         * Broker
         */
        $brokers = Broker::where('api_key', $api_key)->get();
        foreach ($brokers as $broker) {
            $broker->name = $req->name;
            $broker->image = $image;
        }
        $broker->save();

        return redirect()->route('user.brokers')->with('success', 'Broker has been updated.');
    }
}
