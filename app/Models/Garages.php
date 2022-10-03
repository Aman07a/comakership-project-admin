<?php

namespace App\Models;

use ZipArchive;
use App\Models\Broker;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Garages extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'comments',
        'isolation_types',
        'car_capacity',
        'facilities',
        'type',
        'height',
        'width',
        'length',
        'area',
        'content',
        'property_ID',
    ];

    public static function getGaragesFromXMLObjects($api_key)
    {
        /**
         * Broker variables
         */
        $broker = Broker::where('api_key', '=', $api_key)->value('id');
        $broker_id = Broker::find($broker);
        $broker_key = $broker_id->api_key;

        /**
         ** Model: Garages
         */
        $garages = new Garages();

        /**
         ** Array variables
         */
        $garagesArray = $garages->toArray();
        $totalGaragesArray = [];

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

                        // dump($propertyData["RealEstateProperty"]);

                        // It shows all data from RealEstateProperty
                        foreach ($propertyData["RealEstateProperty"] as $key => $pData) {
                            switch ($key) {
                                case "Garages":
                                    $totalGaragesArray = [
                                        'name' => $pData["Garage"]["Name"] ?? null,
                                        'comments' => $pData["Garage"]["Comments"] ?? null,
                                        'isolation_types' => $pData["Garage"]["IsolationTypes"] ?? null,
                                        'car_capacity' => $pData["Garage"]["CarCapacity"] ?? null,
                                        'facilities' => $pData["Garage"]["Facilities"] ?? null,
                                        'type' => $pData["Garage"]["Type"] ?? null,
                                        'height' => $pData["Garage"]["Dimensions"]["Height"] ?? null,
                                        'width' => $pData["Garage"]["Dimensions"]["Width"] ?? null,
                                        'length' => $pData["Garage"]["Dimensions"]["Length"] ?? null,
                                        'area' => $pData["Garage"]["Dimensions"]["Area"] ?? null,
                                        'content' => $pData["Garage"]["Dimensions"]["Content"] ?? null,
                                    ];
                                    break;
                                case "PropertyInfo":
                                    if (array_key_exists("ID", $pData)) {
                                        $totalGaragesArray["property_ID"] = $pData["ID"];
                                    }
                                    $garagesArray = $totalGaragesArray;
                                    unset($totalGaragesArray);
                                    break;
                            }
                        }
                        $totalGaragesArrays[] = $garagesArray;
                    }
                }
            }

            /**
             ** Done: Add propertyID to $totalGaragesArrays
             */
            foreach ($totalGaragesArrays as $totals) {
                Garages::create(
                    [
                        'name' => $totals["name"] ?? null,
                        'comments' => $totals["comments"] ?? null,
                        'isolation_types' => $totals["isolation_types"] ?? null,
                        'car_capacity' => $totals["car_capacity"] ?? null,
                        'facilities' => $totals["facilities"] ?? null,
                        'type' => $totals["type"] ?? null,
                        'height' => $totals["height"] ?? null,
                        'width' => $totals["width"] ?? null,
                        'length' => $totals["length"] ?? null,
                        'area' => $totals["area"] ?? null,
                        'content' => $totals["content"] ?? null,
                        'property_ID' => $totals["property_ID"] ?? null,
                    ]
                );
            }
        }
    }
}
