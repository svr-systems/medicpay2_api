<?php

namespace App\Http\Controllers;

use App\Models\FacturapiData;
use App\Models\FiscalRegime;
use App\Models\UserFiscalData;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Facturapi\Facturapi;
use stdClass;
use Throwable;

class FacturapiDataController extends Controller {

  public function index(Request $req) {
    try {
      return $this->apiRsp(
        200,
        'Registros retornados correctamente',
        ['item' => FacturapiData::getItem($req)]
      );
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }
  public static function errMsg($err) {
    $err_str = $err->getMessage();
    $err_str = (array) json_decode(substr($err_str, strpos($err_str, '{'), strpos($err_str, '}')), true);

    switch ($err_str['message']) {
      case 'Este RFC del receptor no existe en la lista de RFC inscritos no cancelados del SAT':
        $err_str = 'FISCAL: RFC incorrecto';
        break;
      case 'La clave del campo RegimenFiscalReceptor debe corresponder con el tipo de persona (física o moral).':
        $err_str = 'FISCAL: Régimen incorrecto';
        break;
      case "El nombre o razón social del receptor no coincide con el RFC registrado en el SAT; recuerda que con CFDI 4.0, debe ingresarse en mayúsculas y sin acentos, además ya no debes incluir el régimen societario (ej. \"S.A. de C.V.\")":
        $err_str = "FISCAL: Nombre | Razón social incorrecto, ingresar sin acentos y no incluir el régimen societario (ej. \"S.A. de C.V.\")";
        break;
      case 'El campo DomicilioFiscalReceptor del receptor, debe pertenecer al nombre asociado al RFC registrado en el campo Rfc del Receptor.':
        $err_str = 'FISCAL: CP incorrecto';
        break;
      default:
        $err_str = $err_str['message'];
    }

    return $err_str;
  }

  public static function createCustomer($data) {
    $fiscal_regime = FiscalRegime::find($data->fiscal_regime_id);

    return [
      'tax_id' => $data->code,
      'legal_name' => $data->name,
      'address' => ['zip' => $data->zip],
      'tax_system' => $fiscal_regime->code
    ];
  }

  public static function validCustomer($data) {
    $fapi = new Facturapi(env('FACTURAPI_KEY'));
    $rsp = new stdClass;
    $rsp->msg = null;
    $rsp->err = null;

    $customer = FacturapiDataController::createCustomer($data);

    try {
      $customer = $fapi->Customers->create($customer);
      $fapi->Customers->delete($customer->id);

      return $rsp;
    } catch (Throwable $err) {
      $rsp->msg = FacturapiDataController::errMsg($err);
      $rsp->err = $err;

      return $rsp;
    }
  }

  public function storeOrganization(Request $req) {
    DB::beginTransaction();
    try {
      $fapi = new Facturapi(env('FACTURAPI_USER_KEY'));
      $rsp = new stdClass;
      $rsp->msg = null;
      $rsp->err = null;

      $item = UserFiscalData::getDataByUser($req->user()->id);

      $facturapi_data = FacturapiData::getItemByUserFiscalData($item->id);
      $organization_id = null;
      if ($facturapi_data) {
        $organization_id = $facturapi_data->organization;
      }

      if (!$organization_id) {
        try {
          $organization = $fapi->Organizations->create(array(
            'name' => $item->uiid
          ));

          $rsp->fiscal_organization = $organization->id;

          $organization = $fapi->Organizations->updateCustomization(
            $rsp->fiscal_organization,
            [
              'pdf_extra' => [
                'round_unit_price' => true,
                "tax_breakdown" => false,
                "ieps_breakdown" => false
              ]
            ],
          );
          $organization_id = $organization->id;
        } catch (Throwable $err) {
          DB::rollback();
          return $this->apiRsp(422, FacturapiDataController::errMsg($err));
        }
      }

      $fiscal_regime = FiscalRegime::find($item->fiscal_regime_id, ['code']);
      try {
        $organization = $fapi->Organizations->updateLegal(
          $organization_id, [
            'name' => $item->uiid,
            'legal_name' => $item->name,
            'tax_system' => $fiscal_regime->code,
            'address' => [
              'zip' => $item->zip,
              'street' => '-',
              'exterior' => '-',
            ]
          ]
        );
      } catch (Throwable $err) {
        DB::rollback();
        return $this->apiRsp(422, FacturapiDataController::errMsg($err));
      }
      try {
        
        $content = \file_get_contents(storage_path('app/svr.cer'));
        $is_value_found = strpos($content, $item->name);
        if (!$is_value_found) {
          return $this->apiRsp(422, 'La razón social no coincide con el archivo cargado');
        } 

        $is_value_found = strpos($content, $item->code);
        if (!$is_value_found) {
          return $this->apiRsp(422, 'El RFC social no coincide con el archivo cargado');
        } 

        $organization = $fapi->Organizations->uploadCertificate(
          $organization_id,
          [
            'cerFile' => $req->cer_doc,
            'keyFile' => $req->key_doc,
            'password' => $req->password,
          ],
        );
      } catch (Throwable $err) {
        DB::rollback();
        return $this->apiRsp(422, FacturapiDataController::errMsg($err));
      }

      if (!$facturapi_data) {
        $facturapi_data = new FacturapiData;
        $facturapi_data->created_by_id = $req->user()->id;
      }

      $facturapi_data->updated_by_id = $req->user()->id;
      $facturapi_data->user_fiscal_data_id = $item->id;
      $facturapi_data->organization = $organization_id;
      $facturapi_data->certificate_updated_at = Carbon::parse($organization->certificate->updated_at)->format('Y-m-d H:i:s');
      $facturapi_data->certificate_expires_at = Carbon::parse($organization->certificate->expires_at)->format('Y-m-d H:i:s');
      $facturapi_data->certificate_serial_number = $organization->certificate->serial_number;
      // $facturapi_data->certificate_updated_at = date('Y-m-d H:i:s');
      // $facturapi_data->certificate_expires_at = date('Y-m-d H:i:s');
      // $facturapi_data->certificate_serial_number = "-------";

      $facturapi_data->save();
      DB::commit();
      return $this->apiRsp(
        200,
        'Correctamente',
        ['items' => $facturapi_data]
      );

    } catch (Throwable $err) {
      DB::rollback();
      return $this->apiRsp(500, null, $err);
    }
  }
}
