<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Broker;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\XMLController;

class CollectionsController extends Controller
{
    public function index($api_key)
    {
        /**
         * ---------------------------------
         ** DONE: if api_key already exist:
         ** Added Soft Delete to old data
         ** Then create new data
         * ---------------------------------
         ** DONE: if api_key does not exist: 
         ** Create new data
         * ---------------------------------
         */

        if (Broker::withTrashed()->where('api_key', $api_key)->exists()) {
            $broker_id = Broker::withTrashed()->where('api_key', $api_key)->value('id');
            if (Property::withTrashed()->where('broker_id', $broker_id)->exists()) {
                /**
                 ** Soft Delete: Active data
                 */
                $softDeleteData = XMLController::softDeleteData($api_key);

                foreach ($softDeleteData as $isDeleted) {
                    $propertiesDeleted = Property::onlyTrashed()->where('broker_id', $isDeleted->id)->get();
                    echo "Message: Broker and properties have been soft deleted" . ". " .
                        "API: " . $isDeleted->api_key . ". " .
                        "Total properties: " . $propertiesDeleted->count() . ". " .
                        "Date: " . Carbon::now()->format('Y-m-d H:i:s') . ".\n";
                }

                /**
                 ** Merged: All data (all tables)
                 */
                XMLController::mergeDailyData($api_key);
            } else {
                /**
                 ** Merged: All data (all tables)
                 */
                XMLController::mergeDailyData($api_key);

                if (Property::withTrashed()->where('broker_id', $broker_id)->exists()) {
                    $properties = Property::withTrashed()->where('broker_id', $broker_id)->get();
                    echo "Message: Old properties have not been soft deleted" . ". " .
                        "New: Properties have been successfully created" . ". " .
                        "Total properties: " . $properties->count() . ". " .
                        "Date: " . Carbon::now()->format('Y-m-d H:i:s') . ".\n";
                }
            }
        } else {
            /**
             ** Checking if API_KEY is failed or empty
             */
            if (!empty($api_key)) {
                /**
                 ** Merged: All data (all tables)
                 */
                XMLController::mergeAllData($api_key);
                /**
                 ** Validating: if api_key is the same from brokers.api_key
                 */
                $brokers = DB::table('brokers')->where('api_key', '=', $api_key)->get();

                foreach ($brokers as $broker) {
                    $broker_name = $broker->name;
                    if (!empty(DB::table('properties')->where('broker_id', '=', $broker->id)->get())) {
                        $properties = DB::table('properties')->where('broker_id', '=', $broker->id)->get();
                    } else {
                        $properties = array();
                    }
                }

                if ($properties->count() === 0) {
                    echo "New: Broker has not been successfully created" . ". " .
                        "API: " . $broker_name . ". " .
                        "Total properties: " . $properties->count() . ". " .
                        "Date: " . Carbon::now()->format('Y-m-d H:i:s') . ". " .
                        "Error: $api_key.zip has not been created\n";
                } else {
                    echo "New: Broker has been successfully created" . ". " .
                        "API: " . $broker_name . ". " .
                        "Total properties: " . $properties->count() . ". " .
                        "Date: " . Carbon::now()->format('Y-m-d H:i:s') . ". " .
                        "Info: Properties have been successfully created\n";
                }
            } else {
                echo "Cronjob has been cancelled!";
            }
        }
    }

    public function activateCronjob()
    {
        $broker = DB::table('brokers')->latest('id')->first();
        $api_key = $broker->api_key;

        if (DB::table('brokers')->where('api_key', $api_key)->whereNull('deleted_at')->exists()) {
            $broker_id = DB::table('brokers')->where('api_key', $api_key)->whereNull('deleted_at')->value('id');
            if (DB::table('properties')->where('broker_id', $broker_id)->whereNull('deleted_at')->exists()) {
                /**
                 ** Soft delete: data
                 */
                $softDeleteData = XMLController::softDeleteData($api_key);

                foreach ($softDeleteData as $isDeleted) {
                    $propertiesDeleted = Property::onlyTrashed()->where('broker_id', $isDeleted->id)->get();
                    echo "Message: Broker and properties have been soft deleted" . ". " .
                        "API: " . $isDeleted->api_key . ". " .
                        "Total properties: " . $propertiesDeleted->count() . ". " .
                        "Date: " . Carbon::now()->format('Y-m-d H:i:s') . ".\n";
                }
            } else {
                /**
                 ** Create: Only properties and other components (without brokers)
                 */
                XMLController::mergeAllData($api_key);

                if (Property::where('broker_id', $broker_id)->whereNull('deleted_at')->exists()) {
                    $createdProperties = Property::where('broker_id', $broker_id)->whereNull('deleted_at')->get();
                    echo "Message: Properties have been created" . ". " .
                        "API: " . $api_key . ". " .
                        "Total properties: " . $createdProperties->count() . ". " .
                        "Date: " . Carbon::now()->format('Y-m-d H:i:s') . ".\n";
                }
            }
        } else {
            /**
             ** Create: all components (brokers, properties, 13 components)
             */
            XMLController::mergeDailyData($api_key);
        }
    }
}
