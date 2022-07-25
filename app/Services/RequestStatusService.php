<?php
/**
 * @author Sultonazar Mamadazizov <sultonazar.mamadazizov@mail.ru>
 * @copyright Copyright (c) 2022, coded.tj
 */

namespace App\Services;

use App\Enums\UpdateRequestStateEnum;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RequestStatusService
{
    /**
     * Updates upload request status
     *
     * @param UpdateRequestStateEnum $state
     * @return void
     */
    public function apply(UpdateRequestStateEnum $state)
    {
        switch ($state) {
            case UpdateRequestStateEnum::UPDATING:
                DB::table('update_request_states')->insert([
                    'created_at' => Carbon::now(),
                    'state' => UpdateRequestStateEnum::UPDATING->value
                ]);
                break;

            case UpdateRequestStateEnum::FAILED:
            case UpdateRequestStateEnum::SUCCESS:
                $this->updateRequestStatusInDb($state);
                break;
        }
    }

    /**
     * Returns current status code
     *
     * @return array
     */
    public function status(): array
    {
        //Check last request state first
        $record = DB::table('update_request_states')
            ->orderBy('id', 'desc')
            ->first();

        if ($record) {
            if ($record->state === UpdateRequestStateEnum::UPDATING->value)
                return ['result' => false, 'info' => 'updating'];
            if ($record->state === UpdateRequestStateEnum::SUCCESS->value)
                return ['result' => true, 'info' => 'ok'];
        }

        //Check for any successful request
        if (DB::table('update_request_states')
            ->where('state', UpdateRequestStateEnum::SUCCESS->value)
            ->first())
            return ['result' => true, 'info' => 'ok'];
        else
            return ['result' => false, 'info' => 'empty'];

    }

    private function updateRequestStatusInDb(UpdateRequestStateEnum $state): int
    {
        return DB::table('update_request_states')
            ->orderBy('id', 'desc')
            ->take(1)
            ->update([
                'terminated_at' => Carbon::now(),
                'state' => $state->value
            ]);
    }
}
