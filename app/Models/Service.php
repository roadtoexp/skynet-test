<?php

declare(strict_types=1);

namespace App\Models;

use Core\DB;
use PDO;

final class Service extends DB
{
    public function updateTariff($userId, $serviceId, $tariffId): bool
    {
        $payday = date('Y-m-d', strtotime('today midnight'));

        $sqlQuery = '
        UPDATE services 
        SET 
            tarif_id = ?, 
            payday = ? 
        WHERE 
            user_id = ? AND
            ID = ?
         ';
        $query = $this->db->prepare($sqlQuery);

        return $query->execute([$tarifId, $payday, $userId, $serviceId]);
    }
}