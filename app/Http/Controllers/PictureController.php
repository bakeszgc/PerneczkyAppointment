<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\User;
use App\Models\Barber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PictureController extends Controller
{
    public function uploadCropped(Request $request, User $user) {

        if (!auth()->user()->is_admin && auth()->user()->id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $validator = Validator::make($request->all(), [
            'croppedImg' => 'required|image|mimes:png,jpg,jpeg,gif|max:4096',
            'source' => ['nullable','string']
        ]);

        if ($validator->fails()) {
            return redirect()->route('users.show',['user' => auth()->user(), 'showPicture' => true])->withErrors($validator);
        }

        if ($request->hasFile('croppedImg')) {
            $avatarName = 'pfp_' . time() . '.' . request()->croppedImg->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('pfp',$request->croppedImg,$avatarName);
            
            $user->update([
                'pfp_path' => $avatarName
            ]);
        }

        $source = $request->source ?? 'user';

        if ($source === 'admin') {
            return redirect()->route('barbers.show',['barber' => $user->barber, 'showPicture' => true])
                ->with('success','The profile picture has been updated successfully!');
        } else {
            return redirect()->route('users.show',['user' => $user, 'showPicture' => true])
                ->with('success','Your profile picture has been updated successfully!');
        }
    }
}