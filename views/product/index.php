<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Product;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Товары';
$this->params['breadcrumbs'][] = ['label' => 'Панель управления - Покупки', 'url' => ['/purchases']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="product-index">
    <div class="row">
        <div class="col-12">
            <div class="dashboard-header">
                <h1 class="dashboard-title">
                    <i class="fas fa-box"></i>
                    Мои товары
                </h1>
                <p class="dashboard-subtitle">Управляйте своими товарами и категориями</p>
                
                <div class="dashboard-navigation">
                    <?= Html::a('<i class="fas fa-shopping-cart"></i> Покупки', ['/purchase/index'], [
                        'class' => 'btn btn-outline-primary dashboard-nav-btn'
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="product-table-card">
                <div class="card-body">
                    <?php Pjax::begin(); ?>
                    
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            
                            [
                                'attribute' => 'image',
                                'label' => 'ИЗОБРАЖЕНИЕ',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return Html::img($model->getImageUrl(), [
                                        'class' => 'product-thumbnail',
                                        'alt' => Html::encode($model->title),
                                        'onclick' => 'openImageModal(this)'
                                    ]);
                                },
                                'contentOptions' => ['style' => 'width: 100px; text-align: center; vertical-align: middle;'],
                                'headerOptions' => ['style' => 'width: 100px; text-align: center;'],
                            ],
                            
                            [
                                'attribute' => 'title',
                                'label' => 'НАЗВАНИЕ',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return Html::a(
                                        Html::encode($model->title),
                                        ['view', 'id' => $model->id],
                                        ['class' => 'product-name-link']
                                    );
                                },
                                'contentOptions' => ['style' => 'vertical-align: middle;'],
                                'headerOptions' => ['style' => 'width: 200px;'],
                            ],
                            
                            [
                                'attribute' => 'category_id',
                                'label' => 'КАТЕГОРИЯ',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    if ($model->category) {
                                        return Html::a(
                                            '<i class="fas fa-folder"></i> ' . Html::encode($model->category->title),
                                            ['/category/view', 'id' => $model->category_id],
                                            ['class' => 'category-name-link']
                                        );
                                    }
                                    return '<span class="text-muted">Не указана</span>';
                                },
                                'filter' => Html::activeDropDownList($searchModel, 'category_id', 
                                    \yii\helpers\ArrayHelper::map(\app\models\Category::find()->all(), 'id', 'title'),
                                    ['class' => 'form-control', 'prompt' => 'Все категории']
                                ),
                                'contentOptions' => ['style' => 'vertical-align: middle;'],
                                'headerOptions' => ['style' => 'width: 180px;'],
                            ],
                            
                            [
                                'attribute' => 'warranty_period',
                                'label' => 'ГАРАНТИЯ',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return '<span class="warranty-period">' . $model->getFormattedWarrantyPeriod() . '</span>';
                                },
                                'contentOptions' => ['style' => 'vertical-align: middle; text-align: center;'],
                                'headerOptions' => ['style' => 'width: 120px; text-align: center;'],
                            ],
                            
                            [
                                'attribute' => 'serial_number',
                                'label' => 'СЕРИЙНЫЙ НОМЕР',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    if ($model->serial_number) {
                                        return '<span class="serial-number">' . Html::encode($model->serial_number) . '</span>';
                                    }
                                    return '<span class="text-muted">—</span>';
                                },
                                'contentOptions' => ['style' => 'vertical-align: middle; text-align: center;'],
                                'headerOptions' => ['style' => 'width: 150px; text-align: center;'],
                            ],
                            
                            [
                                'attribute' => 'created_at',
                                'label' => 'ДАТА СОЗДАНИЯ',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return '<span class="date-created">' . $model->getFormattedCreatedDate() . '</span>';
                                },
                                'contentOptions' => ['style' => 'vertical-align: middle; text-align: center;'],
                                'headerOptions' => ['style' => 'width: 140px; text-align: center;'],
                            ],
                            
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => 'ДЕЙСТВИЯ',
                                'template' => '{view}',
                                'buttons' => [
                                    'view' => function ($url, $model, $key) {
                                        return Html::a('<i class="fas fa-eye"></i>', $url, [
                                            'class' => 'btn btn-sm btn-info',
                                            'title' => 'Просмотр',
                                            'data-pjax' => '0'
                                        ]);
                                    },
                                ],
                                'contentOptions' => ['style' => 'width: 80px; text-align: center;'],
                                'headerOptions' => ['style' => 'width: 80px; text-align: center;'],
                            ],
                        ],
                        'tableOptions' => ['class' => 'table table-hover'],
                        'summary' => 'Показано {begin}-{end} из {totalCount} товаров',
                        'emptyText' => 'У вас пока нет товаров. Товары создаются при добавлении покупок.',
                    ]); ?>
                    
                    <?php Pjax::end(); ?>
                </div>
            </div>
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
.product-index {
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

.product-table-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    overflow: hidden;
}

