<?php

namespace App\Http\Controllers;

use App\Models\Current;
use App\Models\Garages;
use App\Models\Gardens;
use App\Models\Dimensions;
use App\Models\Construction;
use Illuminate\Http\Request;
use App\Models\ClimatControl;
use App\Models\LocalizationInfo;
use App\Models\ThirdPartyMedias;
use App\Http\Controllers\Controller;

class ModelController extends Controller
{
    public function index()
    {
        $api_key = env('API_KEY');

        // ClimatControl::getClimatControlsFromXMLObjects($api_key);
        // Construction::getConstructionsFromXMLObjects($api_key);
        // Current::getCurrentsFromXMLObjects($api_key);
        // Dimensions::getDimensionsFromXMLObjects($api_key);
        // Garages::getGaragesFromXMLObjects($api_key);
        // Gardens::getGardensFromXMLObjects($api_key);
        // LocalizationInfo::getLocalizationInfoFromXMLObjects($api_key);
        // ThirdPartyMedias::getThirdPartyMediasFromXMLObjects($api_key);
    }
}
