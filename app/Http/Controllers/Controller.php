<?php

namespace App\Http\Controllers;


use App\Services\SDNService;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    private SDNService $service;

    public function __construct(SDNService $service)
    {
        $this->service = $service;
    }

    /**
     * Updates data
     *
     * @return void
     */
    public function update(){
        $this->service->updateData('https://www.treasury.gov/ofac/downloads/sdn.xml');
    }

    /**
     * Returns current data state: empty, updating, ok
     *
     * @return void
     */
    public function state(){

    }
}
