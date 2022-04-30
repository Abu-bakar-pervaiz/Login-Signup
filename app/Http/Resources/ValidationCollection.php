<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ValidationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'status'        => 'error',
            'statusMessage' => 'Validation Error',
            'httpCode'      => '400',
            'errorCode'     => '1000',
            'response'      => $this->collection
        ];;
    }
}
