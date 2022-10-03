<?php

namespace App\Models;

use ZipArchive;
use Carbon\Carbon;
use App\Models\Broker;
use App\Helpers\ArrayHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agency extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'foreign_ID',
        'name',
        'vat_number',
        'logo',
        'bank_account_appellation',
        'bank_account_number',
        'legal_name',
        'COC_number',
        'real_estate_association',
        'real_estate_association_number',
        'visit_address',
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
        'property_ID',
    ];

    public function broker()
    {
        return $this->belongsTo(Broker::class);
    }

    public static function getAgenciesFromXMLObjects($api_key)
    {
        /**
         * Broker variables
         */
        $broker = Broker::where('api_key', '=', $api_key)->value('id');
        $broker_id = Broker::find($broker);
        $broker_key = $broker_id->api_key;

        /**
         ** Model: Agency
         */
        $agency = new Agency();

        /**
         ** Array variables
         */
        $agencyArray = $agency->toArray();
        $totalAgenciesArray = [];

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
                                    $totalAgenciesArray = [
                                        'foreign_ID' => $pData["Agency"]["ForeignID"] ?? null,
                                        'name' => $pData["Agency"]["Name"] ?? null,
                                        'vat_number' => $pData["Agency"]["VATNumber"] ?? null,
                                        'logo' => $pData["Agency"]["LogoURL"] ?? null,
                                        'bank_account_appellation' => $pData["Agency"]["BankAccountAppellation"] ?? null,
                                        'bank_account_number' => $pData["Agency"]["BankAccountNumber"] ?? null,
                                        'legal_name' => $pData["Agency"]["LegalName"] ?? null,
                                        'COC_number' => $pData["Agency"]["COCNumber"] ?? null,
                                        'real_estate_association' => $pData["Agency"]["RealEstateAssociation"] ?? null,
                                        'real_estate_association_number' => $pData["Agency"]["RealEstateAssociationNumber"] ?? null,
                                        'visit_address' => $pData["Agency"]["VisitAddress"]["AddressLine1"]["Translation"] ?? null,
                                        'visit_street' => $pData["Agency"]["VisitAddress"]["Streetname"]["Translation"] ?? null,
                                        'visit_house_number' => $pData["Agency"]["VisitAddress"]["HouseNumber"] ?? null,
                                        'visit_zip_code' => $pData["Agency"]["VisitAddress"]["PostalCode"] ?? null,
                                        'visit_district' => $pData["Agency"]["VisitAddress"]["District"]["Translation"] ?? null,
                                        'visit_city' => $pData["Agency"]["VisitAddress"]["CityName"]["Translation"] ?? null,
                                        'visit_sub_region' => $pData["Agency"]["VisitAddress"]["Region"]["Translation"] ?? null,
                                        'visit_region' => $pData["Agency"]["VisitAddress"]["SubRegion"]["Translation"] ?? null,
                                        'visit_country_code' => $pData["Agency"]["VisitAddress"]["CountryCode"] ?? null,
                                        'postal_address' => $pData["Agency"]["PostalAddress"]["AddressLine1"]["Translation"] ?? null,
                                        'postal_street' => $pData["Agency"]["PostalAddress"]["Streetname"]["Translation"] ?? null,
                                        'postal_house_number' => $pData["Agency"]["PostalAddress"]["HouseNumber"]["Translation"] ?? null,
                                        'postal_zip_code' => $pData["Agency"]["PostalAddress"]["PostalCode"]["Translation"] ?? null,
                                        'postal_district' => $pData["Agency"]["PostalAddress"]["District"]["Translation"] ?? null,
                                        'postal_city' => $pData["Agency"]["PostalAddress"]["CityName"]["Translation"] ?? null,
                                        'postal_sub_region' => $pData["Agency"]["PostalAddress"]["Region"]["Translation"] ?? null,
                                        'postalAgencyRegion' => $pData["Agency"]["PostalAddress"]["SubRegion"]["Translation"] ?? null,
                                        'postal_region' => $pData["Agency"]["PostalAddress"]["CountryCode"]["Translation"] ?? null,
                                        'phone' => $pData["Agency"]["Phone"] ?? null,
                                        'fax' => $pData["Agency"]["Fax"] ?? null,
                                        'email' => $pData["Agency"]["Email"] ?? null,
                                        'website' => $pData["Agency"]["WebsiteURL"] ?? null,
                                    ];
                                    break;
                                case "PropertyInfo":
                                    if (array_key_exists("ID", $pData)) {
                                        $totalAgenciesArray["property_ID"] = $pData["ID"];
                                    }
                                    $agencyArray = $totalAgenciesArray;
                                    unset($totalAgenciesArray);
                                    break;
                            }
                        }
                        $totalAgencyArrays[] = $agencyArray;
                    }
                }
            }

            /**
             ** Done: Add propertyID to $totalAgencyArrays
             */
            // if (count($totalAgencyArrays) > 1) {
            //     $filterDuplicateAgency = array_unique(array_column($totalAgencyArrays, 'foreign_ID'));
            //     $removeDuplicateAgency = array_intersect_key($totalAgencyArrays, $filterDuplicateAgency);
            //     $convertToObjects InAgency = json_decode(json_encode($removeDuplicateAgency[0]), true);

            foreach ($totalAgencyArrays as $totals) {
                Agency::create(
                    [
                        'foreign_ID' => $totals["foreign_ID"] ?? null,
                        'name' => $totals["name"] ?? null,
                        'vat_number' => $totals["vat_number"] ?? null,
                        'logo' => $totals["logo"] ?? null,
                        'bank_account_appellation' => $totals["bank_account_appellation"] ?? null,
                        'bank_account_number' => $totals["bank_account_number"] ?? null,
                        'legal_name' => $totals["legal_name"] ?? null,
                        'COC_number' => $totals["COC_number"] ?? null,
                        'real_estate_association' => $totals["real_estate_association"] ?? null,
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
                        'property_ID' => $totals["property_ID"] ?? null,
                    ]
                );
            }

            // }
        }
    }
}
