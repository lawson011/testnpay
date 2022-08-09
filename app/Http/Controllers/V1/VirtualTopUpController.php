<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\ApplicationProcessFailedException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Etranzact\XmlPostService;
use App\Http\Requests\NewVirtualTopUpRequest;

class VirtualTopUpController extends Controller
{
    public $vtu;

    /**
     * VirtualTopUpController constructor.
     * @param XmlPostService $vtu
     */
    public function __construct(XmlPostService $vtu)
    {
        $this->vtu = $vtu;
    }

    /**
     * @param NewVirtualTopUpRequest $request
     * @return mixed
     * @throws ApplicationProcessFailedException
     */
    public function menu(NewVirtualTopUpRequest $request)
    {
        $validated = $request->validated();
        return $this->vtu->menu($validated);
    }
}
