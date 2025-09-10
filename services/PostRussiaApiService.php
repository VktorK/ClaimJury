<?php

namespace app\services;

use Yii;
use yii\base\Component;
use yii\httpclient\Client;

/**
 * Сервис для работы с API отслеживания посылок
 * Использует API "Моя посылочка" для получения информации о статусе отправлений
 */
class PostRussiaApiService extends Component
{
    /**
     * URL для API "Моя посылочка"
     */
    const API_URL = 'https://moyaposylka.ru/api/v1/tracking';
    
    /**
     * API ключ (должен быть настроен в конфигурации)
     */
    public $apiKey;
    
    /**
     * Таймаут запросов в секундах
     */
    public $timeout = 30;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        
        if (empty($this->apiKey)) {
            $this->apiKey = Yii::$app->params['postRussiaApiKey'] ?? null;
        }
    }

    /**
     * Отслеживание отправления по трек-номеру через API "Моя посылочка"
     * 
     * @param string $trackingNumber Трек-номер
     * @return array Результат отслеживания с полем 'success'
     */
    public function trackPackage($trackingNumber)
    {
        if (empty($trackingNumber)) {
            Yii::error('Трек-номер не указан');
            return [
                'success' => false,
                'message' => 'Трек-номер не указан',
                'error_code' => 'EMPTY_TRACKING_NUMBER'
            ];
        }

        if (empty($this->apiKey)) {
            Yii::error('API ключ не настроен');
            return [
                'success' => false,
                'message' => 'API ключ не настроен. Обратитесь к администратору.',
                'error_code' => 'API_KEY_NOT_SET'
            ];
        }

        try {
            // Формируем URL для API
            $url = self::API_URL;
            
            // Параметры для POST запроса
            $params = [
                'tracking_number' => $trackingNumber
            ];
            
            // Используем cURL для POST запроса
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_VERBOSE, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'X-Api-Key: ' . '16877bfb71454406c3ad7b02a5137d80',
                'User-Agent: ClaimJury/1.0'
            ]);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);
            
            if ($curlError) {
                throw new \Exception('cURL ошибка: ' . $curlError);
            }
            
            if ($httpCode === 200) {
                $data = json_decode($response, true);
                
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception('Ошибка парсинга JSON: ' . json_last_error_msg());
                }
                
                // Проверяем успешность ответа
                if (isset($data['success']) && $data['success'] === true) {
                    $result = $this->parseMoyaPosylkaData($data, $trackingNumber);
                    $result['success'] = true;
                    return $result;
                }
                
                // Если есть ошибка в ответе
                if (isset($data['error'])) {
                    return [
                        'success' => false,
                        'message' => $data['error']['message'] ?? 'Ошибка API',
                        'error_code' => $data['error']['code'] ?? 'API_ERROR',
                        'tracking_number' => $trackingNumber
                    ];
                }
                
                return [
                    'success' => false,
                    'status' => 'not_found',
                    'message' => 'Отправление не найдено',
                    'tracking_number' => $trackingNumber,
                    'error_code' => 'PACKAGE_NOT_FOUND'
                ];
            } else {
                $errorMessage = 'Ошибка API Моя посылочка: ' . $httpCode;
                if ($httpCode == 404) {
                    $errorMessage = 'Отправление не найдено';
                } elseif ($httpCode == 429) {
                    $errorMessage = 'Превышен лимит запросов. Попробуйте позже.';
                } elseif ($httpCode >= 500) {
                    $errorMessage = 'Временная недоступность сервиса. Попробуйте позже.';
                }
                
                Yii::error($errorMessage . ' - HTTP Code: ' . $httpCode . ', Response: ' . $response);
                return [
                    'success' => false,
                    'message' => $errorMessage,
                    'error_code' => 'API_ERROR_' . $httpCode,
                    'status_code' => $httpCode
                ];
            }
            
        } catch (\Exception $e) {
            Yii::error('Исключение при обращении к API Моя посылочка: ' . $e->getMessage());
            Yii::error('URL запроса: ' . $url);
            Yii::error('Трек-номер: ' . $trackingNumber);
            
            // Более детальная диагностика ошибки
            $errorMessage = 'Ошибка соединения с API: ' . $e->getMessage();
            if (strpos($e->getMessage(), 'getaddrinfo failed') !== false) {
                $errorMessage = 'Ошибка DNS: не удается найти сервер API. Проверьте подключение к интернету.';
            } elseif (strpos($e->getMessage(), 'Connection refused') !== false) {
                $errorMessage = 'Сервер API недоступен. Попробуйте позже.';
            } elseif (strpos($e->getMessage(), 'timeout') !== false) {
                $errorMessage = 'Превышено время ожидания ответа от API.';
            }
            
            return [
                'success' => false,
                'message' => $errorMessage,
                'error_code' => 'CONNECTION_ERROR',
                'debug_info' => [
                    'url' => $url,
                    'tracking_number' => $trackingNumber,
                    'original_error' => $e->getMessage()
                ]
            ];
        }
    }

    /**
     * Парсинг данных отслеживания от API "Моя посылочка"
     * 
     * @param array $data Данные от API
     * @param string $trackingNumber Трек-номер
     * @return array Обработанные данные
     */
    private function parseMoyaPosylkaData($data, $trackingNumber)
    {
        $result = [
            'tracking_number' => $trackingNumber,
            'status' => 'unknown',
            'events' => [],
            'last_update' => time(),
            'delivered' => false,
            'delivery_date' => null
        ];

        // Обрабатываем события (может быть в разных полях)
        $events = [];
        if (isset($data['events']) && is_array($data['events'])) {
            $events = $data['events'];
        } elseif (isset($data['data']['events']) && is_array($data['data']['events'])) {
            $events = $data['data']['events'];
        } elseif (isset($data['tracking']['events']) && is_array($data['tracking']['events'])) {
            $events = $data['tracking']['events'];
        }

        foreach ($events as $event) {
            $eventData = [
                'date' => isset($event['date']) ? $event['date'] : 
                         (isset($event['datetime']) ? $event['datetime'] : null),
                'time' => isset($event['time']) ? $event['time'] : null,
                'location' => isset($event['location']) ? $event['location'] : 
                            (isset($event['place']) ? $event['place'] : ''),
                'description' => isset($event['description']) ? $event['description'] : 
                               (isset($event['status']) ? $event['status'] : ''),
                'status' => isset($event['status']) ? $event['status'] : ''
            ];
            
            $result['events'][] = $eventData;
            
            // Проверяем, доставлено ли отправление
            if (isset($event['status']) && 
                (stripos($event['status'], 'delivered') !== false || 
                 stripos($event['status'], 'вручено') !== false ||
                 stripos($event['status'], 'доставлено') !== false)) {
                $result['delivered'] = true;
                $result['delivery_date'] = $eventData['date'];
            }
        }
        
        // Определяем текущий статус из последнего события
        if (!empty($result['events'])) {
            $lastEvent = end($result['events']);
            $result['status'] = $this->getStatusFromMoyaPosylkaEvent($lastEvent['status'] ?? $lastEvent['description']);
        }

        // Если есть общий статус в данных
        if (isset($data['status'])) {
            $result['status'] = $this->getStatusFromMoyaPosylkaEvent($data['status']);
        } elseif (isset($data['data']['status'])) {
            $result['status'] = $this->getStatusFromMoyaPosylkaEvent($data['data']['status']);
        } elseif (isset($data['tracking']['status'])) {
            $result['status'] = $this->getStatusFromMoyaPosylkaEvent($data['tracking']['status']);
        }

        return $result;
    }

    /**
     * Парсинг данных отслеживания из SOAP ответа (старый метод для совместимости)
     * 
     * @param object $operationHistoryData Данные от SOAP API
     * @param string $trackingNumber Трек-номер
     * @return array Обработанные данные
     */
    private function parseSoapTrackingData($operationHistoryData, $trackingNumber)
    {
        $result = [
            'tracking_number' => $trackingNumber,
            'status' => 'unknown',
            'events' => [],
            'last_update' => time(),
            'delivered' => false,
            'delivery_date' => null
        ];

        // Обрабатываем события
        if (isset($operationHistoryData->historyRecord)) {
            $events = is_array($operationHistoryData->historyRecord) 
                ? $operationHistoryData->historyRecord 
                : [$operationHistoryData->historyRecord];
            
            foreach ($events as $record) {
                $eventData = [
                    'date' => isset($record->OperationParameters->OperDate) 
                        ? $record->OperationParameters->OperDate 
                        : null,
                    'time' => isset($record->OperationParameters->OperTime) 
                        ? $record->OperationParameters->OperTime 
                        : null,
                    'location' => isset($record->AddressParameters->OperationAddress->Description) 
                        ? $record->AddressParameters->OperationAddress->Description 
                        : '',
                    'description' => isset($record->OperationParameters->OperAttr->Name) 
                        ? $record->OperationParameters->OperAttr->Name 
                        : '',
                    'status' => isset($record->OperationParameters->OperAttr->Name) 
                        ? $record->OperationParameters->OperAttr->Name 
                        : ''
                ];
                
                $result['events'][] = $eventData;
                
                // Проверяем, доставлено ли отправление
                if (isset($record->OperationParameters->OperAttr->Name) && 
                    stripos($record->OperationParameters->OperAttr->Name, 'вручено') !== false) {
                    $result['delivered'] = true;
                    $result['delivery_date'] = $eventData['date'];
                }
            }
            
            // Определяем текущий статус из последнего события
            if (!empty($result['events'])) {
                $lastEvent = end($result['events']);
                $result['status'] = $this->getStatusFromSoapEvent($lastEvent['description']);
            }
        }

        return $result;
    }

    /**
     * Парсинг данных отслеживания (старый метод для совместимости)
     * 
     * @param array $data Данные от API
     * @return array Обработанные данные
     */
    private function parseTrackingData($data)
    {
        $result = [
            'tracking_number' => $data['track-number'] ?? '',
            'status' => $this->getStatusFromEvents($data['tracking-events'] ?? []),
            'events' => [],
            'last_update' => time(),
            'delivered' => false,
            'delivery_date' => null
        ];

        // Обрабатываем события
        if (isset($data['tracking-events'])) {
            foreach ($data['tracking-events'] as $event) {
                $eventData = [
                    'date' => $event['date'] ?? null,
                    'time' => $event['time'] ?? null,
                    'location' => $event['location'] ?? '',
                    'description' => $event['description'] ?? '',
                    'status' => $event['status'] ?? ''
                ];
                
                $result['events'][] = $eventData;
                
                // Проверяем, доставлено ли отправление
                if (isset($event['status']) && in_array($event['status'], ['DELIVERED', 'Вручено', 'ВРУЧЕНО'])) {
                    $result['delivered'] = true;
                    $result['delivery_date'] = $eventData['date'];
                }
            }
        }

        return $result;
    }

    /**
     * Получение статуса из события "Моя посылочка"
     * 
     * @param string $eventStatus Статус события
     * @return string Статус
     */
    private function getStatusFromMoyaPosylkaEvent($eventStatus)
    {
        $status = mb_strtolower($eventStatus);
        
        // Маппинг статусов от "Моя посылочка"
        $statusMap = [
            'accepted' => 'Принято в отделении связи',
            'in_transit' => 'В пути',
            'arrived' => 'Прибыло в место вручения',
            'delivered' => 'Вручено',
            'returned' => 'Возвращено отправителю',
            'customs' => 'На таможне',
            'lost' => 'Утеряно',
            'принято' => 'Принято в отделении связи',
            'в_пути' => 'В пути',
            'прибыло' => 'Прибыло в место вручения',
            'вручено' => 'Вручено',
            'доставлено' => 'Вручено',
            'возвращено' => 'Возвращено отправителю',
            'таможня' => 'На таможне',
            'утеряно' => 'Утеряно'
        ];
        
        // Проверяем точное совпадение
        if (isset($statusMap[$status])) {
            return $statusMap[$status];
        }
        
        // Проверяем частичное совпадение
        foreach ($statusMap as $key => $value) {
            if (strpos($status, $key) !== false) {
                return $value;
            }
        }
        
        return $eventStatus; // Возвращаем оригинальный статус, если не удалось определить
    }

    /**
     * Получение статуса из SOAP события (старый метод для совместимости)
     * 
     * @param string $eventDescription Описание события
     * @return string Статус
     */
    private function getStatusFromSoapEvent($eventDescription)
    {
        $description = mb_strtolower($eventDescription);
        
        if (strpos($description, 'вручено') !== false || strpos($description, 'доставлено') !== false) {
            return 'Вручено';
        } elseif (strpos($description, 'прибыло') !== false) {
            return 'Прибыло в место вручения';
        } elseif (strpos($description, 'в пути') !== false || strpos($description, 'транзит') !== false) {
            return 'В пути';
        } elseif (strpos($description, 'принято') !== false) {
            return 'Принято в отделении связи';
        } elseif (strpos($description, 'возвращено') !== false) {
            return 'Возвращено отправителю';
        } elseif (strpos($description, 'таможня') !== false) {
            return 'На таможне';
        } elseif (strpos($description, 'утеряно') !== false) {
            return 'Утеряно';
        }
        
        return $eventDescription; // Возвращаем оригинальное описание, если не удалось определить статус
    }

    /**
     * Получение текущего статуса из событий (старый метод для совместимости)
     * 
     * @param array $events События отслеживания
     * @return string Текущий статус
     */
    private function getStatusFromEvents($events)
    {
        if (empty($events)) {
            return 'unknown';
        }

        // Берем последнее событие
        $lastEvent = end($events);
        
        $statusMap = [
            'ACCEPTED' => 'Принято в отделении связи',
            'IN_TRANSIT' => 'В пути',
            'ARRIVED' => 'Прибыло в место вручения',
            'DELIVERED' => 'Вручено',
            'RETURNED' => 'Возвращено отправителю',
            'CUSTOMS' => 'На таможне',
            'LOST' => 'Утеряно',
            'ПРИНЯТО' => 'Принято в отделении связи',
            'В_ПУТИ' => 'В пути',
            'ПРИБЫЛО' => 'Прибыло в место вручения',
            'ВРУЧЕНО' => 'Вручено',
            'ВОЗВРАЩЕНО' => 'Возвращено отправителю',
            'ТАМОЖНЯ' => 'На таможне',
            'УТЕРЯНО' => 'Утеряно'
        ];

        $status = $lastEvent['status'] ?? 'unknown';
        return $statusMap[$status] ?? $status;
    }

    /**
     * Проверка валидности трек-номера
     * 
     * @param string $trackingNumber Трек-номер
     * @return bool Валидный ли трек-номер
     */
    public function validateTrackingNumber($trackingNumber)
    {
        // Российские трек-номера обычно имеют формат: 14 цифр или 13 цифр + буква
        $pattern = '/^[0-9]{13}[A-Z]?$|^[0-9]{14}$/';
        return preg_match($pattern, $trackingNumber);
    }

    /**
     * Получение статуса отслеживания в удобном формате
     * 
     * @param string $status Статус от API
     * @return array Информация о статусе
     */
    public function getStatusInfo($status)
    {
        $statuses = [
            'Принято в отделении связи' => [
                'class' => 'info',
                'icon' => 'fas fa-inbox',
                'color' => '#3B82F6'
            ],
            'В пути' => [
                'class' => 'warning',
                'icon' => 'fas fa-truck',
                'color' => '#F59E0B'
            ],
            'Прибыло в место вручения' => [
                'class' => 'primary',
                'icon' => 'fas fa-map-marker-alt',
                'color' => '#8B5CF6'
            ],
            'Вручено' => [
                'class' => 'success',
                'icon' => 'fas fa-check-circle',
                'color' => '#10B981'
            ],
            'Возвращено отправителю' => [
                'class' => 'danger',
                'icon' => 'fas fa-undo',
                'color' => '#EF4444'
            ],
            'На таможне' => [
                'class' => 'secondary',
                'icon' => 'fas fa-passport',
                'color' => '#6B7280'
            ],
            'Утеряно' => [
                'class' => 'danger',
                'icon' => 'fas fa-exclamation-triangle',
                'color' => '#DC2626'
            ]
        ];

        return $statuses[$status] ?? [
            'class' => 'secondary',
            'icon' => 'fas fa-question-circle',
            'color' => '#6B7280'
        ];
    }
}
