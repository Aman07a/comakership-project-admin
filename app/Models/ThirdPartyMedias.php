<?php

namespace App\Models;

use ZipArchive;
use App\Models\Broker;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ThirdPartyMedias extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'creation_date_time',
        'modification_date_time',
        'hash',
        'third_party_ID_code',
        'embed_code',
        'property_ID',
    ];

    public static function getThirdPartyMediasFromXMLObjects($api_key)
    {
        /**
         * Broker variables
         */
        $broker = Broker::where('api_key', '=', $api_key)->value('id');
        $broker_id = Broker::find($broker);
        $broker_key = $broker_id->api_key;

        /**
         ** Model: ThirdPartyMedias
         */
        $thirdPartyMedias = new ThirdPartyMedias();

        /**
         ** Array variables
         */
        $thirdPartyMediasArray = $thirdPartyMedias->toArray();
        $totalThirdPartyMediasArray = [];

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
                                case "ThirdPartyMedias":
                                    if (array_key_exists("ThirdPartyMedia", $pData)) {
                                        $totalThirdPartyMediasArray = [
                                            'title' => $pData["ThirdPartyMedia"]["Title"] ?? null,
                                            'description' => $pData["ThirdPartyMedia"]["Description"] ?? null,
                                            'creation_date_time' => $pData["ThirdPartyMedia"]["CreationDateTime"] ?? null,
                                            'modification_date_time' => $pData["ThirdPartyMedia"]["ModificationDateTime"] ?? null,
                                            'hash' => $pData["ThirdPartyMedia"]["Hash"] ?? null,
                                            'third_party_ID_code' => $pData["ThirdPartyMedia"]["ThirdPartyIDCode"] ?? null,
                                            'embed_code' => $pData["ThirdPartyMedia"]["EmbedCode"] ?? null,
                                        ];
                                    }
                                    break;
                                case "PropertyInfo":
                                    if (array_key_exists("ID", $pData)) {
                                        $totalThirdPartyMediasArray["property_ID"] = $pData["ID"];
                                    }
                                    $thirdPartyMediasArray = $totalThirdPartyMediasArray;
                                    unset($totalThirdPartyMediasArray);
                                    break;
                            }
                        }
                        $totalThirdPartyMediasArrays[] = $thirdPartyMediasArray;
                    }
                }
            }

            /**
             ** Done: Add propertyID to $totalThirdPartyMediasArrays
             */
            foreach ($totalThirdPartyMediasArrays as $totals) {
                ThirdPartyMedias::create(
                    [
                        'title' => $totals["title"] ?? null,
                        'description' => $totals["description"] ?? null,
                        'creation_date_time' => $totals["creation_date_time"] ?? null,
                        'modification_date_time' => $totals["modification_date_time"] ?? null,
                        'hash' => $totals["hash"] ?? null,
                        'third_party_ID_code' => $totals["third_party_ID_code"] ?? null,
                        'embed_code' => $totals["embed_code"] ?? null,
                        'property_ID' => $totals["property_ID"] ?? null,
                    ]
                );
            }
        }
    }
}
