<?php
/**
 * @author Sultonazar Mamadazizov <sultonazar.mamadazizov@mail.ru>
 * @copyright Copyright (c) 2022, coded.tj
 */

namespace App\Repositories;

use App\Models\Entity;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JsonRepository extends AbstractRepository
{

    /**
     * Syncs provided entities with database
     */
    public function sync(Collection $entities)
    {
        $entities->chunk(50)->each(function (Collection $freshEntities) {
            $this->upsertMany($freshEntities);
        });

        $this->deleteRedundantEntities($entities);
    }

    public function upsertMany(Collection $entities)
    {
        $storedEntities = DB::table('entities')
            ->select('id', 'hash')
            ->whereIn('id', $entities->pluck('id'))
            ->get();

        $entities->filter(function (Entity $fresh) use ($storedEntities) {
            return $storedEntities->doesntContain(function ($stored) use ($fresh) {
                return $stored->id === $fresh->id && $stored->hash === $fresh->hash;
            });
            //Prepare filtered entities for upsert
        })->map(function (Entity $entity) {
            return $entity->toArray();
            //Upsert entities to DB
        })->pipe(function (Collection $changedOrNew) {
            DB::table('entities')->upsert($changedOrNew->toArray(), ['id']);
        });
    }

    public function deleteRedundantEntities(Collection $freshEntities)
    {
        DB::table('entities')->select('id')->get()->pluck('id')
            ->diff($freshEntities->pluck('id')->toArray())
            ->pipe(function ($toDelete) {
                DB::table('entities')->whereIn('id', $toDelete)->delete();
            });
    }

    public function getAliases(string $name, ?string $type)
    {
        $name = strtolower($name);

        $res = DB::table('entities')
            ->whereRaw("LOWER(data->'$.firstName') = JSON_QUOTE('". $name ."')")
            ->orWhereRaw("LOWER(data->'$.lastName') = JSON_QUOTE('". $name ."')")
            ->orWhereRaw("JSON_CONTAINS(LOWER(JSON_EXTRACT(data, '$.akaList[*].firstName')), JSON_QUOTE('" . $name . "'))")
            ->orWhereRaw("JSON_CONTAINS(LOWER(JSON_EXTRACT(data, '$.akaList[*].lastName')), JSON_QUOTE('" . $name . "'))")
            ->first();

        return $res;
    }


}
