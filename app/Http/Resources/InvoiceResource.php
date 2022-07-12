<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use phpDocumentor\Reflection\Types\Parent_;

class InvoiceResource extends JsonResource
{
  // * public property
  public $status;
  public $message;

  /**
   * __construct
   * * ketika method InvoiceResource di panggil function ini akan pertama kali di jalankan
   * @param  mixed $status
   * @param  mixed $message
   * @param  mixed $resource
   * @return void
   */
  public function __construct($status, $message, $resource)
  {
    Parent::__construct($resource);

    $this->status = $status;
    $this->message = $message;
  }
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request)
  {
    return [
      'success' => $this->status,
      'message' => $this->message,
      'data'    => $this->resource
    ];
  }
}
