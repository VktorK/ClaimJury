<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Buyer */

$this->title = $model->getFullName();
$this->params['breadcrumbs'][] = ['label' => 'Покупки', 'url' => ['/purchase/index']];
$this->params['breadcrumbs'][] = ['label' => 'Покупатели', 'url' => ['/buyer/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="buyer-view">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <div class="header-top">
                            <?= Html::a('<i class="fas fa-arrow-left"></i> Назад к списку', ['/purchase/index'], [
                                'class' => 'btn btn-outline-light btn-sm back-btn'
                            ]) ?>
                        </div>
                        <h1 class="card-title">
                            <i class="fas fa-user"></i> <?= Html::encode($this->title) ?>
                        </h1>
                    </div>
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="buyer-image-section">
                                    <?= Html::img($model->getImageUrl(), [
                                        'class' => 'buyer-main-image',
                                        'alt' => $model->getFullName(),
                                        'style' => 'width: 100%; max-width: 300px; height: auto; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);'
                                    ]) ?>
                                </div>
                            </div>
                            
                            <div class="col-md-8">
                                <div class="buyer-info">
                                    <div class="info-section">
                                        <h4><i class="fas fa-info-circle"></i> Основная информация</h4>
                                        <div class="info-grid">
                                            <div class="info-item">
                                                <label>Фамилия:</label>
                                                <span><?= Html::encode($model->lastName) ?></span>
                                            </div>
                                            <div class="info-item">
                                                <label>Имя:</label>
                                                <span><?= Html::encode($model->firstName) ?></span>
                                            </div>
                                            <div class="info-item">
                                                <label>Отчество:</label>
                                                <span><?= Html::encode($model->middleName) ?></span>
                                            </div>
                                            <div class="info-item">
                                                <label>Адрес:</label>
                                                <span><?= Html::encode($model->address ?: 'Не указан') ?></span>
                                            </div>
                                            <div class="info-item">
                                                <label>Дата рождения:</label>
                                                <span><?= $model->getFormattedBirthday() ?></span>
                                            </div>
                                            <div class="info-item">
                                                <label>Паспорт:</label>
                                                <span><?= Html::encode($model->passport ?: 'Не указан') ?></span>
                                            </div>
                                            <div class="info-item">
                                                <label>Дата создания:</label>
                                                <span><?= $model->getFormattedCreatedDate() ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php if (!empty($model->purchases)): ?>
                        <div class="purchases-section">
                            <h4><i class="fas fa-shopping-cart"></i> Покупки покупателя</h4>
                            <div class="purchases-list">
                                <?php foreach (array_slice($model->purchases, 0, 10) as $purchase): ?>
                                    <div class="purchase-item" onclick="openPurchaseModal(<?= $purchase->id ?>)" style="cursor: pointer;">
                                        <div class="purchase-info">
                                            <div class="purchase-product">
                                                <?php if ($purchase->product_id && $purchase->product): ?>
                                                    <i class="fas fa-box"></i> <?= Html::encode($purchase->product->title) ?>
                                                <?php else: ?>
                                                    <i class="fas fa-tag"></i> <?= Html::encode($purchase->product_name) ?>
                                                <?php endif; ?>
                                            </div>
                                            <div class="purchase-date"><?= $purchase->getFormattedPurchaseDate() ?></div>
                                        </div>
                                        <div class="purchase-amount"><?= $purchase->getFormattedAmount() ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="card-footer">
                        <div class="card-actions">
                            <?= Html::a('<i class="fas fa-shopping-cart"></i> Покупки', ['/purchase/index'], ['class' => 'btn btn-outline-primary']) ?>
                            <?= Html::a('<i class="fas fa-edit"></i> Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
                            <?= Html::a('<i class="fas fa-trash"></i> Удалить', ['delete', 'id' => $model->id], [
                                'class' => 'btn btn-danger',
                                'data-confirm' => 'Вы уверены, что хотите удалить этого покупателя?',
                                'data-method' => 'post'
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-chart-bar"></i> Статистика</h5>
                    </div>
                    <div class="card-body">
                        <div class="stats-content">
                            <div class="stat-item">
                                <div class="stat-value"><?= count($model->purchases) ?></div>
                                <div class="stat-label">Всего покупок</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value"><?= number_format(array_sum(array_column($model->purchases, 'amount')), 0, ',', ' ') ?> р</div>
                                <div class="stat-label">Общая сумма</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно для просмотра покупки -->
<div class="modal fade" id="purchaseModal" tabindex="-1" aria-labelledby="purchaseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="purchaseModalLabel">Информация о покупке</h5>
                <button type="button" class="btn-close" onclick="closePurchaseModal()" aria-label="Закрыть">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="purchaseModalBody">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Загрузка...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closePurchaseModal()">Закрыть</button>
                <a id="purchaseViewLink" href="" class="btn btn-primary">
                    <i class="fas fa-eye"></i> Подробнее
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function openPurchaseModal(purchaseId) {
        const modal = document.getElementById('purchaseModal');
        const modalBody = document.getElementById('purchaseModalBody');
        const purchaseViewLink = document.getElementById('purchaseViewLink');
        
        // Находим данные покупки
        const purchaseData = window.purchasesData.find(p => p.id === purchaseId);
        
        if (purchaseData) {
            modalBody.innerHTML = `
                <div class="purchase-details">
                    <div class="detail-item">
                        <label>Товар:</label>
                        <span>${purchaseData.product_name}</span>
                    </div>
                    <div class="detail-item">
                        <label>Дата покупки:</label>
                        <span>${purchaseData.formatted_date}</span>
                    </div>
                    <div class="detail-item">
                        <label>Сумма:</label>
                        <span>${purchaseData.formatted_amount}</span>
                    </div>
                    <div class="detail-item">
                        <label>Описание:</label>
                        <span>${purchaseData.description || 'Не указано'}</span>
                    </div>
                </div>
            `;
            
            purchaseViewLink.href = `/purchase/view/${purchaseId}`;
        } else {
            modalBody.innerHTML = '<div class="text-center text-muted">Данные о покупке не найдены</div>';
        }
        
        modal.style.display = 'block';
        modal.classList.add('show');
        document.body.classList.add('modal-open');
        
        // Добавляем backdrop
        const existingBackdrop = document.getElementById('purchaseModalBackdrop');
        if (existingBackdrop) {
            existingBackdrop.remove();
        }
        
        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop fade show';
        backdrop.id = 'purchaseModalBackdrop';
        backdrop.style.position = 'fixed';
        backdrop.style.top = '0';
        backdrop.style.left = '0';
        backdrop.style.width = '100%';
        backdrop.style.height = '100%';
        backdrop.style.backgroundColor = 'rgba(0,0,0,0.5)';
        backdrop.style.zIndex = '1040';
        document.body.appendChild(backdrop);
        
        backdrop.addEventListener('click', closePurchaseModal);
    }
    
    function closePurchaseModal() {
        const modal = document.getElementById('purchaseModal');
        modal.style.display = 'none';
        modal.classList.remove('show');
        document.body.classList.remove('modal-open');
        
        const backdrop = document.getElementById('purchaseModalBackdrop');
        if (backdrop) {
            backdrop.remove();
        }
    }
    
    window.openPurchaseModal = openPurchaseModal;
    window.closePurchaseModal = closePurchaseModal;
    
    // Закрытие по Escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closePurchaseModal();
        }
    });
});

