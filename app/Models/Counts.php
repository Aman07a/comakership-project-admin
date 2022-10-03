<?php

namespace App\Models;

use ZipArchive;
use Carbon\Carbon;
use App\Models\Broker;
use App\Helpers\ArrayHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Counts extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'count_of_floors',
        'count_of_garages',
        'count_of_rooms',
        'count_of_bedrooms',
        'count_of_bathrooms',
        'count_of_toilettes',
        'count_of_gardens',
        'count_of_moorings_cattles',
        'count_of_moorings_dairy_cattles',
        'count_of_garage_places',
        'property_ID',
    ];

    public static function getCountsFromXMLObjects($api_key)
    {
        /**
         * Broker variables
         */
        $broker = Broker::where('api_key', '=', $api_key)->value('id');
        $broker_id = Broker::find($broker);
        $broker_key = $broker_id->api_key;

        /**
         ** Model: Counts
         */
        $counts = new Counts();

        /**
         ** Array variables
         */
        $countsArray = $counts->toArray();
        $totalCountArray = [];

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
                                case "Counts":
                                    $totalCountArray = [
                                        'count_of_floors' => $pData["CountOfFloors"] ?? null,
                                        'count_of_garages' => $pData["CountOfGarages"] ?? null,
                                        'count_of_rooms' => $pData["CountOfRooms"] ?? null,
                                        'count_of_bedrooms' => $pData["CountOfBedrooms"] ?? null,
                                        'count_of_bathrooms' => $pData["CountOfBathrooms"] ?? null,
                                        'count_of_toilettes' => $pData["CountOfToilettes"] ?? null,
                                        'count_of_gardens' => $pData["CountOfGardens"] ?? null,
                                        'count_of_moorings_cattles' => $pData["CountOfMooringsCattles"] ?? null,
                                        'count_of_moorings_dairy_cattles' => $pData["CountOfMooringsDairyCattles"] ?? null,
                                        'count_of_garage_places' => $pData["CountOfGaragePlaces"] ?? null,
                                    ];
                                    break;
                                case "PropertyInfo":
                                    if (array_key_exists("ID", $pData)) {
                                        $totalCountArray["property_ID"] = $pData["ID"];
                                    }
                                    $countsArray = $totalCountArray;
                                    unset($totalCountArray);
                                    break;
                            }
                        }
                        $totalCountsArrays[] = $countsArray;
                    }
                }
            }

            /**
             ** Done: Add propertyID to $totalCountsArrays
             */
            foreach ($totalCountsArrays as $totals) {
                Counts::create(
                    [
                        "count_of_floors" => $totals["count_of_floors"] ?? null,
                        "count_of_garages" => $totals["count_of_garages"] ?? null,
                        "count_of_rooms" => $totals["count_of_rooms"] ?? null,
                        "count_of_bedrooms" => $totals["count_of_bedrooms"] ?? null,
                        "count_of_bathrooms" => $totals["count_of_bathrooms"] ?? null,
                        "count_of_toilettes" => $totals["count_of_toilettes"] ?? null,
                        "count_of_gardens" => $totals["count_of_gardens"] ?? null,
                        "count_of_moorings_cattles" => $totals["count_of_moorings_cattles"] ?? null,
                        "count_of_moorings_dairy_cattles" => $totals["count_of_moorings_dairy_cattles"] ?? null,
                        "count_of_garage_places" => $totals["count_of_garage_places"] ?? null,
                        "property_ID" => $totals["property_ID"],
                    ]
                );
            }
        }
    }
}
