<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
  /**
   * index
   *
   * @return void
   */
  public function index()
  {
    $invoices = Invoice::with('customer')->when(request()->q, function ($invoices) {
      $invoices = $invoices->where('invoice', 'like', '%' . request()->q . '%');
    })->latest()->paginate(5);

    //  * return with Api Resource
    return new InvoiceResource(true, 'List Data Invoices', $invoices);
  }


  /**
   * show data invoice
   *
   * @param  mixed $id
   * @return void
   */
  public function  show($id)
  {
    $invoice = Invoice::with(['orders.product', 'customer', 'city', 'province'])
      ->whereId($id)->first();

    //  * jika ada return true dan hasilkan data success with Api Resource
    if ($invoice) {
      return new InvoiceResource(true, 'Detail Data Invoice!', $invoice);
    }

    //  * jika tidak ada return false dan hasilkan data failed with Api Resource
    return new InvoiceResource(false, 'Detail Data Invoice Tidak Ditemukan!', null);
  }
}
