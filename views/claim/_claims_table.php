<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Claim;

/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'tableOptions' => ['class' => 'table table-striped table-hover'],
    'options' => ['class' => 'table-responsive'],
    'emptyText' => 'Данные отсутствуют',
    'emptyTextOptions' => ['class' => 'text-center text-muted py-4'],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        [
            'attribute' => 'claim_type',
            'label' => 'ТИП ПРЕТЕНЗИИ',
            'format' => 'raw',
            'value' => function ($model) {
                return '<span class="claim-type-badge">' . $model->getClaimTypeLabel() . '</span>';
            }
        ],

        [
            'attribute' => 'status',
            'label' => 'СТАТУС',
            'format' => 'raw',
            'value' => function ($model) {
                return '<span class="badge ' . $model->getStatusClass() . '">' . $model->getStatusLabel() . '</span>';
            }
        ],

        [
            'attribute' => 'claim_date',
            'label' => 'ДАТА ПОДАЧИ',
            'format' => 'raw',
            'value' => function ($model) {
                return '<span class="claim-date">' . $model->getFormattedClaimDate() . '</span>';
            }
        ],


        [
            'class' => 'yii\grid\ActionColumn',
            'header' => 'ДЕЙСТВИЯ',
            'template' => '{view} {update} {delete}',
            'buttons' => [
                'view' => function ($url, $model, $key) {
                    return Html::a('<i class="fas fa-eye"></i>', $url, [
                        'class' => 'btn btn-sm btn-outline-primary',
                        'title' => 'Просмотр'
                    ]);
                },
                'update' => function ($url, $model, $key) {
                    if (!$model->canEdit()) {
                        return '';
                    }
                    return Html::a('<i class="fas fa-edit"></i>', $url, [
                        'class' => 'btn btn-sm btn-outline-warning',
                        'title' => 'Редактировать'
                    ]);
                },
                'delete' => function ($url, $model, $key) {
                    if (!$model->canDelete()) {
                        return '';
                    }
                    return Html::a('<i class="fas fa-trash"></i>', $url, [
                        'class' => 'btn btn-sm btn-outline-danger',
                        'title' => 'Удалить',
                        'data-confirm' => 'Вы уверены, что хотите удалить эту претензию?',
                        'data-method' => 'post'
                    ]);
                },
            ],
        ],
    ],
]); ?>

<style>
.table th {
    background: #f8f9fa;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
    padding: 12px 8px;
}

.table td {
    font-size: 0.85rem;
    padding: 12px 8px;
    vertical-align: middle;
}

.claim-type-badge {
    background: linear-gradient(135deg, #3B82F6, #1D4ED8);
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.claim-date {
    color: #6B7280;
    font-weight: 500;
}

.claim-amount {
    color: #059669;
    font-weight: 600;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.badge {
    font-size: 0.8rem;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 500;
}

.badge-warning { background: linear-gradient(135deg, #F59E0B, #D97706); color: white; }
.badge-info { background: linear-gradient(135deg, #3B82F6, #1D4ED8); color: white; }
.badge-success { background: linear-gradient(135deg, #10B981, #059669); color: white; }
.badge-danger { background: linear-gradient(135deg, #EF4444, #DC2626); color: white; }
.badge-secondary { background: linear-gradient(135deg, #6B7280, #4B5563); color: white; }
</style>
