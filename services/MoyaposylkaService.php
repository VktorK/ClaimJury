<?php

namespace app\services;

use app\models\Package;
use Yii;
use yii\base\Component;
use yii\httpclient\Client;
use yii\helpers\Json;

class MoyaposylkaService extends Component
{
    public $apiToken;
    public $baseUrl;

    private $client;

    public function __construct($config = [])
    {
        parent::__construct($config);

        // Правильные ключи параметров
        $this->apiToken = Yii::$app->params['moyaposylkaApiToken'] ?? null;
        $this->baseUrl = Yii::$app->params['moyaposylkaBaseUrl'] ?? null;

        // Проверка конфигурации
        if (!$this->apiToken) {
            throw new \yii\base\InvalidConfigException('Moyaposylka API token is not configured');
        }
        if (!$this->baseUrl) {
            throw new \yii\base\InvalidConfigException('Moyaposylka base URL is not configured');
        }
    }

    public function init()
    {
        parent::init();

        $this->client = new \yii\httpclient\Client([
            'baseUrl' => $this->baseUrl,
            'requestConfig' => [
                'format' => \yii\httpclient\Client::FORMAT_JSON,
            ],
            'responseConfig' => [
                'format' => \yii\httpclient\Client::FORMAT_JSON,
            ],
        ]);
    }

    /**
     * Добавляет трекер для отслеживания
     */
    public function addTracker($carrier, $barcode, int $claim_id, array $params = [])
    {
        try {
            $url = "/trackers/{$carrier}/{$barcode}";

            $request = $this->client->createRequest()
                ->setMethod('POST')
                ->setUrl($url)
                ->setHeaders([
                    'Authorization' => "Bearer {$this->apiToken}",
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ]);

            if (!empty($params)) {
                $request->setData($params);
            } else {
                $request->setContent('{}');
            }

            $response = $request->send();


            if ($response->isOk) {
                $data = $response->data;
                if (isset($data['success']) && $data['success'] === false) {
                    $package = new Package();
                    $package->track_number = $barcode;
                    $package->claim_id = $claim_id;
                    $package->status = Package::STATUS_PENDING;
                    $package->last_check = time();

                    if ($package->save()) {
                        return json_encode([
                            'success' => true,
                            'message' => 'Трек-номер добавлен и находится в обработке. Данные появятся в ближайшее время.'
                        ]);
                    } else {
                        return json_encode(['success' => false, 'message' => 'Ошибка сохранения трекера.']);
                    }

                } elseif (isset($data['success']) && $data['success'] === true) {
                    // Если бы данные были получены сразу (редкий случай для нового трека)
                    // ... обрабатываем полученные данные о посылке (data['data']) и сохраняем их в модель $package...
                    return json_encode(['success' => true, 'message' => 'Данные по трек-номеру получены!', 'data' => $data['data']]);
                } else {
                    return json_encode(['success' => false, 'message' => 'Ошибка API: ' . $data['message']]);
                }
            } else {

                return json_encode(['success' => false, 'message' => 'Ошибка соединения с сервером отслеживания.']);
            }

        } catch (\Exception $e) {
            return json_encode(['success' => false, 'message' => $e->getMessage()]);
        }

    }

    /**
     * Получает информацию о трекере
     */
    public function getTrackerInfo($carrier, $barcode)
    {
        try {
            // 1. Отправляем запрос и получаем ответ
            $response = $this->client->get("/trackers/{$carrier}/{$barcode}")->send();

            // 2. Логируем сырой ответ для диагностики
            Yii::info("Raw API response for {$barcode}: " . $response->getBody()->getContents(), 'moyaposylka');

            // 3. Сбрасываем указатель потока для повторного чтения
            $response->getBody()->rewind();

            // 4. Проверяем HTTP статус
            if ($response->isOk) {
                $data = $response->data;
                Yii::info("Success API response: " . Json::encode($data), 'moyaposylka');
                return $data;
            } else {
                Yii::error("HTTP Error: Status: {$response->getStatusCode()}, Body: " . $response->getBody()->getContents());
                return false;
            }

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Обработка сетевых ошибок
            if ($e->hasResponse()) {
                Yii::error("API Error: " . $e->getResponse()->getBody()->getContents());
            } else {
                Yii::error("Network Error: " . $e->getMessage());
            }
            return false;

        } catch (\Exception $e) {
            Yii::error("General Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Получает историю статусов отправления
     */
    public function getTrackerHistory($carrier, $barcode)
    {
        try {
            $response = $this->client->get("/trackers/{$carrier}/{$barcode}/history")->send();
            
            if ($response->isOk) {
                return $response->data;
            } else {
                Yii::error(" Ошибка получения истории трекера:" . Json::encode($response->data));
                return false;
            }
            
        } catch (\Exception $e) {
            Yii::error("Ошибка API Moyaposylka: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Определяет перевозчика по номеру отслеживания
     */
    public function detectCarrier($trackingNumber): string
    {
//        // Простая логика определения перевозчика по номеру
//        $number = preg_replace('/[^0-9]/', '', $trackingNumber);
//
//        if (strlen($number) == 13 && (substr($number, 0, 2) == '13' || substr($number, 0, 2) == '14')) {
//            return 'russian-post'; // Почта России
//        } elseif (strlen($number) == 14 && substr($number, 0, 2) == '20') {
//            return 'dpd'; // DPD
//        } elseif (strlen($number) == 12 && substr($number, 0, 2) == '10') {
//            return 'cdek'; // CDEK
//        } elseif (strlen($number) == 11 && substr($number, 0, 2) == '11') {
//            return 'boxberry'; // Boxberry
//        } else {
            return 'russian-post'; // По умолчанию Почта России

    }

    public function debugTrackerInfo($carrier, $barcode)
    {
        try {
            Yii::info("Debug: Sending request to /trackers/{$carrier}/{$barcode}", 'debug');

            $response = $this->client->get("/trackers/{$carrier}/{$barcode}")->send();

            Yii::info("Debug: HTTP Status: " . $response->getStatusCode(), 'debug');
            Yii::info("Debug: Headers: " . Json::encode($response->getHeaders()), 'debug');

            $bodyContent = $response->getBody()->getContents();
            Yii::info("Debug: Raw Body: " . $bodyContent, 'debug');

            // Пробуем распарсить JSON
            $data = json_decode($bodyContent, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Yii::error("Debug: JSON Parse Error: " . json_last_error_msg(), 'debug');
                return ['error' => 'Invalid JSON', 'raw' => $bodyContent];
            }

            return [
                'http_status' => $response->getStatusCode(),
                'data' => $data,
                'raw_response' => $bodyContent
            ];

        } catch (\Exception $e) {
            Yii::error("Debug Exception: " . $e->getMessage(), 'debug');
            return ['error' => $e->getMessage()];
        }
    }

    public function testConnection()
    {
        try {
            $request = $this->client->createRequest()
                ->setMethod('GET')
                ->setUrl('/status')
                ->addHeaders([
                    'Authorization' => 'Bearer ' . $this->apiToken,
                ]);

            $response = $request->send();

            return [
                'success' => true,
                'status' => $response->getStatusCode(),
                'body' => $response->getContent()
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

}