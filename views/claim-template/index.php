<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\ClaimTemplate;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ClaimTemplateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Шаблоны претензий';
$this->params['breadcrumbs'][] = ['label' => 'Панель управления', 'url' => ['/dashboard']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="claim-template-index">
    <div class="row">
        <div class="col-12">
            <div class="dashboard-header">
                <h1 class="dashboard-title">
                    <i class="fas fa-file-alt"></i>
                    Шаблоны претензий
                </h1>
                <p class="dashboard-subtitle">Управление шаблонами для генерации претензий</p>
                
                <div class="dashboard-navigation">
                    <?= Html::a('<i class="fas fa-tachometer-alt"></i> Панель управления', ['/dashboard'], [
                        'class' => 'btn btn-outline-primary dashboard-nav-btn'
                    ]) ?>
                    <?= Html::a('<i class="fas fa-shopping-cart"></i> Покупки', ['/purchase/index'], [
                        'class' => 'btn btn-outline-primary dashboard-nav-btn'
                    ]) ?>
                    <?= Html::a('<i class="fas fa-exclamation-triangle"></i> Претензии', ['/claim/index'], [
                        'class' => 'btn btn-outline-primary dashboard-nav-btn'
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="template-table-card">
                <div class="card-header">
                    <div class="card-header-content">
                        <h3>
                            <i class="fas fa-list"></i>
                            Список шаблонов
                        </h3>
                        <?= Html::a('<i class="fas fa-plus"></i> Создать шаблон', ['create'], [
                            'class' => 'btn btn-success create-template-btn'
                        ]) ?>
                    </div>
                </div>
                <div class="card-body">
                    <?php Pjax::begin(); ?>
                    <div class="table-responsive">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

                                [
                                    'attribute' => 'name',
                                    'label' => 'НАЗВАНИЕ',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return Html::a(
                                            '<i class="fas fa-file-alt"></i> ' . Html::encode($model->name),
                                            ['view', 'id' => $model->id],
                                            ['class' => 'template-name-link']
                                        );
                                    },
                                ],

                                [
                                    'attribute' => 'type',
                                    'label' => 'ТИП',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        $types = $model->getClaimTypes();
                                        $typeName = isset($types[$model->type]) ? $types[$model->type] : $model->type;
                                        return '<span class="template-type-badge">' . Html::encode($typeName) . '</span>';
                                    },
                                    'filter' => Html::activeDropDownList($searchModel, 'type', 
                                        array_merge(['' => 'Все типы'], ClaimTemplate::getClaimTypes()),
                                        ['class' => 'form-control']
                                    )
                                ],

                                [
                                    'attribute' => 'description',
                                    'label' => 'ОПИСАНИЕ',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return $model->description ? 
                                            '<span class="template-description">' . Html::encode($model->description) . '</span>' : 
                                            '<span class="text-muted">Не указано</span>';
                                    },
                                ],

                                [
                                    'attribute' => 'status',
                                    'label' => 'СТАТУС',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        $statusClass = $model->status == ClaimTemplate::STATUS_ACTIVE ? 'badge-success' : 'badge-secondary';
                                        $statusText = $model->status == ClaimTemplate::STATUS_ACTIVE ? 'Активен' : 'Неактивен';
                                        return '<span class="badge ' . $statusClass . '">' . $statusText . '</span>';
                                    },
                                    'filter' => Html::activeDropDownList($searchModel, 'status', 
                                        [
                                            '' => 'Все статусы',
                                            ClaimTemplate::STATUS_ACTIVE => 'Активен',
                                            ClaimTemplate::STATUS_INACTIVE => 'Неактивен',
                                        ],
                                        ['class' => 'form-control']
                                    )
                                ],

                                [
                                    'attribute' => 'created_at',
                                    'label' => 'СОЗДАН',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return '<span class="template-date">' . 
                                            Yii::$app->formatter->asDate($model->created_at, 'php:d.m.Y H:i') . 
                                            '</span>';
                                    },
                                ],

                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => 'ДЕЙСТВИЯ',
                                    'template' => '{view} {update} {preview} {delete}',
                                    'buttons' => [
                                        'view' => function ($url, $model, $key) {
                                            return Html::a('<i class="fas fa-eye"></i>', ['view', 'id' => $model->id], [
                                                'class' => 'btn btn-sm btn-outline-primary',
                                                'title' => 'Просмотр',
                                                'data-toggle' => 'tooltip'
                                            ]);
                                        },
                                        'update' => function ($url, $model, $key) {
                                            return Html::a('<i class="fas fa-edit"></i>', ['update', 'id' => $model->id], [
                                                'class' => 'btn btn-sm btn-outline-warning',
                                                'title' => 'Редактировать',
                                                'data-toggle' => 'tooltip'
                                            ]);
                                        },
                                        'preview' => function ($url, $model, $key) {
                                            return Html::a('<i class="fas fa-search"></i>', ['preview', 'id' => $model->id], [
                                                'class' => 'btn btn-sm btn-outline-info',
                                                'title' => 'Предварительный просмотр',
                                                'data-toggle' => 'tooltip',
                                                'target' => '_blank'
                                            ]);
                                        },
                                        'delete' => function ($url, $model, $key) {
                                            return Html::a('<i class="fas fa-trash"></i>', ['delete', 'id' => $model->id], [
                                                'class' => 'btn btn-sm btn-outline-danger',
                                                'title' => 'Удалить',
                                                'data-toggle' => 'tooltip',
                                                'data-confirm' => 'Вы уверены, что хотите удалить этот шаблон?',
                                                'data-method' => 'post'
                                            ]);
                                        },
                                    ],
                                ],
                            ],
                            'tableOptions' => ['class' => 'table table-striped table-hover'],
                            'emptyText' => 'Шаблоны не найдены',
                            'emptyTextOptions' => ['class' => 'text-center text-muted py-4'],
                        ]); ?>
                    </div>
                    <?php Pjax::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.dashboard-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
    font-size: 1.1rem;
    margin-bottom: 25px;
    opacity: 0.9;
}

