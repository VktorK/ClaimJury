<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Purchase;

/* @var $this yii\web\View */
/* @var $model app\models\Purchase */

$this->title = 'Просмотр покупки';
$this->params['breadcrumbs'][] = ['label' => 'Панель управления', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="purchase-view">
    <div class="row">
        <div class="col-lg-8">
            <div class="purchase-details-card">
                <div class="card-header">
                    <h2>
                        <i class="fas fa-shopping-bag"></i>
                        <?= Html::encode($model->product_name) ?>
                    </h2>
                    <div class="card-actions">
                        <?= Html::a('<i class="fas fa-shopping-cart"></i> Покупки', ['/purchase/index'], ['class' => 'btn btn-outline-primary']) ?>
                        <?= Html::a('<i class="fas fa-edit"></i> Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
                        <?= Html::a('<i class="fas fa-trash"></i> Удалить', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data-confirm' => 'Вы уверены, что хотите удалить эту покупку?',
                            'data-method' => 'post'
                        ]) ?>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="purchase-info">
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Товар:</label>
                                <?php if ($model->product_id && $model->product): ?>
                                    <span class="product-link">
                                        <?= Html::a(
                                            '<i class="fas fa-box"></i> ' . Html::encode($model->product->title),
                                            ['/product/view', 'id' => $model->product_id],
                                            ['class' => 'product-name-link']
                                        ) ?>
                                    </span>
                                <?php else: ?>
                                    <span><i class="fas fa-tag"></i> <?= Html::encode($model->product_name) ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="info-item">
                                <label>Продавец:</label>
                                <?php if ($model->seller_id): ?>
                                    <span class="seller-link">
                                        <?= Html::a(
                                            Html::encode($model->getSellerName()),
                                            ['/seller/view', 'id' => $model->seller_id],
                                            ['class' => 'seller-name-link']
                                        ) ?>
                                    </span>
                                <?php else: ?>
                                    <span><?= Html::encode($model->getSellerName()) ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="info-item">
                                <label>Покупатель:</label>
                                <?php if ($model->buyer_id): ?>
                                    <span class="buyer-link">
                                        <?= Html::a(
                                            Html::encode($model->getBuyerName()),
                                            ['/buyer/view', 'id' => $model->buyer_id],
                                            ['class' => 'buyer-name-link']
                                        ) ?>
                                    </span>
                                <?php else: ?>
                                    <span><?= Html::encode($model->getBuyerName()) ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="info-item">
                                <label>Дата покупки:</label>
                                <span><?= $model->getFormattedPurchaseDate() ?></span>
                            </div>
                            
                            <div class="info-item">
                                <label>Сумма:</label>
                                <span class="amount"><?= $model->getFormattedAmount() ?></span>
                            </div>
                            
                            <div class="info-item">
                                <label>Гарантийный срок:</label>
                                <span class="warranty-period"><?= $model->getFormattedWarrantyPeriod() ?></span>
                            </div>
                            
                            <div class="info-item">
                                <label>Срок обращения:</label>
                                <?php 
                                $deadline = $model->getFormattedAppealDeadline();
                                $isExpired = $model->appeal_deadline && strtotime($model->appeal_deadline) < time();
                                $class = $isExpired ? 'appeal-deadline expired' : 'appeal-deadline';
                                ?>
                                <span class="<?= $class ?>"><?= $deadline ?></span>
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
        
        <div class="col-lg-4">
            <div class="receipt-card">
                <div class="card-header">
                    <h3>
                        <i class="fas fa-receipt"></i>
                        Чек
                    </h3>
                </div>
                
                <div class="card-body">
                    <?php if ($model->receipt_image): ?>
                        <div class="receipt-image-container">
                            <img src="<?= $model->getReceiptUrl() ?>" alt="Чек" class="receipt-image" 
                                 data-receipt-url="<?= $model->getReceiptUrl() ?>"
                                 onclick="openReceiptModal(this)">
                            <div class="receipt-overlay">
                                <button class="btn btn-primary" onclick="openReceiptModal(this.closest('.receipt-image-container').querySelector('img'))">
                                    <i class="fas fa-expand"></i> Открыть
                                </button>
                                <?= Html::a('<i class="fas fa-trash"></i> Удалить', ['delete-receipt', 'id' => $model->id], [
                                    'class' => 'btn btn-danger',
                                    'data-confirm' => 'Вы уверены, что хотите удалить чек?',
                                    'data-method' => 'post'
                                ]) ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="no-receipt">
                            <i class="fas fa-receipt"></i>
                            <p>Чек не прикреплен</p>
                            <small>Добавьте чек при редактировании покупки</small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="purchase-meta">
                <div class="meta-item">
                    <label>Дата создания покупки:</label>
                    <span><?= $model->getFormattedCreatedDate() ?></span>
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
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalReceiptImage" src="" alt="Чек" class="img-fluid" style="max-height: 70vh; border-radius: 8px;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-primary" onclick="downloadReceipt()">
                    <i class="fas fa-download"></i> Скачать
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.purchase-view {
    padding: 20px 0;
}

.purchase-details-card,
.receipt-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    margin-bottom: 20px;
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

.card-header h2,
.card-header h3 {
    margin: 0;
    color: #333;
    font-weight: 600;
}

.card-header h2 i,
.card-header h3 i {
    margin-right: 10px;
    color: #667eea;
}

.card-actions {
    display: flex;
    gap: 10px;
}

.card-body {
    padding: 30px;
}

.info-grid {
    display: grid;
    gap: 20px;
}

.info-item {
    display: flex;
    flex-direction: column;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #667eea;
}

.info-item.full-width {
    grid-column: 1 / -1;
}

.info-item label {
    font-weight: 600;
    color: #555;
    margin-bottom: 5px;
    font-size: 0.9rem;
}

.info-item span {
    color: #333;
    font-size: 1rem;
}

.amount {
    font-weight: 700;
    color: #28a745;
    font-size: 1.2rem;
}

.warranty-period {
    font-weight: 500;
    color: #17a2b8;
    font-size: 1rem;
}

.appeal-deadline {
    font-weight: 500;
    color: #28a745;
    font-size: 1rem;
}

.appeal-deadline.expired {
    color: #dc3545;
    font-weight: 600;
}

.seller-link {
    display: inline-block;
}

.seller-name-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
    padding: 5px 10px;
    border-radius: 6px;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.seller-name-link:hover {
    color: #764ba2;
    background: #f8f9fa;
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.2);
}

