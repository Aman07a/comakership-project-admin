<?php

namespace App\Models;

use ZipArchive;
use App\Models\Broker;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClimatControl extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'energy_class',
        'energy_index',
        'due_date',
        'has_energy_certificate',
        'number',
        'heating_is_combi_boiler',
        'heating_year_of_manufacture',
        'heating_energy_source',
        'heating_ownership',
        'heating_methods_water',
        'heating_methods',
        'heating_type_of_boiler',
        'ventilation',
        'property_ID',
    ];

    public static function getClimatControlsFromXMLObjects($api_key)
    {
        /**
         * Broker variables
         */
        $broker = Broker::where('api_key', '=', $api_key)->value('id');
        $broker_id = Broker::find($broker);
        $broker_key = $broker_id->api_key;

        /**
         ** Model: ClimatControl
         */
        $climatControls = new ClimatControl();

        /**
         ** Array variables
         */
        $climatControlsArray = $climatControls->toArray();
        $totalClimatControlArray = [];

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
                                case "ClimatControl":
                                    $totalClimatControlArray = [
                                        'energy_class' => $pData["EnergyCertificate"]["EnergyClass"] ?? null,
                                        'energy_index' => $pData["EnergyCertificate"]["EnergyIndex"] ?? null,
                                        'due_date' => $pData["EnergyCertificate"]["DueDate"] ?? null,
                                        'has_energy_certificate' => $pData["EnergyCertificate"]["HasEnergyCertificate"] ?? null,
                                        'number' => $pData["EnergyCertificate"]["Number"] ?? null,

                                        'heating_is_combi_boiler' => $pData["Heating"]["IsCombiBoiler"] ?? null,
                                        'heating_year_of_manufacture' => $pData["Heating"]["YearOfManufacture"] ?? null,
                                        'heating_energy_source' => $pData["Heating"]["EnergySource"] ?? null,
                                        'heating_ownership' => $pData["Heating"]["Ownership"] ?? null,
                                        'heating_methods_water' => $pData["Heating"]["HeatingMethodsWater"] ?? null,
                                        'heating_methods' => $pData["Heating"]["HeatingMethods"] ?? null,
                                        'heating_type_of_boiler' => $pData["Heating"]["TypeOfBoiler"] ?? null,

                                        'ventilation' => $pData["Ventilation"] ?? null,
                                    ];

                                    if (isset($pData["Ventilation"]) && is_array($pData["Ventilation"]) && !empty($pData["Ventilation"])) {
                                        $totalClimatControlArray["ventilation"] = $pData["Ventilation"];
                                    } else {
                                        $totalClimatControlArray["ventilation"] = null;
                                    }
                                    break;
                                case "PropertyInfo":
                                    if (array_key_exists("ID", $pData)) {
                                        $totalClimatControlArray["property_ID"] = $pData["ID"];
                                    }
                                    $climatControlsArray = $totalClimatControlArray;
                                    unset($totalClimatControlArray);
                                    break;
                            }
                        }
                        $totalClimatControlsArrays[] = $climatControlsArray;
                    }
                }
            }

            /**
             ** Done: Add propertyID to $totalClimatControlsArrays
             */
            foreach ($totalClimatControlsArrays as $totals) {
                ClimatControl::create(
                    [
                        'energy_class' => $totals["energy_class"] ?? null,
                        'energy_index' => $totals["energy_index"] ?? null,
                        'due_date' => $totals["due_date"] ?? null,
                        'has_energy_certificate' => $totals["has_energy_certificate"] ?? null,
                        'number' => $totals["number"] ?? null,
                        'heating_is_combi_boiler' => $totals[""] ?? null,
                        'heating_year_of_manufacture' => $totals["heating_year_of_manufacture"] ?? null,
                        'heating_energy_source' => $totals["heating_energy_source"] ?? null,
                        'heating_ownership' => $totals["heating_ownership"] ?? null,
                        'heating_methods_water' => $totals["heating_methods_water"] ?? null,
                        'heating_methods' => $totals["heating_methods"] ?? null,
                        'heating_type_of_boiler' => $totals["heating_type_of_boiler"] ?? null,
                        'ventilation' => $totals["ventilation"] ?? null,
                        'property_ID' => $totals["property_ID"] ?? null,
                    ]
                );
            }
        }
    }
}
