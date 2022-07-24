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

    public function __construct(array $data)
    {
        $this->id = $data['uid'];

        $this->hash = hash('sha256', serialize($data));

        $this->data = $data;
    }

    public function toArray(){
        return [
            'id' => $this->id,
            'hash' => $this->hash,
            'data' => json_encode($this->data)
        ];
    }
}
