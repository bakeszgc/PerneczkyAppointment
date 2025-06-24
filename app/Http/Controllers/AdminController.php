<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use App\Models\Service;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index() {
        $barbers = Barber::limit(6)->get();
        $services = Service::all();

        return view('admin/admin',[
            'barbers' => $barbers,
            'services' => $services
        ]);
    }

    public function barberIndex() {
        $barbers = Barber::all();
        return view('admin.barber_index',['barbers' => $barbers]);
    }
}
