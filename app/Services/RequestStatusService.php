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
