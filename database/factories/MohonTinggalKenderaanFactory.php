<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\Lot;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MohonTinggalKenderaan>
 */
class MohonTinggalKenderaanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dateStart = Carbon::instance($this->faker->dateTimeBetween('-15 days', '+15 days'));
        $dateEnd = Carbon::instance($dateStart)->addDays(Rand(1, 14));
        $createdAt = Carbon::instance($dateStart->copy()->subDays(rand(1,5)));
        return [
            'uuid' => Uuid::uuid4(),
            'no_rujukan' => 'W/TK/' . $this->faker->numberBetween(1, 99) . '/' . $dateStart->month . $dateStart->year . '/' . $this->faker->unique()->numberBetween(1, 999),
            'user_id' => User::inRandomOrder()->value('id') ?? 1,
            'id_lot' => Lot::inRandomOrder()->value('id') ?? 1,
             'model' => $this->faker->randomElement(['HONDA CIVIC 1996', 'PERODUA MYVI', 'PROTON SAGA', 'TOYOTA VIOS']),
             'no_pendaftaran' => strtoupper($this->faker->bothify('??? ####')),
             'warna' => $this->faker->safeColorName(),
             'aras' => $this->faker->randomElement(['B1', 'B2', 'G', 'L1']),
            'tujuan' => $this->faker->randomElement(['KURSUS', 'KERJA LUAR', 'CUTI', 'MESYUARAT', 'TETAMU/VIP', 'HAMIL']),
            'tarikh_mula' => $dateStart,
            'tarikh_tamat' => $dateEnd,
            'bangunan' => $this->faker->randomElement(['P1', 'P2', 'A1', 'B1']),
            'tarikh_mohon' => $dateStart->subDays(rand(1,5)),
            'status_permohonan' => $this->faker->numberBetween(0, 5),
            'status' => $this->faker->randomElement(['0', '1']),
            'created_by' => $this->faker->userName(),
            'updated_by' => $this->faker->userName(),
            'deleted_by' => null,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }
}
