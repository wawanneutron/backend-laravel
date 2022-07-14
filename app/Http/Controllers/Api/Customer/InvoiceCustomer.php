<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceCustomer extends Controller
{
  /**
   * index
   * data invoice customer
   * @return void
   */
  public function index()
  {
    $invoices = Invoice::when(request()->q, function ($invoices) {
      $invoices =  $invoices->where('invoice', 'like', '%' . request()->q . '%');
    })->where('customer_id', auth()->guard('api_customer')->user()->id)
      ->latest()
      ->paginate(5);

    // * return with Api Resource
    return new InvoiceResource(true, 'List Data Invoice: ' . auth()->guard('api_customer')->user()->name . '', $invoices);
  }

  /**
   * show
   * detail invoice customer
   * @param  mixed $id
   * @return void
   */
  public function show($snap_token)
  {
    $invoice = Invoice::with(['orders.product', 'customer', 'city', 'province'])
      ->where('customer_id', auth()->guard('api_customer')->user()->id)
      ->where('snap_token', $snap_token)
      ->latest();

    if ($invoice) {
      //  * return success with Api Resource
      return new InvoiceResource(true, 'Detail Data Invoice: ' . $invoice->snap_token . '', $invoice);
    }

    //  * return failed with Api Resource
    return new InvoiceResource(false, 'Detail Data Invoice Tidak Ditemukan!', null);
  }
}
