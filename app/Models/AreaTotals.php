<?php

namespace App\Models;

use ZipArchive;
use Carbon\Carbon;
use App\Models\Broker;
use App\Helpers\APIHelpers;
use App\Helpers\ArrayHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class AreaTotals extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'floor_area_gross',
        'storage_area_external',
        'building_related_outdoor_space_area',
        'effective_area',
        'glass_coverings',
        'other_indoor_space_area',
        'floor_area',
        'property_ID',
    ];

    public static function getAreaTotalsFromXMLObjects($api_key)
    {
        /**
         * Broker variables
         */
        $broker = Broker::where('api_key', '=', $api_key)->value('id');
        $broker_id = Broker::find($broker);
        $broker_key = $broker_id->api_key;

        /**
         ** Model: AreaTotals
         */
        $areaTotals = new AreaTotals();

        /**
         ** Array variables
         */
        $areaTotalsArray = $areaTotals->toArray();
        $totalAreaTotalArray = [];

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
                                case "AreaTotals":
                                    $totalAreaTotalArray = [
                                        'floor_area_gross' => $pData["FloorAreaGross"] ?? null,
                                        'storage_area_external' => $pData["StorageAreaExternal"] ?? null,
                                        'building_related_outdoor_space_area' => $pData["BuildingRelatedOutdoorSpaceArea"] ?? null,
                                        'effective_area' => $pData["EffectiveArea"] ?? null,
                                        'glass_coverings' => $pData["GlassCoverings"] ?? null,
                                        'other_indoor_space_area' => $pData["OtherIndoorSpaceArea"] ?? null,
                                        'floor_area' => $pData["FloorArea"] ?? null,
                                    ];
                                    break;
                                case "PropertyInfo":
                                    if (array_key_exists("ID", $pData)) {
                                        $totalAreaTotalArray["property_ID"] = $pData["ID"];
                                    }
                                    $areaTotalsArray = $totalAreaTotalArray;
                                    unset($totalAreaTotalArray);
                                    break;
                            }
                        }
                        $totalAreaTotalsArrays[] = $areaTotalsArray;
                    }
                }
            }

            /**
             ** Done: Add propertyID to $totalAreaTotalsArrays
             */
            foreach ($totalAreaTotalsArrays as $totals) {
                AreaTotals::create(
                    [
                        'floor_area_gross' => $totals["floor_area_gross"] ?? null,
                        'storage_area_external' => $totals["storage_area_external"] ?? null,
                        'building_related_outdoor_space_area' => $totals["building_related_outdoor_space_area"] ?? null,
                        'effective_area' => $totals["effective_area"] ?? null,
                        'glass_coverings' => $totals["glass_coverings"] ?? null,
                        'other_indoor_space_area' => $totals["other_indoor_space_area"] ?? null,
                        'floor_area' => $totals["floor_area"] ?? null,
                        'property_ID' => $totals["property_ID"] ?? null,
                    ]
                );
            }
        }
    }
}
