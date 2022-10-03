<?php

namespace App\Models;

use ZipArchive;
use Carbon\Carbon;
use App\Models\Broker;
use App\Helpers\ArrayHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'acceptance',
        'acceptance_description',
        'acceptance_date',
        'self_intrest',
        'is_for_rent',
        'is_for_sale',
        'open_house',
        'is_incentive',
        'available_from_date',
        'available_until_date',
        'auction_date',
        'linked_object',
        'property_ID',
    ];

    public static function getOffersFromXMLObjects($api_key)
    {
        /**
         * Broker variables
         */
        $broker = Broker::where('api_key', '=', $api_key)->value('id');
        $broker_id = Broker::find($broker);
        $broker_key = $broker_id->api_key;

        /**
         ** Model: Offer
         */
        $offers = new Offer();

        /**
         ** Array variables
         */
        $offersArray = $offers->toArray();
        $totalOffersArray = [];

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
                                case "Offer":
                                    $totalOffersArray = [
                                        'acceptance' => $pData["Acceptance"] ?? null,
                                        'acceptance_date' => $pData["AcceptanceDate"] ?? null,
                                        'self_intrest' => $pData["SelfIntrest"] ?? null,
                                        'is_for_rent' => $pData["IsForRent"] ?? null,
                                        'is_for_sale' => $pData["IsForSale"] ?? null,
                                        'available_from_date' => $pData["AvailableFromDate"] ?? null,
                                        'available_until_date' => $pData["AvailableUntilDate"] ?? null,
                                        'auction_date' => $pData["AuctionDate"] ?? null,
                                        'open_house' => $pData["OpenHouse"]["OpenHouseEvent"]["Title"]["Translation"] ?? null,
                                    ];
                                    if (isset($pData["AcceptanceDescription"]) && is_array($pData["AcceptanceDescription"]) && !empty($pData["AcceptanceDescription"])) {
                                        $totalOffersArray["acceptance_description"] = $pData["AcceptanceDescription"]["Translation"];
                                    } else {
                                        $totalOffersArray["acceptance_description"] = null;
                                    }
                                    if (isset($pData["IsIncentive"]) && is_array($pData["IsIncentive"]) && !empty($pData["IsIncentive"])) {
                                        $totalOffersArray["is_incentive"] = $pData["IsIncentive"];
                                    } else {
                                        $totalOffersArray["is_incentive"] = null;
                                    }
                                    if (isset($pData["LinkedObject"]) && is_array($pData["LinkedObject"]) && !empty($pData["LinkedObject"])) {
                                        $totalOffersArray["linked_object"] = $pData["LinkedObject"];
                                    } else {
                                        $totalOffersArray["linked_object"] = null;
                                    }
                                    break;
                                case "PropertyInfo":
                                    if (array_key_exists("ID", $pData)) {
                                        $totalOffersArray["property_ID"] = $pData["ID"];
                                    }
                                    $offersArray = $totalOffersArray;
                                    unset($totalOffersArray);
                                    break;
                            }
                        }

                        $totalOffersArrays[] = $offersArray;
                    }
                }
            }

            /**
             ** Done: Add propertyID to $totalOffersArrays
             */
            foreach ($totalOffersArrays as $totals) {
                Offer::create(
                    [
                        'acceptance' => $totals["acceptance"],
                        'acceptance_description' => $totals["acceptance_description"],
                        'acceptance_date' => $totals["acceptance_date"],
                        'self_intrest' => $totals["self_intrest"],
                        'is_for_rent' => $totals["is_for_rent"],
                        'is_for_sale' => $totals["is_for_sale"],
                        'open_house' => $totals["open_house"],
                        'is_incentive' => $totals["is_incentive"],
                        'available_from_date' => $totals["available_from_date"],
                        'available_until_date' => $totals["available_until_date"],
                        'auction_date' => $totals["auction_date"],
                        'linked_object' => $totals["linked_object"],
                        'property_ID' => $totals["property_ID"],
                    ]
                );
            }
        }
    }
}
