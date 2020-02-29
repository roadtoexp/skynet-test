<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Tariff;
use App\Models\User;
use DateInterval;
use DateTime;
use DateTimeZone;
use Exception;

final class UserController extends BaseApiController
{
    /**
     * @param  int  $userId
     * @param  int  $serviceId
     * @throws Exception
     */
    public function getTariffs(int $userId, int $serviceId): void
    {
        $userTariffs = (new User())->findTariffByServiceId($userId, $serviceId);
        if (empty($userTariffs)) {
            $this->abort(404, 'Not Found');
        }

        $serviceTariffs = (new Tariff())->findByUserIdAndServicesId($userId, $serviceId);
        if (empty($serviceTariffs)) {
            $this->abort(404, 'Not Found');
        }

        $this->response(
            'HTTP/1.1 200 OK',
            [
                'result' => 'ok',
                'title' => $userTariffs['title'],
                'link' => $userTariffs['link'],
                'speed' => $userTariffs['speed'],
                'tarifs' => $this->addNewPayDay($serviceTariffs)
            ]
        );
    }

    /**
     * @param  array  $serviceTariffs
     * @return array
     * @throws Exception
     */
    private function addNewPayDay(array $serviceTariffs): array
    {
        $tariffs = [];
        foreach ($serviceTariffs as $tariff) {
            $tariffs[] = [
                'title' => $tariff['title'],
                'price' => $tariff['price'],
                'pay_period' => $tariff['pay_period'],
                'new_payday' => $this->calculateNewPayDate(
                    $tariff['pay_period']
                ),
                'speed' => $tariff['speed'],
            ];
        }

        return $tariffs;
    }

    /**
     * @param  string  $payPeriod
     * @return string
     * @throws Exception
     */
    private function calculateNewPayDate(string $payPeriod): string
    {
        try {
            $newPayDate = new DateTime(
                date('Y-m-d 00:00:00'),
                new DateTimeZone('Europe/Moscow')
            );
            $newPayDate->add(new DateInterval('P'.$payPeriod.'M'));
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
        return $newPayDate->format('UO');
    }
}
