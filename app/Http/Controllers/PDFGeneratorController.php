<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use DB;
use Session;
use App\Models\Front_model;
use Request;
use Input;
use PDF;
use Illuminate\Support\Facades\Response;

class PDFGeneratorController extends Controller {

  public function HtmlToPDF()
  {    
      $view = \View::make('HtmlToPDF');
      $html_content = $view->render();


      PDF::SetTitle('Sample PDF');
      PDF::AddPage();
      PDF::writeHTML($html_content, true, false, true, false, '');

      PDF::Output(uniqid().'_SamplePDF.pdf');
  }
  
  public function proformaInvoiceReport()
  {
    $session = Session::get(null);
    $cars = Front_model::getAllCar([$session['negotiation']->car_id]);
    $car = $cars[0];
    $destination_port = Front_model::getDestinationPort($session['proforma_invoice']->port_destination);

    $view = \View::make('report.proforma_invoice',compact('session','car','destination_port'));
    $html_content = $view->render();

    PDF::SetTitle('Proforma Invoice');
    PDF::AddPage();
    PDF::writeHTML($html_content, true, false, true, false, '');

    return Response::make(PDF::Output(uniqid().'salesorder.pdf'), 200, array('Content-Type' => 'application/pdf'));
  }
  
  public function invoiceReport()
  {
    
    $session = Session::get(null);
    $cars = Front_model::getAllCar([$session['negotiation']->car_id]);
    $car = $cars[0];
    $destination_port = Front_model::getDestinationPort($session['proforma_invoice']->port_destination);
    
    $view = \View::make('report.invoice',compact('session','car','destination_port'));
    $html_content = $view->render();

    PDF::SetTitle('Sales Order');
    PDF::AddPage();
    PDF::writeHTML($html_content, true, false, true, false, '');

    return Response::make(PDF::Output(uniqid().'invoice.pdf'), 200, array('Content-Type' => 'application/pdf'));
  }
  
}
