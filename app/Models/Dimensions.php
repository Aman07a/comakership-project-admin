<?php

namespace App\Models;

use ZipArchive;
use App\Models\Broker;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dimensions extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'width',
        'length',
        'area',
        'content',
        'property_ID',
    ];

    public static function getDimensionsFromXMLObjects($api_key)
    {
        /**
         * Broker variables
         */
        $broker = Broker::where('api_key', '=', $api_key)->value('id');
        $broker_id = Broker::find($broker);
        $broker_key = $broker_id->api_key;

        /**
         ** Model: Dimensions
         */
        $dimensions = new Dimensions();

        /**
         ** Array variables
         */
        $dimensionsArray = $dimensions->toArray();
        $totalDimensionsArray = [];

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
                                case "Dimensions":
                                    $totalDimensionsArray = [
                                        'width' => $pData["Land"]["Width"] ?? null,
                                        'length' => $pData["Land"]["Length"] ?? null,
                                        'area' => $pData["Land"]["Area"] ?? null,
                                        'content' => $pData["Content"] ?? null,
                                    ];
                                    break;
                                case "PropertyInfo":
                                    if (array_key_exists("ID", $pData)) {
                                        $totalDimensionsArray["property_ID"] = $pData["ID"];
                                    }
                                    $dimensionsArray = $totalDimensionsArray;
                                    unset($totalDimensionsArray);
                                    break;
                            }
                        }
                        $totalDimensionsArrays[] = $dimensionsArray;
                    }
                }
            }

            /**
             ** Done: Add propertyID to $totalDimensionsArrays
             */
            foreach ($totalDimensionsArrays as $totals) {
                Dimensions::create(
                    [
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
