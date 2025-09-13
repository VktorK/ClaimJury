<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Purchase;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $totalAmount float */
/* @var $purchasesCount int */

$this->title = 'Покупки';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="purchase-index">
    <div class="row">
        <div class="col-12">
            <div class="dashboard-header">
                <h1 class="dashboard-title">
                    <i class="fas fa-shopping-cart"></i>
                    Покупки
                </h1>
                <p class="dashboard-subtitle">Управляйте своими покупками и чеками</p>
                
                <div class="dashboard-navigation">
                    <?= Html::a('<i class="fas fa-tachometer-alt"></i> Панель управления', ['/dashboard'], [
                        'class' => 'btn btn-outline-primary dashboard-nav-btn'
                    ]) ?>
                    <?= Html::a('<i class="fas fa-store"></i> Продавцы', ['/seller/index'], [
                        'class' => 'btn btn-outline-primary dashboard-nav-btn'
                    ]) ?>
                    <?= Html::a('<i class="fas fa-user"></i> Покупатели', ['/buyer/index'], [
                        'class' => 'btn btn-outline-primary dashboard-nav-btn'
                    ]) ?>
                    <?= Html::a('<i class="fas fa-box"></i> Мои товары', ['/product/index'], [
                        'class' => 'btn btn-outline-primary dashboard-nav-btn'
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Статистика -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="stat-content">
                    <h3><?= $purchasesCount ?></h3>
                    <p>Всего покупок</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-ruble-sign"></i>
                </div>
                <div class="stat-content">
                    <h3><?= number_format($totalAmount, 0, ',', ' ') ?> р</h3>
                    <p>Общая сумма</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-plus"></i>
                </div>
                <div class="stat-content">
                    <h3><?= Html::a('Добавить покупку', ['create'], ['class' => 'btn btn-primary btn-sm']) ?></h3>
                    <p>Новая запись</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Список покупок -->
    <div class="row">
        <div class="col-12">
            <div class="purchases-card">
                <div class="card-header">
                    <h3>Покупки</h3>
                    <div class="card-actions">
                        <?= Html::a('<i class="fas fa-plus"></i> Добавить покупку', ['create'], ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
                
                <div class="card-body">
                    <?php Pjax::begin(); ?>
                    
                    <div class="table-responsive">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'tableOptions' => ['class' => 'table table-hover'],
                        'columns' => [
                            [
                                'attribute' => 'product_id',
                                'label' => 'Товар',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    if ($model->product_id && $model->product) {
                                        return Html::a(
                                            '<i class="fas fa-box"></i> ' . Html::encode($model->product->title),
                                            ['/product/view', 'id' => $model->product_id],
                                            ['class' => 'product-name-link']
                                        );
                                    } else {
                                        return Html::a(
                                            '<i class="fas fa-tag"></i> ' . Html::encode($model->product_name),
                                            ['view', 'id' => $model->id],
                                            ['class' => 'product-link']
                                        );
                                    }
                                },
                            ],
                            [
                                'attribute' => 'seller_id',
                                'label' => 'Продавец',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    if ($model->seller_id) {
                                        return Html::a(
                                            Html::encode($model->getSellerName()),
                                            ['/seller/view', 'id' => $model->seller_id],
                                            ['class' => 'seller-name-link']
                                        );
                                    }
                                    return Html::encode($model->getSellerName());
                                },
                            ],
                            [
                                'attribute' => 'buyer_id',
                                'label' => 'Покупатель',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    if ($model->buyer_id) {
                                        return Html::a(
                                            Html::encode($model->getBuyerName()),
                                            ['/buyer/view', 'id' => $model->buyer_id],
                                            ['class' => 'buyer-name-link']
                                        );
                                    }
                                    return Html::encode($model->getBuyerName());
                                },
                            ],
                            [
                                'attribute' => 'purchase_date',
                                'label' => 'Дата покупки',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return $model->getFormattedPurchaseDate();
                                },
                            ],
                            [
                                'attribute' => 'amount',
                                'label' => 'Сумма',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return '<span class="amount">' . $model->getFormattedAmount() . '</span>';
                                },
                            ],
                            [
                                'attribute' => 'warranty_period',
                                'label' => 'Гарантийный срок',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return '<span class="warranty-period">' . $model->getFormattedWarrantyPeriod() . '</span>';
                                },
                            ],
                            [
                                'attribute' => 'appeal_deadline',
                                'label' => 'Срок обращения',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    $deadline = $model->getFormattedAppealDeadline();
                                    $isExpired = $model->appeal_deadline && strtotime($model->appeal_deadline) < time();
                                    $class = $isExpired ? 'appeal-deadline expired' : 'appeal-deadline';
                                    return '<span class="' . $class . '">' . $deadline . '</span>';
                                },
                            ],
                            [
                                'attribute' => 'receipt_image',
                                'label' => 'Чек',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    if ($model->receipt_image) {
                                        $receiptUrl = $model->getReceiptUrl();
                                        return '<div class="receipt-preview" style="position: relative; display: inline-block; width: 50px; height: 50px;">
                                                    <img src="' . $receiptUrl . '" alt="Чек" class="receipt-thumb" 
                                                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px; border: 2px solid #e9ecef; cursor: pointer;" 
                                                         title="Нажмите для просмотра" 
                                                         data-receipt-url="' . $receiptUrl . '"
                                                         onclick="openReceiptModal(this)">
                                                    <div class="receipt-overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); border-radius: 6px; display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s ease;">
                                                        <i class="fas fa-expand" style="color: white; font-size: 1rem;"></i>
                                                    </div>
                                                </div>';
                                    } else {
                                        return '<i class="fas fa-receipt text-muted" title="Чек не прикреплен" style="font-size: 1.5rem;"></i>';
                                    }
                                },
                            ],
                            [
                                'label' => 'Претензии',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    $claimsCount = $model->getClaims()->count();
                                    if ($claimsCount > 0) {
                                        return Html::button(
                                            '<i class="fas fa-exclamation-triangle"></i> ' . $claimsCount,
                                            [
                                                'class' => 'btn btn-sm btn-warning claims-link',
                                                'title' => 'Просмотреть претензии по этой покупке',
                                                'onclick' => 'openClaimsModal(' . $model->id . ')'
                                            ]
                                        );
                                    } else {
                                        return Html::a(
                                            '<i class="fas fa-plus"></i> Создать',
                                            ['/claim/create', 'Claim[purchase_id]' => $model->id],
                                            [
                                                'class' => 'btn btn-sm btn-outline-success claims-create-link',
                                                'title' => 'Создать претензию по этой покупке'
                                            ]
                                        );
                                    }
                                },
                                'headerOptions' => ['style' => 'width: 120px; text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: center;'],
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '<div class="btn-group">{view} {update} {delete}</div>',
                                'header' => 'Действия',
                                'headerOptions' => ['style' => 'width: 180px; text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: center;'],
                                'buttons' => [
                                    'view' => function ($url, $model, $key) {
                                        return Html::a(
                                            '<i class="fas fa-eye"></i>',
                                            $url,
                                            [
                                                'class' => 'btn btn-sm btn-outline-primary', 
                                                'title' => 'Просмотр',
                                                'style' => 'margin: 1px;'
                                            ]
                                        );
                                    },
                                    'update' => function ($url, $model, $key) {
                                        return Html::a(
                                            '<i class="fas fa-edit"></i>',
                                            $url,
                                            [
                                                'class' => 'btn btn-sm btn-outline-warning', 
                                                'title' => 'Редактировать',
                                                'style' => 'margin: 1px;'
                                            ]
                                        );
                                    },
                                    'delete' => function ($url, $model, $key) {
                                        return Html::a(
                                            '<i class="fas fa-trash"></i>',
                                            $url,
                                            [
                                                'class' => 'btn btn-sm btn-outline-danger',
                                                'title' => 'Удалить',
                                                'style' => 'margin: 1px;',
                                                'data-confirm' => 'Вы уверены, что хотите удалить эту покупку?',
                                                'data-method' => 'post'
                                            ]
                                        );
                                    },
                                ],
                            ],
                        ],
                        'emptyText' => 'Данные отсутствуют',
                        'emptyTextOptions' => ['class' => 'text-center text-muted py-4'],
                    ]); ?>
                    </div>
                    
                    <?php Pjax::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно для просмотра чеков -->
