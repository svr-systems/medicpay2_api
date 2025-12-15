@extends('email.scaffold.Main')

@section('content')
  <div>
    <h2 class="font-weight-light">
      Pago confirmado
    </h2>
    <p class="text">
      Tu pago se realizó correctamente.
      <br>
      Adjuntamos el <strong>comprobante de pago</strong> (ticket) para tu referencia.
      <br>
      <br>
      Si necesitas factura, haz clic en el siguiente botón:
    </p>
    <p>
      <a href="{{ $data->link }}" class="button button_success" style="color: white; text-decoration: none;">
        Generar factura
      </a>
    </p>
  </div>
@endsection