.dashboard-navigation {
    display: flex;
    justify-content: center;
    gap: 15px;
    flex-wrap: wrap;
}

.dashboard-nav-btn {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    padding: 10px 20px;
    border-radius: 25px;
    text-decoration: none;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.dashboard-nav-btn:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.5);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 255, 255, 0.2);
}

.template-table-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    overflow: hidden;
    width: 100%;
    max-width: none;
}

.card-header {
    background: linear-gradient(135deg, #8B5CF6, #A855F7);
    color: white;
    padding: 20px 30px;
    border-bottom: none;
}

.card-header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.card-header h3 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.create-template-btn {
    background: linear-gradient(135deg, #10B981, #059669);
    border: none;
    color: white;
    padding: 10px 20px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.create-template-btn:hover {
    background: linear-gradient(135deg, #059669, #047857);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
}

.card-body {
    padding: 30px;
}

.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    border-radius: 0 0 15px 15px;
}

.table {
    margin: 0;
    min-width: 100%;
    width: 100%;
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
    white-space: nowrap;
}

.table td {
    font-size: 0.85rem;
    padding: 12px 8px;
    vertical-align: middle;
    white-space: nowrap;
}

.template-name-link {
    color: #8B5CF6;
    font-weight: 600;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
}

.template-name-link:hover {
    color: #7C3AED;
    text-decoration: none;
}

.template-type-badge {
    background: linear-gradient(135deg, #3B82F6, #1D4ED8);
    color: white;
    padding: 4px 12px;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.template-description {
    color: #6B7280;
    font-size: 0.85rem;
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    display: inline-block;
}

.template-date {
    color: #9CA3AF;
    font-size: 0.8rem;
}

.badge {
    padding: 4px 8px;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 12px;
}

.badge-success {
    background: linear-gradient(135deg, #10B981, #059669);
    color: white;
}

.badge-secondary {
    background: linear-gradient(135deg, #6B7280, #4B5563);
    color: white;
}

.btn-group {
    display: flex;
    gap: 5px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn {
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-outline-primary {
    border-color: #8B5CF6;
    color: #8B5CF6;
}

.btn-outline-primary:hover {
    background-color: #8B5CF6;
    border-color: #8B5CF6;
    color: white;
}

.btn-outline-warning {
    border-color: #F59E0B;
    color: #F59E0B;
}

.btn-outline-warning:hover {
    background-color: #F59E0B;
    border-color: #F59E0B;
    color: white;
}

.btn-outline-info {
    border-color: #3B82F6;
    color: #3B82F6;
}

.btn-outline-info:hover {
    background-color: #3B82F6;
    border-color: #3B82F6;
    color: white;
}

.btn-outline-danger {
    border-color: #EF4444;
    color: #EF4444;
}

.btn-outline-danger:hover {
    background-color: #EF4444;
    border-color: #EF4444;
    color: white;
}

/* Адаптивные стили */
@media (max-width: 768px) {
    .card-header-content {
        flex-direction: column;
        text-align: center;
    }
    
    .dashboard-navigation {
        flex-direction: column;
        align-items: center;
    }
    
    .table th,
    .table td {
        padding: 8px 4px;
        font-size: 0.8rem;
    }
    
    .btn-group {
        flex-direction: column;
        gap: 2px;
    }
    
    .btn {
        padding: 4px 8px;
        font-size: 0.75rem;
    }
}
</style>