<div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="receiptModalLabel">Просмотр чека</h5>
                <button type="button" class="btn-close" onclick="closeReceiptModal()" aria-label="Закрыть">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="modalReceiptImage" src="" alt="Чек" class="img-fluid" style="max-height: 70vh; border-radius: 8px;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeReceiptModal()">Закрыть</button>
                <button type="button" class="btn btn-primary" onclick="downloadReceipt()">
                    <i class="fas fa-download"></i> Скачать
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно для просмотра претензий -->
<div class="modal fade" id="claimsModal" tabindex="-1" aria-labelledby="claimsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #ff6b35, #f7931e); color: white; border-radius: 10px 10px 0 0;">
                <h5 class="modal-title" id="claimsModalLabel">Претензии</h5>
                <button type="button" class="btn-close" onclick="closeClaimsModal()" aria-label="Закрыть" style="color: white;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
                <!-- Контент будет загружен через AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeClaimsModal()">Закрыть</button>
                <button type="button" class="btn btn-success" onclick="createClaim()">
                    <i class="fas fa-plus"></i> Создать претензию
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.purchase-index {
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

/* Анимация блика для кнопок навигации */
.dashboard-nav-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.6s;
}

.dashboard-nav-btn:hover::before {
    left: 100%;
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

.stat-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    margin-bottom: 20px;
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 20px;
}

