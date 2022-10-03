<?php

namespace App\Models;

use ZipArchive;
use Carbon\Carbon;
use App\Models\Broker;
use App\Helpers\ArrayHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyInfo extends Model
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
        'status',
        'hide_address',
        'hide_house_number',
        'hide_price',
        'confidential',
        'mandate_date',
        'creation_date_time',
        'modification_date_time',
        'foreign_agency_ID',
        'foreign_ID',
        'property_info_ID',
        'property_company_name',
        'origin',
        'public_reference_number',
        'exclusive_status',
        'tags',
        'property_ID',
    ];

    public static function getPropertyInfoFromXMLObjects($api_key)
    {
        /**
         * Broker variables
         */
        $broker = Broker::where('api_key', '=', $api_key)->value('id');
        $broker_id = Broker::find($broker);
        $broker_key = $broker_id->api_key;

        /**
         ** Model: PropertyInfo
         */
        $propertyInfo = new PropertyInfo();

        /**
         ** Array variables
         */
        $propertyInfoArrays = $propertyInfo->toArray();
        $totalPropertyInfoArray = [];

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

                    // It propertyInfo the number of arrays are in .XML files
                    if (count($propertyData["RealEstateProperty"])) {

                        // Shows which status is "WITHDRAWN"
                        if ($propertyData["RealEstateProperty"]["PropertyInfo"]["Status"] == "WITHDRAWN") {
                            continue;
                        }

                        // It shows all data from RealEstateProperty
                        foreach ($propertyData["RealEstateProperty"] as $key => $pData) {
                            switch ($key) {
                                case "PropertyInfo":
                                    $totalPropertyInfoArray = [
                                        'status' => $pData["Status"] ?? null,
                                        'hide_address' => $pData["HideAddress"] ?? null,
                                        'hide_house_number' => $pData["HideHousenumber"] ?? null,
                                        'hide_price' => $pData["HidePrice"] ?? null,
                                        'confidential' => $pData["Confidential"] ?? null,
                                        'mandate_date' => $pData["MandateDate"] ?? null,
                                        'creation_date_time' => $pData["CreationDateTime"] ?? null,
                                        'modification_date_time' => $pData["ModificationDateTime"] ?? null,
                                        'foreign_agency_ID' => $pData["ForeignAgencyID"] ?? null,
                                        'foreign_ID' => $pData["ForeignID"] ?? null,
                                        'property_info_ID' => $pData["ID"] ?? null,
                                        'property_company_name' => $pData["propertyCompanyName"] ?? null,
                                        'origin' => $pData["Origin"] ?? null,
                                        'public_reference_number' => $pData["PublicReferenceNumber"] ?? null,
                                        'exclusive_status' => $pData["ExclusiveStatus"] ?? null,
                                        'tags' => $pData["Tags"] ?? null,
                                    ];

                                    if (array_key_exists("ID", $pData)) {
                                        $totalPropertyInfoArray["property_ID"] = $pData["ID"];
                                    }
                                    $propertyInfoArrays = $totalPropertyInfoArray;
                                    unset($totalPropertyInfoArray);
                                    break;
                            }
                        }
                        $totalPropertyInfoArrays[] = $propertyInfoArrays;
                    }
                }
            }

            /**
             ** Done: Add propertyID to $totalPropertyInfoArrays
             */
            foreach ($totalPropertyInfoArrays as $totals) {
                PropertyInfo::create(
                    [
                        'status' => $totals["status"] ?? null,
                        'hide_address' => $totals["hide_address"] ?? null,
                        'hide_house_number' => $totals["hide_house_number"] ?? null,
                        'hide_price' => $totals["hide_price"] ?? null,
                        'confidential' => $totals["confidential"] ?? null,
                        'mandate_date' => $totals["mandate_date"] ?? null,
                        'creation_date_time' => $totals["creation_date_time"] ?? null,
                        'modification_date_time' => $totals["modification_date_time"] ?? null,
                        'foreign_agency_ID' => $totals["foreign_agency_ID"] ?? null,
                        'foreign_ID' => $totals["foreign_ID"] ?? null,
                        'property_info_ID' => $totals["property_info_ID"] ?? null,
                        'property_company_name' => $totals["property_company_name"] ?? null,
                        'origin' => $totals["origin"] ?? null,
                        'public_reference_number' => $totals["public_reference_number"] ?? null,
                        'exclusive_status' => $totals["exclusive_status"] ?? null,
                        'tags' => $totals["tags"] ?? null,
                        'property_ID' => $totals["property_ID"] ?? null,
                    ]
                );
            }
        }
    }
}
