<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function password(Request $request)
    {
        $validated = $request->validateWithBag('password', [
            'current_password' => 'required|current_password',
            'password'         => 'required|min:8|confirmed',
        ]);

        $request->user()->update(['password' => $validated['password']]);

        return back()->with('status', 'Contraseña actualizada correctamente.');
    }
}
