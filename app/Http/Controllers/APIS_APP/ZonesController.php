<?php
namespace App\Http\Controllers\APIS_APP;

use App\Http\Controllers\Controller;
use App\Models\Zone;
use Illuminate\Http\Request;

class ZonesController extends Controller
{
    //
    public function listZones()
    {
        $zones = Zone::all();

        return response()->json(['status'=>200,'message'=>'Petición exitosa','zones'=>$zones]);
    }
}
