<?php

namespace App\Models;

use ZipArchive;
use App\Models\Broker;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Construction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'is_ready_for_construction',
        'construction_period',
        'construction_year_from',
        'construction_year_to',
        'is_under_construction',
        'construction_comment',
        'roof_type',
        'roof_materials',
        'roof_comments',
        'isolation_types',
        'is_new_estate',
        'construction_options',
        'windows',
        'property_ID',
    ];

    public static function getConstructionsFromXMLObjects($api_key)
    {
        /**
         * Broker variables
         */
        $broker = Broker::where('api_key', '=', $api_key)->value('id');
        $broker_id = Broker::find($broker);
        $broker_key = $broker_id->api_key;

        /**
         ** Model: Construction
         */
        $constructions = new Construction();

        /**
         ** Array variables
         */
        $constructionsArray = $constructions->toArray();
        $totalConstructionArray = [];

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
                                case "Construction":
                                    $totalConstructionArray = [
                                        'is_ready_for_construction' => $pData["IsReadyForConstruction"] ?? null,
                                        'construction_period' => $pData["ConstructionPeriod"] ?? null,
                                        'construction_year_from' => $pData["ConstructionYearFrom"] ?? null,
                                        'construction_year_to' => $pData["ConstructionYearTo"] ?? null,
                                        'is_under_construction' => $pData["IsUnderConstruction"] ?? null,
                                        'construction_comment' => $pData["ConstructionComment"] ?? null,
                                        'roof_type' => $pData["RoofType"] ?? null,
                                        'roof_materials' => $pData["RoofMaterials"] ?? null,
                                        'roof_comments' => $pData["RoofComments"] ?? null,
                                        'isolation_types' => $pData["IsolationTypes"] ?? null,
                                        'is_new_estate' => $pData["IsNewEstate"] ?? null,
                                    ];

                                    if (isset($pData["ConstructionOptions"]) && is_array($pData["ConstructionOptions"]) && !empty($pData["ConstructionOptions"])) {
                                        $totalConstructionArray["construction_options"] = $pData["ConstructionOptions"];
                                    } else {
                                        $totalConstructionArray["construction_options"] = null;
                                    }
                                    if (isset($pData["Windows"]) && is_array($pData["Windows"]) && !empty($pData["Windows"])) {
                                        $totalConstructionArray["windows"] = $pData["Windows"];
                                    } else {
                                        $totalConstructionArray["windows"] = null;
                                    }
                                    break;
                                case "PropertyInfo":
                                    if (array_key_exists("ID", $pData)) {
                                        $totalConstructionArray["property_ID"] = $pData["ID"];
                                    }
                                    $constructionsArray = $totalConstructionArray;
                                    unset($totalConstructionArray);
                                    break;
                            }
                        }
                        $totalConstructionsArrays[] = $constructionsArray;
                    }
                }
            }

            /**
             ** Done: Add propertyID to $totalConstructionsArrays
             */
            foreach ($totalConstructionsArrays as $totals) {
                Construction::create(
                    [
                        'is_ready_for_construction' => $totals["is_ready_for_construction"] ?? null,
                        'construction_period' => $totals["construction_period"] ?? null,
                        'construction_year_from' => $totals["construction_year_from"] ?? null,
                        'construction_year_to' => $totals["construction_year_to"] ?? null,
                        'is_under_construction' => $totals["is_under_construction"] ?? null,
                        'construction_comment' => $totals["construction_comment"] ?? null,
                        'roof_type' => $totals["roof_type"] ?? null,
                        'roof_materials' => $totals["roof_materials"] ?? null,
                        'roof_comments' => $totals["roof_comments"] ?? null,
                        'isolation_types' => $totals["isolation_types"] ?? null,
                        'is_new_estate' => $totals["is_new_estate"] ?? null,
                        'construction_options' => $totals["construction_options"] ?? null,
                        'windows' => $totals["windows"] ?? null,
                        'property_ID' => $totals["property_ID"] ?? null,
                    ]
                );
            }
        }
    }
}
