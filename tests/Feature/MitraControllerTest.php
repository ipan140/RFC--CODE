<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Mitra;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MitraControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_can_store_a_new_mitra()
    {
        $response = $this->post(route('mitra.store'), [
            'nama' => 'Mitra Baru',
            'lokasi' => 'Jakarta',
            'email' => 'mitra@example.com',
            'telepon' => '081234567890',
        ]);

        $response->assertRedirect(route('mitra.index'));
        $this->assertDatabaseHas('mitras', ['nama' => 'Mitra Baru']);
    }

    /** @test */
    public function it_can_update_existing_mitra()
    {
        $mitra = Mitra::factory()->create();

        $response = $this->put(route('mitra.update', $mitra), [
            'nama' => 'Mitra Diperbarui',
            'lokasi' => 'Bandung',
            'email' => 'updated@example.com',
            'telepon' => '089876543210',
        ]);

        $response->assertRedirect(route('mitra.index'));
        $this->assertDatabaseHas('mitras', [
            'id' => $mitra->id,
            'nama' => 'Mitra Diperbarui',
        ]);
    }

    /** @test */
    public function it_can_delete_a_mitra()
    {
        $mitra = Mitra::factory()->create();

        $response = $this->delete(route('mitra.destroy', $mitra));

        $response->assertRedirect(route('mitra.index'));
        $this->assertDatabaseMissing('mitras', ['id' => $mitra->id]);
    }

    /** @test */
    public function it_can_store_a_new_mitra_without_optional_fields()
    {
        $response = $this->post(route('mitra.store'), [
            'nama' => 'Mitra Tanpa Foto',
            'lokasi' => 'Jakarta',
            'email' => 'mitra@example.com',
            'telepon' => '081234567890',
        ]);

        $response->assertRedirect(route('mitra.index'));
        $this->assertDatabaseHas('mitras', ['nama' => 'Mitra Tanpa Foto']);
    }
}
