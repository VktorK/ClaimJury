<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Seller;

/* @var $this yii\web\View */
/* @var $model app\models\Seller */

$this->title = 'Карточка продавца: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Панель управления', 'url' => ['/dashboard']];
$this->params['breadcrumbs'][] = ['label' => 'Продавцы', 'url' => ['/sellers']];
$this->params['breadcrumbs'][] = $model->title;
?>

<div class="seller-view">
    <div class="row">
        <div class="col-lg-8">
            <div class="seller-card">
                <div class="card-header">
                    <div class="seller-header">
                        <div class="seller-avatar">
                            <i class="fas fa-store"></i>
                        </div>
                        <div class="seller-info">
                            <h1 class="seller-title"><?= Html::encode($model->title) ?></h1>
                            <p class="seller-subtitle">Информация о продавце</p>
                        </div>
                    </div>
                    <div class="card-actions">
                        <?= Html::a('<i class="fas fa-edit"></i> Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-outline-primary']) ?>
                        <?= Html::a('<i class="fas fa-arrow-left"></i> Назад к списку', ['/dashboard'], ['class' => 'btn btn-outline-secondary']) ?>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="seller-details">
                        <div class="detail-section">
                            <h3><i class="fas fa-info-circle"></i> Основная информация</h3>
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <label>Название:</label>
                                    <span><?= Html::encode($model->title) ?></span>
                                </div>
                                
                                <?php if ($model->address): ?>
                                <div class="detail-item">
                                    <label>Адрес:</label>
                                    <span><?= Html::encode($model->address) ?></span>
                                </div>
                                <?php endif; ?>
                                
                                <?php if ($model->ogrn): ?>
                                <div class="detail-item">
                                    <label>ОГРН:</label>
                                    <span><?= Html::encode($model->ogrn) ?></span>
                                </div>
                                <?php endif; ?>
                                
                                <?php if ($model->date_creation): ?>
                                <div class="detail-item">
                                    <label>Дата создания организации:</label>
                                    <span><?= Yii::$app->formatter->asDate($model->date_creation, 'php:d.m.Y') ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="detail-section">
                            <h3><i class="fas fa-shopping-cart"></i> Статистика покупок</h3>
                            <div class="stats-grid">
                                <div class="stat-item">
                                    <div class="stat-number"><?= count($model->purchases) ?></div>
                                    <div class="stat-label">Всего покупок</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-number"><?= number_format(array_sum(array_column($model->purchases, 'amount')), 0, ',', ' ') ?> р</div>
                                    <div class="stat-label">Общая сумма</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-number"><?= $model->date_creation ? Yii::$app->formatter->asDate($model->date_creation, 'php:Y') : '—' ?></div>
                                    <div class="stat-label">Год основания</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="purchases-card">
                <div class="card-header">
                    <h3><i class="fas fa-receipt"></i> Последние покупки</h3>
                </div>
                <div class="card-body">
                    <?php if ($model->purchases): ?>
                        <div class="purchases-list">
                            <?php foreach (array_slice($model->purchases, 0, 5) as $purchase): ?>
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
                            
                            <?php if (count($model->purchases) > 5): ?>
                                <div class="text-center mt-3">
                                    <?= Html::a('Показать все покупки', ['/dashboard'], ['class' => 'btn btn-outline-primary btn-sm']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center text-muted">
                            <i class="fas fa-shopping-cart fa-2x mb-3"></i>
                            <p>Покупки у этого продавца не найдены</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.seller-view {
    padding: 20px 0;
}

.seller-card,
.purchases-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    overflow: hidden;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 25px 30px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 20px;
}

.seller-header {
    display: flex;
    align-items: center;
    gap: 20px;
}

.seller-avatar {
    width: 80px;
    height: 80px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
}

.seller-info h1 {
    margin: 0 0 5px 0;
    font-size: 2rem;
    font-weight: 700;
}

.seller-info p {
    margin: 0;
    opacity: 0.9;
    font-size: 1.1rem;
}

.card-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.card-body {
    padding: 30px;
}

.detail-section {
    margin-bottom: 30px;
}

.detail-section h3 {
    color: #667eea;
    font-size: 1.3rem;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 15px;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #667eea;
}

.detail-item label {
    font-weight: 600;
    color: #555;
    margin: 0;
}

.detail-item span {
    color: #333;
    font-weight: 500;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 20px;
}

.stat-item {
    text-align: center;
    padding: 20px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 10px;
    border: 1px solid #e9ecef;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #667eea;
    margin-bottom: 5px;
}

.stat-label {
    color: #666;
    font-size: 0.9rem;
    font-weight: 500;
}

.purchases-list {
    max-height: 400px;
    overflow-y: auto;
}

.purchase-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    border-bottom: 1px solid #f8f9fa;
    transition: background 0.3s ease;
}

.purchase-item:hover {
    background: #f8f9fa;
}

.purchase-item:last-child {
    border-bottom: none;
}

.purchase-product {
    font-weight: 500;
    color: #333;
    margin-bottom: 5px;
}

.purchase-date {
    font-size: 0.85rem;
    color: #666;
}

