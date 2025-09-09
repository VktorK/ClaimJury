<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Category;

/* @var $this yii\web\View */
/* @var $model app\models\Category */

$this->title = 'Просмотр категории';
$this->params['breadcrumbs'][] = ['label' => 'Главная', 'url' => ['/purchases']];
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['/category/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="category-view">
    <div class="row">
        <div class="col-lg-8">
            <div class="category-details-card">
                <div class="card-header">
                    <h2>
                        <i class="fas fa-folder"></i>
                        <?= Html::encode($model->title) ?>
                    </h2>
                </div>
                
                <div class="card-body">
                    <div class="category-info">
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Название:</label>
                                <span><?= Html::encode($model->title) ?></span>
                            </div>
                            
                            <div class="info-item">
                                <label>Дата создания:</label>
                                <span><?= $model->getFormattedCreatedDate() ?></span>
                            </div>
                            
                            <div class="info-item">
                                <label>Количество товаров:</label>
                                <span class="product-count"><?= count($model->products) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Картинки категории -->
            <div class="category-images-card">
                <div class="card-header">
                    <h3>
                        <i class="fas fa-images"></i>
                        Примеры товаров
                    </h3>
                </div>
                <div class="card-body">
                    <div class="category-images">
                        <?php
                        $categoryImages = [];
                        $categoryName = strtolower($model->title);
                        
                        // Определяем картинки в зависимости от категории
                        if (strpos($categoryName, 'бытовая техника') !== false || strpos($categoryName, 'техника') !== false) {
                            $categoryImages = [
                                [
                                    'url' => 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=400&h=300&fit=crop',
                                    'title' => 'Холодильник',
                                    'description' => 'Современный холодильник с технологией No Frost'
                                ],
                                [
                                    'url' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=400&h=300&fit=crop',
                                    'title' => 'Стиральная машина',
                                    'description' => 'Энергоэффективная стиральная машина'
                                ],
                                [
                                    'url' => 'https://images.unsplash.com/photo-1571175443880-49e1d25b2bc5?w=400&h=300&fit=crop',
                                    'title' => 'Микроволновка',
                                    'description' => 'Микроволновая печь с грилем'
                                ],
                                [
                                    'url' => 'https://images.unsplash.com/photo-1574269909862-7e1d70bb8078?w=400&h=300&fit=crop',
                                    'title' => 'Пылесос',
                                    'description' => 'Робот-пылесос с навигацией'
                                ],
                                [
                                    'url' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=400&h=300&fit=crop',
                                    'title' => 'Посудомойка',
                                    'description' => 'Встраиваемая посудомоечная машина'
                                ],
                                [
                                    'url' => 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=400&h=300&fit=crop',
                                    'title' => 'Морозильник',
                                    'description' => 'Морозильная камера с системой заморозки'
                                ]
                            ];
                        } elseif (strpos($categoryName, 'телефон') !== false || strpos($categoryName, 'смартфон') !== false) {
                            $categoryImages = [
                                [
                                    'url' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=400&h=300&fit=crop',
                                    'title' => 'iPhone',
                                    'description' => 'Современный смартфон с камерой Pro'
                                ],
                                [
                                    'url' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=400&h=300&fit=crop',
                                    'title' => 'Samsung Galaxy',
                                    'description' => 'Android смартфон с большим экраном'
                                ],
                                [
                                    'url' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=400&h=300&fit=crop',
                                    'title' => 'Xiaomi',
                                    'description' => 'Бюджетный смартфон с хорошими характеристиками'
                                ],
                                [
                                    'url' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=400&h=300&fit=crop',
                                    'title' => 'Huawei',
                                    'description' => 'Смартфон с мощной камерой'
                                ],
                                [
                                    'url' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=400&h=300&fit=crop',
                                    'title' => 'OnePlus',
                                    'description' => 'Флагманский смартфон с быстрой зарядкой'
                                ],
                                [
                                    'url' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=400&h=300&fit=crop',
                                    'title' => 'Google Pixel',
                                    'description' => 'Смартфон с чистой версией Android'
                                ]
                            ];
                        } else {
                            // Общие картинки для других категорий
                            $categoryImages = [
                                [
                                    'url' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=400&h=300&fit=crop',
                                    'title' => 'Товар 1',
                                    'description' => 'Пример товара из категории'
                                ],
                                [
                                    'url' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=400&h=300&fit=crop',
                                    'title' => 'Товар 2',
                                    'description' => 'Еще один пример товара'
                                ],
                                [
                                    'url' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=400&h=300&fit=crop',
                                    'title' => 'Товар 3',
                                    'description' => 'Третий пример товара'
                                ]
                            ];
                        }
                        
                        foreach ($categoryImages as $image): ?>
                            <div class="image-item">
                                <div class="image-container">
                                    <img src="<?= $image['url'] ?>" 
                                         alt="<?= Html::encode($image['title']) ?>" 
                                         class="category-image"
                                         onclick="openImageModal(this)"
                                         loading="lazy">
                                    <div class="image-overlay">
                                        <i class="fas fa-search-plus"></i>
                                    </div>
                                </div>
                                <div class="image-info">
                                    <h4><?= Html::encode($image['title']) ?></h4>
                                    <p><?= Html::encode($image['description']) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="category-stats">
                <div class="stat-item">
                    <label>Всего товаров:</label>
                    <span><?= count($model->products) ?></span>
                </div>
                
                <?php if (!empty($model->products)): ?>
                    <div class="stat-item">
                        <label>С гарантией:</label>
                        <span><?= count(array_filter($model->products, function($product) { return $product->warranty_period > 0; })) ?></span>
                    </div>
                    
                    <div class="stat-item">
                        <label>С серийным номером:</label>
                        <span><?= count(array_filter($model->products, function($product) { return !empty($product->serial_number); })) ?></span>
                    </div>
                    
                    <div class="stat-item">
                        <label>Общая сумма покупок:</label>
                        <span><?php 
                            $totalAmount = 0;
                            foreach ($model->products as $product) {
                                foreach ($product->purchases as $purchase) {
                                    if (is_numeric($purchase->amount)) {
                                        $totalAmount += (float)$purchase->amount;
                                    }
                                }
                            }
                            echo number_format($totalAmount, 0, ',', ' ') . ' р';
                        ?></span>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($model->products)): ?>
                <div class="products-card">
                    <div class="card-header">
                        <h3>
                            <i class="fas fa-box"></i>
                            Товары в категории
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="products-list">
                            <?php foreach ($model->products as $product): ?>
                                <div class="product-item">
                                    <div class="product-info">
                                        <h4>
                                            <?= Html::a(
                                                Html::encode($product->title),
                                                ['/product/view', 'id' => $product->id],
                                                ['class' => 'product-link']
                                            ) ?>
                                        </h4>
                                        <div class="product-meta">
                                            <span class="product-date">
                                                <i class="fas fa-calendar"></i>
                                                <?= $product->getFormattedCreatedDate() ?>
                                            </span>
                                            <?php if ($product->warranty_period): ?>
                                                <span class="product-warranty">
                                                    <i class="fas fa-shield-alt"></i>
                                                    <?= $product->getFormattedWarrantyPeriod() ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
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
.category-view {
    padding: 20px 0;
}

