<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\InputHarian;
use App\Models\PeriodeTanam;
use App\Models\KategoriSampel;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;

class RiwayatTanamanControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function dapat_menampilkan_data_riwayat_tanaman_berdasarkan_filter_periode_dan_kategori()
    {
        $periode = PeriodeTanam::factory()->create();
        $kategori = KategoriSampel::factory()->create(['periode_tanam_id' => $periode->id]);

        InputHarian::factory()->create([
            'periode_tanam_id' => $periode->id,
            'kategori_sampel_id' => $kategori->id,
            'pupuk' => 'Urea'
        ]);

        $response = $this->get(route('riwayat_tanaman.index', [
            'filter_periode_id' => $periode->id,
            'kategori_sampel_id' => $kategori->id,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Urea');
    }

    /** @test */
    public function dapat_memfilter_berdasarkan_tanggal_awal_dan_akhir()
    {
        $periode = PeriodeTanam::factory()->create();
        $kategori = KategoriSampel::factory()->create(['periode_tanam_id' => $periode->id]);

        InputHarian::factory()->create([
            'periode_tanam_id' => $periode->id,
            'kategori_sampel_id' => $kategori->id,
            'waktu' => now()->subDay(),
            'pupuk' => 'Kompos'
        ]);

        InputHarian::factory()->create([
            'periode_tanam_id' => $periode->id,
            'kategori_sampel_id' => $kategori->id,
            'waktu' => now()->subDays(10),
        ]);

        $response = $this->get(route('riwayat_tanaman.index', [
            'tanggal_awal' => now()->subDays(2)->toDateString(),
            'tanggal_akhir' => now()->toDateString(),
        ]));

        $response->assertStatus(200);
        $response->assertSee('Kompos');
    }

    /** @test */
    public function dapat_mengekspor_data_ke_csv()
    {
        $periode = PeriodeTanam::factory()->create();
        $kategori = KategoriSampel::factory()->create(['periode_tanam_id' => $periode->id]);

        InputHarian::factory()->count(2)->create([
            'periode_tanam_id' => $periode->id,
            'kategori_sampel_id' => $kategori->id,
        ]);

        $response = $this->get(route('riwayat_tanaman.export', [
            'filter_periode_id' => $periode->id,
            'kategori_sampel_id' => $kategori->id,
        ]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Disposition');
    }

    /** @test */
    public function jika_tidak_ada_data_maka_export_mengembalikan_error()
    {
        $response = $this->get(route('riwayat_tanaman.export'));
        $response->assertRedirect();
        $response->assertSessionHas('error', 'Tidak ada data untuk diekspor.');
    }
}
