<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    // تحديث الشامل: الاسم، الصورة، وكلمة المرور (اختيارية)
    public function update(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name'   => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // إيلا بغى يبدل المودباس عاد كنفرضوا عليه الشروط، وإيلا خلا خاوين ماكنقيسوهش
        if ($request->filled('current_password') || $request->filled('password')) {
            $rules['current_password'] = ['required', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail('Le mot de passe actuel est incorrect.');
                }
            }];
            $rules['password'] = 'required|min:6|confirmed';
        }

        $request->validate($rules);

        // تحديث الاسم
        $user->name = $request->name;

        // تحديث الصورة
        if ($request->hasFile('avatar')) {
            if ($user->avatar && file_exists(public_path($user->avatar))) {
                @unlink(public_path($user->avatar));
            }

            $image = $request->file('avatar');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/avatars'), $imageName);

            $user->avatar = 'uploads/avatars/' . $imageName;
        }

        // تحديث كلمة المرور إيلا كانت مدخلة
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profil mis à jour avec succès !');
    }
}