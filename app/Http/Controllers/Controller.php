<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $itemResource;

    protected function createResource($data)
    {
        if (!$this->itemResource) {
            return $data;
        }

        if ($data instanceof Collection) {
            return $this->itemResource::collection($data);
        }

        return new $this->itemResource($data);
    }
}
