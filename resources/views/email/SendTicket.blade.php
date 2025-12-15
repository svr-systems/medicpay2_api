@extends('email.scaffold.Main')

@section('content')
  <div>
    <h2 class="font-weight-light">Ticket de compra</h2>
    <p class="text">
      <a href="{{ $data->link }}" class="button button_success" style="color: white; text-decoration: none;">
      Quiero facturar
    </a>
    </p>
  </div>
@endsection