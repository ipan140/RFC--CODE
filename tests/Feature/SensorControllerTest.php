<?php

namespace Tests\Feature;

use App\Models\Sensor;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions; // Import DatabaseTransactions

class SensorControllerTest extends TestCase
{
    // Use DatabaseTransactions to ensure database is rolled back after each test
    use DatabaseTransactions;

    // Test the index method
    public function testIndexMethodReturnsViewWithData()
    {
        $sensor = Sensor::factory()->create(); // Pastikan Anda sudah membuat factory untuk Sensor

        $response = $this->get(route('sensor.index'));

        $response->assertStatus(200)
                 ->assertViewIs('sensor.index')
                 ->assertViewHas('dataSensor', function ($data) use ($sensor) {
                     // Menggunakan assertContains untuk koleksi
                     return $data->contains(function ($item) use ($sensor) {
                         return $item->id === $sensor->id; // Pastikan ID sensor cocok
                     });
                 });
    }

    // Test the store method
    public function testStoreMethodStoresDataCorrectly()
    {
        $data = [
            'parameter' => 'ph',
            'value' => 7.5,
            'time' => now(),
            'ri' => '1234'
        ];

        $response = $this->post(route('sensor.store'), $data);

        $response->assertRedirect(route('sensor.index'));
        $response->assertSessionHas('success', 'Data sensor berhasil ditambahkan.');

        // Format waktu agar cocok dengan yang ada di database
        $this->assertDatabaseHas('sensors', [
            'parameter' => 'ph',
            'value' => 7.5,
            'ri' => '1234',
            'time' => Carbon::parse($data['time'])->toDateTimeString(),
        ]);
    }

    // Test the edit method
    public function testEditMethodReturnsEditView()
    {
        $sensor = Sensor::factory()->create();

        $response = $this->get(route('sensor.edit', $sensor->id));

        $response->assertStatus(200)
                 ->assertViewIs('sensor.edit')
                 ->assertViewHas('sensor', $sensor);
    }

    // Test the update method
    public function testUpdateMethodUpdatesDataCorrectly()
    {
        $sensor = Sensor::factory()->create();
        $updatedData = [
            'parameter' => 'humidity',
            'value' => 55,
            'time' => now(),
            'ri' => '5678'
        ];

        $response = $this->put(route('sensor.update', $sensor->id), $updatedData);

        $response->assertRedirect(route('sensor.index'));
        $response->assertSessionHas('success', 'Sensor berhasil diperbarui!');

        // Gunakan format waktu yang sama seperti di database
        $this->assertDatabaseHas('sensors', [
            'parameter' => 'humidity',
            'value' => 55,
            'ri' => '5678',
            'time' => Carbon::parse($updatedData['time'])->toDateTimeString(),
        ]);
    }

    // Test the destroy method
    public function testDestroyMethodDeletesData()
    {
        $sensor = Sensor::factory()->create();

        $response = $this->delete(route('sensor.destroy', $sensor->id));

        $response->assertRedirect(route('sensor.index'));
        $response->assertSessionHas('success', 'Sensor berhasil dihapus!');

        $this->assertDatabaseMissing('sensors', ['id' => $sensor->id]);
    }

    // Test the fetchAllData method (using mock API response)
    public function testFetchAllData()
    {
        $device = 'ph';

        // Membuat response mock yang lebih realistis
        Http::fake([
            'https://platform.antares.id:8443/~/antares-cse/antares-id/*' => Http::response([
                'm2m:cnt' => [
                    'm2m:cin' => [
                        [
                            'con' => '7.5',
                            'ri' => '1234',
                            'ct' => '20230508T123456',
                        ]
                    ]
                ]
            ], 200)
        ]);

        $response = $this->get(route('sensor.getDeviceData', $device));

        $response->assertStatus(200);
        $response->assertViewIs('sensor.index');
        $response->assertViewHas('device', $device);
        $response->assertViewHas('items', function ($items) {
            return count($items) > 0 && $items[0]['value'] == 7.5;
        });
    }

    // Test the export method
    public function testExportMethodExportsDataCorrectly()
    {
        Sensor::factory()->create([
            'parameter' => 'ph',
            'value' => 7.5,
            'ri' => '1234',
            'time' => now()
        ]);

        $response = $this->get(route('sensor.export'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv');
        $response->assertHeader('Content-Disposition', 'attachment; filename="data-semua-sensor.csv"');

        // Verifikasi isi file CSV (contoh memeriksa bagian awal file CSV)
        $csvContent = $response->getContent();
        $this->assertStringContainsString('parameter,value,ri,time', $csvContent);
        $this->assertStringContainsString('ph,7.5,1234,' . now()->toDateString(), $csvContent);
    }
}
