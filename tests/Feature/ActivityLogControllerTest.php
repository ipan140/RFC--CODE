<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

class ActivityLogControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test jika halaman log aktivitas dapat dimuat dengan benar.
     *
     * @return void
     */
    public function test_can_view_activity_logs()
    {
        // Membuat beberapa log aktivitas
        $logs = Activity::factory()->count(5)->create();

        // Mengunjungi halaman log aktivitas
        $response = $this->get(route('logaktivitas.index'));

        // Memastikan response berhasil (status code 200)
        $response->assertStatus(200);

        // Memastikan ada data log yang ditampilkan di halaman
        $response->assertViewHas('logs', $logs);
    }

    /**
     * Test paginasi di halaman log aktivitas.
     *
     * @return void
     */
    public function test_activity_log_pagination()
    {
        // Membuat 15 log aktivitas
        $logs = Activity::factory()->count(15)->create();

        // Mengunjungi halaman log aktivitas
        $response = $this->get(route('logaktivitas.index'));

        // Memastikan response berhasil (status code 200)
        $response->assertStatus(200);

        // Memastikan bahwa halaman tersebut memiliki tombol navigasi untuk paginasi
        $response->assertSee('Next');
        $response->assertSee('Previous');

        // Memastikan bahwa view menerima logs
        $response->assertViewHas('logs', $logs);
    }
}
