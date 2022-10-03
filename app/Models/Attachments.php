<?php

namespace App\Models;

use ZipArchive;
use Carbon\Carbon;
use App\Models\Broker;
use App\Helpers\ArrayHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attachments extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Disabling Laravel's Eloquent timestamps
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'main_image',
        'title',
        'description',
        'type',
        'creation_date_time',
        'modification_date_time',
        'hash',
        'URL_normalized_file',
        'URL_medium_file',
        'index',
        'URL_thumb_file',
        'property_ID',
    ];

    public static function getAttachmentsFromXMLObjects($api_key)
    {
        /**
         * Broker variables
         */
        $broker = Broker::where('api_key', '=', $api_key)->value('id');
        $broker_id = Broker::find($broker);
        $broker_key = $broker_id->api_key;

        /**
         ** Model: Attachments
         */
        $attachments = new Attachments();

        /**
         ** Array variables
         */
        $attachmentsArray = $attachments->toArray();
        $imagesArray = [];

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
                                case "Attachments":
                                    if (array_key_exists("Attachment", $pData)) {
                                        if (isset($pData["Attachment"][0]["URLNormalizedFile"]) && !empty($pData["Attachment"][0]["URLNormalizedFile"])) {
                                            $attachmentsArray["mainImage"] = $pData["Attachment"][0]["URLNormalizedFile"];
                                        } else {
                                            $attachmentsArray["mainImage"] = 'https://i.imgur.com/M1WiAcV.png';
                                        }
                                        foreach ($pData["Attachment"] as $element) {
                                            $imagesArray[] = [
                                                "title" => $element["Title"]["Translation"] ?? null,
                                                "description" => $element["Description"]["Translation"] ?? null,
                                                "type" => $element["Type"] ?? null,
                                                "creationDateTime" => $element["CreationDateTime"] ?? null,
                                                "modificationDateTime" => $element["ModificationDateTime"] ?? null,
                                                "hash" => $element["Hash"] ?? null,
                                                "urlNormalizedFile" => $element["URLNormalizedFile"] ?? null,
                                                "urlMediumFile" => $element["URLMediumFile"] ?? null,
                                                "index" => $element["Index"] ?? null,
                                                "urlThumbFile" => $element["URLThumbFile"] ?? null,
                                            ];
                                        }
                                    } else {
                                        $imagesArray[] = [
                                            "urlNormalizedFile" => "https://i.imgur.com/M1WiAcV.png",
                                        ];
                                        $attachmentsArray["images"] = $imagesArray;
                                    }
                                    break;
                                case "PropertyInfo":
                                    if (array_key_exists("ID", $pData)) {
                                        foreach ($imagesArray as &$image) {
                                            $image["propertyID"] = $pData["ID"];
                                        }
                                    }
                                    $attachmentsArray["images"] = $imagesArray;
                                    unset($imagesArray);
                                    break;
                            }
                        }
                        $totalAttachmentsArrays[] = $attachmentsArray;
                    }
                }
            }
            /**
             ** Done: Add propertyID to $totalAttachmentsArrays
             */
            foreach ($totalAttachmentsArrays as $totals) {
                $mainImage = array(
                    'main_image' => $totals["mainImage"],
                );
                foreach ($totals["images"] as &$images) {
                    Attachments::create(
                        [
                            'main_image' => $mainImage["main_image"] ?? null,
                            'title' => $images["title"] ?? null,
                            'description' => $images["description"] ?? null,
                            'type' => $images["type"] ?? null,
                            'creation_date_time' => $images["creationDateTime"] ?? null,
                            'modification_date_time' => $images["modificationDateTime"] ?? null,
                            'hash' => $images["hash"] ?? null,
                            'URL_normalized_file' => $images["urlNormalizedFile"] ?? null,
                            'URL_medium_file' => $images["urlMediumFile"] ?? null,
                            'index' => $images["index"] ?? null,
                            'URL_thumb_file' => $images["urlThumbFile"] ?? null,
                            'property_ID' => $images["propertyID"] ?? null,
                        ]
                    );
                }
            }
        }
    }
}
