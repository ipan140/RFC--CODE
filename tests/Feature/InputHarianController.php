<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\InputHarian;
use App\Models\PeriodeTanam;
use App\Models\KategoriSampel;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class InputHarianController extends TestCase
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
    public function user_can_view_input_harian_index()
    {
        $response = $this->get(route('input_harian.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_store_input_harian_with_sensor_and_foto()
    {
        Storage::fake('public');

        $kategori = KategoriSampel::factory()->create(['nama' => 'Sampel 1']);

        $data = [
            'periode_tanam_id' => $this->periode->id,
            'kategori_sampel_id' => $kategori->id,
            'pupuk' => 'Urea',
            'waktu' => now()->toDateTimeString(),
            'panjang_daun' => 10.5,
            'lebar_daun' => 5.2,
            'ph' => 6.5,
            'pota' => 45,
            'phospor' => 30,
            'EC' => 2.1,
            'Nitrogen' => 70,
            'humidity' => 60,
            'temp' => 27,
            'foto' => UploadedFile::fake()->image('daun.jpg')
        ];

        $response = $this->post(route('input_harian.store'), $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('input_harians', [
            'periode_tanam_id' => $this->periode->id,
            'kategori_sampel_id' => $kategori->id,
            'pupuk' => 'Urea',
        ]);
    }

    /** @test */
    public function user_can_update_input_harian()
    {
        $input = InputHarian::factory()->create();
        $updatedData = [
            'periode_tanam_id' => $input->periode_tanam_id,
            'kategori_sampel_id' => $input->kategori_sampel_id,
            'pupuk' => 'NPK',
            'ph' => 7.0
        ];

        $response = $this->put(route('input_harian.update', $input->id), $updatedData);

        $response->assertRedirect();
        $this->assertDatabaseHas('input_harians', ['id' => $input->id, 'pupuk' => 'NPK']);
    }

    /** @test */
    public function user_can_delete_input_harian()
    {
        $input = InputHarian::factory()->create();
        $response = $this->delete(route('input_harian.destroy', $input->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('input_harians', ['id' => $input->id]);
    }
}
