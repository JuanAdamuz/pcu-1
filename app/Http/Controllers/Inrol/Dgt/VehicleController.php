<?php

namespace App\Http\Controllers\Inrol\Dgt;

use App\Arma\Vehicle;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class VehicleController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['integration_required']);
    }

    public function listOwnVehicles()
    {
        $user = Auth::user();
        $vehicles = Cache::remember('vehiclecontroller.listOwnVehicles.'.$user->id, 15, function () use ($user) {
            return $user->player->vehicles;
        });

        return view('inrol.dgt.matriculados')
            ->with('vehicles', $vehicles);
    }

    public function viewOwnLicenses()
    {
        $user = Auth::user();
        $licenses = Cache::remember('vehiclecontroller.viewOwnLicenses.'.$user->id, 15, function () use ($user) {
            return $user->player->civ_licenses;
        });

        return view('inrol.dgt.permisos')
            ->with('licenses', $licenses);
    }

    public function transferPage($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        if (! $vehicle->isTransferable()) {
            abort(403);
        }

        return view('inrol.dgt.transferir')->with('vehicle', $vehicle);
    }
}
