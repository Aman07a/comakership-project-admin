<?php

namespace App\Models;

use ZipArchive;
use Carbon\Carbon;
use App\Models\Broker;
use App\Helpers\ArrayHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'foreign_ID',
        'name',
        'description',
        'logo',
        'real_estate_association_number',
        'visit_adress',
        'visit_street',
        'visit_house_number',
        'visit_zip_code',
        'visit_district',
        'visit_city',
        'visit_sub_region',
        'visit_region',
        'visit_country_code',
        'postal_address',
        'postal_street',
        'postal_house_number',
        'postal_zip_code',
        'postal_district',
        'postal_city',
        'postal_sub_region',
        'postal_region',
        'postal_country_code',
        'phone',
        'fax',
        'email',
        'website',
        'social_medias',
        'property_ID',
    ];

    public static function getDepartmentsFromXMLObjects($api_key)
    {
        /**
         * Broker variables
         */
        $broker = Broker::where('api_key', '=', $api_key)->value('id');
        $broker_id = Broker::find($broker);
        $broker_key = $broker_id->api_key;

        /**
         ** Model: Department
         */
        $department = new Department();

        /**
         ** Array variables
         */
        $departmentArray = $department->toArray();
        $totalDepartmentsArray = [];

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
                                    $totalDepartmentsArray = [
                                        'foreign_ID' => $pData["Department"]["ForeignID"] ?? null,
                                        'name' => $pData["Department"]["Name"] ?? null,
                                        'description' => $pData["Department"]["Description"] ? $pData["Department"]["Description"] : null,
                                        'logo' => $pData["Department"]["LogoURL"] ?? null,
                                        'real_estate_association_number' => $pData["Department"]["RealEstateAssociationNumber"] ?? null,
                                        'visit_address' => $pData["Department"]["VisitAddress"]["AddressLine1"]["Translation"] ?? null,
                                        'visit_street' => $pData["Department"]["VisitAddress"]["Streetname"]["Translation"] ?? null,
                                        'visit_house_number' => $pData["Department"]["VisitAddress"]["HouseNumber"] ?? null,
                                        'visit_zip_code' => $pData["Department"]["VisitAddress"]["PostalCode"] ?? null,
                                        'visit_district' => $pData["Department"]["VisitAddress"]["District"]["Translation"] ?? null,
                                        'visit_city' => $pData["Department"]["VisitAddress"]["CityName"]["Translation"] ?? null,
                                        'visit_sub_region' => $pData["Department"]["VisitAddress"]["SubRegion"]["Translation"] ?? null,
                                        'visit_region' => $pData["Department"]["VisitAddress"]["Region"]["Translation"] ?? null,
                                        'visit_country_code' => $pData["Department"]["VisitAddress"]["CountryCode"] ?? null,
                                        'postal_address' => $pData["Department"]["PostalAddress"]["AddressLine1"]["Translation"] ?? null,
                                        'postal_street' => $pData["Department"]["PostalAddress"]["Streetname"]["Translation"] ?? null,
                                        'postal_house_number' => $pData["Department"]["PostalAddress"]["HouseNumber"] ?? null,
                                        'postal_zip_code' => $pData["Department"]["PostalAddress"]["PostalCode"] ?? null,
                                        'postal_district' => $pData["Department"]["PostalAddress"]["District"]["Translation"] ?? null,
                                        'postal_city' => $pData["Department"]["PostalAddress"]["CityName"]["Translation"] ?? null,
                                        'postal_sub_region' => $pData["Department"]["PostalAddress"]["SubRegion"]["Translation"] ?? null,
                                        'postal_region' => $pData["Department"]["PostalAddress"]["Region"]["Translation"] ?? null,
                                        'postal_country_code' => $pData["Department"]["PostalAddress"]["CountryCode"] ?? null,
                                        'phone' => $pData["Department"]["Phone"] ?? null,
                                        'fax' => $pData["Department"]["Fax"] ?? null,
                                        'email' => $pData["Department"]["Email"] ?? null,
                                        'website' => $pData["Department"]["WebsiteURL"] ?? null,
                                    ];

                                    if (isset($pData["Department"]["SocialMedias"]) && is_array($pData["Department"]["SocialMedias"]) && !empty($pData["Department"]["SocialMedias"])) {
                                        $totalDepartmentsArray['social_medias'] = $pData["Department"]["SocialMedias"];
                                    } else {
                                        $totalDepartmentsArray['social_medias'] = null;
                                    }

                                    break;
                                case "PropertyInfo":
                                    if (array_key_exists("ID", $pData)) {
                                        $totalDepartmentsArray["property_ID"] = $pData["ID"];
                                    }
                                    $departmentArray = $totalDepartmentsArray;
                                    unset($totalDepartmentsArray);
                                    break;
                            }
                        }
                        $totalDepartmentArrays[] = $departmentArray;
                    }
                }
            }

            /**
             ** Done: Add propertyID to $totalDepartmentArrays
             */
            // if (count($totalDepartmentArrays) > 1) {
            //     $filterDuplicateDepartment = array_unique(array_column($totalDepartmentArrays, 'foreign_ID'));
            //     $removeDuplicateDepartment = array_intersect_key($totalDepartmentArrays, $filterDuplicateDepartment);
            //     $convertToObjectsInDepartment = json_decode(json_encode($removeDuplicateDepartment[0]), true);

            foreach ($totalDepartmentArrays as $totals) {
                Department::create(
                    [
                        'foreign_ID' => $totals["foreign_ID"] ?? null,
                        'name' => $totals["name"] ?? null,
                        'description' => $totals["description"] ?? null,
                        'logo' => $totals["logo"] ?? null,
                        'real_estate_association_number' => $totals["real_estate_association_number"] ?? null,
                        'visit_address' => $totals["visit_address"] ?? null,
                        'visit_street' => $totals["visit_street"] ?? null,
                        'visit_house_number' => $totals["visit_house_number"] ?? null,
                        'visit_zip_code' => $totals["visit_zip_code"] ?? null,
                        'visit_district' => $totals["visit_district"] ?? null,
                        'visit_city' => $totals["visit_city"] ?? null,
                        'visit_sub_region' => $totals["visit_sub_region"] ?? null,
                        'visit_region' => $totals["visit_region"] ?? null,
                        'visit_country_code' => $totals["visit_country_code"] ?? null,
                        'postal_address' => $totals["postal_address"] ?? null,
                        'postal_street' => $totals["postal_street"] ?? null,
                        'postal_house_number' => $totals["postal_house_number"] ?? null,
                        'postal_zip_code' => $totals["postal_zip_code"] ?? null,
                        'postal_district' => $totals["postal_district"] ?? null,
                        'postal_city' => $totals["postal_city"] ?? null,
                        'postal_sub_region' => $totals["postal_sub_region"] ?? null,
                        'postal_region' => $totals["postal_region"] ?? null,
                        'postal_country_code' => $totals["postal_country_code"] ?? null,
                        'phone' => $totals["phone"] ?? null,
                        'fax' => $totals["fax"] ?? null,
                        'email' => $totals["email"] ?? null,
                        'website' => $totals["website"] ?? null,
                        'social_medias' => $totals["social_medias"] ?? null,
                        'property_ID' => $totals["property_ID"] ?? null,
                    ]
                );
            }

            // }
        }
    }
}
