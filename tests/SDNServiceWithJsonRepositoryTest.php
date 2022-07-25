<?php
/**
 * @author Sultonazar Mamadazizov <sultonazar.mamadazizov@mail.ru>
 * @copyright Copyright (c) 2022, coded.tj
 */

namespace Tests;

use App\Repositories\JsonRepository;
use App\Services\SDNService;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Testing\DatabaseMigrations;

class SDNServiceWithJsonRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    public function test_data_insert_for_the_first_time()
    {
        $service = new SDNService(new JsonRepository());

        $service->updateData(base_path() . '/tests/samples/data.xml');

        $this->assertCount(3, DB::table('entities')->get());
    }

    public function test_data_update_with_one_entity_changed_in_sdn()
    {
        $service = new SDNService(new JsonRepository());

        $service->updateData(base_path() . '/tests/samples/data.xml');

        $service->updateData(base_path() . '/tests/samples/data2.xml');

        $this->assertCount(3, DB::table('entities')->get());

        $this->assertCount(1,
            DB::table('entities')
                ->where('id', 2674)
                ->where('data->lastName', 'LABAS')
                ->get());
    }

    public function test_data_update_with_two_entities_removed_from_sdn()
    {
        $service = new SDNService(new JsonRepository());

        $service->updateData(base_path() . '/tests/samples/data.xml');

        $service->updateData(base_path() . '/tests/samples/data3.xml');

        $this->assertCount(1, DB::table('entities')->get());
    }

    public function test_get_user_names_by_name()
    {
        $service = new SDNService(new JsonRepository());

        $service->updateData(base_path() . '/tests/samples/data.xml');

        $res = $service->getUserNames('dr. ayman', null);

        $expected = [
            [
                "uid" => 2676,
                "firstName" => "Dr. Ayman",
                "lastName" => "AL ZAWAHIRI"
            ], [
                "uid" => 2676,
                "firstName" => "Ayman",
                "lastName" => "AL-ZAWAHIRI"
            ], [
                "uid" => 2676,
                "firstName" => "Ahmad Fuad",
                "lastName" => "SALIM"
            ], [
                "uid" => 2676,
                "firstName" => "Aiman Muhammad Rabi",
                "lastName" => "AL-ZAWAHIRI"
            ]
        ];

        $this->assertEquals($expected, $res);
    }

    public function test_get_user_names_by_aka()
    {
        $service = new SDNService(new JsonRepository());

        $service->updateData(base_path() . '/tests/samples/data.xml');

        $res = $service->getUserNames('Ahmad Fuad', null);

        $expected = [
            [
                "uid" => "2676",
                "firstName" => "Dr. Ayman",
                "lastName" => "AL ZAWAHIRI"
            ], [
                "uid" => "2676",
                "firstName" => "Ayman",
                "lastName" => "AL-ZAWAHIRI"
            ], [
                "uid" => "2676",
                "firstName" => "Ahmad Fuad",
                "lastName" => "SALIM"
            ], [
                "uid" => "2676",
                "firstName" => "Aiman Muhammad Rabi",
                "lastName" => "AL-ZAWAHIRI"
            ]
        ];

        $this->assertEquals($expected, $res);
    }

    public function test_get_user_names_by_aka_only_strong()
    {
        $service = new SDNService(new JsonRepository());

        $service->updateData(base_path() . '/tests/samples/data.xml');

        $res = $service->getUserNames('Aiman Muhammad Rabi', "stRonG");

        $expected = [
            [
                "uid" => "2676",
                "firstName" => "Dr. Ayman",
                "lastName" => "AL ZAWAHIRI"
            ], [
                "uid" => "2676",
                "firstName" => "Ayman",
                "lastName" => "AL-ZAWAHIRI"
            ], [
                "uid" => "2676",
                "firstName" => "Aiman Muhammad Rabi",
                "lastName" => "AL-ZAWAHIRI"
            ]
        ];

        $this->assertEquals($expected, $res);
    }

    public function test_get_user_names_by_aka_only_weak()
    {
        $service = new SDNService(new JsonRepository());

        $service->updateData(base_path() . '/tests/samples/data.xml');

        $res = $service->getUserNames('Ahmad Fuad', "wEak");

        $expected = [
            [
                "uid" => "2676",
                "firstName" => "Ahmad Fuad",
                "lastName" => "SALIM"
            ]
        ];

        $this->assertEquals($expected, $res);
    }
}
