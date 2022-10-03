<?php

namespace App\Models;

use ZipArchive;
use Carbon\Carbon;
use App\Models\Broker;
use App\Helpers\ArrayHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evaluations extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'for_special_target_audience',
        'communal_areas',
        'security_measures',
        'is_qualified_for_people_with_disabilities',
        'is_qualified_for_seniors',
        'comfort_quality',
        'certifications',
        'polution_types',
        'maintenance_inside',
        'maintenance_outside',
        'property_ID',
    ];

    public static function getEvaluationsFromXMLObjects($api_key)
    {
        /**
         * Broker variables
         */
        $broker = Broker::where('api_key', '=', $api_key)->value('id');
        $broker_id = Broker::find($broker);
        $broker_key = $broker_id->api_key;

        /**
         ** Model: Evaluations
         */
        $evaluations = new Evaluations();

        /**
         ** Array variables
         */
        $evaluationsArray = $evaluations->toArray();
        $totalEvaluationsArray = [];

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
                                case "Evaluations":
                                    $totalEvaluationsArray = [
                                        'for_special_target_audience' => $pData["ForSpecialTargetAudience"] ?? null,
                                        'is_qualified_for_people_with_disabilities' => $pData["IsQualifiedForPeopleWithDisabilities"] ?? null,
                                        'is_qualified_for_seniors' => $pData["IsQualifiedForSeniors"] ?? null,
                                        'comfort_quality' => $pData["ComfortQuality"] ?? null,
                                        'polution_types' => $pData["PolutionTypes"]["Polution"] ?? null,
                                        'maintenance_inside' => $pData["MaintenanceInside"] ?? null,
                                        'maintenance_outside' => $pData["MaintenanceOutside"] ?? null,
                                    ];
                                    if (array_key_exists("Certifications", $pData)) {
                                        if (array_key_exists("Certificate", $pData["Certifications"])) {
                                            $totalEvaluationsArray["certifications"] = join(",", $pData["Certifications"]["Certificate"]);
                                        }
                                    } else {
                                        $totalEvaluationsArray["certifications"] = null;
                                    }
                                    if (isset($pData["CommunalAreas"]) && is_array($pData["CommunalAreas"]) && !empty($pData["CommunalAreas"])) {
                                        $totalEvaluationsArray["communal_areas"] = $pData["CommunalAreas"];
                                    } else {
                                        $totalEvaluationsArray["communal_areas"] = null;
                                    }
                                    if (isset($pData["SecurityMeasures"]) && is_array($pData["SecurityMeasures"]) && !empty($pData["SecurityMeasures"])) {
                                        $totalEvaluationsArray["security_measures"] = $pData["SecurityMeasures"];
                                    } else {
                                        $totalEvaluationsArray["security_measures"] = null;
                                    }
                                    break;
                                case "PropertyInfo":
                                    if (array_key_exists("ID", $pData)) {
                                        $totalEvaluationsArray["property_ID"] = $pData["ID"];
                                    }
                                    $evaluationsArray = $totalEvaluationsArray;
                                    unset($totalEvaluationsArray);
                                    break;
                            }
                        }
                        $totalEvaluationsArrays[] = $evaluationsArray;
                    }
                }
            }

            /**
             ** Done: Add propertyID to $totalEvaluationsArrays
             */
            foreach ($totalEvaluationsArrays as $totals) {
                Evaluations::create(
                    [
                        'for_special_target_audience' => $totals["for_special_target_audience"] ?? null,
                        'communal_areas' => $totals["communal_areas"] ?? null,
                        'security_measures' => $totals["security_measures"] ?? null,
                        'is_qualified_for_people_with_disabilities' => $totals["is_qualified_for_people_with_disabilities"] ?? null,
                        'is_qualified_for_seniors' => $totals["is_qualified_for_seniors"] ?? null,
                        'comfort_quality' => $totals["comfort_quality"] ?? null,
                        'certifications' => $totals["certifications"] ?? null,
                        'polution_types' => $totals["polution_types"] ?? null,
                        'maintenance_inside' => $totals["maintenance_inside"] ?? null,
                        'maintenance_outside' => $totals["maintenance_outside"] ?? null,
                        'property_ID' => $totals["property_ID"] ?? null,
                    ]
                );
            }
        }
    }
}
