<?php

namespace App\Validation;

use App\Models\ModelKendaraan;

class KursiRules
{
    public function valid_seat_number(string $str, string $fields, array $data): bool
    {
        $idkendaraan = $data['idkendaraan'] ?? null;
        
        if (empty($idkendaraan)) {
            return false;
        }

        $kendaraanModel = new ModelKendaraan();
        $kendaraan = $kendaraanModel->find($idkendaraan);

        if (!$kendaraan) {
            return false;
        }

        return (int)$str <= (int)$kendaraan['jumlahkursi'] && (int)$str > 0;
    }
}
