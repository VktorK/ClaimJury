<?php

namespace app\services;

use Yii;

/**
 * Мок-сервис для имитации работы API отслеживания
 * Используется для демонстрации функциональности без реального API
 */
class MockTrackingService
{
    /**
     * Имитация отслеживания отправления
     * 
     * @param string $trackingNumber Трек-номер
     * @return array Результат отслеживания
     */
    public function trackPackage($trackingNumber)
    {
        if (empty($trackingNumber)) {
            return [
                'success' => false,
                'message' => 'Трек-номер не указан',
                'error_code' => 'EMPTY_TRACKING_NUMBER'
            ];
        }

        // Имитируем задержку API
        usleep(500000); // 0.5 секунды

        // Генерируем случайные данные для демонстрации
        $statuses = [
            'Принято в отделении связи',
            'В пути',
            'Прибыло в место вручения',
            'Вручено',
            'Возвращено отправителю'
        ];

        $locations = [
            'Москва',
            'Санкт-Петербург',
            'Екатеринбург',
            'Новосибирск',
            'Казань',
            'Нижний Новгород'
        ];

        $currentStatus = $statuses[array_rand($statuses)];
        $isDelivered = ($currentStatus === 'Вручено');

        $events = [];
        $eventCount = rand(3, 8);
        
        for ($i = 0; $i < $eventCount; $i++) {
            $eventDate = date('Y-m-d', strtotime('-' . ($eventCount - $i) . ' days'));
            $eventTime = date('H:i:s', rand(8 * 3600, 20 * 3600));
            
            $events[] = [
                'date' => $eventDate,
                'time' => $eventTime,
                'location' => $locations[array_rand($locations)],
                'description' => $this->getEventDescription($currentStatus, $i, $eventCount),
                'status' => $this->getEventStatus($currentStatus, $i, $eventCount)
            ];
        }

        return [
            'success' => true,
            'tracking_number' => $trackingNumber,
            'status' => $currentStatus,
            'events' => $events,
            'last_update' => time(),
            'delivered' => $isDelivered,
            'delivery_date' => $isDelivered ? $events[count($events) - 1]['date'] : null
        ];
    }

    /**
     * Получение описания события
     */
    private function getEventDescription($currentStatus, $index, $total)
    {
        $descriptions = [
            'Принято в отделении связи' => 'Отправление принято в отделении связи',
            'В пути' => 'Отправление в пути',
            'Прибыло в место вручения' => 'Отправление прибыло в место вручения',
            'Вручено' => 'Отправление вручено получателю',
            'Возвращено отправителю' => 'Отправление возвращено отправителю'
        ];

        return $descriptions[$currentStatus] ?? 'Обработка отправления';
    }

    /**
     * Получение статуса события
     */
    private function getEventStatus($currentStatus, $index, $total)
    {
        $statusMap = [
            'Принято в отделении связи' => 'ACCEPTED',
            'В пути' => 'IN_TRANSIT',
            'Прибыло в место вручения' => 'ARRIVED',
            'Вручено' => 'DELIVERED',
            'Возвращено отправителю' => 'RETURNED'
        ];

        return $statusMap[$currentStatus] ?? 'PROCESSING';
    }

    /**
     * Проверка валидности трек-номера
     */
    public function validateTrackingNumber($trackingNumber)
    {
        // Российские трек-номера обычно имеют формат: 14 цифр или 13 цифр + буква
        $pattern = '/^[0-9]{13}[A-Z]?$|^[0-9]{14}$/';
        return preg_match($pattern, $trackingNumber);
    }
}
