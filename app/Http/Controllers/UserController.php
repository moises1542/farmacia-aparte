<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->get();

        return view('settings.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();

        return view('settings.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'role_id'  => 'required|integer|exists:roles,id',
            'name'     => 'required|max:191',
            'dni'      => 'required|digits:8|unique:users',
            'phone'    => 'nullable|digits_between:6,15',
            'tuition'  => 'nullable|max:20',
            'email'    => 'required|email|max:191|unique:users',
            'password' => 'required|min:8',
        ]);

        User::create($validated);

        return to_route('users.index')
            ->with('ok', 'Usuario registrado correctamente.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();

        return view('settings.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'role_id'  => 'required|integer|exists:roles,id',
            'name'     => 'required|max:191',
            'dni'      => [
                'required',
                'digits:8',
                Rule::unique('users', 'dni')->ignore($user->id),
            ],
            'phone'    => 'nullable|digits_between:6,15',
            'tuition'  => 'nullable|max:20',
            'email'    => [
                'required',
                'email',
                'max:191',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => 'nullable|min:8',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        if ($user->id === $request->user()->id && (int) $validated['role_id'] !== $user->role_id) {
            return back()
                ->withErrors(['role_id' => 'No puedes cambiar tu propio rol.'])
                ->withInput();
        }

        $user->update($validated);

        return to_route('users.index')
            ->with('ok', 'Usuario actualizado correctamente.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return to_route('users.index')
                ->with('error', 'No puedes eliminar tu propio usuario.');
        }

        if ($user->sales()->exists()) {
            return to_route('users.index')
                ->with('error', 'No se puede eliminar: tiene ventas asociadas.');
        }

        $user->delete();

        return to_route('users.index')
            ->with('ok', 'Usuario eliminado correctamente.');
    }
}
