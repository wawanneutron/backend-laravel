<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class DashboardCustomer extends Controller
{
  /**
   * index
   *
   * @return void
   */
  public function index()
  {
    // * count invoice pending, success, expired, failed
    $pending = Invoice::where('status', 'pending')
      ->where('customer_id', auth()->guard('api_customer')->user()->id)
      ->count();
    $success = Invoice::where('status', 'success')
      ->where('customer_id', auth()->guard('api_customer')->user()->id)
      ->count();
    $expired = Invoice::where('status', 'expired')
      ->where('customer_id', auth()->guard('api_customer')->user()->id)
      ->count();
    $failed = Invoice::where('status', 'failed')
      ->where('customer_id', auth()->guard('api_customer')->user()->id)
      ->count();

    // * return response json
    return response()->json([
      'success' => true,
      'message' => 'Data Statistik Dashboard Customer',
      'data'    => [
        'count' => [
          'pending' => $pending,
          'success' => $success,
          'expired' => $expired,
          'failed'  => $failed
        ]
      ]
    ], 200);
  }
}