.purchase-amount {
    font-weight: 600;
    color: #28a745;
    font-size: 1.1rem;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    padding: 10px 20px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.btn i {
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .card-header {
        flex-direction: column;
        align-items: stretch;
    }
    
    .seller-header {
        flex-direction: column;
        text-align: center;
    }
    
    .card-actions {
        justify-content: center;
    }
    
    .detail-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
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
    background: rgba(0, 0, 0, 0.5);
    display: none;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.modal.show {
    display: block;
    opacity: 1;
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
    border: 1px solid rgba(0, 0, 0, 0.2);
    border-radius: 0.3rem;
    outline: 0;
}

.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1rem;
    border-bottom: 1px solid #dee2e6;
    border-top-left-radius: calc(0.3rem - 1px);
    border-top-right-radius: calc(0.3rem - 1px);
}

.modal-body {
    position: relative;
    flex: 1 1 auto;
    padding: 1rem;
}

.modal-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding: 1rem;
    border-top: 1px solid #dee2e6;
    border-bottom-right-radius: calc(0.3rem - 1px);
    border-bottom-left-radius: calc(0.3rem - 1px);
}

.btn-close {
    padding: 0.25rem 0.25rem;
    margin: -0.25rem -0.25rem -0.25rem auto;
    background: transparent;
    border: 0;
    border-radius: 0.25rem;
    opacity: 0.5;
    cursor: pointer;
}

.btn-close:hover {
    opacity: 0.75;
}

.btn-close::before {
    content: "×";
    font-size: 1.5rem;
    font-weight: bold;
    color: #000;
}

/* Стили для элементов покупок */
.purchase-item {
    transition: all 0.3s ease;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 8px;
}

.purchase-item:hover {
    background-color: rgba(111, 66, 193, 0.1);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(111, 66, 193, 0.15);
}

.purchase-product i {
    margin-right: 8px;
    color: #6f42c1;
}

.spinner-border {
    display: inline-block;
    width: 2rem;
    height: 2rem;
    vertical-align: text-bottom;
    border: 0.25em solid currentColor;
    border-right-color: transparent;
    border-radius: 50%;
    animation: spinner-border 0.75s linear infinite;
}

@keyframes spinner-border {
    to {
        transform: rotate(360deg);
    }
}

/* Стили для содержимого модального окна */
.purchase-modal-content {
    padding: 20px;
}

.purchase-details h4 {
    color: #6f42c1;
    margin-bottom: 20px;
    font-weight: 600;
}

.purchase-details .detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #e9ecef;
}

.purchase-details .detail-item:last-child {
    border-bottom: none;
}

.purchase-details .detail-item label {
    font-weight: 600;
    color: #333;
    min-width: 120px;
}

.purchase-details .detail-item span {
    color: #666;
    text-align: right;
    flex: 1;
    margin-left: 20px;
}

.alert {
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    border-left: 4px solid;
}

.alert-info {
    background-color: #d1ecf1;
    border-color: #17a2b8;
    color: #0c5460;
}
</style>

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
    console.log('DOM загружен, инициализация модального окна');
    
    function openPurchaseModal(purchaseId) {
        console.log('Открытие модального окна для покупки:', purchaseId);
        
        const modal = document.getElementById('purchaseModal');
        const modalBody = document.getElementById('purchaseModalBody');
        const viewLink = document.getElementById('purchaseViewLink');
        
        if (!modal) {
            console.error('Модальное окно не найдено');
            return;
        }
        
        // Показываем модальное окно
        modal.style.display = 'block';
        modal.classList.add('show');
        document.body.classList.add('modal-open');
        
        // Устанавливаем ссылку на подробный просмотр
        if (viewLink) {
            viewLink.href = '/purchase/view/' + purchaseId;
        }
        
        // Показываем спиннер
        if (modalBody) {
            modalBody.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Загрузка...</span></div></div>';
        }
        
        // Находим данные о покупке из переданных данных
        const purchaseData = window.purchasesData ? window.purchasesData.find(p => p.id == purchaseId) : null;
        
        if (purchaseData) {
            modalBody.innerHTML = `
                <div class="purchase-modal-content">
                    <div class="purchase-details">
                        <h4><i class="fas fa-shopping-bag"></i> Информация о покупке</h4>
                        <div class="detail-item">
                            <label>Товар:</label>
                            <span>${purchaseData.product_name || 'Не указан'}</span>
                        </div>
                        <div class="detail-item">
                            <label>Дата покупки:</label>
                            <span>${purchaseData.formatted_date}</span>
                        </div>
                        <div class="detail-item">
                            <label>Сумма:</label>
                            <span>${purchaseData.formatted_amount}</span>
                        </div>
                        ${purchaseData.description ? `
                        <div class="detail-item">
                            <label>Описание:</label>
                            <span>${purchaseData.description}</span>
                        </div>
                        ` : ''}
                    </div>
                </div>
            `;
        } else {
            modalBody.innerHTML = `
                <div class="purchase-modal-content">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Информация о покупке #${purchaseId}
                    </div>
                    <div class="text-center">
                        <p>Для просмотра полной информации о покупке нажмите кнопку "Подробнее"</p>
                    </div>
                </div>
            `;
        }
    }
    
    function closePurchaseModal() {
        console.log('Закрытие модального окна');
        const modal = document.getElementById('purchaseModal');
        if (modal) {
            modal.style.display = 'none';
            modal.classList.remove('show');
            document.body.classList.remove('modal-open');
        }
    }
    
    // Делаем функции глобальными
    window.openPurchaseModal = openPurchaseModal;
    window.closePurchaseModal = closePurchaseModal;
    
    // Закрытие по клику на фон
    const modal = document.getElementById('purchaseModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closePurchaseModal();
            }
        });
    }
    
    // Закрытие по Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closePurchaseModal();
        }
    });
    
    console.log('Модальное окно инициализировано');
});

// Передаем данные о покупках в JavaScript
window.purchasesData = [
    <?php foreach (array_slice($model->purchases, 0, 5) as $purchase): ?>
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
