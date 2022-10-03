<?php

namespace App\Http\Controllers;

use ZipArchive;
use App\Models\Broker;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveBrokerRequest;

class ZipController extends Controller
{
    public function zipCreateAndDownload(SaveBrokerRequest $req)
    {
        /**
         * Doing: Saving API data to Broker()
         */
        $broker = new Broker();
        $broker->name = $req->name;
        $broker->api_key = $req->api_key;

        /**
         ** Environment (.ENV) Variables
         */
        $api_http = env('API_HTTP_URL');
        $api_version = env('API_VERSION');
        $api_zip = env('API_ZIP_URL');

        // Broker Key
        $broker_key = $req->api_key;
        // Create new zip object
        $zip = new ZipArchive();
        // Store the public path
        $publicDir = public_path();
        // Define the file name. Give it a unique name to avoid overriding.
        $zipFileName = "$broker_key.zip";
        // Define the file path
        $filePath = $publicDir . '/documents/temp/' . $zipFileName;

        /**
         * TODO: When clicked on the button (PRESS + Request) to update your temp file
         * TODO: The file from the user will be overwrited.
         */

        /**
         * TODO: Check if .ZIP File Exists
         */
        if (file_exists($filePath)) {
            return back();
        } else {
            /**
             * TODO: Downloading .ZIP File
             */
            $url = $api_http . $api_version . '/' . $broker_key . '/' . $api_zip;
            $checkFileUrl = file_get_contents($url);
            file_put_contents(public_path("documents/temp/$broker_key.zip"), $checkFileUrl);

            /**
             * TODO: Save the Broker()
             */
            $broker->save();
        }
        return back()->withSuccess("API Key saved successfully!");
    }
}
