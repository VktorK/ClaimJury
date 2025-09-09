<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Buyer;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BuyerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Покупатели';
$this->params['breadcrumbs'][] = ['label' => 'Главная', 'url' => ['/purchases']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="buyer-index">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="header-top">
                            <?= Html::a('<i class="fas fa-shopping-cart"></i> Покупки', ['/purchase/index'], [
                                'class' => 'btn btn-outline-light btn-sm back-btn'
                            ]) ?>
                        </div>
                        <h1 class="card-title">
                            <i class="fas fa-users"></i> <?= Html::encode($this->title) ?>
                        </h1>
                    </div>
                    
                    <div class="card-body">
                        <?php Pjax::begin(); ?>
                        
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'tableOptions' => ['class' => 'table table-hover'],
                            'columns' => [
                                [
                                    'attribute' => 'image',
                                    'label' => 'ФОТО ПАСПОРТА',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return Html::img($model->getImageUrl(), [
                                            'class' => 'buyer-thumbnail',
                                            'alt' => $model->getFullName(),
                                            'style' => 'width: 40px; height: 40px; object-fit: cover; border-radius: 50%;'
                                        ]);
                                    },
                                    'contentOptions' => ['style' => 'width: 80px; text-align: center; vertical-align: middle;'],
                                    'headerOptions' => ['style' => 'width: 80px; text-align: center;'],
                                ],
                                [
                                    'attribute' => 'lastName',
                                    'label' => 'ФАМИЛИЯ',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return Html::a(
                                            Html::encode($model->lastName),
                                            ['view', 'id' => $model->id],
                                            ['class' => 'buyer-name-link']
                                        );
                                    },
                                    'contentOptions' => ['style' => 'vertical-align: middle;'],
                                    'headerOptions' => ['style' => 'width: 120px;'],
                                ],
                                [
                                    'attribute' => 'firstName',
                                    'label' => 'ИМЯ',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return Html::a(
                                            Html::encode($model->firstName),
                                            ['view', 'id' => $model->id],
                                            ['class' => 'buyer-name-link']
                                        );
                                    },
                                    'contentOptions' => ['style' => 'vertical-align: middle;'],
                                    'headerOptions' => ['style' => 'width: 120px;'],
                                ],
                                [
                                    'attribute' => 'middleName',
                                    'label' => 'ОТЧЕСТВО',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return Html::a(
                                            Html::encode($model->middleName),
                                            ['view', 'id' => $model->id],
                                            ['class' => 'buyer-name-link']
                                        );
                                    },
                                    'contentOptions' => ['style' => 'vertical-align: middle;'],
                                    'headerOptions' => ['style' => 'width: 120px;'],
                                ],
                                [
                                    'attribute' => 'address',
                                    'label' => 'АДРЕС',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return Html::encode($model->address ?: 'Не указан');
                                    },
                                    'contentOptions' => ['style' => 'vertical-align: middle;'],
                                    'headerOptions' => ['style' => 'width: 200px;'],
                                ],
                                [
                                    'attribute' => 'passport',
                                    'label' => 'ПАСПОРТ',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return Html::encode($model->passport ?: 'Не указан');
                                    },
                                    'contentOptions' => ['style' => 'vertical-align: middle;'],
                                    'headerOptions' => ['style' => 'width: 120px;'],
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => 'ДЕЙСТВИЯ',
                                    'template' => '{view}',
                                    'buttons' => [
                                        'view' => function ($url, $model, $key) {
                                            return Html::a(
                                                '<i class="fas fa-eye"></i>',
                                                ['view', 'id' => $model->id],
                                                [
                                                    'class' => 'btn btn-sm btn-outline-primary',
                                                    'title' => 'Просмотр',
                                                    'data-toggle' => 'tooltip'
                                                ]
                                            );
                                        },
                                    ],
                                    'contentOptions' => ['style' => 'width: 80px; text-align: center;'],
                                    'headerOptions' => ['style' => 'width: 80px; text-align: center;'],
                                ],
                            ],
                            'emptyText' => 'У вас пока нет покупателей. Покупатели создаются при добавлении покупок.',
                            'emptyTextOptions' => ['class' => 'text-center text-muted py-4'],
                        ]); ?>
                        
                        <?php Pjax::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.buyer-index .card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-bottom: none;
}

.buyer-index .header-top {
    margin-bottom: 15px;
}

.buyer-index .back-btn {
    background: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    transition: all 0.3s ease;
}

.buyer-index .back-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    border-color: rgba(255, 255, 255, 0.5);
    color: white;
    transform: translateY(-2px);
}

.buyer-index .card-title {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
}

.buyer-index .table th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    color: #495057;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}

.buyer-index .table td {
    border-bottom: 1px solid #dee2e6;
}

.buyer-index .table-hover tbody tr:hover {
    background-color: rgba(102, 126, 234, 0.1);
}

.buyer-index .buyer-name-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
}

.buyer-index .buyer-name-link:hover {
    color: #764ba2;
    text-decoration: none;
}

.buyer-index .buyer-thumbnail {
    border: 2px solid #e9ecef;
    transition: border-color 0.3s ease;
}

.buyer-index .buyer-thumbnail:hover {
    border-color: #667eea;
}

.buyer-index .btn-outline-primary {
    border-color: #667eea;
    color: #667eea;
}

.buyer-index .btn-outline-primary:hover {
    background-color: #667eea;
    border-color: #667eea;
    color: white;
}

@media (max-width: 768px) {
    .buyer-index .table-responsive {
        font-size: 0.9rem;
    }
    
    .buyer-index .buyer-thumbnail {
        width: 30px !important;
        height: 30px !important;
    }
}
</style>
