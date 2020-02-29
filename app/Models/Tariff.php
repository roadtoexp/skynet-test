<?php

declare(strict_types=1);

namespace App\Models;

use PDO;
use Core\DB;

final class Tariff extends DB
{
    public function findByUserIdAndServicesId(int $userId, int $serviceId): array
    {
        $sqlTariffGroupIdQuery = '
        SELECT tarifs.tarif_group_id
        FROM services  
        LEFT JOIN tarifs  ON (tarifs.ID = services.tarif_id)
        WHERE 
            services.user_id = ? AND 
            services.ID = ?
        ';
        $sqlQuery = "
        SELECT * 
        FROM tarifs
        WHERE 
            tarif_group_id IN  ( $sqlTariffGroupIdQuery )
        ";

        $query = $this->db->prepare($sqlQuery);
        $query->execute([$userId, $serviceId]);

        $tariffs = $query->fetchAll(PDO::FETCH_ASSOC);
        return empty($tariffs) ? [] : $tariffs;
    }
}