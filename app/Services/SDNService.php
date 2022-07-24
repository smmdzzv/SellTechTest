<?php
/**
 * @author Sultonazar Mamadazizov <sultonazar.mamadazizov@mail.ru>
 * @copyright Copyright (c) 2022, coded.tj
 */

namespace App\Services;

use App\Models\Entity;
use App\Repositories\Repository;
use Illuminate\Support\Collection;
use XMLReader;

class SDNService
{
    private Repository $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Fetches data from API and stores it in Database
     *
     * @param string $src
     * @return void
     */
    public function updateData(string $src): void
    {
        $xml = XMLReader::open($src);

        while ($xml->read() && $xml->name !== 'sdnEntry');

        $entities = new Collection();

        while ($xml->name === 'sdnEntry') {
            $entry = simplexml_load_string($xml->readOuterXml());

            if ((string)$entry->sdnType === 'Individual'){
                $entities->push(
                    new Entity((json_decode(json_encode($entry), 1)))
                );
            }

            $xml->next('sdnEntry');
        }

        $this->repository->sync($entities);
    }
}
