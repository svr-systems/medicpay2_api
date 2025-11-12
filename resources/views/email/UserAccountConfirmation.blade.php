@extends('email.scaffold.Main')

@section('content')
  <div>
    <h2 class="font-weight-light">Confirmación de cuenta</h2>
    <p class="text">
    ¡Te damos la bienvenida a Escolar!
    <br>
    <br>
    Para confirmar tu cuenta, haz clic en el siguiente botón:
    </p>
    <p>
    <a href="{{ $data->link }}" class="button button_success" style="color: white; text-decoration: none;">
      CONFIRMAR CUENTA
    </a>
    </p>
  </div>
@endsection