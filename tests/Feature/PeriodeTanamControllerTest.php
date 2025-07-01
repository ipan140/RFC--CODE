<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\PeriodeTanam;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PeriodeTanamControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_can_store_a_new_periode_tanam()
    {
        $data = [
            'nama_tanaman' => 'Pisang',
            'deskripsi' => 'Tanaman pisang sistem NFT',
            'tanggal_tanam' => '2025-01-10',
            'status' => 'on going',
        ];

        $response = $this->post(route('periode_tanam.store'), $data);

        $response->assertRedirect();

        $this->assertDatabaseHas('periode_tanams', [
            'nama_tanaman' => 'Pisang',
            'deskripsi' => 'Tanaman pisang sistem NFT',
            'status' => 'on going',
        ]);

        // Hindari assertDatabaseCount jika database tidak dikosongkan
        $this->assertTrue(PeriodeTanam::where('nama_tanaman', 'Pisang')->exists());
    }

    /** @test */
    public function it_can_update_existing_periode_tanam()
    {
        $periode = PeriodeTanam::factory()->create([
            'nama_tanaman' => 'Lama',
            'deskripsi' => 'Deskripsi awal',
            'tanggal_tanam' => '2025-01-01',
            'status' => 'on going',
        ]);

        $updateData = [
            'nama_tanaman' => 'Mangga',
            'deskripsi' => 'Diperbarui jadi tanaman mangga',
            'tanggal_tanam' => '2025-01-15',
            'status' => 'selesai',
        ];

        $response = $this->put(route('periode_tanam.update', $periode->id), $updateData);

        $response->assertRedirect();

        $this->assertDatabaseHas('periode_tanams', [
            'id' => $periode->id,
            'nama_tanaman' => 'Mangga',
            'deskripsi' => 'Diperbarui jadi tanaman mangga',
            'status' => 'selesai',
        ]);
    }

    /** @test */
    public function it_can_delete_a_periode_tanam()
    {
        $periode = PeriodeTanam::factory()->create();

        $response = $this->delete(route('periode_tanam.destroy', $periode->id));

        $response->assertRedirect();

        $this->assertDatabaseMissing('periode_tanams', [
            'id' => $periode->id,
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_storing()
    {
        $response = $this->post(route('periode_tanam.store'), []);

        $response->assertSessionHasErrors([
            'nama_tanaman',
            'deskripsi',
            'tanggal_tanam',
            'status',
        ]);
    }
}
