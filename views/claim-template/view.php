<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ClaimTemplate */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Панель управления', 'url' => ['/dashboard']];
$this->params['breadcrumbs'][] = ['label' => 'Шаблоны претензий', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="claim-template-view">
    <div class="row">
        <div class="col-12">
            <div class="dashboard-header">
                <h1 class="dashboard-title">
                    <i class="fas fa-file-alt"></i>
                    <?= Html::encode($this->title) ?>
                </h1>
                <p class="dashboard-subtitle">Просмотр шаблона претензии</p>
                
                <div class="dashboard-navigation">
                    <?= Html::a('<i class="fas fa-arrow-left"></i> Назад к списку', ['index'], [
                        'class' => 'btn btn-outline-light dashboard-nav-btn'
                    ]) ?>
                    <?= Html::a('<i class="fas fa-edit"></i> Редактировать', ['update', 'id' => $model->id], [
                        'class' => 'btn btn-outline-light dashboard-nav-btn'
                    ]) ?>
                    <?= Html::a('<i class="fas fa-search"></i> Предварительный просмотр', ['preview', 'id' => $model->id], [
                        'class' => 'btn btn-outline-light dashboard-nav-btn',
                        'target' => '_blank'
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="template-info-card">
                <div class="card-header">
                    <h3>
                        <i class="fas fa-info-circle"></i>
                        Информация о шаблоне
                    </h3>
                </div>
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            'name',
                            [
                                'attribute' => 'type',
                                'value' => $model->getTypeName(),
                                'format' => 'raw',
                            ],
                            'description',
                            [
                                'attribute' => 'status',
                                'value' => $model->getStatusText(),
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'created_at',
                                'value' => Yii::$app->formatter->asDate($model->created_at, 'php:d.m.Y H:i'),
                            ],
                            [
                                'attribute' => 'updated_at',
                                'value' => Yii::$app->formatter->asDate($model->updated_at, 'php:d.m.Y H:i'),
                            ],
                        ],
                    ]) ?>
                </div>
            </div>

            <div class="template-content-card">
                <div class="card-header">
                    <h3>
                        <i class="fas fa-code"></i>
                        Содержимое шаблона
                    </h3>
                </div>
                <div class="card-body">
                    <div class="template-content">
                        <pre><?= Html::encode($model->template_content) ?></pre>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="template-actions-card">
                <div class="card-header">
                    <h3>
                        <i class="fas fa-cogs"></i>
                        Действия
                    </h3>
                </div>
                <div class="card-body">
                    <div class="action-buttons">
                        <?= Html::a('<i class="fas fa-edit"></i> Редактировать шаблон', ['update', 'id' => $model->id], [
                            'class' => 'btn btn-warning btn-block'
                        ]) ?>
                        
                        <?= Html::a('<i class="fas fa-search"></i> Предварительный просмотр', ['preview', 'id' => $model->id], [
                            'class' => 'btn btn-info btn-block',
                            'target' => '_blank'
                        ]) ?>
                        
                        <?= Html::a('<i class="fas fa-copy"></i> Дублировать', ['create', 'duplicate' => $model->id], [
                            'class' => 'btn btn-secondary btn-block'
                        ]) ?>
                        
                        <?= Html::a('<i class="fas fa-trash"></i> Удалить', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger btn-block',
                            'data-confirm' => 'Вы уверены, что хотите удалить этот шаблон?',
                            'data-method' => 'post'
                        ]) ?>
                    </div>
                </div>
            </div>

            <div class="template-stats-card">
                <div class="card-header">
                    <h3>
                        <i class="fas fa-chart-bar"></i>
                        Статистика
                    </h3>
                </div>
                <div class="card-body">
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-number"><?= strlen($model->template_content) ?></div>
                            <div class="stat-label">Символов</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"><?= substr_count($model->template_content, '{') ?></div>
                            <div class="stat-label">Плейсхолдеров</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"><?= substr_count($model->template_content, "\n") + 1 ?></div>
                            <div class="stat-label">Строк</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.dashboard-header {
    background: linear-gradient(135deg, #8B5CF6 0%, #A855F7 100%);
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

.template-info-card,
.template-content-card,
.template-actions-card,
.template-stats-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    overflow: hidden;
    margin-bottom: 20px;
}

.card-header {
    background: linear-gradient(135deg, #8B5CF6, #A855F7);
    color: white;
    padding: 20px 25px;
    border-bottom: none;
}

.template-actions-card .card-header {
    background: linear-gradient(135deg, #3B82F6, #1D4ED8);
}

.template-stats-card .card-header {
    background: linear-gradient(135deg, #10B981, #059669);
}

.card-header h3 {
    margin: 0;
    font-size: 1.3rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-body {
    padding: 25px;
}

.template-content {
    background: #F9FAFB;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    padding: 20px;
    max-height: 400px;
    overflow-y: auto;
}

.template-content pre {
    margin: 0;
    font-family: 'Courier New', monospace;
    font-size: 0.9rem;
    line-height: 1.5;
    color: #374151;
    white-space: pre-wrap;
    word-wrap: break-word;
}

.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.btn {
    border-radius: 8px;
    font-weight: 600;
    padding: 12px 20px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    text-decoration: none;
    border: none;
}

.btn-block {
    width: 100%;
}

.btn-warning {
    background: linear-gradient(135deg, #F59E0B, #D97706);
    color: white;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #D97706, #B45309);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
}

.btn-info {
    background: linear-gradient(135deg, #3B82F6, #1D4ED8);
    color: white;
}

.btn-info:hover {
    background: linear-gradient(135deg, #1D4ED8, #1E40AF);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
}

.btn-secondary {
    background: linear-gradient(135deg, #6B7280, #4B5563);
    color: white;
}

.btn-secondary:hover {
    background: linear-gradient(135deg, #4B5563, #374151);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(107, 114, 128, 0.4);
}

.btn-danger {
    background: linear-gradient(135deg, #EF4444, #DC2626);
    color: white;
}

.btn-danger:hover {
    background: linear-gradient(135deg, #DC2626, #B91C1C);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
}

.stat-item {
    text-align: center;
    padding: 15px;
    background: #F9FAFB;
    border-radius: 8px;
    border: 1px solid #E5E7EB;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: #10B981;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 0.8rem;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}

/* Стили для DetailView */
.table {
    margin: 0;
}

.table th {
    background: #F9FAFB;
    font-weight: 600;
    color: #374151;
    border: none;
    padding: 12px 15px;
    width: 30%;
}

.table td {
    border: none;
    padding: 12px 15px;
    color: #6B7280;
}

/* Адаптивные стили */
@media (max-width: 768px) {
    .dashboard-title {
        font-size: 2rem;
        flex-direction: column;
        gap: 10px;
    }
    
    .dashboard-navigation {
        flex-direction: column;
        align-items: center;
    }
    
    .card-body {
        padding: 20px;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 10px;
    }
    
    .template-content {
        max-height: 300px;
    }
}
</style>
