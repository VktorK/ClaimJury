<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $purchasesCount int */
/* @var $totalAmount float */
/* @var $sellersCount int */
/* @var $productsCount int */
/* @var $recentPurchases app\models\Purchase[] */
/* @var $popularSellers app\models\Seller[] */

$this->title = 'Панель управления';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="dashboard-index">
    <div class="row">
        <div class="col-12">
            <div class="dashboard-header">
                <h1 class="dashboard-title">
                    <i class="fas fa-tachometer-alt"></i>
                    Панель управления
                </h1>
                <p class="dashboard-subtitle">Добро пожаловать в вашу панель управления</p>
            </div>
        </div>
    </div>

    <!-- Основной блок МОИ ПОКУПКИ -->
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card dashboard-main-block">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-shopping-cart"></i>
                        МОИ ПОКУПКИ
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="dashboard-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h4 class="dashboard-block-title">Управление покупками</h4>
                    <p class="dashboard-block-text">
                        Просматривайте свои покупки, управляйте чеками, товарами, продавцами и категориями. 
                        Полная статистика и история ваших покупок.
                    </p>
                    <div class="dashboard-actions">
                        <?= Html::a('Перейти к покупкам', ['/purchase/index'], [
                            'class' => 'btn btn-primary btn-lg',
                            'style' => 'width: 100%; max-width: 300px;'
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.dashboard-index {
    padding: 20px 0;
}

.dashboard-header {
    text-align: center;
    margin-bottom: 50px;
}

.dashboard-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 10px;
}

.dashboard-title i {
    color: #3498db;
    margin-right: 15px;
}

.dashboard-subtitle {
    font-size: 1.2rem;
    color: #7f8c8d;
    margin-bottom: 0;
}

.dashboard-main-block {
    border: none;
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    overflow: hidden;
}

.dashboard-main-block:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
}

.dashboard-main-block .card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 25px;
}

.dashboard-main-block .card-title {
    margin-bottom: 0;
    font-weight: 700;
    font-size: 1.5rem;
}

.dashboard-main-block .card-title i {
    margin-right: 15px;
    font-size: 1.8rem;
}

.dashboard-main-block .card-body {
    padding: 40px 30px;
}

.dashboard-icon {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 25px;
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

.dashboard-icon i {
    font-size: 3rem;
    color: white;
}

.dashboard-block-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 20px;
}

.dashboard-block-text {
    font-size: 1.1rem;
    color: #7f8c8d;
    line-height: 1.6;
    margin-bottom: 30px;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

.dashboard-actions .btn {
    border-radius: 15px;
    font-weight: 600;
    padding: 15px 30px;
    font-size: 1.1rem;
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
    transition: all 0.3s ease;
}

.dashboard-actions .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
}

.card {
    border: none;
    border-radius: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.card-header {
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    border-radius: 20px 20px 0 0;
}

.card-title {
    margin-bottom: 0;
    font-weight: 600;
    color: #2c3e50;
}

.card-title i {
    color: #3498db;
    margin-right: 10px;
}
</style>
