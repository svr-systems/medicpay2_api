@extends('email.scaffold.Main')

@section('content')
  <div>
    <h2 class="font-weight-light">Cuenta confirmada</h2>
    <p class="text">
    Por favor toma nota de la siguiente información confidencial:
    <br>
    <br>
    <b>E-mail</b>
    <br>
    {{ $data->email }}
    <br>
    <br>
    <b>Contraseña</b>
    <br>
    <i>La que capturaste al confirmar tu cuenta</i>
    </p>
    <p>
    <br>
    <a href="{{ $data->link }}" class="button button_info" style="color: white; text-decoration: none;">
      INICIAR SESIÓN
    </a>
    </p>
  </div>
@endsection