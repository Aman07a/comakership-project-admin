<?php

namespace App\Models;

use ZipArchive;
use App\Models\Broker;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gardens extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'quality',
        'type',
        'width',
        'length',
        'area',
        'is_main_garden',
        'orientation',
        'has_backyard_entrance',
        'property_ID',
    ];

    public static function getGardensFromXMLObjects($api_key)
    {
        /**
         * Broker variables
         */
        $broker = Broker::where('api_key', '=', $api_key)->value('id');
        $broker_id = Broker::find($broker);
        $broker_key = $broker_id->api_key;

        /**
         ** Model: Gardens
         */
        $gardens = new Gardens();

        /**
         ** Array variables
         */
        $gardensArray = $gardens->toArray();
        $gardenArray = [];
        $emptyGardens = [];
        $filledGardens = [];

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
                                case "Gardens":
                                    if (array_key_exists("Garden", $pData)) {
                                        if (!array_is_list($pData["Garden"])) {
                                            $gardenArray[] = [
                                                "name" => $pData["Garden"]["Name"] ?? null,
                                                "description" => $pData["Garden"]["Description"] ?? null,
                                                "quality" => $pData["Garden"]["Quality"] ?? null,
                                                "type" => $pData["Garden"]["Type"] ?? null,
                                                "width" => $pData["Garden"]["Dimensions"]["Width"] ?? null,
                                                "length" => $pData["Garden"]["Dimensions"]["Length"] ?? null,
                                                "area" => $pData["Garden"]["Dimensions"]["Area"] ?? null,
                                                "is_main_garden" => $pData["Garden"]["IsMainGarden"] ?? null,
                                                "orientation" => $pData["Garden"]["Orientation"] ?? null,
                                                "has_backyard_entrance" => $pData["Garden"]["HasBackyardEntrance"] ?? null,
                                            ];
                                        }
                                        if (array_is_list($pData["Garden"])) {
                                            foreach ($pData["Garden"] as $element) {
                                                $gardenArray[] = [
                                                    "name" => $element["Name"] ?? null,
                                                    "description" => $element["Description"] ?? null,
                                                    "quality" => $element["Quality"] ?? null,
                                                    "type" => $element["Type"] ?? null,
                                                    "width" => $element["Dimensions"]["Width"] ?? null,
                                                    "length" => $element["Dimensions"]["Length"] ?? null,
                                                    "area" => $element["Dimensions"]["Area"] ?? null,
                                                    "is_main_garden" => $element["IsMainGarden"] ?? null,
                                                    "orientation" => $element["Orientation"] ?? null,
                                                    "has_backyard_entrance" => $element["HasBackyardEntrance"] ?? null,
                                                ];
                                            }
                                        }
                                        $gardensArray = $gardenArray;
                                        unset($gardenArray);
                                    }
                                    break;
                                case "PropertyInfo":
                                    if (array_key_exists("ID", $pData)) {
                                        $gardensArray["property_ID"] = $pData["ID"];
                                    }
                                    break;
                            }
                        }
                        $totalGardensArrays[] = $gardensArray;
                    }
                }
            }

            /**
             ** Done: Add propertyID to $totalGardensArrays
             */
            foreach ($totalGardensArrays as $totals) {
                if (!isset($totals[0])) {
                    Gardens::create([
                        'name' => $totals["name"] ?? null,
                        'description' => $totals["description"] ?? null,
                        'quality' => $totals["quality"] ?? null,
                        'type' => $totals["type"] ?? null,
                        'width' => $totals["width"] ?? null,
                        'length' => $totals["length"] ?? null,
                        'area' => $totals["area"] ?? null,
                        'is_main_garden' => $totals["is_main_garden"] ?? null,
                        'orientation' => $totals["orientation"] ?? null,
                        'has_backyard_entrance' => $totals["has_backyard_entrance"] ?? null,
                        'property_ID' => $totals["property_ID"] ?? null,
                    ]);
                }

                if (isset($totals[0])) {
                    $property = array(
                        'property_ID' => $totals["property_ID"],
                    );
                    unset($totals["property_ID"]);

                    foreach ($totals as $element) {
                        Gardens::create([
                            'name' => $element["name"] ?? null,
                            'description' => $element["description"] ?? null,
                            'quality' => $element["quality"] ?? null,
                            'type' => $element["type"] ?? null,
                            'width' => $element["width"] ?? null,
                            'length' => $element["length"] ?? null,
                            'area' => $element["area"] ?? null,
                            'is_main_garden' => $element["is_main_garden"] ?? null,
                            'orientation' => $element["orientation"] ?? null,
                            'has_backyard_entrance' => $element["has_backyard_entrance"] ?? null,
                            'property_ID' => $property["property_ID"],
                        ]);
                    }
                }
            }
        }
    }
}