.seller-name-link::after {
    content: '\f35d';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    font-size: 0.8rem;
    opacity: 0.7;
}

.product-name-link {
    color: #28a745;
    text-decoration: none;
    font-weight: 500;
    padding: 5px 10px;
    border-radius: 6px;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.product-name-link:hover {
    color: #1e7e34;
    background: #f8f9fa;
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(40, 167, 69, 0.2);
}

.product-name-link::after {
    content: '\f35d';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    font-size: 0.8rem;
    opacity: 0.7;
}

.receipt-image-container {
    position: relative;
    text-align: center;
}

.receipt-image {
    max-width: 100%;
    max-height: 300px;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.receipt-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.receipt-image-container:hover .receipt-overlay {
    opacity: 1;
}

.no-receipt {
    text-align: center;
    padding: 40px 20px;
    color: #666;
}

.no-receipt i {
    font-size: 3rem;
    margin-bottom: 15px;
    color: #ddd;
}

.no-receipt p {
    margin: 0 0 5px 0;
    font-weight: 500;
}

.no-receipt small {
    color: #999;
}

.purchase-meta {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.meta-item {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #f8f9fa;
}

.meta-item:last-child {
    border-bottom: none;
}

.meta-item label {
    font-weight: 500;
    color: #666;
}

.meta-item span {
    color: #333;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
}

@media (max-width: 768px) {
    .card-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    .card-actions {
        justify-content: center;
    }
    
    .info-grid {
        gap: 15px;
    }
    
    .info-item {
        padding: 10px;
    }
}
</style>

<script>
let currentReceiptUrl = '';

// Функция для открытия модального окна с чеком
function openReceiptModal(imgElement) {
    const receiptUrl = imgElement.getAttribute('data-receipt-url');
    if (receiptUrl) {
        currentReceiptUrl = receiptUrl;
        document.getElementById('modalReceiptImage').src = receiptUrl;
        
        // Показываем модальное окно
        const modal = new bootstrap.Modal(document.getElementById('receiptModal'));
        modal.show();
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

// Закрытие модального окна по Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modal = bootstrap.Modal.getInstance(document.getElementById('receiptModal'));
        if (modal) {
            modal.hide();
        }
    }
});
</script>
