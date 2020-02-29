<?php

declare(strict_types=1);

namespace App\Models;

use Core\DB;
use PDO;

final class User extends DB
{
    public function findTariffByServiceId(int $userId, int $serviceId): array
    {
        $sqlQuery = '
        SELECT tarifs.* 
        FROM services 
        INNER JOIN tarifs ON tarifs.ID = services.tarif_id 
        WHERE 
            services.user_id = ? AND 
            services.ID = ?
        ';

        $query = $this->db->prepare($sqlQuery);
        $query->execute([$userId, $serviceId]);

        $tariff = $query->fetch(PDO::FETCH_ASSOC);

        return $tariff ?? [];
    }
}