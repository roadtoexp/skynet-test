<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Service;
use App\Models\Tariff;
use App\Models\User;

final class ServiceController extends BaseApiController
{
    public function updateTariff(int $userId, int $serviceId): void
    {
        $data = json_decode(
            file_get_contents('php://input'),
            true
        );
        $tariffId = $data['tarif_id'] ?? null;

        if (!is_numeric($tariffId)) {
            $this->abort(400, 'Bad Request');
        }

        if ((new Service())->updateTariff(
            $userId,
            $serviceId,
            $tariffId
        )) {
            $this->response(
                'HTTP/1.1 404 Not Found',
                [
                    'result' => 'error',
                ]
            );
        }
        $this->abort(404, 'Not Found');
    }
}