<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Product;

/* @var $this yii\web\View */
/* @var $model app\models\Product */

$this->title = 'Просмотр товара';
$this->params['breadcrumbs'][] = ['label' => 'Покупки', 'url' => ['/purchases']];
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['/product/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="product-view">

    <!-- Шапка слева, изображение справа -->
    <div class="row mb-2">
        <div class="col-lg-8">
            <div class="product-header-card">
                <div class="card-body">
                    <div class="product-header-info">
                        <h2 class="product-title"><?= Html::encode($model->title) ?></h2>
                        <p class="product-subtitle">Информация о товаре</p>
                        
                        <div class="product-actions">
                            <?= Html::a('<i class="fas fa-edit"></i> Редактировать', ['update', 'id' => $model->id], [
                                'class' => 'btn btn-primary product-action-btn'
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Информация о товаре снизу -->
    <div class="row">
        <div class="col-lg-6">
            <div class="product-table-card">
                <div class="card-body">
                    <div class="product-info">
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Название:</label>
                                <span><?= Html::encode($model->title) ?></span>
                            </div>
                            
                            <?php if ($model->category): ?>
                                <div class="info-item">
                                    <label>Категория:</label>
                                    <span class="category-link">
                                        <?= Html::a(
                                            '<i class="fas fa-folder"></i> ' . Html::encode($model->category->title),
                                            ['/category/view', 'id' => $model->category_id],
                                            ['class' => 'category-name-link']
                                        ) ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="info-item">
                                <label>Дата создания:</label>
                                <span><?= $model->getFormattedCreatedDate() ?></span>
                            </div>
                            
                            <?php if ($model->description): ?>
                                <div class="info-item full-width">
                                    <label>Описание:</label>
                                    <span><?= Html::encode($model->description) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="product-stats">
                <div class="stat-item">
                    <label>Всего покупок:</label>
                    <span><?= count($model->purchases) ?></span>
                </div>
                <div class="stat-item">
                    <label>Общая сумма:</label>
                    <span><?= number_format(array_sum(array_column($model->purchases, 'amount')), 0, ',', ' ') ?> р</span>
                </div>
            </div>
        </div>
    </div>
    
    <?php if (!empty($model->purchases)): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="purchases-card">
                    <div class="card-header">
                        <h3>
                            <i class="fas fa-shopping-cart"></i>
                            Покупки этого товара
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="purchases-list">
                            <?php foreach ($model->purchases as $purchase): ?>
                                <div class="purchase-item">
                                    <div class="purchase-info">
                                        <h4>
                                            <?= Html::a(
                                                Html::encode($purchase->product_name),
                                                ['/purchase/view', 'id' => $purchase->id],
                                                ['class' => 'purchase-link']
                                            ) ?>
                                        </h4>
                                        <div class="purchase-meta">
                                            <span class="purchase-date">
                                                <i class="fas fa-calendar"></i>
                                                <?= $purchase->getFormattedPurchaseDate() ?>
                                            </span>
                                            <span class="purchase-amount">
                                                <i class="fas fa-ruble-sign"></i>
                                                <?= $purchase->getFormattedAmount() ?>
                                            </span>
                                            <?php if ($purchase->seller): ?>
                                                <span class="purchase-seller">
                                                    <i class="fas fa-store"></i>
                                                    <?= Html::a(
                                                        Html::encode($purchase->seller->title),
                                                        ['/seller/view', 'id' => $purchase->seller_id],
                                                        ['class' => 'seller-link']
                                                    ) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>


<style>
.product-view {
    padding: 20px 0;
}

.dashboard-header {
    text-align: center;
    margin-bottom: 40px;
    padding: 30px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    color: white;
    position: relative;
}

.dashboard-navigation {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-top: 25px;
    flex-wrap: wrap;
}

.dashboard-nav-btn {
    background: rgba(255, 255, 255, 0.15);
    border: 2px solid rgba(255, 255, 255, 0.4);
    color: white;
    border-radius: 12px;
    padding: 12px 24px;
    font-size: 1rem;
    font-weight: 600;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    position: relative;
    overflow: hidden;
    min-width: 160px;
    text-align: center;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.dashboard-nav-btn:hover {
    background: rgba(255, 255, 255, 0.25);
    border-color: rgba(255, 255, 255, 0.6);
    color: white;
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.2);
    text-decoration: none;
}

.dashboard-nav-btn:active {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.dashboard-nav-btn i {
    font-size: 1.1rem;
}

.dashboard-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 10px;
}

.dashboard-title i {
    margin-right: 15px;
}

.dashboard-subtitle {
    font-size: 1.2rem;
    opacity: 0.9;
    margin: 0;
}

.product-header-card,
.product-table-card,
.product-image-card,
.purchases-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    overflow: hidden;
}

.card-body {
    padding: 30px;
}

.product-header-info {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.product-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #333;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 15px;
}

.product-title::before {
    content: '\f49e';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    color: #8B5CF6;
    font-size: 2rem;
}

.product-subtitle {
    font-size: 1.2rem;
    color: #666;
    margin: 0;
    opacity: 0.9;
}

.product-actions {
    display: flex;
    gap: 15px;
    align-items: center;
}

.product-action-btn {
    background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.2));
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.3);
    border-radius: 12px;
    padding: 12px 24px;
    font-weight: 600;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    position: relative;
    overflow: hidden;
}

