<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Lot;
use App\Models\MohonTinggalKenderaan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class MohonTinggalKenderaanTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and get token for authentication
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test_token')->plainTextToken;
    }

    public function test_can_get_permohonan_by_date()
    {
        // Create a lot
        $lot = Lot::factory()->create();

        // Create permohonan for today
        $today = Carbon::now()->format('Y-m-d');
        $permohonan = MohonTinggalKenderaan::factory()->create([
            'user_id' => $this->user->id,
            'id_lot' => $lot->id,
            'tarikh_mula' => $today . ' 08:00:00',
            'tarikh_tamat' => $today . ' 18:00:00',
            'status' => '1',
            'status_permohonan' => 1,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/permohonan-tinggal-kenderaan/by-date?date=' . $today);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'status',
                    'message',
                    'data' => [
                        'requested_date',
                        'total_records',
                        'permohonan_list' => [
                            '*' => [
                                'id',
                                'uuid',
                                'no_rujukan',
                                'user' => [
                                    'id',
                                    'name',
                                    'email',
                                ],
                                'lot' => [
                                    'id',
                                    'name',
                                ],
                                'vehicle_details' => [
                                    'model',
                                    'no_pendaftaran',
                                    'warna',
                                ],
                                'parking_details' => [
                                    'aras',
                                    'bangunan',
                                ],
                                'period' => [
                                    'tarikh_mula',
                                    'tarikh_tamat',
                                ],
                                'tujuan',
                                'tarikh_mohon',
                                'status_permohonan',
                                'status_permohonan_text',
                                'created_at',
                            ]
                        ]
                    ]
                ])
                ->assertJson([
                    'status' => 'success',
                    'data' => [
                        'requested_date' => $today,
                        'total_records' => 1,
                    ]
                ]);
    }

    public function test_returns_empty_list_for_date_with_no_permohonan()
    {
        $futureDate = Carbon::now()->addDays(30)->format('Y-m-d');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/permohonan-tinggal-kenderaan/by-date?date=' . $futureDate);

        $response->assertStatus(200)
                ->assertJson([
                    'status' => 'success',
                    'data' => [
                        'requested_date' => $futureDate,
                        'total_records' => 0,
                        'permohonan_list' => []
                    ]
                ]);
    }

    public function test_requires_valid_date_format()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/permohonan-tinggal-kenderaan/by-date?date=invalid-date');

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['date']);
    }

    public function test_requires_authentication()
    {
        $today = Carbon::now()->format('Y-m-d');

        $response = $this->getJson('/api/permohonan-tinggal-kenderaan/by-date?date=' . $today);

        $response->assertStatus(401);
    }

    public function test_can_get_all_permohonan_with_pagination()
    {
        $lot = Lot::factory()->create();

        // Create multiple permohonan
        MohonTinggalKenderaan::factory()->count(5)->create([
            'user_id' => $this->user->id,
            'id_lot' => $lot->id,
            'status' => '1',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/permohonan-tinggal-kenderaan?per_page=3');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'status',
                    'message',
                    'data' => [
                        'permohonan_list',
                        'pagination' => [
                            'current_page',
                            'last_page',
                            'per_page',
                            'total',
                            'from',
                            'to',
                        ]
                    ]
                ])
                ->assertJson([
                    'status' => 'success',
                    'data' => [
                        'pagination' => [
                            'per_page' => 3,
                            'total' => 5,
                        ]
                    ]
                ]);
    }

    public function test_can_filter_by_status_permohonan()
    {
        $lot = Lot::factory()->create();

        // Create permohonan with different statuses
        MohonTinggalKenderaan::factory()->create([
            'user_id' => $this->user->id,
            'id_lot' => $lot->id,
            'status' => '1',
            'status_permohonan' => 1, // Approved
        ]);

        MohonTinggalKenderaan::factory()->create([
            'user_id' => $this->user->id,
            'id_lot' => $lot->id,
            'status' => '1',
            'status_permohonan' => 0, // Pending
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/permohonan-tinggal-kenderaan?status_permohonan=1');

        $response->assertStatus(200);

        $data = $response->json();
        $this->assertEquals(1, $data['data']['pagination']['total']);
        $this->assertEquals(1, $data['data']['permohonan_list'][0]['status_permohonan']);
    }

    public function test_validates_filter_parameters()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/permohonan-tinggal-kenderaan?status_permohonan=5&per_page=150');

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['status_permohonan', 'per_page']);
    }
}
