<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    
    public function change(Request $request)
    {
        $request->validate([
            'lang' => 'required|string|min:2'
        ]);

        $lang = $request->lang;

        if ($lang != 'hu' && $lang != 'en') {
            return redirect()->back()->with('error',"We don't have that language yet!");
        }

        Session::put("lang", $lang);
        //add cookie if consent is stored

        return redirect()->back();
    }
}
