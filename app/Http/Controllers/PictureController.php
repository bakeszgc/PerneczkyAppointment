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

        $user = auth()->user();

        if ($request->hasFile('croppedImg')) {
            $avatarName = 'pfp_' . time() . '.' . request()->croppedImg->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('pfp',$request->croppedImg,$avatarName);
            
            $user->update([
                'pfp_path' => $avatarName
            ]);
        }
        return redirect()->back()->with('success','asd');
    }
}