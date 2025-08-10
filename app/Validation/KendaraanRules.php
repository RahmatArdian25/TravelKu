<?php

namespace App\Validation;

use App\Models\ModelKursiKendaraan;

class KendaraanRules
{
    public function jumlah_kursi_masuk_akal(string $value, ?string $fields = null, array $data = []): bool
    {
        if (empty($data['idkendaraan'])) {
            return true; // Untuk create
        }

        $model = new ModelKursiKendaraan();
        $terpakai = $model->where('idkendaraan', $data['idkendaraan'])->countAllResults();

        return (int)$value >= $terpakai;
    }
}
