<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Seller;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SellerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Продавцы';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="seller-index">
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
                        <i class="fas fa-store"></i>
                        Мои продавцы
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
                                'attribute' => 'title',
                                'label' => 'НАЗВАНИЕ',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return Html::a(
                                        Html::encode($model->title),
                                        ['view', 'id' => $model->id],
                                        ['class' => 'seller-name-link']
                                    );
                                },
                                'contentOptions' => ['style' => 'vertical-align: middle;'],
                                'headerOptions' => ['style' => 'width: 200px;'],
                            ],
                            
                            [
                                'attribute' => 'address',
                                'label' => 'АДРЕС',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return '<span class="seller-address">' . Html::encode($model->address) . '</span>';
                                },
                                'contentOptions' => ['style' => 'vertical-align: middle;'],
                                'headerOptions' => ['style' => 'width: 250px;'],
                            ],
                            
                            [
                                'attribute' => 'ogrn',
                                'label' => 'ОГРН',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    if ($model->ogrn) {
                                        return '<span class="seller-ogrn">' . Html::encode($model->ogrn) . '</span>';
                                    }
                                    return '<span class="text-muted">—</span>';
                                },
                                'contentOptions' => ['style' => 'vertical-align: middle; text-align: center;'],
                                'headerOptions' => ['style' => 'width: 150px; text-align: center;'],
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
                        'summary' => 'Показано {begin}-{end} из {totalCount} продавцов',
                        'emptyText' => 'У вас пока нет продавцов. Продавцы создаются при добавлении покупок.',
                    ]); ?>
                    
                    <?php Pjax::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.seller-index {
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
    background: linear-gradient(135deg, #6f42c1 0%, #8e44ad 100%);
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
    font-size: 1.8rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.card-body {
    padding: 30px;
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
    background-color: rgba(111, 66, 193, 0.05);
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
    border-color: #6f42c1;
    outline: none;
    box-shadow: 0 0 0 2px rgba(111, 66, 193, 0.25);
}

/* Стили для кнопок действий */
.btn-sm {
    margin: 0 2px;
    border-radius: 4px;
}

/* Стили для ссылок */
.seller-name-link {
    color: #6f42c1;
    text-decoration: none;
    font-weight: 500;
}

.seller-name-link:hover {
    color: #5a2d91;
    text-decoration: underline;
}

.seller-address {
    color: #333;
    font-size: 0.9rem;
}

.seller-ogrn {
    color: #28a745;
    font-weight: 500;
    font-family: monospace;
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
    
    .btn-sm {
        padding: 4px 8px;
        font-size: 0.7rem;
    }
}
</style>