.card-actions {
    display: flex;
    gap: 10px;
}

.card-body {
    padding: 30px;
}

.product-thumbnail {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    cursor: pointer;
    transition: transform 0.3s ease;
}

.product-thumbnail:hover {
    transform: scale(1.1);
}

.product-name-link {
    color: #28a745;
    text-decoration: none;
    font-weight: 500;
}

.product-name-link:hover {
    color: #1e7e34;
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

.warranty-period {
    color: #fd7e14;
    font-weight: 500;
}

.serial-number {
    color: #6c757d;
    font-family: monospace;
    font-size: 0.9rem;
}

.date-created {
    color: #6c757d;
    font-size: 0.9rem;
}

.btn {
    padding: 8px 12px;
    border-radius: 6px;
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-size: 0.9rem;
}

.btn-sm {
    padding: 6px 10px;
    font-size: 0.8rem;
}

.btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}

.btn-success:hover {
    background: linear-gradient(135deg, #1e7e34 0%, #1a7a33 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.btn-info {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    color: white;
}

.btn-info:hover {
    background: linear-gradient(135deg, #138496 0%, #117a8b 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
}

.btn-warning {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
    color: #212529;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #e0a800 0%, #d39e00 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
}

.btn-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
}

.btn-danger:hover {
    background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
}


.alert i {
    margin-right: 8px;
}

.table {
    margin-bottom: 0;
}

.table th {
    background: #f8f9fa;
    border-top: none;
    font-weight: 600;
    color: #333;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 0.5px;
    padding: 12px 8px;
    white-space: nowrap;
}

.table td {
    vertical-align: middle;
    border-top: 1px solid #e9ecef;
    padding: 12px 8px;
}

.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.grid-view .table {
    table-layout: fixed;
    width: 100%;
}

.table-hover tbody tr:hover {
    background-color: rgba(40, 167, 69, 0.05);
}

/* Стили для фильтров */
.grid-view .filters input[type="text"],
.grid-view .filters select {
    width: 100%;
    padding: 6px 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 0.9rem;
}

.grid-view .filters input[type="text"]:focus,
.grid-view .filters select:focus {
    border-color: #28a745;
    outline: none;
    box-shadow: 0 0 0 2px rgba(40, 167, 69, 0.25);
}

/* Стили для кнопок действий */
.btn-sm {
    margin: 0 2px;
    border-radius: 4px;
}

/* Стили для ссылок */
.product-name-link {
    color: #28a745;
    text-decoration: none;
    font-weight: 500;
}

.product-name-link:hover {
    color: #1e7e34;
    text-decoration: underline;
}

.category-name-link {
    color: #6f42c1;
    text-decoration: none;
}

.category-name-link:hover {
    color: #5a2d91;
    text-decoration: underline;
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
    
    .card-body {
        padding: 20px;
    }
    
    .table-responsive {
        font-size: 0.9rem;
    }
    
    .product-thumbnail {
        width: 40px;
        height: 40px;
    }
    
    .btn-sm {
        padding: 4px 8px;
        font-size: 0.7rem;
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