.stat-icon i {
    font-size: 1.5rem;
    color: white;
}

.stat-content h3 {
    font-size: 2rem;
    font-weight: 700;
    margin: 0 0 5px 0;
    color: #333;
}

.stat-content p {
    margin: 0;
    color: #666;
    font-size: 0.9rem;
}

.purchases-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    overflow: hidden;
}

.card-header {
    background: #f8f9fa;
    padding: 20px 30px;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h3 {
    margin: 0;
    color: #333;
    font-weight: 600;
}

.card-body {
    padding: 0;
}

.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    border-radius: 0 0 15px 15px;
}

.table {
    margin: 0;
    min-width: 800px; /* Минимальная ширина таблицы */
    width: 100%;
}

.table th {
    background: #f8f9fa;
    border: none;
    padding: 12px 8px;
    font-weight: 600;
    color: #555;
    font-size: 0.85rem;
}

.table td {
    padding: 12px 8px;
    border: none;
    border-bottom: 1px solid #f8f9fa;
    vertical-align: middle;
    font-size: 0.85rem;
}

.product-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.85rem;
}

.product-link:hover {
    color: #764ba2;
    text-decoration: none;
}

.amount {
    font-weight: 600;
    color: #28a745;
    font-size: 0.85rem;
}

.warranty-period {
    font-weight: 500;
    color: #17a2b8;
    font-size: 0.8rem;
}

.appeal-deadline {
    font-weight: 500;
    color: #28a745;
    font-size: 0.8rem;
}

.appeal-deadline.expired {
    color: #dc3545;
    font-weight: 600;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
}

.btn-sm {
    padding: 5px 10px;
    font-size: 0.8rem;
}

.receipt-preview {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 50px;
}

.receipt-thumb {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 6px;
    border: 2px solid #e9ecef;
    cursor: pointer;
    transition: all 0.3s ease;
}

.receipt-thumb:hover {
    border-color: #667eea;
    transform: scale(1.05);
}

.receipt-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.receipt-preview:hover .receipt-overlay {
    opacity: 1;
}

.receipt-overlay .btn {
    padding: 2px 6px;
    font-size: 0.7rem;
}

.table .btn {
    margin: 1px;
    padding: 4px 8px;
    font-size: 0.8rem;
    display: inline-block;
}

.table .btn i {
    font-size: 0.8rem;
}

.btn-group {
    display: flex;
    gap: 2px;
    justify-content: center;
    flex-wrap: wrap;
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
    
    .stat-card {
        flex-direction: column;
        text-align: center;
    }
    
    .stat-icon {
        margin-right: 0;
        margin-bottom: 15px;
    }
    
    .card-header {
        flex-direction: column;
        gap: 15px;
    }
    
    .table .btn {
        margin: 1px;
        padding: 2px 4px;
        font-size: 0.7rem;
    }
    
    .receipt-preview {
        width: 40px;
        height: 40px;
    }
    
    .table-responsive {
        border-radius: 0;
    }
    
    .table {
        min-width: 600px; /* Уменьшаем минимальную ширину на мобильных */
    }
    
    .table th,
    .table td {
        padding: 8px 4px;
        font-size: 0.75rem;
    }
    
    .receipt-thumb {
        width: 40px;
        height: 40px;
    }
}