.product-action-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.product-action-btn:hover::before {
    left: 100%;
}

.product-action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.15);
}

.product-action-btn:active {
    transform: translateY(0);
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.btn-primary.product-action-btn {
    background: linear-gradient(135deg, #8B5CF6, #A855F7);
    color: white;
}

.btn-danger.product-action-btn {
    background: linear-gradient(135deg, #EF4444, #F87171);
    color: white;
}

.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.info-item.full-width {
    grid-column: 1 / -1;
}

.info-item label {
    font-weight: 600;
    color: #333;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-item span {
    color: #666;
    font-size: 1rem;
}


.product-stats {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin-top: 20px;
}

.stat-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #e9ecef;
}

.stat-item:last-child {
    border-bottom: none;
}

.stat-item label {
    font-weight: 600;
    color: #333;
}

.stat-item span {
    color: #28a745;
    font-weight: 600;
    font-size: 1.1rem;
}

.purchases-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.purchase-item {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    border-left: 4px solid #28a745;
}

.purchase-info h4 {
    margin: 0 0 10px 0;
    color: #333;
}

.purchase-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    font-size: 0.9rem;
    color: #666;
}

.purchase-meta span {
    display: flex;
    align-items: center;
    gap: 5px;
}

.purchase-link {
    color: #28a745;
    text-decoration: none;
    font-weight: 500;
}

.purchase-link:hover {
    color: #1e7e34;
    text-decoration: underline;
}

.seller-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
}

.seller-link:hover {
    color: #764ba2;
    text-decoration: underline;
}

.category-name-link {
    color: #6f42c1;
    text-decoration: none;
    font-weight: 500;
    padding: 3px 8px;
    border-radius: 4px;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 3px;
}

.category-name-link:hover {
    color: #5a32a3;
    background: #f8f9fa;
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 2px 6px rgba(111, 66, 193, 0.2);
}

.category-name-link::after {
    content: '\f35d';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    font-size: 0.7rem;
    opacity: 0.7;
}


@media (max-width: 768px) {
    .dashboard-title {
        font-size: 2rem;
    }
    
    .dashboard-navigation {
        flex-direction: column;
        align-items: center;
        gap: 12px;
        margin-top: 20px;
    }
    
    .dashboard-nav-btn {
        min-width: 140px;
        padding: 10px 20px;
        font-size: 0.9rem;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .card-body {
        padding: 20px;
    }
    
    .purchase-meta {
        flex-direction: column;
        gap: 8px;
    }
}
</style>

