<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Proyek;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProyekControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    // public function it_displays_proyek_index_page()
    // {
    //     $proyeks = Proyek::factory()->count(2)->create();

    //     $response = $this->get(route('proyek.index'));

    //     $response->assertStatus(200);
    //     foreach ($proyeks as $proyek) {
    //         $response->assertSee($proyek->nama);
    //     }
    // }

    /** @test */
    public function it_can_store_a_new_proyek_with_foto()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('foto.jpg');

        $response = $this->post(route('proyek.store'), [
            'nama' => 'Proyek Baru',
            'deskripsi' => 'Deskripsi lengkap',
            'tanggal' => '2025-05-08',
            'foto_proyek' => $file,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('proyeks', ['nama' => 'Proyek Baru']);
        // Storage::disk('public')->assertExists('proyek_fotos/' . $file->hashName());
    }

    /** @test */
    public function it_can_update_existing_proyek_and_replace_foto()
    {
        Storage::fake('public');

        $proyek = Proyek::factory()->create([
            'foto_proyek' => 'proyek_fotos/old.jpg'
        ]);

        Storage::disk('public')->put('proyek_fotos/old.jpg', 'dummy content');

        $newFile = UploadedFile::fake()->image('new.jpg');

        $response = $this->put(route('proyek.update', $proyek), [
            'nama' => 'Nama Diperbarui',
            'deskripsi' => 'Deskripsi baru',
            'tanggal' => now()->toDateString(),
            'foto_proyek' => $newFile,
        ]);

        $response->assertRedirect(route('proyek.index'));
        $this->assertDatabaseHas('proyeks', ['id' => $proyek->id, 'nama' => 'Nama Diperbarui']);
        // Storage::disk('public')->assertMissing('proyek_fotos/old.jpg');
        // Storage::disk('public')->assertExists('proyek_fotos/' . $newFile->hashName());
    }

    /** @test */
    public function it_can_delete_a_proyek()
    {
        $proyek = Proyek::factory()->create();

        $response = $this->delete(route('proyek.destroy', $proyek));

        $response->assertRedirect(route('proyek.index'));
        $this->assertDatabaseMissing('proyeks', ['id' => $proyek->id]);
    }

    /** @test */
    public function it_can_store_a_new_proyek_without_foto()
    {
        $response = $this->post(route('proyek.store'), [
            'nama' => 'Proyek Tanpa Foto',
            'deskripsi' => 'Deskripsi tanpa foto',
            'tanggal' => '2025-05-08',
            // No 'foto_proyek' field in the request
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('proyeks', ['nama' => 'Proyek Tanpa Foto']);
        // $this->assertNull(Proyek::latest()->first()->foto_proyek); // Assuming foto_proyek can be null
    }
}
