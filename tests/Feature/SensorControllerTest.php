<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Sensor;
use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SensorControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $now = now();

        $this->admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'status' => 1,
                'profile_picture' => null,
                'api_token' => Str::random(60),
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        $this->actingAs($this->admin);
    }

    public function testStoreMethodStoresDataCorrectly()
    {
        $waktu = now()->format('Y-m-d H:i:s');
        $data = [
            'parameter' => 'ph',
            'value' => 7.5,
            'waktu' => $waktu,
            'ri' => '1234',
        ];

        $response = $this->post(route('sensor.store'), $data);

        $response->assertRedirect(route('sensor.index'));
        $response->assertSessionHas('success', 'Data sensor berhasil ditambahkan.');

        $this->assertDatabaseHas('sensors', [
            'parameter' => 'ph',
            'value' => 7.5,
            'waktu' => $waktu,
            'ri' => '1234',
        ]);
    }

    public function testUpdateMethodUpdatesDataCorrectly()
    {
        $sensor = Sensor::factory()->create();

        $newTime = now()->format('Y-m-d\TH:i');

        $response = $this->put(route('sensor.update', $sensor->id), [
            'parameter' => 'humidity',
            'value' => 55,
            'waktu' => $newTime,
            'ri' => '5678',
        ]);

        $response->assertRedirect(route('sensor.index'));
        $response->assertSessionHas('success', 'Sensor berhasil diperbarui!');

        $this->assertDatabaseHas('sensors', [
            'parameter' => 'humidity',
            'value' => 55,
            'ri' => '5678',
        ]);
    }

    public function testDestroyMethodDeletesData()
    {
        $sensor = Sensor::factory()->create();

        $response = $this->delete(route('sensor.destroy', $sensor->id));

        $response->assertRedirect(route('sensor.index'));
        $response->assertSessionHas('success', 'Sensor berhasil dihapus!');

        $this->assertDatabaseMissing('sensors', ['id' => $sensor->id]);
    }

    // public function testFetchAllData()
    // {
    //     $fakeDevices = [
    //         'ph' => ['con' => '5.92', 'ri' => 'cin-ph', 'ct' => '20250607T150324'],
    //         'pota' => ['con' => '13', 'ri' => 'cin-pota', 'ct' => '20250607T150324'],
    //         'phospor' => ['con' => '6', 'ri' => 'cin-phospor', 'ct' => '20250607T150324'],
    //         'ec' => ['con' => '0.79', 'ri' => 'cin-ec', 'ct' => '20250607T150324'],
    //         'nitrogen' => ['con' => '8', 'ri' => 'cin-nitro', 'ct' => '20250607T150324'],
    //         'humidity' => ['con' => '2.72', 'ri' => 'cin-humid', 'ct' => '20250607T150324'],
    //         'temp' => ['con' => '4.22', 'ri' => 'cin-temp', 'ct' => '20250607T150324'],
    //     ];

    //     foreach ($fakeDevices as $device => $data) {
    //         Http::fake([
    //             "*/{$device}/la" => Http::response([
    //                 'm2m:cin' => [
    //                     'con' => $data['con'],
    //                     'ri' => '/antares-cse/' . $data['ri'],
    //                     'ct' => $data['ct']
    //                 ]
    //             ], 200),
    //         ]);
    //     }

    //     // Call route to fetch and store Antares data
    //     $response = $this->post(route('sensor.fetchStore'));

    //     $response->assertRedirect(route('sensor.index'));
    //     $response->assertSessionHas('success');

    //     // Validate database entries
    //     foreach ($fakeDevices as $parameter => $data) {
    //         $expectedValue = in_array($parameter, ['pota', 'phospor', 'nitrogen'])
    //             ? round((float)$data['con'], 2)
    //             : round(((float)$data['con']) / 100, 2);

    //         $this->assertDatabaseHas('sensors', [
    //             'parameter' => strtoupper($parameter),
    //             'value' => $expectedValue,
    //         ]);
    //     }
    // }


    public function testExportMethodExportsDataCorrectly()
    {
        Http::fake([
            '*' => Http::response([
                'm2m:cin' => [
                    'con' => '5.92',
                    'ri' => '/antares-cse/cin-Yr1zzM5DoLtVQC9sEPili9s0AF0Davnl',
                    'ct' => '20250607T150324'
                ]
            ], 200)
        ]);

        $filePath = storage_path('app/public/data-semua-sensor.csv');

        $response = $this->get(route('sensor.export'));

        $response->assertStatus(200);
        $this->assertFileExists($filePath);

        $content = file_get_contents($filePath);

        $this->assertStringContainsString('Parameter,"Resource ID",Waktu,Nilai', $content);

        $this->assertMatchesRegularExpression(
            '/(ph|pota|phospor|EC|Nitrogen|humidity|temp),\/antares-cse\/cin-[^,]+,"2025-06-07 15:03:24",5\.92/',
            $content
        );

        unlink($filePath);
    }
}
