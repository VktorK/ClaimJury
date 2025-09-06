<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Product;

/* @var $this yii\web\View */
/* @var $model app\models\Product */

$this->title = 'Просмотр товара';
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="product-view">
    <div class="row">
        <div class="col-lg-8">
            <div class="product-details-card">
                <div class="card-header">
                    <h2>
                        <i class="fas fa-box"></i>
                        <?= Html::encode($model->title) ?>
                    </h2>
                    <div class="card-actions">
                        <?= Html::a('<i class="fas fa-edit"></i> Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
                        <?= Html::a('<i class="fas fa-trash"></i> Удалить', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data-confirm' => 'Вы уверены, что хотите удалить этот товар?',
                            'data-method' => 'post'
                        ]) ?>
                    </div>
                </div>
                
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
                                <span><?= Yii::$app->formatter->asDatetime($model->created_at) ?></span>
                            </div>
                            
                            <div class="info-item">
                                <label>Последнее обновление:</label>
                                <span><?= Yii::$app->formatter->asDatetime($model->updated_at) ?></span>
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
            <div class="product-image-card">
                <div class="card-header">
                    <h3>
                        <i class="fas fa-image"></i>
                        Изображение товара
                    </h3>
                </div>
                <div class="card-body">
                    <div class="product-image-container">
                        <img src="<?= $model->getImageUrl() ?>" 
                             alt="<?= Html::encode($model->title) ?>" 
                             class="product-image"
                             onclick="openImageModal(this)">
                        <div class="image-overlay">
                            <i class="fas fa-search-plus"></i>
                        </div>
                    </div>
                </div>
            </div>
            
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

<!-- Модальное окно для просмотра изображения -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Изображение товара</h5>
                <button type="button" class="btn-close" onclick="closeImageModal()" aria-label="Закрыть">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="" class="img-fluid">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeImageModal()">Закрыть</button>
                <a id="downloadImage" href="" download class="btn btn-primary">
                    <i class="fas fa-download"></i> Скачать
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.product-view {
    padding: 20px 0;
}

.product-details-card,
.product-image-card,
.purchases-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    overflow: hidden;
}

.card-header {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    padding: 25px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h2,
.card-header h3 {
    margin: 0;
    font-weight: 600;
}

.card-header h2 i,
.card-header h3 i {
    margin-right: 10px;
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

.product-image-container {
    position: relative;
    text-align: center;
    border-radius: 10px;
    overflow: hidden;
    cursor: pointer;
    transition: transform 0.3s ease;
}

.product-image-container:hover {
    transform: scale(1.02);
}

.product-image {
    width: 100%;
    height: 300px;
    object-fit: cover;
    border-radius: 10px;
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
    border-radius: 10px;
}

.product-image-container:hover .image-overlay {
    opacity: 1;
}

.image-overlay i {
    color: white;
    font-size: 2rem;
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
    cursor: pointer;
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

@media (max-width: 768px) {
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

<script>
function openImageModal(img) {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    const downloadLink = document.getElementById('downloadImage');
    
    modalImg.src = img.src;
    modalImg.alt = img.alt;
    downloadLink.href = img.src;
    downloadLink.download = img.alt + '.jpg';
    
    modal.style.display = 'block';
    modal.classList.add('show');
    document.body.classList.add('modal-open');
    
    // Add backdrop
    const existingBackdrop = document.getElementById('imageModalBackdrop');
    if (existingBackdrop) {
        existingBackdrop.remove();
    }
    
    const backdrop = document.createElement('div');
    backdrop.className = 'modal-backdrop fade show';
    backdrop.id = 'imageModalBackdrop';
    backdrop.style.position = 'fixed';
    backdrop.style.top = '0';
    backdrop.style.left = '0';
    backdrop.style.width = '100%';
    backdrop.style.height = '100%';
    backdrop.style.backgroundColor = 'rgba(0,0,0,0.5)';
    backdrop.style.zIndex = '1040';
    document.body.appendChild(backdrop);
    
    // Close on backdrop click
    backdrop.addEventListener('click', closeImageModal);
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.style.display = 'none';
    modal.classList.remove('show');
    document.body.classList.remove('modal-open');
    
    // Remove backdrop
    const backdrop = document.getElementById('imageModalBackdrop');
    if (backdrop) {
        backdrop.remove();
    }
}

// Close modal on Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeImageModal();
    }
});
</script>
