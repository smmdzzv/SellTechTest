<?php

namespace App\Http\Controllers;


use App\Http\Responses\UpdateResponse;
use App\Services\SDNService;
use Illuminate\Http\Request;
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
     */
    public function update()
    {
        try {
            $this->service->updateData('https://www.treasury.gov/ofac/downloads/sdn.xml');
            return response()->json(new UpdateResponse(true, "", 200));
        } catch (\Exception|\Throwable $e) {
            return response()->json(new UpdateResponse(false, "service unavailable", 503), 503);
        }
    }

    /**
     * Returns current data state: empty, updating, ok
     *
     * @return void
     */
    public function getState()
    {

    }

    /**
     * Returns individuals aka list
     *
     * @param Request $request
     * @return mixed
     */
    public function getNames(Request $request)
    {
        if (!$request->query('name'))
            throw new BadMethodCallException("Name parameter is missing");

        return $this->service->getUserNames($request->query('name'), $request->query('type'));
    }
}
