<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\KategoriSampel;
use App\Models\PeriodeTanam;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class KategoriSampelControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $admin;
    protected $periode;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat user admin dan login
        $this->admin = User::factory()->create([
            'role' => 'admin'
        ]);
        $this->actingAs($this->admin);

        // Buat satu periode_tanam sebagai ID konsisten
        $this->periode = PeriodeTanam::factory()->create([
            'nama_tanaman' => 'Melon',
            'deskripsi' => 'Melon hidroponik',
            'tanggal_tanam' => now(),
            'status' => 'on going',
        ]);
    }

    /** @test */
    public function it_can_store_a_new_kategori_sampel()
    {
        $response = $this->post(route('kategori_sampel.store'), [
            'nama' => 'sample 1',
            'deskripsi' => 'Melon albino',
            'periode_tanam_id' => $this->periode->id,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('kategori_sampel', [
            'nama' => 'sample 1',
            'deskripsi' => 'Melon albino',
            'periode_tanam_id' => $this->periode->id,
        ]);
    }

    /** @test */
    public function it_can_update_kategori_sampel()
    {
        $kategori = KategoriSampel::factory()->create([
            'nama' => 'lama',
            'deskripsi' => 'lama',
            'periode_tanam_id' => $this->periode->id,
        ]);

        $response = $this->put(route('kategori_sampel.update', $kategori->id), [
            'nama' => 'sample 2',
            'deskripsi' => 'Melon merah',
            'periode_tanam_id' => $this->periode->id,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('kategori_sampel', [
            'id' => $kategori->id,
            'nama' => 'sample 2',
            'deskripsi' => 'Melon merah',
            'periode_tanam_id' => $this->periode->id,
        ]);
    }

    /** @test */
    public function it_can_delete_kategori_sampel()
    {
        $kategori = KategoriSampel::factory()->create([
            'periode_tanam_id' => $this->periode->id,
        ]);

        $response = $this->delete(route('kategori_sampel.destroy', $kategori->id));

        $response->assertRedirect();

        $this->assertDatabaseMissing('kategori_sampel', [
            'id' => $kategori->id,
        ]);
    }

    /** @test */
    // public function it_validates_required_fields_on_store()
    // {
    //     $response = $this->post(route('kategori_sampel.store'), []);

    //     $response->assertSessionHasErrors([
    //         'nama',
    //         'deskripsi',
    //         'periode_tanam_id',
    //     ]);
    // }

    /** @test */
    public function it_requires_valid_periode_tanam_id()
    {
        $response = $this->post(route('kategori_sampel.store'), [
            'nama' => 'invalid sample',
            'deskripsi' => 'Invalid ID',
            'periode_tanam_id' => 999999,
        ]);

        $response->assertSessionHasErrors('periode_tanam_id');
    }

    /** @test */
    public function kategori_sampel_terhubung_dengan_periode_tanam()
    {
        $kategori = KategoriSampel::factory()->create([
            'periode_tanam_id' => $this->periode->id,
        ]);

        $this->assertTrue($kategori->periodeTanam->is($this->periode));
    }
}