// Передаем данные покупок в JavaScript
window.purchasesData = [
    <?php foreach (array_slice($model->purchases, 0, 10) as $purchase): ?>
    {
        id: <?= $purchase->id ?>,
        product_name: <?= json_encode($purchase->product_name) ?>,
        formatted_date: <?= json_encode($purchase->getFormattedPurchaseDate()) ?>,
        formatted_amount: <?= json_encode($purchase->getFormattedAmount()) ?>,
        description: <?= json_encode($purchase->description) ?>
    },
    <?php endforeach; ?>
];
</script>

<style>
.buyer-view .card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-bottom: none;
}

.buyer-view .header-top {
    margin-bottom: 15px;
}

.buyer-view .back-btn {
    background: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    transition: all 0.3s ease;
}

.buyer-view .back-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    border-color: rgba(255, 255, 255, 0.5);
    color: white;
    transform: translateY(-2px);
}

.buyer-view .card-title {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
}

.buyer-view .info-section h4 {
    color: #495057;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e9ecef;
}

.buyer-view .info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.buyer-view .info-item {
    display: flex;
    flex-direction: column;
}

.buyer-view .info-item label {
    font-weight: 600;
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 5px;
}

.buyer-view .info-item span {
    color: #495057;
    font-size: 1rem;
}

.buyer-view .purchases-section {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 2px solid #e9ecef;
}

.buyer-view .purchases-section h4 {
    color: #495057;
    margin-bottom: 20px;
}

.buyer-view .purchases-list {
    max-height: 400px;
    overflow-y: auto;
}

.buyer-view .purchase-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    margin-bottom: 10px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.buyer-view .purchase-item:hover {
    background: #e9ecef;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.buyer-view .purchase-info {
    flex: 1;
}

.buyer-view .purchase-product {
    font-weight: 600;
    color: #495057;
    margin-bottom: 5px;
}

.buyer-view .purchase-date {
    font-size: 0.9rem;
    color: #6c757d;
}

.buyer-view .purchase-amount {
    font-weight: 600;
    color: #28a745;
    font-size: 1.1rem;
}

.buyer-view .stats-content {
    text-align: center;
}

.buyer-view .stat-item {
    margin-bottom: 20px;
}

.buyer-view .stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: #667eea;
    margin-bottom: 5px;
}

.buyer-view .stat-label {
    color: #6c757d;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.buyer-view .card-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.buyer-view .modal {
    display: none;
}

.buyer-view .modal.show {
    display: block;
}

.buyer-view .purchase-details {
    padding: 20px 0;
}

.buyer-view .detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #e9ecef;
}

.buyer-view .detail-item:last-child {
    border-bottom: none;
}

.buyer-view .detail-item label {
    font-weight: 600;
    color: #495057;
    margin: 0;
}

.buyer-view .detail-item span {
    color: #6c757d;
}

@media (max-width: 768px) {
    .buyer-view .info-grid {
        grid-template-columns: 1fr;
    }
    
    .buyer-view .card-actions {
        flex-direction: column;
    }
    
    .buyer-view .card-actions .btn {
        width: 100%;
    }
}
</style>
