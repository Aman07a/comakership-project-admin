<?php

namespace App\Models;

use ZipArchive;
use App\Models\Broker;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Current extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'is_vacated',
        'association_of_owners_has_long_term_maintenance_plan',
        'check_list_association_of_owners_available',
        'current_destination_description',
        'current_usage_description',
        'percentage_rented',
        'is_partially_rented',
        'revenue_per_year',
        'for_take_over_items',
        'pavement',
        'sector_types',
        'property_ID',
    ];

    public static function getCurrentsFromXMLObjects($api_key)
    {
        /**
         * Broker variables
         */
        $broker = Broker::where('api_key', '=', $api_key)->value('id');
        $broker_id = Broker::find($broker);
        $broker_key = $broker_id->api_key;

        /**
         ** Model: Current
         */
        $currents = new Current();

        /**
         ** Array variables
         */
        $currentsArray = $currents->toArray();
        $totalCurrentArray = [];

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
                                case "Current":
                                    $totalCurrentArray = [
                                        'is_vacated' => $pData["IsVacated"] ?? null,
                                        'association_of_owners_has_long_term_maintenance_plan' => $pData["AssociationOfOwnersHasLongTermMaintenancePlan"] ?? null,
                                        'check_list_association_of_owners_available' => $pData["CheckListAssociationOfOwnersAvailable"] ?? null,
                                        'current_destination_description' => $pData["CurrentDestinationDescription"] ?? null,
                                        'current_usage_description' => $pData["CurrentUsageDescription"] ?? null,
                                        'percentage_rented' => $pData["PercentageRented"] ?? null,
                                        'is_partially_rented' => $pData["IsPartiallyRented"] ?? null,
                                        'revenue_per_year' => $pData["RevenuePerYear"] ?? null,
                                    ];
                                    if (isset($pData["ForTakeOverItems"]) && is_array($pData["ForTakeOverItems"]) && !empty($pData["ForTakeOverItems"])) {
                                        $totalCurrentArray["for_take_over_items"] = $pData["ForTakeOverItems"];
                                    } else {
                                        $totalCurrentArray["for_take_over_items"] = null;
                                    }
                                    if (isset($pData["Pavement"]) && is_array($pData["Pavement"]) && !empty($pData["Pavement"])) {
                                        $totalCurrentArray["pavement"] = $pData["Pavement"];
                                    } else {
                                        $totalCurrentArray["pavement"] = null;
                                    }
                                    if (isset($pData["SectorTypes"]) && is_array($pData["SectorTypes"]) && !empty($pData["SectorTypes"])) {
                                        $totalCurrentArray["sector_types"] = $pData["SectorTypes"];
                                    } else {
                                        $totalCurrentArray["sector_types"] = null;
                                    }
                                    break;
                                case "PropertyInfo":
                                    if (array_key_exists("ID", $pData)) {
                                        $totalCurrentArray["property_ID"] = $pData["ID"];
                                    }
                                    $currentsArray = $totalCurrentArray;
                                    unset($totalCurrentArray);
                                    break;
                            }
                        }
                        $totalCurrentsArrays[] = $currentsArray;
                    }
                }
            }

            /**
             ** Done: Add propertyID to $totalCurrentsArrays
             */
            foreach ($totalCurrentsArrays as $totals) {
                Current::create(
                    [
                        'is_vacated' => $totals["is_vacated"] ?? null,
                        'association_of_owners_has_long_term_maintenance_plan' => $totals["association_of_owners_has_long_term_maintenance_plan"] ?? null,
                        'check_list_association_of_owners_available' => $totals["check_list_association_of_owners_available"] ?? null,
                        'current_destination_description' => $totals["current_destination_description"] ?? null,
                        'current_usage_description' => $totals["current_usage_description"] ?? null,
                        'percentage_rented' => $totals["percentage_rented"] ?? null,
                        'is_partially_rented' => $totals["is_partially_rented"] ?? null,
                        'revenue_per_year' => $totals["revenue_per_year"] ?? null,
                        'for_take_over_items' => $totals["for_take_over_items"] ?? null,
                        'pavement' => $totals["pavement"] ?? null,
                        'sector_types' => $totals["sector_types"] ?? null,
                        'property_ID' => $totals["property_ID"] ?? null,
                    ]
                );
            }
        }
    }
}
