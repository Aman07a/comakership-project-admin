<?php

namespace App\Models;

use ZipArchive;
use Carbon\Carbon;
use App\Models\Broker;
use App\Helpers\ArrayHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Financials extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'commission_contact_gross',
        'commission_customer_bmm',
        'commission_customer_gross',
        'commission_customer_percent',
        'service_costs',
        'gas_costs',
        'water_costs',
        'electricity_costs',
        'heating_costs',
        'price_history',
        'rent_price',
        'rent_specification',
        'rent_price_type',
        'furniture_costs',
        'advanced_payment_amount',
        'deposit',
        'purchase_price',
        'realised_price',
        'price_code',
        'purchase_condition',
        'purchase_specification',
        'property_ID',
    ];

    public static function getFinancialsFromXMLObjects($api_key)
    {
        /**
         * Broker variables
         */
        $broker = Broker::where('api_key', '=', $api_key)->value('id');
        $broker_id = Broker::find($broker);
        $broker_key = $broker_id->api_key;

        /**
         ** Model: Financials
         */
        $financials = new Financials();

        /**
         ** Array variables
         */
        $financialsArray = $financials->toArray();
        $totalFinancialsArray = [];

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
                                case "Financials":
                                    $totalFinancialsArray = [
                                        'commission_contact_gross' => $pData["Commissions"]["CommissionContactGross"] ?? null,
                                        'commission_customer_bmm' => $pData["Commissions"]["CommissionCustomerBmm"] ?? null,
                                        'commission_customer_gross' => $pData["Commissions"]["CommissionCustomerGross"] ?? null,
                                        'commission_customer_percent' => $pData["Commissions"]["CommissionCustomerPercent"] ?? null,
                                        'service_costs' => $pData["ServiceCosts"] ?? null,
                                        'gas_costs' => $pData["Indications"]["GasCosts"] ?? null,
                                        'water_costs' => $pData["Indications"]["WaterCosts"] ?? null,
                                        'electricity_costs' => $pData["Indications"]["ElectricityCosts"] ?? null,
                                        'heating_costs' => $pData["Indications"]["HeatingCosts"] ?? null,
                                        'price_history' => $pData["PriceHistory"] ?? null,
                                        'rent_price' => $pData["RentPrice"] ?? null,
                                        'rent_price_type' => $pData["RentPriceType"] ?? null,
                                        'furniture_costs' => $pData["FurnitureCosts"] ?? null,
                                        'advanced_payment_amount' => $pData["AdvancedPaymentAmount"] ?? null,
                                        'deposit' => $pData["Deposit"] ?? null,
                                        'purchase_price' => $pData["PurchasePrice"] ?? null,
                                        'realised_price' => $pData["RealisedPrice"] ?? null,
                                        'price_code' => $pData["PriceCode"] ?? null,
                                        'purchase_condition' => $pData["PurchaseCondition"] ?? null,
                                        'purchase_specification' => $pData["PurchaseSpecification"] ?? null,
                                    ];

                                    if (array_key_exists("RentSpecification", $pData)) {
                                        if (array_key_exists("Specification", $pData["RentSpecification"])) {
                                            if (is_array($pData["RentSpecification"]["Specification"])) {
                                                $totalFinancialsArray["rent_specification"] = join(",", $pData["RentSpecification"]["Specification"]);
                                            } else {
                                                $totalFinancialsArray["rent_specification"] = $pData["RentSpecification"]["Specification"];
                                            }
                                        }
                                    }
                                    break;
                                case "PropertyInfo":
                                    if (array_key_exists("ID", $pData)) {
                                        $totalFinancialsArray["property_ID"] = $pData["ID"];
                                    }
                                    $financialsArray = $totalFinancialsArray;
                                    unset($totalFinancialsArray);
                                    break;
                            }
                        }
                        $totalFinancialsArrays[] = $financialsArray;
                    }
                }
            }

            /**
             ** Done: Add propertyID to $totalFinancialsArrays
             */
            foreach ($totalFinancialsArrays as $totals) {
                Financials::create(
                    [
                        'commission_contact_gross' => $totals["commission_contact_gross"] ?? null,
                        'commission_customer_bmm' => $totals["commission_customer_bmm"] ?? null,
                        'commission_customer_percent' => $totals["commission_customer_percent"] ?? null,
                        'service_costs' => $totals["service_costs"] ?? null,
                        'gas_costs' => $totals["gas_costs"] ?? null,
                        'water_costs' => $totals["water_costs"] ?? null,
                        'electricity_costs' => $totals["electricity_costs"] ?? null,
                        'heating_costs' => $totals["heating_costs"] ?? null,
                        'price_history' => $totals["price_history"] ?? null,
                        'rent_price' => $totals["rent_price"] ?? null,
                        'rent_specification' => $totals["rent_specification"] ?? null,
                        'rent_price_type' => $totals["rent_price_type"] ?? null,
                        'furniture_costs' => $totals["furniture_costs"] ?? null,
                        'advanced_payment_amount' => $totals["advanced_payment_amount"] ?? null,
                        'deposit' => $totals["deposit"] ?? null,
                        'purchase_price' => $totals["purchase_price"] ?? null,
                        'realised_price' => $totals["realised_price"] ?? null,
                        'price_code' => $totals["price_code"] ?? null,
                        'purchase_condition' => $totals["purchase_condition"] ?? null,
                        'purchase_specification' => $totals["purchase_specification"] ?? null,
                        'property_ID' => $totals["property_ID"] ?? null,
                    ]
                );
            }
        }
    }
}
