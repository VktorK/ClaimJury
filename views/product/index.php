<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Product;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Товары';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="product-index">
    <div class="row">
        <div class="col-12">
            <div class="index-card">
                <div class="card-header">
                    <div class="header-top">
                        <?= Html::a('<i class="fas fa-shopping-cart"></i> Мои покупки', ['/purchase/index'], [
                            'class' => 'btn btn-outline-light btn-sm back-btn'
                        ]) ?>
                    </div>
                    <h2>
                        <i class="fas fa-box"></i>
                        Мои товары
                    </h2>
                </div>
                
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
                                    return '<span class="date-created">' . Yii::$app->formatter->asDate($model->created_at) . '</span>';
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

.index-card {
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
    position: relative;
}

.header-top {
    position: absolute;
    top: 20px;
    right: 30px;
}

.back-btn {
    border: 2px solid rgba(255, 255, 255, 0.3);
    color: white;
    background: transparent;
    transition: all 0.3s ease;
}

.back-btn:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.6);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(255, 255, 255, 0.2);
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
    .card-header {
        padding: 20px 15px;
        text-align: center;
    }
    
    .header-top {
        position: static;
        margin-bottom: 15px;
        text-align: center;
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
