@extends('email.scaffold.Main')

@section('content')
  <div>
    <h2 class="font-weight-light">Consulta</h2>
    <p class="text">
      <strong>ID: </strong> {{ $data->uiid }} <br>
      <strong>Folio: </strong> {{ $data->folio }} <br>
      <strong>Fecha: </strong> {{ $data->date }} <br>
      <strong>Medico: </strong> {{ $data->doctor }} <br>
      <strong>Paciente: </strong> {{ $data->patient }} <br>
      <strong>Monto: </strong> ${{ $data->charge_amount }} MXN <br>
    </p>
    <p class="text">
      <a href="{{ $data->link }}" class="button button_success" style="color: white; text-decoration: none;">
      Quiero pagar en línea
    </a>
    </p>
  </div>
@endsection