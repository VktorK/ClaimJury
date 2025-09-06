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

$this->title = 'Панель управления - Мои покупки';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="purchase-index">
    <div class="row">
        <div class="col-12">
            <div class="dashboard-header">
                <div class="dashboard-header-top">
                    <?= Html::a('<i class="fas fa-tachometer-alt"></i> Панель управления', ['/dashboard'], [
                        'class' => 'btn btn-outline-primary btn-sm dashboard-back-btn'
                    ]) ?>
                    <?= Html::a('<i class="fas fa-store"></i> Продавцы', ['/seller/index'], [
                        'class' => 'btn btn-outline-primary btn-sm dashboard-back-btn'
                    ]) ?>
                    <?= Html::a('<i class="fas fa-box"></i> Мои товары', ['/product/index'], [
                        'class' => 'btn btn-outline-primary btn-sm dashboard-back-btn'
                    ]) ?>
                </div>
                <h1 class="dashboard-title">
                    <i class="fas fa-shopping-cart"></i>
                    Мои покупки
                </h1>
                <p class="dashboard-subtitle">Управляйте своими покупками и чеками</p>
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
                    <h3>Мои покупки</h3>
                    <div class="card-actions">
                        <?= Html::a('<i class="fas fa-plus"></i> Добавить покупку', ['create'], ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
                
                <div class="card-body">
                    <?php Pjax::begin(); ?>
                    
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
                    ]); ?>
                    
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

.dashboard-header-top {
    position: absolute;
    top: 20px;
    left: 20px;
    display: flex;
    gap: 10px;
}

.dashboard-back-btn {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    border-radius: 8px;
    padding: 8px 16px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.dashboard-back-btn:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.5);
    color: white;
    transform: translateY(-1px);
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

.table {
    margin: 0;
}

.table th {
    background: #f8f9fa;
    border: none;
    padding: 15px;
    font-weight: 600;
    color: #555;
}

.table td {
    padding: 15px;
    border: none;
    border-bottom: 1px solid #f8f9fa;
    vertical-align: middle;
}

.product-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
}

.product-link:hover {
    color: #764ba2;
    text-decoration: none;
}

.amount {
    font-weight: 600;
    color: #28a745;
}

.warranty-period {
    font-weight: 500;
    color: #17a2b8;
    font-size: 0.9rem;
}

.appeal-deadline {
    font-weight: 500;
    color: #28a745;
    font-size: 0.9rem;
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
    
    .dashboard-header-top {
        position: static;
        margin-bottom: 20px;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }
    
    .dashboard-back-btn {
        font-size: 0.8rem;
        padding: 6px 12px;
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
    
    .receipt-thumb {
        width: 40px;
        height: 40px;
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
    }
});
</script>
