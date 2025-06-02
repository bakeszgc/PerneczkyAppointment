<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PictureController extends Controller
{
    public function uploadCropped(Request $request) {
        $request->validate([
            'croppedImg' => 'required|image|mimes:png,jpg,jpeg,gif|max:2048'
        ]);

        if ($request->hasFile('croppedImg')) {
            $avatarName = 'pfp_' . time() . '.' . request()->croppedImg->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('pfp',$request->croppedImg,$avatarName);
            $avatarPath = $avatarName;
            
        }
        return redirect()->back()->with('success','asd');
    }
}