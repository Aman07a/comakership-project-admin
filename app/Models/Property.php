<?php

namespace App\Models;

use ZipArchive;
use App\Models\Broker;
use App\Traits\BelongsToBroker;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory, SoftDeletes;
    use BelongsToBroker;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'street',
        'house_number',
        'zipcode',
        'province',
        'city',
        'main_image',
        'effective_area',
        'property_info_ID',
        'broker_id'
    ];

    public function broker()
    {
        return $this->belongsTo(Broker::class);
    }

    public static function getPropertiesFromXMLObjects($api_key)
    {
        /**
         * Broker variables
         */
        $broker = Broker::where('api_key', '=', $api_key)->value('id');
        $broker_id = Broker::find($broker);
        $broker_key = $broker_id->api_key;

        /**
         ** Model: Property
         */
        $properties = new Property();

        /**
         ** Array variables
         */
        $propertiesArray = $properties->toArray();
        $propertyArray = [];

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
                                    $propertiesArray["effective_area"] = $pData["EffectiveArea"] ?? null;
                                    break;
                                case "Attachments":
                                    if (array_key_exists("Attachment", $pData)) {
                                        if (isset($pData["Attachment"][0]["URLNormalizedFile"]) && !empty($pData["Attachment"][0]["URLNormalizedFile"])) {
                                            $propertiesArray["mainImage"] = $pData["Attachment"][0]["URLNormalizedFile"];
                                        } else {
                                            $propertiesArray["mainImage"] = 'https://i.imgur.com/M1WiAcV.png';
                                        }
                                    }
                                    break;
                                case "Location":
                                    if (array_key_exists("Address", $pData)) {
                                        $propertyArray = [
                                            "street" => $pData["Address"]["Streetname"]["Translation"] ?? null,
                                            "house_number" => $pData["Address"]["HouseNumber"] ?? null,
                                            "zipcode" => $pData["Address"]["PostalCode"] ?? null,
                                            "province" => $pData["Address"]["Region"]["Translation"] ?? null,
                                            "city" => $pData["Address"]["CityName"]["Translation"] ?? null,
                                        ];
                                        $propertiesArray["address"] = $propertyArray;
                                        unset($propertyArray);
                                    }
                                    break;
                                case "PropertyInfo":
                                    $propertiesArray["brokers"] = $broker_id;
                                    $propertiesArray["property_info_ID"] = $pData["ID"] ?? null;
                                    break;
                            }
                        }
                        $totalPropertiesArrays[] = $propertiesArray;
                    }
                }
            }

            /**
             ** Done: Add propertyID to $totalPropertiesArrays
             */
            foreach ($totalPropertiesArrays as $totals) {
                $mainImage = array(
                    'main_image' => $totals["mainImage"],
                );
                Property::create(
                    [
                        "street" => $totals["address"]["street"] ?? null,
                        "house_number" => $totals["address"]["house_number"] ?? null,
                        "zipcode" => $totals["address"]["zipcode"] ?? null,
                        "province" => $totals["address"]["province"] ?? null,
                        "city" => $totals["address"]["city"] ?? null,
                        "main_image" => $mainImage["main_image"] ?? null,
                        "effective_area" => $totals["effective_area"] ?? null,
                        "property_info_ID" => $totals["property_info_ID"] ?? null,
                        "broker_id" => $totals["brokers"]["id"],
                    ]
                );
            }
        }
    }
}
