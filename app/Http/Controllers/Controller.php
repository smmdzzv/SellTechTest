<?php

namespace App\Http\Controllers;


use App\Enums\UpdateRequestStateEnum;
use App\Http\Responses\UpdateResponse;
use App\Services\RequestStatusService;
use App\Services\SDNService;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    private SDNService $service;
    private RequestStatusService $requestStatusService;

    public function __construct(SDNService $service, RequestStatusService $requestStatusService)
    {
        $this->service = $service;

        $this->requestStatusService = $requestStatusService;
    }

    /**
     * Updates data
     *
     */
    public function update()
    {
        try {

            $this->requestStatusService->apply(UpdateRequestStateEnum::UPDATING);

            $this->service->updateData('https://www.treasury.gov/ofac/downloads/sdn.xml');

            $this->requestStatusService->apply(UpdateRequestStateEnum::SUCCESS);

            return response()->json(new UpdateResponse(true, "", 200));

        } catch (\Exception|\Throwable $e) {

            $this->requestStatusService->apply(UpdateRequestStateEnum::FAILED);

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