/* Дополнительные стили для очень маленьких экранов */
@media (max-width: 480px) {
    .table {
        min-width: 500px;
    }
    
    .table th,
    .table td {
        padding: 6px 2px;
        font-size: 0.7rem;
    }
    
    .btn-group {
        flex-direction: column;
        gap: 1px;
    }
    
    .table .btn {
        padding: 1px 3px;
        font-size: 0.65rem;
    }
}

/* Стили для модального окна */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1050;
    display: none;
}

.modal.show {
    display: block;
}

.modal-dialog {
    position: relative;
    width: auto;
    max-width: 800px;
    margin: 1.75rem auto;
    pointer-events: none;
}

.modal-content {
    position: relative;
    display: flex;
    flex-direction: column;
    width: 100%;
    pointer-events: auto;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid rgba(0,0,0,.2);
    border-radius: 0.3rem;
    outline: 0;
}

.modal-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    padding: 1rem 1rem;
    border-bottom: 1px solid #dee2e6;
    border-top-left-radius: calc(0.3rem - 1px);
    border-top-right-radius: calc(0.3rem - 1px);
}

.modal-title {
    margin-bottom: 0;
    line-height: 1.5;
}

.btn-close {
    padding: 0.25rem 0.25rem;
    margin: -0.25rem -0.25rem -0.25rem auto;
    background: transparent;
    border: 0;
    border-radius: 0.25rem;
    opacity: 0.5;
}

.btn-close:hover {
    opacity: 0.75;
}

.modal-body {
    position: relative;
    flex: 1 1 auto;
    padding: 1rem;
}

.modal-footer {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: flex-end;
    padding: 0.75rem;
    border-top: 1px solid #dee2e6;
    border-bottom-right-radius: calc(0.3rem - 1px);
    border-bottom-left-radius: calc(0.3rem - 1px);
}

.modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1040;
    width: 100vw;
    height: 100vh;
    background-color: #000;
}

.modal-backdrop.fade {
    opacity: 0;
}

.modal-backdrop.show {
    opacity: 0.5;
}

.seller-name-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
    padding: 3px 8px;
    border-radius: 4px;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 3px;
    font-size: 0.85rem;
}

.seller-name-link:hover {
    color: #764ba2;
    background: #f8f9fa;
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 2px 6px rgba(102, 126, 234, 0.2);
}

.seller-name-link::after {
    content: '\f35d';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    font-size: 0.7rem;
    opacity: 0.7;
}

.product-name-link {
    color: #28a745;
    text-decoration: none;
    font-weight: 500;
    padding: 3px 8px;
    border-radius: 4px;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 3px;
}

.product-name-link:hover {
    color: #1e7e34;
    background: #f8f9fa;
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 2px 6px rgba(40, 167, 69, 0.2);
}

.product-name-link::after {
    content: '\f35d';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    font-size: 0.7rem;
    opacity: 0.7;
}

.buyer-name-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
    padding: 3px 8px;
    border-radius: 4px;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 3px;
    font-size: 0.85rem;
}

.buyer-name-link:hover {
    color: #764ba2;
    background: #f8f9fa;
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 2px 6px rgba(102, 126, 234, 0.2);
}

.buyer-name-link::after {
    content: '\f35d';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    font-size: 0.7rem;
    opacity: 0.7;
    margin-left: 3px;
}

/* Стили для кнопок претензий */
.claims-link {
    background: linear-gradient(135deg, #f59e0b, #d97706) !important;
    border: none !important;
    color: white !important;
    font-weight: 600 !important;
    padding: 6px 12px !important;
    border-radius: 8px !important;
    transition: all 0.3s ease !important;
    text-decoration: none !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 4px !important;
    font-size: 0.8rem !important;
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3) !important;
}

.claims-link:hover {
    background: linear-gradient(135deg, #d97706, #b45309) !important;
    color: white !important;
    text-decoration: none !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4) !important;
}

