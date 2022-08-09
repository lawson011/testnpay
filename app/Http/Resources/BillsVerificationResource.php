<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BillsVerificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this['slug'] === 'dstv' || $this['slug'] === 'gotv') {
            return $this->defaultResponse($this);
        }

        if ($this['slug'] === 'spectranetprepaid') {
            return $this->spectranet($this);
        }

        if ($this['slug'] === 'lccprepaid') {
            return $this->lcc($this);
        }
    }

    public function modifyData($data)
    {
        $response = explode('~', $data);

        return $response;
    }

    public function defaultResponse($params)
    {
        return [
            'name' => $this->modifyData($params['name'])[2],
            'account' => $params['account'],
            'products' => $params['products']['Product'],
            'amount' => '',
        ];
    }

    public function lcc($params)
    {
        return [
            'name' => $params['name'],
            'account' => $params['account'],
            'amount' => null,
            'products' => null
        ];
    }

    public function spectranet($params)
    {
        $response = json_decode($params['name'], true);

        return [
            'name' => $response['firstname'] . ' ' . $response['lastname'],
            'amount' => $response['amount'],
            'products' => null,
            'account' => $params['account'],
        ];
    }
}
