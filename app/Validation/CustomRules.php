<?php

namespace App\Validation;

use App\Models\ModelKursiKendaraan;

class CustomRules
{
    public function is_seat_unique(?string $str, string $fields, array $data): bool
    {
        $model = new ModelKursiKendaraan();
        
        $params = explode(',', $fields);
        $idkendaraanField = $params[0] ?? 'idkendaraan';
        $jadwalField = $params[1] ?? 'jadwalberangkat';
        $idkursiField = $params[2] ?? null;
        
        $idkendaraan = $data[$idkendaraanField] ?? null;
        $jadwal = $data[$jadwalField] ?? null;
        $idkursi = $idkursiField ? ($data[$idkursiField] ?? null) : null;
        
        return !$model->isSeatDuplicate($idkendaraan, $str, $jadwal, $idkursi);
    }
}