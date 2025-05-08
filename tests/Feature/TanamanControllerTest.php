<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\Tanaman;
use Carbon\Carbon;

class TanamanControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    // public function it_can_display_tanaman_index_page()
    // {
    //     $response = $this->get(route('tanaman.index'));
    //     $response->assertStatus(200);
    //     $response->assertViewIs('tanaman.index');
    // }

    /** @test */
    public function it_can_store_new_tanaman()
    {
        $data = [
            'nama_tanaman' => 'Bayam',
            'deskripsi' => 'Tanaman sayuran hijau',
            'tanggal_tanam' => now()->toDateString(),
            'status' => 'on going',
        ];

        $response = $this->post(route('tanaman.store'), $data);
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Data tanaman berhasil ditambahkan.');

        $this->assertDatabaseHas('tanaman', [
            'nama_tanaman' => 'Bayam',
            'status' => 'on going',
        ]);
    }

    /** @test */
    public function it_can_update_tanaman()
    {
        $tanaman = Tanaman::create([
            'nama_tanaman' => 'Kangkung',
            'deskripsi' => 'Tanaman air',
            'tanggal_tanam' => now(),
            'status' => 'on going',
        ]);

        $newData = [
            'nama_tanaman' => 'Kangkung Super',
            'deskripsi' => 'Tanaman air cepat tumbuh',
            'tanggal_tanam' => now()->toDateString(),
            'status' => 'selesai',
        ];

        $response = $this->put(route('tanaman.update', $tanaman->id), $newData);
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Data tanaman berhasil diperbarui.');

        $this->assertDatabaseHas('tanaman', [
            'nama_tanaman' => 'Kangkung Super',
            'status' => 'selesai',
        ]);
    }

    /** @test */
    public function it_can_delete_tanaman()
    {
        $tanaman = Tanaman::create([
            'nama_tanaman' => 'Sawi',
            'deskripsi' => 'Sayuran daun hijau',
            'tanggal_tanam' => now(),
            'status' => 'on going',
        ]);

        $response = $this->delete(route('tanaman.destroy', $tanaman->id));
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Data tanaman berhasil dihapus.');

        $this->assertDatabaseMissing('tanaman', ['id' => $tanaman->id]);
    }

    /** @test */
    public function it_validates_tanaman_creation()
    {
        $data = [
            'nama_tanaman' => '',  // Invalid, should trigger validation
            'deskripsi' => 'Tanaman sayuran hijau',
            'tanggal_tanam' => now()->toDateString(),
            'status' => 'on going',
        ];

        $response = $this->post(route('tanaman.store'), $data);
        $response->assertSessionHasErrors('nama_tanaman');
    }

    /** @test */
    public function it_validates_tanaman_update()
    {
        $tanaman = Tanaman::create([
            'nama_tanaman' => 'Kangkung',
            'deskripsi' => 'Tanaman air',
            'tanggal_tanam' => now(),
            'status' => 'on going',
        ]);

        $newData = [
            'nama_tanaman' => '',  // Invalid, should trigger validation
            'deskripsi' => 'Tanaman air cepat tumbuh',
            'tanggal_tanam' => now()->toDateString(),
            'status' => 'selesai',
        ];

        $response = $this->put(route('tanaman.update', $tanaman->id), $newData);
        $response->assertSessionHasErrors('nama_tanaman');
    }
}