.claims-create-link {
    background: linear-gradient(135deg, #10B981, #059669) !important;
    border: 1px solid #059669 !important;
    color: white !important;
    font-weight: 600 !important;
    padding: 6px 12px !important;
    border-radius: 8px !important;
    transition: all 0.3s ease !important;
    text-decoration: none !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 4px !important;
    font-size: 0.8rem !important;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3) !important;
}

.claims-create-link:hover {
    background: linear-gradient(135deg, #059669, #047857) !important;
    border-color: #047857 !important;
    color: white !important;
    text-decoration: none !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4) !important;
}

/* Стили для модального окна претензий */
#claimsModal .modal-content {
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    border: none;
}

#claimsModal .modal-header {
    border-bottom: none;
    padding: 1rem 1.5rem;
}

#claimsModal .modal-body {
    padding: 1.5rem;
    max-height: 60vh;
    overflow-y: auto;
}

#claimsModal .modal-footer {
    border-top: 1px solid #e9ecef;
    padding: 1rem 1.5rem;
}

#claimsModal .btn-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    opacity: 0.8;
}

#claimsModal .btn-close:hover {
    opacity: 1;
}

/* Стили для таблицы в модальном окне */
#claimsModal .table {
    margin-bottom: 0;
    font-size: 0.9rem;
}

#claimsModal .table th {
    background: #f8f9fa;
    font-weight: 600;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
    padding: 10px 8px;
}

#claimsModal .table td {
    font-size: 0.85rem;
    padding: 10px 8px;
    vertical-align: middle;
    border-bottom: 1px solid #f8f9fa;
}

#claimsModal .table-responsive {
    border: none;
}

#claimsModal .btn-sm {
    padding: 4px 8px;
    font-size: 0.75rem;
    margin: 1px;
}

#claimsModal .claim-type-badge {
    background: linear-gradient(135deg, #3B82F6, #1D4ED8);
    color: white;
    padding: 3px 8px;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 500;
}

#claimsModal .purchase-link {
    color: #059669;
    font-weight: 500;
    text-decoration: none;
    font-size: 0.85rem;
}

#claimsModal .purchase-link:hover {
    color: #047857;
    text-decoration: underline;
}

/* Анимация для индикатора загрузки */
#claimsModal .spinner-border-sm {
    width: 1rem;
    height: 1rem;
}

#claimsModal .text-center {
    padding: 20px;
}

/* Скрытие всех фильтров в модальном окне */
#claimsModal .filters,
#claimsModal .grid-view thead tr:first-child {
    display: none !important;
}

.claims-link {
    background: linear-gradient(135deg, #f59e0b, #d97706) !important;
    border: none !important;
    color: white !important;
    font-weight: 500 !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3) !important;
}

.claims-link:hover {
    background: linear-gradient(135deg, #d97706, #b45309) !important;
    color: white !important;
    text-decoration: none !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4) !important;
}
</style>

<script>
let currentReceiptUrl = '';

// Простая функция для открытия модального окна
function openReceiptModal(imgElement) {
    console.log('openReceiptModal called', imgElement);
    
    const receiptUrl = imgElement.getAttribute('data-receipt-url') || imgElement.src;
    console.log('Receipt URL:', receiptUrl);
    
    if (receiptUrl) {
        currentReceiptUrl = receiptUrl;
        document.getElementById('modalReceiptImage').src = receiptUrl;
        
        // Показываем модальное окно
        const modalElement = document.getElementById('receiptModal');
        console.log('Modal element:', modalElement);
        
        // Простой способ показать модальное окно
        modalElement.style.display = 'block';
        modalElement.classList.add('show');
        document.body.classList.add('modal-open');
        
        // Добавляем backdrop
        const existingBackdrop = document.getElementById('modalBackdrop');
        if (existingBackdrop) {
            existingBackdrop.remove();
        }
        
        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop fade show';
        backdrop.id = 'modalBackdrop';
        backdrop.style.position = 'fixed';
        backdrop.style.top = '0';
        backdrop.style.left = '0';
        backdrop.style.width = '100%';
        backdrop.style.height = '100%';
        backdrop.style.backgroundColor = 'rgba(0,0,0,0.5)';
        backdrop.style.zIndex = '1040';
        document.body.appendChild(backdrop);
        
        // Закрытие по клику на backdrop
        backdrop.addEventListener('click', closeReceiptModal);
    }
}

