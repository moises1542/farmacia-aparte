<div class="mb-3">
    <label for="name" class="form-label">Nombre del usuario</label>
    <input type="text" name="name" id="name"
        class="form-control @error('name') is-invalid @enderror"
        value="{{ old('name', $user->name ?? '') }}">
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="dni" class="form-label">DNI</label>
    <input type="text" name="dni" id="dni" maxlength="8"
        class="form-control @error('dni') is-invalid @enderror"
        value="{{ old('dni', $user->dni ?? '') }}">
    @error('dni')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="phone" class="form-label">Teléfono</label>
    <input type="text" name="phone" id="phone" maxlength="15"
        class="form-control @error('phone') is-invalid @enderror"
        value="{{ old('phone', $user->phone ?? '') }}">
    @error('phone')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="tuition" class="form-label">Colegiatura</label>
    <input type="text" name="tuition" id="tuition" maxlength="20"
        class="form-control @error('tuition') is-invalid @enderror"
        value="{{ old('tuition', $user->tuition ?? '') }}">
    @error('tuition')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="email" class="form-label">Correo</label>
    <input type="email" name="email" id="email"
        class="form-control @error('email') is-invalid @enderror"
        value="{{ old('email', $user->email ?? '') }}">
    @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="password" class="form-label">Contraseña</label>
    <input type="password" name="password" id="password" autocomplete="new-password"
        class="form-control @error('password') is-invalid @enderror">
    @error('password')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    @isset($user)
        <div class="form-text">Déjala en blanco para conservar la actual.</div>
    @endisset
</div>

<div class="mb-3">
    <label for="role_id" class="form-label">Rol</label>
    <select name="role_id" id="role_id"
        class="form-select @error('role_id') is-invalid @enderror">
        <option value="">Selecciona un rol</option>
        @foreach ($roles as $role)
            <option value="{{ $role->id }}"
                @selected(old('role_id', $user->role_id ?? '') == $role->id)>
                {{ $role->name }}
            </option>
        @endforeach
    </select>
    @error('role_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<button type="submit" class="btn btn-primary text-white">Guardar</button>
<a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
