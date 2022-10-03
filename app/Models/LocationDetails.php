<?php

namespace App\Models;

use ZipArchive;
use Carbon\Carbon;
use App\Models\Broker;
use App\Helpers\ArrayHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class LocationDetails extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'administrative_area_level_1',
        'administrative_area_level_1_ID',
        'administrative_area_level_1_short_name',
        'administrative_area_level_2',
        'administrative_area_level_2_ID',
        'administrative_area_level_2_short_name',
        'administrative_area_level_3_ID',
        'latitude',
        'longitude',
        'country_ID',
        'country_name',
        'formatted_address',
        'house_number',
        'house_number_addendum',
        'ISO2_country_code',
        'ISO2_language_code',
        'locality',
        'locality_ID',
        'locality_short_name',
        'zipcode',
        'street_ID',
        'street_name',
        'street_name_short_name',
        'sub_locality',
        'sub_locality_ID',
        'sub_locality_short_name',
        'property_ID',
    ];

    public static function getLocationDetailsFromXMLObjects($api_key)
    {
        /**
         * Broker variables
         */
        $broker = Broker::where('api_key', '=', $api_key)->value('id');
        $broker_id = Broker::find($broker);
        $broker_key = $broker_id->api_key;

        /**
         ** Model: LocationDetails
         */
        $locationDetails = new LocationDetails();

        /**
         ** Array variables
         */
        $locationDetailsArrays = $locationDetails->toArray();
        $totalLocationDetailsArray = [];

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

                    // It locationDetails the number of arrays are in .XML files
                    if (count($propertyData["RealEstateProperty"])) {

                        // Shows which status is "WITHDRAWN"
                        if ($propertyData["RealEstateProperty"]["PropertyInfo"]["Status"] == "WITHDRAWN") {
                            continue;
                        }

                        // It shows all data from RealEstateProperty
                        foreach ($propertyData["RealEstateProperty"] as $key => $pData) {
                            switch ($key) {
                                case "LocationDetails":
                                    $totalLocationDetailsArray = [
                                        'administrative_area_level_1' => $pData["GeoAddressDetails"][0]["AdministrativeAreaLevel1"] ?? null,
                                        'administrative_area_level_1_ID' => $pData["GeoAddressDetails"][0]["AdministrativeAreaLevel1ID"] ?? null,
                                        'administrative_area_level_1_short_name' => $pData["GeoAddressDetails"][0]["AdministrativeAreaLevel1ShortName"] ?? null,
                                        'administrative_area_level_2' => $pData["GeoAddressDetails"][0]["AdministrativeAreaLevel2"] ?? null,
                                        'administrative_area_level_2_ID' => $pData["GeoAddressDetails"][0]["AdministrativeAreaLevel2ID"] ?? null,
                                        'administrative_area_level_2_short_name' => $pData["GeoAddressDetails"][0]["AdministrativeAreaLevel2ShortName"] ?? null,
                                        'administrative_area_level_3_ID' => $pData["GeoAddressDetails"][0]["AdministrativeAreaLevel3ID"] ? $pData["GeoAddressDetails"][0]["AdministrativeAreaLevel3ID"] : null,
                                        'latitude' => $pData["GeoAddressDetails"][0]["Coordinates"]["Latitude"] ?? null,
                                        'longitude' => $pData["GeoAddressDetails"][0]["Coordinates"]["Longitude"] ?? null,
                                        'country_ID' => $pData["GeoAddressDetails"][0]["CountryID"] ?? null,
                                        'country_name' => $pData["GeoAddressDetails"][0]["CountryName"] ?? null,
                                        'formatted_address' => $pData["GeoAddressDetails"][0]["FormattedAddress"] ?? null,
                                        'house_number' => $pData["GeoAddressDetails"][0]["HouseNumber"] ?? null,
                                        'house_number_addendum' => $pData["GeoAddressDetails"][0]["HouseNumberAddendum"] ?? null,
                                        'ISO2_country_code' => $pData["GeoAddressDetails"][0]["ISO2CountryCode"] ?? null,
                                        'ISO2_language_code' => $pData["GeoAddressDetails"][0]["ISO2LanguageCode"] ?? null,
                                        'locality' => $pData["GeoAddressDetails"][0]["Locality"] ?? null,
                                        'locality_ID' => $pData["GeoAddressDetails"][0]["LocalityID"] ?? null,
                                        'locality_short_name' => $pData["GeoAddressDetails"][0]["LocalityShortName"] ?? null,
                                        'zipcode' => $pData["GeoAddressDetails"][0]["PostalCode"] ?? null,
                                        'street_ID' => $pData["GeoAddressDetails"][0]["StreetID"] ?? null,
                                        'street_name' => $pData["GeoAddressDetails"][0]["StreetName"] ?? null,
                                        'street_name_short_name' => $pData["GeoAddressDetails"][0]["StreetNameShortName"] ?? null,
                                        'sub_locality' => $pData["GeoAddressDetails"][0]["Sublocality"] ?? null,
                                        'sub_locality_ID' => $pData["GeoAddressDetails"][0]["SublocalityID"] ? $pData["GeoAddressDetails"][0]["SublocalityID"] : null,
                                        'sub_locality_short_name' => $pData["GeoAddressDetails"][0]["SublocalityShortName"] ?? null,
                                    ];
                                    break;
                                case "PropertyInfo":
                                    if (array_key_exists("ID", $pData)) {
                                        $totalLocationDetailsArray["property_ID"] = $pData["ID"];
                                    }
                                    $locationDetailsArrays = $totalLocationDetailsArray;
                                    unset($totalLocationDetailsArray);
                                    break;
                            }
                        }
                        $totalLocationDetailsArrays[] = $locationDetailsArrays;
                    }
                }
            }

            /**
             ** Done: Add propertyID to $totalLocationDetailsArrays
             */
            foreach ($totalLocationDetailsArrays as $totals) {
                LocationDetails::create(
                    [
                        'administrative_area_level_1' => $totals["administrative_area_level_1"] ?? null,
                        'administrative_area_level_1_ID' => $totals["administrative_area_level_1_ID"] ?? null,
                        'administrative_area_level_1_short_name' => $totals["administrative_area_level_1_short_name"] ?? null,
                        'administrative_area_level_2' => $totals["administrative_area_level_2"] ?? null,
                        'administrative_area_level_2_ID' => $totals["administrative_area_level_2_ID"] ?? null,
                        'administrative_area_level_2_short_name' => $totals["administrative_area_level_2_short_name"] ?? null,
                        'administrative_area_level_3_ID' => $totals["administrative_area_level_3_ID"] ?? null,
                        'latitude' => $totals["latitude"] ?? null,
                        'longitude' => $totals["longitude"] ?? null,
                        'country_ID' => $totals["country_ID"] ?? null,
                        'country_name' => $totals["country_name"] ?? null,
                        'formatted_address' => $totals["formatted_address"] ?? null,
                        'house_number' => $totals["house_number"] ?? null,
                        'house_number_addendum' => $totals["house_number_addendum"] ?? null,
                        'ISO2_country_code' => $totals["ISO2_country_code"] ?? null,
                        'ISO2_language_code' => $totals["ISO2_language_code"] ?? null,
                        'locality' => $totals["locality"] ?? null,
                        'locality_ID' => $totals["locality_ID"] ?? null,
                        'locality_short_name' => $totals["locality_short_name"] ?? null,
                        'zipcode' => $totals["zipcode"] ?? null,
                        'street_ID' => $totals["street_ID"] ?? null,
                        'street_name' => $totals["street_name"] ?? null,
                        'street_name_short_name' => $totals["street_name_short_name"] ?? null,
                        'sub_locality' => $totals["sub_locality"] ?? null,
                        'sub_locality_ID' => $totals["sub_locality_ID"] ?? null,
                        'sub_locality_short_name' => $totals["sub_locality_short_name"] ?? null,
                        'property_ID' => $totals["property_ID"] ?? null,
                    ]
                );
            }
        }
    }
}