// Функция для закрытия модального окна
function closeReceiptModal() {
    const modalElement = document.getElementById('receiptModal');
    modalElement.style.display = 'none';
    modalElement.classList.remove('show');
    document.body.classList.remove('modal-open');
    
    // Удаляем backdrop
    const backdrop = document.getElementById('modalBackdrop');
    if (backdrop) {
        backdrop.remove();
    }
}

// Функция для скачивания чека
function downloadReceipt() {
    if (currentReceiptUrl) {
        const link = document.createElement('a');
        link.href = currentReceiptUrl;
        link.download = 'receipt_' + Date.now() + '.jpg';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
}

// Инициализация после загрузки DOM
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing receipt previews');
    
    // Добавляем hover эффекты для миниатюр чеков
    const receiptPreviews = document.querySelectorAll('.receipt-preview');
    console.log('Found receipt previews:', receiptPreviews.length);
    
    receiptPreviews.forEach(function(preview) {
        const overlay = preview.querySelector('.receipt-overlay');
        const img = preview.querySelector('img');
        
        console.log('Setting up preview:', preview, 'img:', img);
        
        preview.addEventListener('mouseenter', function() {
            if (overlay) {
                overlay.style.opacity = '1';
            }
        });
        
        preview.addEventListener('mouseleave', function() {
            if (overlay) {
                overlay.style.opacity = '0';
            }
        });
        
        // Добавляем обработчик клика напрямую
        if (img) {
            img.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Image clicked directly');
                openReceiptModal(this);
            });
        }
    });
    
    // Обработчики для кнопок закрытия
    const closeBtn = document.querySelector('#receiptModal .btn-close');
    if (closeBtn) {
        closeBtn.addEventListener('click', closeReceiptModal);
    }
    
    const closeFooterBtn = document.querySelector('#receiptModal .modal-footer .btn-secondary');
    if (closeFooterBtn) {
        closeFooterBtn.addEventListener('click', closeReceiptModal);
    }
    
    // Закрытие по клику на backdrop
    const modalElement = document.getElementById('receiptModal');
    if (modalElement) {
        modalElement.addEventListener('click', function(event) {
            if (event.target === modalElement) {
                closeReceiptModal();
            }
        });
    }
});

// Закрытие модального окна по Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeReceiptModal();
        closeClaimsModal();
    }
});

// Функции для модального окна претензий
function openClaimsModal(purchaseId) {
    const modal = document.getElementById('claimsModal');
    if (modal) {
        // Сохраняем ID покупки в глобальной переменной и в sessionStorage
        window.currentPurchaseId = purchaseId;
        sessionStorage.setItem('lastPurchaseId', purchaseId);
        
        modal.style.display = 'block';
        modal.classList.add('show');
        document.body.classList.add('modal-open');
        loadClaims(purchaseId);
    }
}

// Автоматически открываем модальное окно при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const openClaims = urlParams.get('open_claims');
    
    // Проверяем параметр URL
    if (openClaims) {
        setTimeout(() => {
            openClaimsModal(openClaims);
        }, 500);
    }
    // Проверяем sessionStorage (для случаев удаления по прямой ссылке)
    else {
        const lastPurchaseId = sessionStorage.getItem('lastPurchaseId');
        if (lastPurchaseId) {
            setTimeout(() => {
                openClaimsModal(lastPurchaseId);
                // Очищаем sessionStorage после использования
                sessionStorage.removeItem('lastPurchaseId');
            }, 500);
        }
    }
    
    // Добавляем глобальный обработчик для всех ссылок удаления в модальном окне
    document.addEventListener('click', function(e) {
        if (e.target.matches('#claimsModal a[data-method="post"]') && 
            e.target.getAttribute('data-confirm') && 
            e.target.getAttribute('data-confirm').includes('удалить')) {
            e.preventDefault();
            e.stopPropagation();
            const claimId = extractClaimIdFromUrl(e.target.href);
            if (claimId) {
                deleteClaimAjax(claimId);
            }
        }
    });
});

