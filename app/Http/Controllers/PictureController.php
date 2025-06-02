<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;

class PictureController extends Controller
{
    public function uploadCropped(Request $request) {

        $validator = Validator::make($request->all(), [
            'croppedImg' => 'required|image|mimes:png,jpg,jpeg,gif|max:4096'
        ]);

        if ($validator->fails()) {
            return redirect()->route('users.show',['user' => auth()->user(), 'showPicture' => true])->withErrors($validator);
        }

        $user = auth()->user();

        if ($request->hasFile('croppedImg')) {
            $avatarName = 'pfp_' . time() . '.' . request()->croppedImg->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('pfp',$request->croppedImg,$avatarName);
            
            $user->update([
                'pfp_path' => $avatarName
            ]);
        }
        return redirect()->route('users.show',['user' => auth()->user(), 'showPicture' => true])->with('success','Your profile picture has been updated successfully!');
    }
}