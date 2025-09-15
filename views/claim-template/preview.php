<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ClaimTemplate */
/* @var $previewContent string */
/* @var $sampleData array */

$this->title = 'Предварительный просмотр: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Панель управления', 'url' => ['/dashboard']];
$this->params['breadcrumbs'][] = ['label' => 'Шаблоны претензий', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Предварительный просмотр';
?>

<div class="claim-template-preview">
    <div class="row">
        <div class="col-12">
            <div class="dashboard-header">
                <h1 class="dashboard-title">
                    <i class="fas fa-search"></i>
                    Предварительный просмотр
                </h1>
                <p class="dashboard-subtitle"><?= Html::encode($model->name) ?></p>
                
                <div class="dashboard-navigation">
                    <?= Html::a('<i class="fas fa-arrow-left"></i> Назад к шаблону', ['view', 'id' => $model->id], [
                        'class' => 'btn btn-outline-light dashboard-nav-btn'
                    ]) ?>
                    <?= Html::a('<i class="fas fa-edit"></i> Редактировать', ['update', 'id' => $model->id], [
                        'class' => 'btn btn-outline-light dashboard-nav-btn'
                    ]) ?>
                    <?= Html::a('<i class="fas fa-download"></i> Скачать DOCX', ['#'], [
                        'class' => 'btn btn-outline-light dashboard-nav-btn',
                        'onclick' => 'downloadDocx()'
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="preview-content-card">
                <div class="card-header">
                    <h3>
                        <i class="fas fa-file-alt"></i>
                        Заполненный шаблон
                    </h3>
                </div>
                <div class="card-body">
                    <div class="preview-content">
                        <div class="template-formatted"><?= $this->context->formatTemplateContent($previewContent) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="sample-data-card">
                <div class="card-header">
                    <h3>
                        <i class="fas fa-database"></i>
                        Тестовые данные
                    </h3>
                </div>
                <div class="card-body">
                    <div class="sample-data">
                        <div class="data-section">
                            <h5><i class="fas fa-store"></i> Продавец</h5>
                            <div class="data-item">
                                <strong>Название:</strong> <?= Html::encode($sampleData['seller']['name']) ?>
                            </div>
                            <div class="data-item">
                                <strong>ОГРН:</strong> <?= Html::encode($sampleData['seller']['ogrn']) ?>
                            </div>
                            <div class="data-item">
                                <strong>Адрес:</strong> <?= Html::encode($sampleData['seller']['address']) ?>
                            </div>
                            <div class="data-item">
                                <strong>Телефон:</strong> <?= Html::encode($sampleData['seller']['phone']) ?>
                            </div>
                        </div>

                        <div class="data-section">
                            <h5><i class="fas fa-user"></i> Покупатель</h5>
                            <div class="data-item">
                                <strong>ФИО:</strong> <?= Html::encode($sampleData['buyer']['full_name']) ?>
                            </div>
                            <div class="data-item">
                                <strong>Адрес:</strong> <?= Html::encode($sampleData['buyer']['address']) ?>
                            </div>
                            <div class="data-item">
                                <strong>Телефон:</strong> <?= Html::encode($sampleData['buyer']['phone']) ?>
                            </div>
                        </div>

                        <div class="data-section">
                            <h5><i class="fas fa-box"></i> Товар</h5>
                            <div class="data-item">
                                <strong>Название:</strong> <?= Html::encode($sampleData['product']['name']) ?>
                            </div>
                            <div class="data-item">
                                <strong>Модель:</strong> <?= Html::encode($sampleData['product']['model']) ?>
                            </div>
                            <div class="data-item">
                                <strong>Серийный номер:</strong> <?= Html::encode($sampleData['product']['serial']) ?>
                            </div>
                            <div class="data-item">
                                <strong>Категория:</strong> <?= Html::encode($sampleData['product']['category']) ?>
                            </div>
                        </div>

                        <div class="data-section">
                            <h5><i class="fas fa-shopping-cart"></i> Покупка</h5>
                            <div class="data-item">
                                <strong>Дата:</strong> <?= Html::encode($sampleData['purchase']['date']) ?>
                            </div>
                            <div class="data-item">
                                <strong>Цена:</strong> <?= Html::encode($sampleData['purchase']['price']) ?>
                            </div>
                            <div class="data-item">
                                <strong>Гарантия:</strong> <?= Html::encode($sampleData['purchase']['warranty']) ?>
                            </div>
                        </div>

                        <div class="data-section">
                            <h5><i class="fas fa-exclamation-triangle"></i> Недостатки</h5>
                            <div class="data-item">
                                <strong>Текущий недостаток:</strong> <?= Html::encode($sampleData['defect']['current']) ?>
                            </div>
                            <div class="data-item">
                                <strong>Общий недостаток:</strong> <?= Html::encode($sampleData['defect']['general']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="template-info-card">
                <div class="card-header">
                    <h3>
                        <i class="fas fa-info-circle"></i>
                        Информация о шаблоне
                    </h3>
                </div>
                <div class="card-body">
                    <div class="template-info">
                        <div class="info-item">
                            <strong>Название:</strong> <?= Html::encode($model->name) ?>
                        </div>
                        <div class="info-item">
                            <strong>Тип:</strong> <?= Html::encode($model->getTypeName()) ?>
                        </div>
                        <div class="info-item">
                            <strong>Статус:</strong> 
                            <span class="badge badge-<?= $model->status == $model::STATUS_ACTIVE ? 'success' : 'secondary' ?>">
                                <?= $model->getStatusText() ?>
                            </span>
                        </div>
                        <div class="info-item">
                            <strong>Создан:</strong> <?= Yii::$app->formatter->asDate($model->created_at, 'php:d.m.Y H:i') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.dashboard-header {
    background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);
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

.preview-content-card,
.sample-data-card,
.template-info-card {
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

.sample-data-card .card-header {
    background: linear-gradient(135deg, #10B981, #059669);
}

.template-info-card .card-header {
    background: linear-gradient(135deg, #F59E0B, #D97706);
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

.preview-content {
    background: #F9FAFB;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    padding: 30px;
    min-height: 400px;
    overflow-y: auto;
}

.template-formatted {
    font-family: 'Courier New', monospace;
    font-size: 0.9rem;
    line-height: 1.5;
    color: #374151;
}

.template-line {
    margin-bottom: 8px;
    word-wrap: break-word;
}

.template-line-empty {
    height: 16px;
}

.template-line-title {
    text-align: center;
    font-weight: bold;
    font-size: 1.1rem;
    margin: 20px 0;
    text-transform: uppercase;
}

.template-line-field {
    display: flex;
    flex-wrap: wrap;
    align-items: baseline;
    margin-bottom: 4px;
}

.template-line-continuation {
    margin-left: 0;
    padding-left: 0;
    margin-bottom: 4px;
}

.template-label {
    font-weight: bold;
    margin-right: 8px;
    white-space: nowrap;
    min-width: 80px;
}

.template-value {
    flex: 1;
    min-width: 0;
    word-break: break-word;
    margin-left: 0;
}

.template-line-item {
    display: flex;
    align-items: baseline;
    margin-left: 20px;
}

.template-item-number {
    font-weight: bold;
    margin-right: 8px;
    white-space: nowrap;
}

.template-item-text {
    flex: 1;
    min-width: 0;
    word-break: break-word;
}

.template-line-list {
    display: flex;
    align-items: baseline;
    margin-left: 20px;
}

.template-list-marker {
    margin-right: 8px;
    white-space: nowrap;
}

.template-list-text {
    flex: 1;
    min-width: 0;
    word-break: break-word;
}

.template-line-text {
    text-align: justify;
    word-break: break-word;
    margin-bottom: 8px;
    line-height: 1.6;
}

.data-section {
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #E5E7EB;
}

.data-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.data-section h5 {
    color: #374151;
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.data-item {
    margin-bottom: 8px;
    font-size: 0.9rem;
    color: #6B7280;
}

.data-item strong {
    color: #374151;
    font-weight: 600;
}

.template-info .info-item {
    margin-bottom: 10px;
    padding: 8px 0;
    border-bottom: 1px solid #F3F4F6;
}

.template-info .info-item:last-child {
    border-bottom: none;
}

.template-info .info-item strong {
    color: #374151;
    font-weight: 600;
    display: inline-block;
    width: 80px;
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
    
    .preview-content {
        padding: 20px;
        font-size: 0.9rem;
        min-height: 300px;
    }
    
    .data-section h5 {
        font-size: 0.9rem;
    }
    
    .data-item {
        font-size: 0.8rem;
    }
}
</style>

<script>
function downloadDocx() {
    // Здесь можно добавить функционал для скачивания DOCX файла
    alert('Функция скачивания DOCX будет реализована в следующем обновлении');
}
</script>
