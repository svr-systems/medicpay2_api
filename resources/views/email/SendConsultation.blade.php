@extends('email.scaffold.Main')

@section('content')
  <div>
    <h2 class="font-weight-light">
      Consulta registrada
    </h2>
    <p class="text">
      Se ha registrado una consulta en MedicPay.
    </p>
    <p class="text">
      <strong>Folio: </strong>
      <br>
      {{ $data->folio }}
      <br>
      <strong>Monto: </strong>
      <br>
      ${{ $data->charge_amount }} MXN
    </p>
    <p class="contact">
      <strong>ID: </strong>
      <br>
      {{ $data->uiid }}
      <br>
      <strong>Fecha: </strong>
      <br>
      {{ $data->date }}
      <br>
      <strong>Medico: </strong>
      <br>
      {{ $data->doctor }}
    </p>
    <p class="text">
      Para realizar el pago tienes estas opciones:
      <br>
      <br>
      <b>1) PAGO EN KIOSKO</b>
      <br>
      Adjuntamos un PDF con el <strong>código QR</strong> para pagar en kiosko.
      <br>
      Solo debes <strong>escanear el QR</strong> o <strong>teclear el folio</strong> de la consulta.
      <br>
      <br>
      <b>2) PAGO EN LÍNEA</b>
      <br>
      Si prefieres pagar en línea, haz clic en el siguiente botón para continuar:
    </p>
    <p>
      <a href="{{ $data->link }}" class="button button_success" style="color: white; text-decoration: none;">
        Pagar en línea
      </a>
    </p>
  </div>
@endsection