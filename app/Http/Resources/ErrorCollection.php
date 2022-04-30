<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ErrorCollection extends ResourceCollection
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
            'statusMessage' => 'Invalid Credentials',
            'httpCode'      => '403',
            'errorCode'     => '0',
            'response'      => $this->collection
        ];
    }
}
