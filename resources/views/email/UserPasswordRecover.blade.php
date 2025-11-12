@extends('email.scaffold.Main')

@section('content')
  <div>
    <h2 class="font-weight-light">Se ha solicitado recuperar tu contraseña</h2>
    <p class="text">
    Para restablecer la contraseña de tu cuenta, haz clic en el siguiente botón:
    </p>
    <p>
    <a href="{{ $data->link }}" class="button button_info" style="color: white; text-decoration: none;">
      RESTABLECER CONTRASEÑA
    </a>
    </p>
    <p class="text_sub">
    La acción del botón solo estará disponible por 5 minutos
    <br />
    <br />
    Si no fuiste tú quien hizo esta solicitud, puedes ignorar este correo con la seguridad de que tu contraseña no se
    modificará
    </p>
  </div>
@endsection