.category-details-card,
.category-images-card,
.products-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    overflow: hidden;
}

.card-header {
    background: linear-gradient(135deg, #6f42c1 0%, #5a32a3 100%);
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

.product-count {
    color: #6f42c1;
    font-weight: 600;
    font-size: 1.2rem;
}

.category-images {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.image-item {
    background: #f8f9fa;
    border-radius: 12px;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.image-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.image-container {
    position: relative;
    height: 200px;
    overflow: hidden;
    cursor: pointer;
}

.category-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.image-container:hover .category-image {
    transform: scale(1.05);
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(111, 66, 193, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.image-container:hover .image-overlay {
    opacity: 1;
}

.image-overlay i {
    color: white;
    font-size: 2rem;
}

.image-info {
    padding: 15px;
}

.image-info h4 {
    margin: 0 0 8px 0;
    color: #333;
    font-size: 1.1rem;
    font-weight: 600;
}

.image-info p {
    margin: 0;
    color: #666;
    font-size: 0.9rem;
    line-height: 1.4;
}

.category-stats {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
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
    color: #6f42c1;
    font-weight: 600;
    font-size: 1.1rem;
}

.products-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.product-item {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    border-left: 4px solid #6f42c1;
}

.product-info h4 {
    margin: 0 0 10px 0;
    color: #333;
}

.product-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    font-size: 0.9rem;
    color: #666;
}

.product-meta span {
    display: flex;
    align-items: center;
    gap: 5px;
}

.product-link {
    color: #6f42c1;
    text-decoration: none;
    font-weight: 500;
}

.product-link:hover {
    color: #5a32a3;
    text-decoration: underline;
}

.product-warranty {
    color: #fd7e14;
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
    
    .category-images {
        grid-template-columns: 1fr;
    }
    
    .product-meta {
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
