@extends('layouts.guest')

@section('title', 'Login - HidroGest')

@section('content')
<div class="card shadow-lg border-0 rounded-lg mt-5">
    <div class="card-header">
        <h3 class="text-center font-weight-light my-4">HidroGest</h3>
    </div>
    <div class="card-body">
        {{-- TODO: cambiar action a route('login') cuando I1 implemente Breeze/auth --}}
        <form method="POST" action="#">
            @csrf
            <div class="form-floating mb-3">
                <input class="form-control" id="email" name="email" type="email" placeholder="name@example.com" value="{{ old('email') }}" required autofocus />
                <label for="email">Correo electrónico</label>
            </div>
            <div class="form-floating mb-3">
                <input class="form-control" id="password" name="password" type="password" placeholder="Password" required />
                <label for="password">Contraseña</label>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" id="remember" name="remember" type="checkbox" />
                <label class="form-check-label" for="remember">Recordar sesión</label>
            </div>
            @error('email')
            <div class="text-danger small mb-3">{{ $message }}</div>
            @enderror
            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                <button class="btn btn-primary" type="submit">Iniciar sesión</button>
            </div>
        </form>
    </div>
</div>
@endsection