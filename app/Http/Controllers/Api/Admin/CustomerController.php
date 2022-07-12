<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
  /**
   * index
   * * show data customer
   * @return void
   */
  public function index()
  {
    $customers = Customer::when(request()->q, function ($customers) {
      $customers = $customers->whereName('like', '%' . request()->q . '%');
    })->latest()->paginate(5);

    // * return with APi Resource
    return new CustomerResource(true, 'List Data Customers', $customers);
  }
}
