@extends('email.scaffold.Main')

@section('content')
  <div>
    <h2 class="font-weight-light">
      Restablecimiento de contraseña
    </h2>
    <p class="text">
      Se ha recibido una solicitud para restablecer la contraseña de tu cuenta de MedicPay.
      <br>
      <br>
      Para continuar, haz clic en el siguiente botón:
    </p>
    <p>
      <a href="{{ $data->link }}" class="button button_info" style="color: white; text-decoration: none;">
        Restablecer contraseña
      </a>
    </p>
    <br>
    <p class="contact">
      Por seguridad, este enlace tendrá vigencia de 5 minutos.
      <br>
      <br>
      Si no solicitaste este cambio, ignora este correo. No se realizará ninguna modificación en tu cuenta.
    </p>
  </div>
@endsection