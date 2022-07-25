<?php
/**
 * @author Sultonazar Mamadazizov <sultonazar.mamadazizov@mail.ru>
 * @copyright Copyright (c) 2022, coded.tj
 */

namespace App\Enums;

enum UpdateRequestStateEnum: int
{
    case UPDATING = 1;
    case SUCCESS = 2;
    case FAILED = 3;
}
