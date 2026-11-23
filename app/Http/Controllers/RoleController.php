<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();

        return view('settings.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('settings.roles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|max:50|unique:roles',
            'description' => 'nullable|max:255',
        ]);

        Role::create($validated);

        return to_route('roles.index')
            ->with('ok', 'Rol registrado correctamente.');
    }

    public function edit(Role $role)
    {
        return view('settings.roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name'        => [
                'required',
                'max:50',
                Rule::unique('roles', 'name')->ignore($role->id),
            ],
            'description' => 'nullable|max:255',
        ]);

        if ($role->name === Role::ADMIN && $validated['name'] !== Role::ADMIN) {
            return back()
                ->withErrors(['name' => 'El rol Administrador no se puede renombrar.'])
                ->withInput();
        }

        $role->update($validated);

        return to_route('roles.index')
            ->with('ok', 'Rol actualizado correctamente.');
    }

    public function destroy(Role $role)
    {
        if ($role->users()->exists()) {
            return to_route('roles.index')
                ->with('error', 'No se puede eliminar: tiene usuarios asociados.');
        }

        $role->delete();

        return to_route('roles.index')
            ->with('ok', 'Rol eliminado correctamente.');
    }
}
