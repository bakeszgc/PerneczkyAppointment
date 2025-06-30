<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BarberController extends Controller
{
    public function index()
    {
        $barbers = Barber::withTrashed()->get();
        return view('barber.index',['barbers' => $barbers]);
    }

    public function create(Request $request)
    {

        $query = $request->input('query');

        $users = User::when($query, function ($q) use ($query) {
            $q->where('first_name','like',"%$query%")
            ->orWhere('last_name','like',"%$query%")
            ->orWhere('email','like',"%$query%")
            ->orWhere('tel_number','like',"%$query%");
        })->paginate(10);

        return view('barber.create',['users' => $users]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => ['required','integer','exists:users,id']
        ]);

        $barber = Barber::create([
            'user_id' => $request->user_id,
            'is_visible' => false
        ]);

        return redirect()->route('barbers.show',$barber)->with('success','New barber has been created successfully!');
    }

    public function show(Request $request, Barber $barber)
    {
        $request->validate([
            'showProfile' => 'nullable|boolean',
            'showPicture' => 'nullable|boolean'
        ]);

        $showProfile = $request->showProfile ?? true;
        $showPicture = $request->showPicture ?? false;

        return view('barber.show',[
            'barber' => $barber,
            'showProfile' => $showProfile,
            'showPicture' => $showPicture
        ]);
    }

    public function update(Request $request, Barber $barber)
    {
        $user = $barber->user;

        $request->validate([
            'first_name' => ['required','string','max:255'],
            'last_name' => ['required','string','max:255'],
            'display_name' => ['nullable','string','max:255',Rule::unique('barbers','display_name')->ignore($barber->id)],
            'email' => ['required','email',Rule::unique('users','email')->ignore($user->id)],
            'date_of_birth' => ['required','date','before_or_equal:today'],
            'telephone_number' => ['required','starts_with:+,0','numeric',Rule::unique('users','tel_number')->ignore($user->id)]
        ]);

        $barber->update([
            'display_name' => $request->display_name,
            'is_visible' => $request->is_visible ? true : false
        ]);

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'date_of_birth' => $request->date_of_birth,
            'tel_number' => $request->telephone_number
        ]);

        return redirect()->route('barbers.show',['barber' => $barber,'showProfile' => true])->with('success',$barber->getName() . "'s personal details have been updated successfully!");
    }

    public function destroy(Barber $barber)
    {
        $barber->update([
            'is_visible' => false
        ]);        
        $barber->delete();
        return redirect()->route('barbers.show',$barber)->with('success',$barber->getName() . "'s barber access has been removed successfully!");
    }

    public function restore(Barber $barber)
    {
        $barber->restore();
        return redirect()->route('barbers.show',$barber)->with('success',$barber->getName() . "'s barber access has been restored successfully!");
    }
}
