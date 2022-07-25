<?php
/**
 * @author Sultonazar Mamadazizov <sultonazar.mamadazizov@mail.ru>
 * @copyright Copyright (c) 2022, coded.tj
 */

namespace App\Models;

class Entity
{
    public int $id;

    public string $hash;

    public array $data;

    /**
     * Returns new entity, which is constructed with API data
     *
     * @param $data
     * @return Entity
     */
    public static function withFetchedData($data): Entity
    {
        $entity = new self();

        $entity->id = $data['uid'];

        if (isset($data['akaList']))
            if (isset($data['akaList']['aka']['uid'])) {
                $data['akaList'][0] = $data['akaList']['aka'];
                unset($data['akaList']['aka']);

            } else $data['akaList'] = array_values(($data['akaList']['aka']));

        $entity->hash = hash('sha256', serialize($data));

        $entity->data = $data;

        return $entity;
    }


    /**
     * Returns new entity, which is constructed with database data
     *
     * @param $data
     * @return Entity
     */
    public static function withDbRow($data): Entity
    {
        $entity = new self();

        $entity->id = $data->id;

        $entity->hash = $data->hash;

        $entity->data = json_decode($data->data, 1);

        return $entity;
    }

    /**
     * Returns all names with uid
     *
     * @return array
     */
    public function getAliases(?string $type): array
    {
        $aliases = [];

        //add primary name to list in case of strong type
        if (!$type || $type === 'strong')
            $aliases[] = [
                'uid' => $this->id,
                'firstName' => $this->data['firstName'],
                'lastName' => $this->data['lastName'],
            ];

        //filter records from akaList
        if (isset($this->data['akaList'])) {

            foreach ($this->data['akaList'] as $aka) {

                if (!$type || $aka['category'] === $type)
                    $aliases[] = $aka;

            }

        }

        //format aliases list
        return  array_map(function ($el) {
            return [
                'uid' => $this->id,
                'firstName' => $el['firstName'],
                'lastName' => $el['lastName']
            ];
        }, $aliases);
    }

    /**
     * Return array from
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'hash' => $this->hash,
            'data' => json_encode($this->data)
        ];
    }
}
