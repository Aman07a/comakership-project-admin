<?php

namespace App\Models;

use ZipArchive;
use Carbon\Carbon;
use App\Models\Broker;
use App\Helpers\ArrayHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Descriptions extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'description_nl',
        'description_en',
        'balcony_description',
        'ground_floor_description',
        'first_floor_description',
        'second_floor_description',
        'other_floor_description',
        'details_description',
        'garden_description',
        'property_ID',
    ];

    public static function getDescriptionsFromXMLObjects($api_key)
    {
        /**
         * Broker variables
         */
        $broker = Broker::where('api_key', '=', $api_key)->value('id');
        $broker_id = Broker::find($broker);
        $broker_key = $broker_id->api_key;

        /**
         ** Model: Descriptions
         */
        $descriptions = new Descriptions();

        /**
         ** Array variables
         */
        $descriptionsArray = $descriptions->toArray();
        $totalDescriptionArray = [];

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
                                case "Descriptions":
                                    $totalDescriptionArray = [
                                        'balcony_description' => $pData["BalconyDescription"]["Translation"] ?? null,
                                        'ground_floor_description' => $pData["GroundFloorDescription"]["Translation"] ?? null,
                                        'first_floor_description' => $pData["FirstFloorDescription"]["Translation"] ?? null,
                                        'second_floor_description' => $pData["SecondFloorDescription"]["Translation"] ?? null,
                                        'other_floor_description' => $pData["OtherFloorDescription"]["Translation"] ?? null,
                                        'details_description' => $pData["DetailsDescription"]["Translation"] ?? null,
                                        'garden_description' => $pData["GardenDescription"]["Translation"] ?? null,
                                    ];
                                    if (isset($pData["AdText"]["Translation"]) && is_array($pData["AdText"]["Translation"])) {
                                        $totalDescriptionArray["description_nl"] = $pData["AdText"]["Translation"][0] ?? null;
                                        $totalDescriptionArray["description_en"] = $pData["AdText"]["Translation"][1] ?? null;
                                    } else {
                                        $totalDescriptionArray["description_nl"] = $pData["AdText"]["Translation"] ?? null;
                                        $totalDescriptionArray["description_en"] = null;
                                    }
                                    break;
                                case "PropertyInfo":
                                    if (array_key_exists("ID", $pData)) {
                                        $totalDescriptionArray["property_ID"] = $pData["ID"];
                                    }
                                    $descriptionsArray = $totalDescriptionArray;
                                    unset($totalDescriptionArray);
                                    break;
                            }
                        }
                        $totalDescriptionsArrays[] = $descriptionsArray;
                    }
                }
            }

            /**
             ** Done: Add propertyID to $totalDescriptionsArrays
             */
            foreach ($totalDescriptionsArrays as $totals) {
                Descriptions::create(
                    [
                        'description_nl' => $totals["description_nl"] ?? null,
                        'description_en' => $totals["description_en"] ?? null,
                        'balcony_description' => $totals["balcony_description"] ?? null,
                        'ground_floor_description' => $totals["ground_floor_description"] ?? null,
                        'first_floor_description' => $totals["first_floor_description"] ?? null,
                        'second_floor_description' => $totals["second_floor_description"] ?? null,
                        'other_floor_description' => $totals["other_floor_description"] ?? null,
                        'details_description' => $totals["details_description"] ?? null,
                        'garden_description' => $totals["garden_description"] ?? null,
                        'property_ID' => $totals["property_ID"] ?? null,
                    ]
                );
            }
        }
    }
}
