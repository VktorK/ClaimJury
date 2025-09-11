<?php

namespace app\services;

use Yii;
use yii\base\Component;
use yii\httpclient\Client;
use yii\helpers\Json;

class MoyaposylkaService extends Component
{
    public $apiToken;
    public $baseUrl;

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->apiToken = Yii::$app->params['apiToken'];
        $this->baseUrl = Yii::$app->params['baseUrl'];
    }

    private $client;

    public function init()
    {
        parent::init();
        $this->client = new Client([
            'baseUrl' => $this->baseUrl,
        ]);
    }

    /**
     * Добавляет трекер для отслеживания
     */
    public function addTracker($carrier, $barcode, array $params = [])
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
                return $response->data;
            } else {
                $errorData = $response->data;
                $errorMessage = $errorData['message'] ?? 'Неизвестная ошибка API';

                return [
                    'success' => false,
                    'message' => $errorMessage,
                    'status' => $response->statusCode,
                    'data' => $errorData
                ];
            }

        } catch (\yii\httpclient\Exception $e) {
            return [
                'success' => false,
                'message' => 'Сетевая ошибка: ' . $e->getMessage()
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Ошибка: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Простая версия без параметров
     */
    public function addSimpleTracker($carrier, $barcode)
    {
        return $this->addTracker($carrier, $barcode);
    }

    /**
     * Получает информацию о трекере
     */
    public function getTrackerInfo($carrier, $barcode)
    {
        try {
            $response = $this->client->get("/trackers/{$carrier}/{$barcode}")->send();
            
            if ($response->isOk) {
                return $response->data;
            } else {
                Yii::error("Ошибка получения информации о трекере: " . Json::encode($response->data));
                return false;
            }
            
        } catch (\Exception $e) {
            Yii::error("Ошибка API Moyaposylka: " . $e->getMessage());
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
                Yii::error("Ошибка получения истории трекера: " . Json::encode($response->data));
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
}