@extends('email.scaffold.Main')

@section('content')
  <div>
    <h2 class="font-weight-light">
      Cuenta confirmada
    </h2>
    <p class="text">
      ¡Listo! Tu cuenta en MedicPay ha sido confirmada.
      <br>
      Correo registrado: {{ $data->email }}
      <br>
      <br>
      Tu información será revisada como parte de un proceso de validación. Si es necesario, te contactaremos para
      corroborar tus datos.
    </p>
    <p>
      <a href="{{ $data->link }}" class="button button_info" style="color: white; text-decoration: none;">
        Iniciar sesión
      </a>
    </p>
    <br>
    <p class="contact">
      Por seguridad, MedicPay nunca te solicitará tu contraseña por correo.
    </p>
  </div>
@endsection