function closeClaimsModal() {
    const modal = document.getElementById('claimsModal');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.remove('show');
        document.body.classList.remove('modal-open');
    }
}

function loadClaims(purchaseId) {
    const modalBody = document.querySelector('#claimsModal .modal-body');
    const modalTitle = document.querySelector('#claimsModal .modal-title');
    
    if (modalTitle) {
        modalTitle.textContent = 'Претензии по покупке #' + purchaseId;
    }
    
    if (modalBody) {
        modalBody.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="sr-only">Загрузка...</span></div><p class="mt-2">Загрузка претензий...</p></div>';
        
        fetch('/claim/index?ClaimSearch[purchase_id]=' + purchaseId)
            .then(response => response.text())
            .then(html => {
                // Парсим HTML и извлекаем только таблицу
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                // Ищем таблицу с классом table-responsive или grid-view
                let table = doc.querySelector('.table-responsive .grid-view');
                if (!table) {
                    table = doc.querySelector('.grid-view');
                }
                if (!table) {
                    table = doc.querySelector('.table');
                }
                
                if (table) {
                    // Создаем контейнер для таблицы
                    modalBody.innerHTML = '<div class="table-responsive">' + table.outerHTML + '</div>';
                    
                    // Скрываем все фильтры в модальном окне
                    hideAllFiltersInModal();
                } else {
                    // Проверяем, есть ли сообщение об отсутствии данных
                    const emptyText = doc.querySelector('.text-center.text-muted');
                    if (emptyText) {
                        modalBody.innerHTML = emptyText.outerHTML;
                    } else {
                        modalBody.innerHTML = '<div class="text-center text-muted"><p>Претензии по этой покупке не найдены</p></div>';
                    }
                }
            })
            .catch(error => {
                console.error('Ошибка загрузки претензий:', error);
                modalBody.innerHTML = '<div class="text-center text-danger"><p>Ошибка загрузки претензий</p></div>';
            });
    }
}

// Функция для скрытия всех фильтров в модальном окне
function hideAllFiltersInModal() {
    // Скрываем всю строку фильтров
    const filtersRow = document.querySelector('#claimsModal .filters');
    if (filtersRow) {
        filtersRow.style.display = 'none';
    }
    
    // Также скрываем заголовки фильтров в таблице
    const filtersHeader = document.querySelector('#claimsModal .grid-view thead tr:first-child');
    if (filtersHeader) {
        filtersHeader.style.display = 'none';
    }
    
    // Заменяем обычные ссылки удаления на AJAX-версии
    replaceDeleteLinksWithAjax();
}

function replaceDeleteLinksWithAjax() {
    const deleteLinks = document.querySelectorAll('#claimsModal a[data-method="post"]');
    
    deleteLinks.forEach(link => {
        if (link.getAttribute('data-confirm') && link.getAttribute('data-confirm').includes('удалить')) {
            // Убираем data-confirm атрибут, чтобы избежать двойного подтверждения
            link.removeAttribute('data-confirm');
            link.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const claimId = extractClaimIdFromUrl(this.href);
                if (claimId) {
                    deleteClaimAjax(claimId);
                }
            });
        }
    });
}

function extractClaimIdFromUrl(url) {
    const match = url.match(/\/claim\/delete\/(\d+)/);
    return match ? match[1] : null;
}

function deleteClaimAjax(claimId) {
    if (!confirm('Вы уверены, что хотите удалить эту претензию?')) {
        return;
    }
    
    fetch('/claim/delete/' + claimId, {
        method: 'POST',
        headers: {
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('HTTP ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Перезагружаем список претензий в модальном окне
            if (window.currentPurchaseId) {
                loadClaims(window.currentPurchaseId);
            }
        } else {
            alert(data.message || 'Ошибка при удалении претензии');
        }
    })
    .catch(error => {
        console.error('Ошибка:', error);
        alert('Ошибка при удалении претензии: ' + error.message);
    });
}


function createClaim() {
    // Получаем ID покупки из глобальной переменной или из контекста
    const purchaseId = window.currentPurchaseId;
    if (purchaseId) {
        window.location.href = '/claim/create?Claim[purchase_id]=' + purchaseId;
    }
}
</script>
