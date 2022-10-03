<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ComponentController extends Controller
{
    public function viewComponent()
    {
        return view('admin.components.view_component');
    }

    public function componentDashboard()
    {
        $components = DB::table('properties')
            ->select(
                'descriptions.*',
                'properties.broker_id',
                'properties.street',
                'properties.house_number',
                'properties.broker_id',
                'properties.zipcode',
                'properties.city',
                'properties.main_image',
                'properties.property_info_ID'
            )
            ->join('descriptions', 'properties.property_info_ID', '=', 'descriptions.property_ID')
            ->whereNull('descriptions.deleted_at')
            ->orderBy('id', 'desc')
            ->get();
        return view('admin.components.index', compact('components'));
    }
}
