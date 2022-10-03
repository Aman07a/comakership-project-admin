<?php

namespace App\Models;

use ZipArchive;
use Carbon\Carbon;
use App\Models\Broker;
use App\Helpers\ArrayHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class BrokerPerson extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'foreign_ID',
        'title',
        'firstname',
        'middle_name',
        'lastname',
        'full_name',
        'gender',
        'dob',
        'email',
        'phone',
        'mobile',
        'fax',
        'photo',
        'social_medias',
        'property_ID',
    ];

    public static function getBrokerPeopleFromXMLObjects($api_key)
    {
        /**
         * Broker variables
         */
        $broker = Broker::where('api_key', '=', $api_key)->value('id');
        $broker_id = Broker::find($broker);
        $broker_key = $broker_id->api_key;

        /**
         ** Model: BrokerPerson
         */
        $brokerPerson = new BrokerPerson();

        /**
         ** Array variables
         */
        $brokerPersonArray = $brokerPerson->toArray();
        $totalBrokerPersonArray = [];

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
                                case "Contact":
                                    $totalBrokerPersonArray = [
                                        'foreign_ID' => $pData["Person"]["ForeignID"] ?? null,
                                        'title' => $pData["Person"]["Title"] ?? null,
                                        'firstname' => $pData["Person"]["FirstName"] ?? null,
                                        'middle_name' => $pData["Person"]["MiddleName"] ?? null,
                                        'lastname' => $pData["Person"]["LastName"] ?? null,
                                        'full_name' => $pData["Person"]["DisplayName"] ?? null,
                                        'gender' => $pData["Person"]["Gender"] ?? null,
                                        'dob' => $pData["Person"]["DateOfBirth"] ?? null,
                                        'email' => $pData["Person"]["Email"] ?? null,
                                        'phone' => $pData["Person"]["Phone"] ?? null,
                                        'mobile' => $pData["Person"]["Mobile"] ?? null,
                                        'fax' => $pData["Person"]["Fax"] ?? null,
                                        'photo' => $pData["Person"]["PhotoURL"] ?? null,
                                    ];

                                    if (isset($pData["Person"]["SocialMedias"]) && is_array($pData["Person"]["SocialMedias"]) && !empty($pData["Person"]["SocialMedias"])) {
                                        $totalDepartmentsArray['social_medias'] = $pData["Person"]["SocialMedias"];
                                    } else {
                                        $totalDepartmentsArray['social_medias'] = null;
                                    }
                                    break;
                                case "PropertyInfo":
                                    if (array_key_exists("ID", $pData)) {
                                        $totalBrokerPersonArray["property_ID"] = $pData["ID"];
                                    }
                                    $brokerPersonArray = $totalBrokerPersonArray;
                                    unset($totalBrokerPersonArray);
                                    break;
                            }
                        }
                        $totalBrokerPersonArrays[] = array_merge($brokerPersonArray);
                    }
                }
            }

            /**
             ** Done: Add propertyID to $totalBrokerPersonArrays
             */
            // if (count($totalBrokerPersonArrays) > 1) {
            //     $filterDuplicateBrokerPerson = array_unique(array_column($totalBrokerPersonArrays, 'foreign_ID'));
            //     $removeDuplicateBrokerPerson = array_intersect_key($totalBrokerPersonArrays, $filterDuplicateBrokerPerson);
            //     $convertToObjectsInBrokerPerson = json_decode(json_encode($removeDuplicateBrokerPerson[0]), true);

            foreach ($totalBrokerPersonArrays as $totals) {
                BrokerPerson::create(
                    [
                        'foreign_ID' => $totals["foreign_ID"] ?? null,
                        'title' => $totals["title"] ?? null,
                        'firstname' => $totals["firstname"] ?? null,
                        'middle_name' => $totals["middle_name"] ?? null,
                        'lastname' => $totals["lastname"] ?? null,
                        'full_name' => $totals["full_name"] ?? null,
                        'gender' => $totals["gender"] ?? null,
                        'dob' => $totals["dob"] ?? null,
                        'email' => $totals["email"] ?? null,
                        'phone' => $totals["phone"] ?? null,
                        'mobile' => $totals["mobile"] ?? null,
                        'fax' => $totals["fax"] ?? null,
                        'photo' => $totals["photo"] ?? null,
                        'social_medias' => $totals["social_medias"] ?? null,
                        'property_ID' => $totals["property_ID"] ?? null,
                    ]
                );
            }

            // }
        }
    }
}
