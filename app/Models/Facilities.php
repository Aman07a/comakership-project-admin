<?php

namespace App\Models;

use ZipArchive;
use Carbon\Carbon;
use App\Models\Broker;
use App\Helpers\ArrayHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facilities extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'air_treatments',
        'air_treatments_office',
        'balcony',
        'company_listings',
        'electricity',
        'fencing',
        'fire_place',
        'garage',
        'garden',
        'horse_trough_indoor',
        'horse_trough_outdoor',
        'horse_trough_drainage',
        'horse_walker',
        'industrial_facilities',
        'installations',
        'internet_connection',
        'leisure_facilities',
        'local_Sewer',
        'milking_System_Types',
        'office',
        'office_facilities',
        'office_facilities_office',
        'parking_types',
        'phone_line',
        'poultry_housing',
        'sewer_connection',
        'social_property_facilities',
        'structures',
        'terrain',
        'drainage',
        'sanitation_lock',
        'open_porch',
        'tank',
        'house',
        'storage_room',
        'upholstered',
        'upholstered_type',
        'ventilation',
        'alarm',
        'roller_blinds',
        'cable_TV',
        'outdoor_awnings',
        'swimming_pool',
        'elevator',
        'airco',
        'windmill',
        'sun_collectors',
        'satellite_dish',
        'jacuzzi',
        'steam_cabin',
        'flue_tube',
        'sliding_doors',
        'french_balcony',
        'sky_light',
        'sauna',
        'property_ID',
    ];

    public static function getFacilitiesFromXMLObjects($api_key)
    {
        /**
         * Broker variables
         */
        $broker = Broker::where('api_key', '=', $api_key)->value('id');
        $broker_id = Broker::find($broker);
        $broker_key = $broker_id->api_key;

        /**
         ** Model: Facilities
         */
        $facilities = new Facilities();

        /**
         ** Array variables
         */
        $facilitiesArray = $facilities->toArray();
        $totalFacilitiesArray = [];

        // Create new zip object
        $zip = new ZipArchive();
        // Store the public path
        $publicDir = public_path();
        // Define the file name. Give it a unique name to avoid overriding.
        $zipFileName = "$broker_key.zip";
        // Define the file path
        $path = $publicDir . '/documents/temp/' . $zipFileName;

        // Check if the ZipArchive can't open the ZIP File
        if ($zip->open($path) != true) {
            echo '<p>Can\'t open zip archive!</p>';
            return false;
        }

        // Check if ZIP file already exists
        if (file_exists($path)) {

            for ($idx = 0; $path = $zip->statIndex($idx); $idx++) {
                /**
                 ** Shows the name of the xml file from $variable.zip
                 ** is_dir: Tells whether the filename is a directory
                 */
                if (!is_dir($path['name'])) {
                    // getFromIndex — Returns the entry contents using its index
                    $contents = $zip->getFromIndex($idx);
                    // simplexml_load_string — Interprets a string of XML into an object
                    $xmlObject = simplexml_load_string($contents, "SimpleXMLElement", LIBXML_NOCDATA);
                    // json_encode — Returns the JSON representation of a value
                    $json = json_encode($xmlObject, JSON_PRETTY_PRINT);
                    // Takes a JSON encoded string and converts it into a PHP variable.
                    $propertyData = json_decode($json, true);

                    // It counts the number of arrays are in .XML files
                    if (count($propertyData["RealEstateProperty"])) {

                        // Shows which status is "WITHDRAWN"
                        if ($propertyData["RealEstateProperty"]["PropertyInfo"]["Status"] == "WITHDRAWN") {
                            continue;
                        }

                        // It shows all data from RealEstateProperty
                        foreach ($propertyData["RealEstateProperty"] as $key => $pData) {
                            switch ($key) {
                                case "Facilities":
                                    $totalFacilitiesArray = [
                                        'air_treatments' => $pData["AirTreatments"]["Available"] ?? null,
                                        'air_treatments_office' => $pData["AirTreatmentsOffice"]["Available"] ?? null,
                                        'balcony' => $pData["Balcony"]["Available"] ?? null,
                                        'company_listings' => $pData["CompanyListings"]["Available"] ?? null,
                                        'electricity' => $pData["Electricity"]["Available"] ?? null,
                                        'fencing' => $pData["Fencing"]["Available"] ?? null,
                                        'fire_place' => $pData["FirePlace"]["Available"] ?? null,
                                        'garage' => $pData["Garage"]["Available"] ?? null,
                                        'garden' => $pData["Garden"]["Available"] ?? null,
                                        'horse_trough_indoor' => $pData["HorseTroughIndoor"]["Available"] ?? null,
                                        'horse_trough_outdoor' => $pData["HorseTroughOutdoor"]["Available"] ?? null,
                                        'horse_trough_drainage' => $pData["HorseTroughDrainage"]["Available"] ?? null,
                                        'horse_walker' => $pData["HorseWalker"]["Available"] ?? null,
                                        'industrial_facilities' => $pData["IndustrialFacilities"]["Available"] ?? null,
                                        'installations' => $pData["Installations"]["Available"] ?? null,
                                        'internet_connection' => $pData["InternetConnection"]["Available"] ?? null,
                                        'leisure_facilities' => $pData["LeisureFacilities"]["Available"] ?? null,
                                        'local_sewer' => $pData["LocalSewer"]["Available"] ?? null,
                                        'milking_System_Types' => $pData["MilkingSystemTypes"]["Available"] ?? null,
                                        'office' => $pData["Office"]["Available"] ?? null,
                                        'office_facilities' => $pData["OfficeFacilities"]["Available"] ?? null,
                                        'office_facilities_office' => $pData["OfficeFacilitiesOffice"]["Available"] ?? null,
                                        'parking_types' => $pData["ParkingTypes"]["Available"] ?? null,
                                        'phone_line' => $pData["PhoneLine"]["Available"] ?? null,
                                        'poultry_housing' => $pData["PoultryHousing"]["Available"] ?? null,
                                        'sewer_connection' => $pData["SewerConnection"]["Available"] ?? null,
                                        'social_property_facilities' => $pData["SocialPropertyFacilities"]["Available"] ?? null,
                                        'structures' => $pData["Structures"]["Available"] ?? null,
                                        'terrain' => $pData["Terrain"]["Available"] ?? null,
                                        'drainage' => $pData["Drainage"]["Available"] ?? null,
                                        'sanitation_lock' => $pData["SanitationLock"]["Available"] ?? null,
                                        'open_porch' => $pData["OpenPorch"]["Available"] ?? null,
                                        'tank' => $pData["Tank"]["Available"] ?? null,
                                        'house' => $pData["House"]["Available"] ?? null,
                                        'storage_room' => $pData["StorageRoom"]["Available"] ?? null,
                                        'upholstered' => $pData["Upholstered"]["Available"] ?? null,
                                        'upholstered_type' => $pData["UpholsteredType"]["Available"] ?? null,
                                        'ventilation' => $pData["Ventilation"]["Available"] ?? null,
                                        'alarm' => $pData["Alarm"]["Available"] ?? null,
                                        'roller_blinds' => $pData["RollerBlinds"]["Available"] ?? null,
                                        'cable_TV' => $pData["Cable_TV"]["Available"] ?? null,
                                        'outdoor_awnings' => $pData["OutdoorAwnings"]["Available"] ?? null,
                                        'swimming_pool' => $pData["SwimmingPool"]["Available"] ?? null,
                                        'elevator' => $pData["Elevator"]["Available"] ?? null,
                                        'airco' => $pData["Airco"]["Available"] ?? null,
                                        'windmill' => $pData["Windmill"]["Available"] ?? null,
                                        'sun_collectors' => $pData["SunCollectors"]["Available"] ?? null,
                                        'satellite_dish' => $pData["SatelliteDish"]["Available"] ?? null,
                                        'jacuzzi' => $pData["Jacuzzi"]["Available"] ?? null,
                                        'steam_cabin' => $pData["SteamCabin"]["Available"] ?? null,
                                        'flue_tube' => $pData["FlueTube"]["Available"] ?? null,
                                        'sliding_doors' => $pData["SlidingDoors"]["Available"] ?? null,
                                        'french_balcony' => $pData["FrenchBalcony"]["Available"] ?? null,
                                        'sky_light' => $pData["SkyLight"]["Available"] ?? null,
                                        'sauna' => $pData["Sauna"]["Available"] ?? null,
                                    ];
                                    break;
                                case "PropertyInfo":
                                    if (array_key_exists("ID", $pData)) {
                                        $totalFacilitiesArray["property_ID"] = $pData["ID"];
                                    }
                                    $facilitiesArray = $totalFacilitiesArray;
                                    unset($totalFacilitiesArray);
                                    break;
                            }
                        }
                        $totalFacilitiesArrays[] = $facilitiesArray;
                    }
                }
            }

            /**
             ** Done: Add propertyID to $totalFacilitiesArrays
             */
            foreach ($totalFacilitiesArrays as $totals) {
                Facilities::create(
                    [
                        'air_treatments' => $totals["air_treatments"] ?? null,
                        'air_treatments_office' => $totals["air_treatments_office"] ?? null,
                        'balcony' => $totals["balcony"] ?? null,
                        'company_listings' => $totals["company_listings"] ?? null,
                        'electricity' => $totals["electricity"] ?? null,
                        'fencing' => $totals["fencing"] ?? null,
                        'fire_place' => $totals["fire_place"] ?? null,
                        'garage' => $totals["garage"] ?? null,
                        'garden' => $totals["garden"] ?? null,
                        'horse_trough_indoor' => $totals["horse_trough_indoor"] ?? null,
                        'horse_trough_outdoor' => $totals["horse_trough_outdoor"] ?? null,
                        'horse_trough_drainage' => $totals["horse_trough_drainage"] ?? null,
                        'horse_walker' => $totals["horse_walker"] ?? null,
                        'industrial_facilities' => $totals["industrial_facilities"] ?? null,
                        'installations' => $totals["installations"] ?? null,
                        'internet_connection' => $totals["internet_connection"] ?? null,
                        'leisure_facilities' => $totals["leisure_facilities"] ?? null,
                        'local_sewer' => $totals["local_Sewer"] ?? null,
                        'milking_system_types' => $totals["milking_System_Types"] ?? null,
                        'office' => $totals["office"] ?? null,
                        'office_facilities' => $totals["office_facilities"] ?? null,
                        'office_facilities_office' => $totals["office_facilities_office"] ?? null,
                        'parking_types' => $totals["parking_types"] ?? null,
                        'phone_line' => $totals["phone_line"] ?? null,
                        'poultry_housing' => $totals["poultry_housing"] ?? null,
                        'sewer_connection' => $totals["sewer_connection"] ?? null,
                        'social_property_facilities' => $totals["social_property_facilities"] ?? null,
                        'structures' => $totals["structures"] ?? null,
                        'terrain' => $totals["terrain"] ?? null,
                        'drainage' => $totals["drainage"] ?? null,
                        'sanitation_lock' => $totals["sanitation_lock"] ?? null,
                        'open_porch' => $totals["open_porch"] ?? null,
                        'tank' => $totals["tank"] ?? null,
                        'house' => $totals["house"] ?? null,
                        'storage_room' => $totals["storage_room"] ?? null,
                        'upholstered' => $totals["upholstered"] ?? null,
                        'upholstered_type' => $totals["upholstered_type"] ?? null,
                        'ventilation' => $totals["ventilation"] ?? null,
                        'alarm' => $totals["alarm"] ?? null,
                        'roller_blinds' => $totals["roller_blinds"] ?? null,
                        'cable_TV' => $totals["cable_TV"] ?? null,
                        'outdoor_awnings' => $totals["outdoor_awnings"] ?? null,
                        'swimming_pool' => $totals["swimming_pool"] ?? null,
                        'elevator' => $totals["elevator"] ?? null,
                        'airco' => $totals["airco"] ?? null,
                        'windmill' => $totals["windmill"] ?? null,
                        'sun_collectors' => $totals["sun_collectors"] ?? null,
                        'satellite_dish' => $totals["satellite_dish"] ?? null,
                        'jacuzzi' => $totals["jacuzzi"] ?? null,
                        'steam_cabin' => $totals["steam_cabin"] ?? null,
                        'flue_tube' => $totals["flue_tube"] ?? null,
                        'sliding_doors' => $totals["sliding_doors"] ?? null,
                        'french_balcony' => $totals["french_balcony"] ?? null,
                        'sky_light' => $totals["sky_light"] ?? null,
                        'sauna' => $totals["sauna"] ?? null,
                        'property_ID' => $totals["property_ID"] ?? null,
                    ]
                );
            }
        }
    }
}
