<?php
/**
 * @author Sultonazar Mamadazizov <sultonazar.mamadazizov@mail.ru>
 * @copyright Copyright (c) 2022, coded.tj
 */

namespace App\Repositories;


use Illuminate\Support\Collection;

abstract class AbstractRepository implements Repository
{
    /**
     * Syncs provided entities with database
     */
    abstract function sync(Collection $entities);

    abstract function getAliases(string $name, string $type);
}
