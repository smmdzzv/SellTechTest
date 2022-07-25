<?php
/**
 * @author Sultonazar Mamadazizov <sultonazar.mamadazizov@mail.ru>
 * @copyright Copyright (c) 2022, coded.tj
 */

namespace App\Http\Responses;

class UpdateResponse
{
    public bool $result;

    public string $info;

    public int $code;

    public function __construct(bool $result, string $info, int $code)
    {
        $this->result = $result;

        $this->info = $info;

        $this->code = $code;
    }
}
