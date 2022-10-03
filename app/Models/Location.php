<?php

namespace App\Models;

use ZipArchive;
use Carbon\Carbon;
use App\Models\Broker;
use App\Helpers\ArrayHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'address',
        'street_name',
        'house_number',
        'house_number_postfix',
        'zipcode',
        'district',
        'city_name',
        'subregion',
        'region',
        'country_code',
        'floor',
        'floor_number',
        'property_ID',
    ];

    public static function getLocationsFromXMLObjects($api_key)
    {
        /**
         * Broker variables
         */
        $broker = Broker::where('api_key', '=', $api_key)->value('id');
        $broker_id = Broker::find($broker);
        $broker_key = $broker_id->api_key;

        /**
         ** Model: Location
         */
        $locations = new Location();

        /**
         ** Array variables
         */
        $locationsArray = $locations->toArray();
        $totalLocationsArray = [];

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

                    // It locations the number of arrays are in .XML files
                    if (count($propertyData["RealEstateProperty"])) {

                        // Shows which status is "WITHDRAWN"
                        if ($propertyData["RealEstateProperty"]["PropertyInfo"]["Status"] == "WITHDRAWN") {
                            continue;
                        }

                        // It shows all data from RealEstateProperty
                        foreach ($propertyData["RealEstateProperty"] as $key => $pData) {
                            switch ($key) {
                                case "Location":
                                    $totalLocationsArray = [
                                        'street_name' => $pData["Address"]["Streetname"]["Translation"] ?? null,
                                        'house_number' => $pData["Address"]["HouseNumber"] ?? null,
                                        'house_number_postfix' => $pData["Address"]["HouseNumberPostfix"] ?? null,
                                        'zipcode' => $pData["Address"]["PostalCode"] ?? null,
                                        'district' => $pData["Address"]["District"]["Translation"] ?? null,
                                        'city_name' => $pData["Address"]["CityName"]["Translation"] ?? null,
                                        'subregion' => $pData["Address"]["SubRegion"]["Translation"] ?? null,
                                        'region' => $pData["Address"]["Region"]["Translation"] ?? null,
                                        'country_code' => $pData["Address"]["CountryCode"] ?? null,
                                        'floor' => $pData["Floor"] ?? null,
                                        'floor_number' => $pData["FloorNumber"] ?? null,
                                    ];
                                    if (!empty($pData["Address"]["AddressLine1"]["Translation"])) {
                                        $totalLocationsArray["address"] = $pData["Address"]["AddressLine1"]["Translation"] ?? null;
                                    } else {
                                        $totalLocationsArray["address"]  = null;
                                    }
                                    break;
                                case "PropertyInfo":
                                    if (array_key_exists("ID", $pData)) {
                                        $totalLocationsArray["property_ID"] = $pData["ID"];
                                    }
                                    $locationsArray = $totalLocationsArray;
                                    unset($totalLocationsArray);
                                    break;
                            }
                        }
                        $totalLocationsArrays[] = $locationsArray;
                    }
                }
            }

            /**
             ** Done: Add propertyID to $totalLocationsArrays
             */
            foreach ($totalLocationsArrays as $totals) {
                Location::create(
                    [
                        'address' => $totals["address"] ?? null,
                        'street_name' => $totals["street_name"] ?? null,
                        'house_number' => $totals["house_number"] ?? null,
                        'house_number_postfix' => $totals["house_number_postfix"] ?? null,
                        'zipcode' => $totals["zipcode"] ?? null,
                        'district' => $totals["district"] ?? null,
                        'city_name' => $totals["city_name"] ?? null,
                        'subregion' => $totals["subregion"] ?? null,
                        'region' => $totals["region"] ?? null,
                        'country_code' => $totals["region"] ?? null,
                        'floor' => $totals["floor"] ?? null,
                        'floor_number' => $totals["floor_number"] ?? null,
                        'property_ID' => $totals["property_ID"] ?? null,
                    ]
                );
            }
        }
    }
}
