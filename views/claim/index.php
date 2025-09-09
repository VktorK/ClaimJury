<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Claim;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ClaimSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Претензии';
$this->params['breadcrumbs'][] = ['label' => 'Панель управления', 'url' => ['/dashboard']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="claim-index">
    <div class="row">
        <div class="col-12">
            <div class="dashboard-header">
                <h1 class="dashboard-title">
                    <i class="fas fa-exclamation-triangle"></i>
                    Претензии
                </h1>
                <p class="dashboard-subtitle">Управление претензиями по вашим покупкам</p>
                
                <div class="dashboard-navigation">
                    <?= Html::a('<i class="fas fa-tachometer-alt"></i> Панель управления', ['/dashboard'], [
                        'class' => 'btn btn-outline-primary dashboard-nav-btn'
                    ]) ?>
                    <?= Html::a('<i class="fas fa-shopping-cart"></i> Покупки', ['/purchase/index'], [
                        'class' => 'btn btn-outline-primary dashboard-nav-btn'
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="claim-table-card">
                <div class="card-header">
                    <div class="card-header-content">
                        <h3>
                            <i class="fas fa-list"></i>
                            Список претензий
                        </h3>
                        <?= Html::a('<i class="fas fa-plus"></i> Создать претензию', ['create'], [
                            'class' => 'btn btn-success create-claim-btn'
                        ]) ?>
                    </div>
                </div>
                <div class="card-body">
                    <?php Pjax::begin(); ?>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            [
                                'attribute' => 'claim_type',
                                'label' => 'ТИП ПРЕТЕНЗИИ',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return Html::a('<span class="claim-type-badge">' . $model->getClaimTypeLabel() . '</span>', ['view', 'id' => $model->id], [
                                        'class' => 'claim-title-link'
                                    ]);
                                },
                                'filter' => Html::activeDropDownList($searchModel, 'claim_type', 
                                    [
                                        Claim::TYPE_REPAIR => 'Ремонт',
                                        Claim::TYPE_REFUND => 'Возврат денежных средств',
                                        Claim::TYPE_REPLACEMENT => 'Замена товара на аналогичный товар',
                                    ],
                                    ['class' => 'form-control', 'prompt' => 'Все типы']
                                )
                            ],

                            [
                                'attribute' => 'purchase_id',
                                'label' => 'ПОКУПКА',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    if ($model->purchase) {
                                        return Html::a(
                                            Html::encode($model->purchase->product_name),
                                            ['/purchase/view', 'id' => $model->purchase->id],
                                            ['class' => 'purchase-link']
                                        );
                                    }
                                    return 'Не указана';
                                },
                                'filter' => Html::activeDropDownList($searchModel, 'purchase_id', 
                                    \app\models\Purchase::find()
                                        ->where(['user_id' => Yii::$app->user->id])
                                        ->select(['id', 'product_name'])
                                        ->indexBy('id')
                                        ->column(),
                                    ['class' => 'form-control', 'prompt' => 'Все покупки']
                                )
                            ],

                            [
                                'attribute' => 'status',
                                'label' => 'СТАТУС',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return '<span class="badge ' . $model->getStatusClass() . '">' . $model->getStatusLabel() . '</span>';
                                },
                                'filter' => Html::activeDropDownList($searchModel, 'status', 
                                    [
                                        Claim::STATUS_PENDING => 'Ожидает рассмотрения',
                                        Claim::STATUS_IN_PROGRESS => 'В процессе',
                                        Claim::STATUS_RESOLVED => 'Решена',
                                        Claim::STATUS_REJECTED => 'Отклонена',
                                        Claim::STATUS_CLOSED => 'Закрыта',
                                    ],
                                    ['class' => 'form-control', 'prompt' => 'Все статусы']
                                )
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
                        'tableOptions' => ['class' => 'table table-striped table-hover'],
                        'options' => ['class' => 'table-responsive'],
                        'emptyText' => 'Данные отсутствуют',
                        'emptyTextOptions' => ['class' => 'text-center text-muted py-4'],
                    ]); ?>
                    <?php Pjax::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.dashboard-header {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
    padding: 30px;
    border-radius: 15px;
    margin-bottom: 30px;
    text-align: center;
}

.dashboard-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
}

.dashboard-subtitle {
    font-size: 1.2rem;
    opacity: 0.9;
    margin-bottom: 25px;
}

.dashboard-navigation {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
}

.dashboard-nav-btn {
    background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.2));
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.3);
    border-radius: 12px;
    padding: 12px 24px;
    font-weight: 600;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    color: white;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.dashboard-nav-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.15);
    color: white;
    text-decoration: none;
}

.claim-table-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    overflow: hidden;
}

.card-header {
    background: linear-gradient(135deg, #8B5CF6, #A855F7);
    color: white;
    padding: 20px 30px;
    margin: 0;
}

.card-header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h3 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.create-claim-btn {
    background: linear-gradient(135deg, #10B981, #059669) !important;
    border: none !important;
    color: white !important;
    padding: 10px 20px !important;
    border-radius: 8px !important;
    font-weight: 500 !important;
    text-decoration: none !important;
    transition: all 0.3s ease !important;
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
    font-size: 0.9rem !important;
}

.create-claim-btn:hover {
    background: linear-gradient(135deg, #059669, #047857) !important;
    color: white !important;
    text-decoration: none !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4) !important;
}

.card-body {
    padding: 30px;
}

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

.claim-title-link {
    color: #8B5CF6;
    font-weight: 600;
    text-decoration: none;
}

.claim-title-link:hover {
    color: #7C3AED;
    text-decoration: underline;
}

.purchase-link {
    color: #059669;
    font-weight: 500;
    text-decoration: none;
}

.purchase-link:hover {
    color: #047857;
    text-decoration: underline;
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
}

.badge-warning { background: linear-gradient(135deg, #F59E0B, #D97706); }
.badge-info { background: linear-gradient(135deg, #3B82F6, #1D4ED8); }
.badge-success { background: linear-gradient(135deg, #10B981, #059669); }
.badge-danger { background: linear-gradient(135deg, #EF4444, #DC2626); }
.badge-secondary { background: linear-gradient(135deg, #6B7280, #4B5563); }
</style>
