<?php

namespace App\Http\Controllers;

use Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Whitecube\LaravelCookieConsent\Facades\Cookies;

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
        
        if (Cookies::hasConsentFor('lang_pref')) {
            return redirect()->back()->withCookie(cookie('lang_pref',$lang,60*24*400));
        } else {
            return redirect()->back();
        }
    }
}
