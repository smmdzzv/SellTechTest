<?php
/**
 * @author Sultonazar Mamadazizov <sultonazar.mamadazizov@mail.ru>
 * @copyright Copyright (c) 2022, coded.tj
 */

namespace App\Repositories;

use Illuminate\Support\Collection;

interface Repository
{

    /**
     * Syncs provided entities with database
     */
    public function sync(Collection $entities);

    /**
     * Returns users aliases
     *
     * @param string $name
     * @param string $type
     * @return mixed
     */
    public function getAliases(string $name, string $type);

}
