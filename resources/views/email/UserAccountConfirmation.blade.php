@extends('email.scaffold.Main')

@section('content')
  <div>
    <h2 class="font-weight-light">
      Confirmación de cuenta
    </h2>
    <p class="text">
      ¡Bienvenido(a) a MedicPay!
      <br>
      <br>
      Para confirmar tu cuenta y establecer tu contraseña, haz clic en el siguiente botón:
    </p>
    <p>
      <a href="{{ $data->link }}" class="button button_success" style="color: white; text-decoration: none;">
        Confirmar cuenta
      </a>
    </p>
    <br>
    <p class="text">
      Tu información será revisada como parte de un proceso de validación. Si es necesario, te contactaremos para
      corroborar tus datos.
    </p>
    <br>
    <p class="contact">
      Si no solicitaste esta cuenta, puedes ignorar este correo.
    </p>
  </div>
@endsection