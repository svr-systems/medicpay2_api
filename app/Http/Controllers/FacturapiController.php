<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Doctor;
use App\Models\FacturapiData;
use App\Models\FiscalRegime;
use App\Models\Patient;
use App\Models\UserFiscalData;
use Crypt;
use Exception;
use Illuminate\Http\Request;
use Facturapi\Facturapi;
use stdClass;
use Throwable;
use Storage;

class FacturapiController extends Controller {
  public function patientConsultationStamp(Request $req) {
    try {
      $response = new \stdClass;
      $facturapi = new Facturapi(env('FACTURAPI_KEY'));

      $consultation_id = Crypt::decryptString($req->consultation_id);
      $consultation = Consultation::getItemById($consultation_id);
      $user_id = $consultation->patient->user->id;
      $user_fiscal_data = UserFiscalData::getItem($user_id);
      if (!$user_fiscal_data->id) {
        return $this->apiRsp(422, 'La información fiscal no ha sido cargada');
      }
      $fiscal_regimes = FiscalRegime::find($user_fiscal_data->fiscal_regime_id);

      $customer = [
        "legal_name" => $user_fiscal_data->name,
        "tax_id" => $user_fiscal_data->code,
        "tax_system" => $fiscal_regimes->code,
        "address" => [
          "zip" => $user_fiscal_data->zip,
          "country" => "MEX"
        ]
      ];

      try {
        $customer = $facturapi->Customers->create($customer);
      } catch (Throwable $err) {
        return $this->apiRsp(422, 'La información fiscal no coincide con los registros del SAT');
      }

      $item = [
        [
          "quantity" => 1,
          "discount" => 0,
          "product" => [
            "description" => "SERVICIOS MÉDICOS DE DOCTORES ESPECIALISTAS",
            "product_key" => "85121600",
            "unit_key" => "E48",
            "price" => $consultation->charge_amount,
            "tax_included" => false,
            "taxes" => [
              [
                "type" => "IVA",
                "rate" => 0
              ]
            ]
          ]
        ]
      ];

      $invoice = $facturapi->Invoices->create([
        "customer" => $customer->id,
        "items" => $item,
        "payment_form" => '04',
        "payment_method" => 'PUE',
        "use" => 'G03'
      ]);

      $pdf = $facturapi->Invoices->download_pdf($invoice->id);
      $xml = $facturapi->Invoices->download_xml($invoice->id);
      $response->pdf = base64_encode($pdf);
      $response->xml = base64_encode($xml);

      $file_path_xml = public_path('..') . "/storage/app/private/temp/" . time() . ".xml";
      $file_path_pdf = public_path('..') . "/storage/app/private/temp/" . time() . ".pdf";
      file_put_contents($file_path_xml, $xml);
      file_put_contents($file_path_pdf, $pdf);

      EmailController::sendInvoiceFiles(null, null, $file_path_xml, $file_path_pdf);
      Storage::delete($file_path_xml);
      Storage::delete($file_path_pdf);

      $consultation = Consultation::find($consultation_id);
      $consultation->invoice_id = $invoice->id;
      $consultation->save();

      return $this->apiRsp(
        200,
        'Registro retornado correctamente',
        ['item' => $response]
      );
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }

  static public function doctorConsultationStamp($consultation_id) {
    $response = new \stdClass;
    $consultation = Consultation::getItemById($consultation_id);
    $doctor = Doctor::find($consultation->doctor_id);
    $user_fiscal_data = UserFiscalData::getItem($doctor->user_id);

    if ($user_fiscal_data->id) {
      $facturapi_data = FacturapiData::getItemByUserFiscalData($user_fiscal_data->id);
      if ($facturapi_data) {

        $facturapi = new Facturapi(env('FACTURAPI_USER_KEY'));
        $organization_key = $facturapi->Organizations->getTestApiKey(
          $facturapi_data->organization_id
        );
        $facturapi = new Facturapi($organization_key);
        $fiscal_regimes = FiscalRegime::find($user_fiscal_data->fiscal_regime_id);

        // return $organization_key;
        $customer = [
          "legal_name" => $user_fiscal_data->name,
          "tax_id" => $user_fiscal_data->code,
          "tax_system" => $fiscal_regimes->code,
          "address" => [
            "zip" => $user_fiscal_data->zip,
            "country" => "MEX"
          ]
        ];

        $customer = $facturapi->Customers->create($customer);

        $item = [
          [
            "quantity" => 1,
            "discount" => 0,
            "product" => [
              "description" => "SERVICIOS MÉDICOS DE DOCTORES ESPECIALISTAS",
              "product_key" => "85121600",
              "unit_key" => "E48",
              "price" => $consultation->consultation_amount,
              "tax_included" => false,
              "taxes" => [
                [
                  "type" => "IVA",
                  "rate" => 0
                ]
              ]
            ]
          ]
        ];

        $invoice = $facturapi->Invoices->create([
          "customer" => $customer->id,
          "items" => $item,
          "payment_form" => '04',
          "payment_method" => 'PUE',
          "use" => 'G03'
        ]);

        $pdf = $facturapi->Invoices->download_pdf($invoice->id);
        $xml = $facturapi->Invoices->download_xml($invoice->id);
        $response->pdf = base64_encode($pdf);
        $response->xml = base64_encode($xml);

        $file_path_xml = public_path('..') . "/storage/app/private/temp/" . time() . ".xml";
        $file_path_pdf = public_path('..') . "/storage/app/private/temp/" . time() . ".pdf";
        file_put_contents($file_path_xml, $xml);
        file_put_contents($file_path_pdf, $pdf);

        EmailController::sendInvoiceFiles(null, null, $file_path_xml, $file_path_pdf);
        Storage::delete($file_path_xml);
        Storage::delete($file_path_pdf);

        // $consultation = Consultation::find($consultation_id);
        // $consultation->invoice_id = $invoice->id;
        // $consultation->save();


      }
    }
    return null;
  }
}
