<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SOLMETEC | MedicPay</title>
  <style>
    @font-face {
      font-family: "Roboto";
      src: url("{{ storage_path('app/public') . '/fonts/Roboto.ttf' }}") format("truetype");
    }

    body {
      background-color: #1A1A1A;
      color: #BCBCBC;
    }

    .a-color {
      text-decoration: none;
      font-size: 19px;
    }

    .content-box {
      width: 80%;
      padding: 50px;
      border-radius: 15px;
      box-shadow: 0px 5px 25px 20px #151515;
      text-align: center;
      font-family: "Roboto", sans-serif !important;
    }

    .font-weight-light {
      font-weight: 300 !important;
    }

    .button {
      cursor: pointer;
      font-weight: bold;
      padding: 8px 12px 8px 12px;
      border-radius: 4px;
      box-shadow: 0px 3px 1px -2px rgba(0, 0, 0, 0.2), 0px 2px 2px 0px rgba(0, 0, 0, 0.14), 0px 1px 5px 0px rgba(0, 0, 0, 0.12);
    }

    .button_success {
      background-color: #4caf50 !important;
      border-color: #4caf50 !important;
    }

    .button_info {
      background-color: #2196f3 !important;
      border-color: #2196f3 !important;
    }

    .text {
      font-size: 13px;
      padding-top: 6px;
    }

    .text_sub,
    .contact {
      font-size: 11px;
      padding-top: 12px;
    }

    .footer {
      font-size: 9px;
      padding-top: 24px;
    }
  </style>
</head>

<body>
  <main>
    <div class="content-box" style="background-color: #1E1E1E;">
      <div>
        <img
          src="data:image/png;base64, {{ base64_encode(file_get_contents(storage_path('app/public') . '/logo.png')) }}"
          height="80px" alt="Logo">
      </div>
      @yield('content')
      @include('email.scaffold.Contact')
      @include('email.scaffold.Footer')
    </div>
  </main>
</body>

</html>