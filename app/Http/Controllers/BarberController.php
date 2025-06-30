<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use Illuminate\Http\Request;

class BarberController extends Controller
{
    public function index()
    {
        $barbers = Barber::all();
        return view('barber.index',['barbers' => $barbers]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Request $request, Barber $barber)
    {
        $request->validate([
            'showProfile' => 'nullable|boolean',
            'showPicture' => 'nullable|boolean',
            'showDestroy' => 'nullable|boolean'
        ]);

        $showProfile = $request->showProfile ?? true;
        $showPicture = $request->showPicture ?? false;
        $showDestroy = $request->showDestroy ?? false;

        return view('barber.show',[
            'barber' => $barber,
            'showProfile' => $showProfile,
            'showPicture' => $showPicture,
            'showDestroy' => $showDestroy
        ]);
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
