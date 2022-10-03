<?php

namespace App\Models;

use ZipArchive;
use Carbon\Carbon;
use App\Models\Broker;
use App\Helpers\ArrayHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Type extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'is_residential_lot',
        'for_permanent_residence',
        'for_recreation',
        'property_type',
        'is_residential',
        'is_commercial',
        'is_agricultural',
        'foreign_property_tags',
        'property_ID',
    ];

    public static function getTypesFromXMLObjects($api_key)
    {
        /**
         * Broker variables
         */
        $broker = Broker::where('api_key', '=', $api_key)->value('id');
        $broker_id = Broker::find($broker);
        $broker_key = $broker_id->api_key;

        /**
         ** Model: Type
         */
        $types = new Type();

        /**
         ** Array variables
         */
        $typesArray = $types->toArray();
        $totalTypesArray = [];

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

                        /**
                         ** Done: to fill all rows from ["RealEstateProperty"]["Type"] in the 14 arrays (items)
                         */

                        // It shows all data from RealEstateProperty
                        foreach ($propertyData["RealEstateProperty"] as $key => $pData) {
                            switch ($key) {
                                case "Type":
                                    $totalTypesArray = [
                                        'is_residential_lot' => $pData["IsResidentialLot"] ?? null,
                                        'for_permanent_residence' => $pData["ForPermanentResidence"] ?? null,
                                        'for_recreation' => $pData["ForRecreation"] ?? null,
                                        'is_residential' => $pData["IsResidential"] ?? null,
                                        'is_commercial' => $pData["IsCommercial"] ?? null,
                                        'is_agricultural' => $pData["IsAgricultural"] ?? null,
                                        'foreign_property_tags' => $pData["ForeignPropertyTags"] ?? null,
                                    ];
                                    if (array_key_exists("PropertyTypes", $pData)) {
                                        if (array_key_exists("PropertyType", $pData["PropertyTypes"])) {
                                            if (is_array($pData["PropertyTypes"]["PropertyType"])) {
                                                $totalTypesArray["property_type"] = join(",", $pData["PropertyTypes"]["PropertyType"]);
                                            } else {
                                                $totalTypesArray["property_type"] = $pData["PropertyTypes"]["PropertyType"];
                                            }
                                        } else {
                                            $totalTypesArray["property_type"] = null;
                                        }
                                    }
                                    break;
                                case "PropertyInfo":
                                    if (array_key_exists("ID", $pData)) {
                                        $totalTypeArray["property_ID"] = $pData["ID"];
                                    }
                                    break;
                            }
                        }
                        $totalTypeArrays[] = array_merge($totalTypesArray, $totalTypeArray);
                    }
                }
            }

            /**
             ** Done: Add propertyID to $totalTypeArrays
             */
            foreach ($totalTypeArrays as $totals) {
                Type::create(
                    [
                        'is_residential_lot' => $totals["is_residential_lot"],
                        'for_permanent_residence' => $totals["for_permanent_residence"],
                        'for_recreation' => $totals["for_recreation"],
                        'property_type' => $totals["property_type"],
                        'is_residential' => $totals["is_residential"],
                        'is_commercial' => $totals["is_commercial"],
                        'is_agricultural' => $totals["is_agricultural"],
                        'foreign_property_tags' => $totals["foreign_property_tags"],
                        'property_ID' => $totals["property_ID"],
                    ]
                );
            }
        }
    }
}
