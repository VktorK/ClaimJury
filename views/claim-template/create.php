<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ClaimTemplate */

$this->title = 'Создание шаблона претензии';
$this->params['breadcrumbs'][] = ['label' => 'Панель управления', 'url' => ['/dashboard']];
$this->params['breadcrumbs'][] = ['label' => 'Шаблоны претензий', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="claim-template-create">
    <div class="row">
        <div class="col-12">
            <div class="dashboard-header">
                <h1 class="dashboard-title">
                    <i class="fas fa-plus-circle"></i>
                    Создание шаблона претензии
                </h1>
                <p class="dashboard-subtitle">Создайте новый шаблон для генерации претензий</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="template-form-card">
                <div class="card-header">
                    <h3>
                        <i class="fas fa-edit"></i>
                        Основная информация
                    </h3>
                </div>
                <div class="card-body">
                    <?php $form = ActiveForm::begin([
                        'id' => 'template-form',
                        'options' => ['class' => 'template-form'],
                        'fieldConfig' => [
                            'template' => "{label}\n<div class=\"form-group\">{input}</div>\n<div class=\"help-block\">{error}</div>",
                            'labelOptions' => ['class' => 'control-label'],
                        ],
                    ]); ?>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'name')->textInput([
                                'maxlength' => true,
                                'placeholder' => 'Введите название шаблона',
                                'class' => 'form-control'
                            ]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'type')->dropDownList(
                                $model->getClaimTypes(),
                                [
                                    'prompt' => 'Выберите тип претензии',
                                    'class' => 'form-control'
                                ]
                            ) ?>
                        </div>
                    </div>

                    <?= $form->field($model, 'description')->textInput([
                        'maxlength' => true,
                        'placeholder' => 'Краткое описание шаблона (необязательно)',
                        'class' => 'form-control'
                    ]) ?>

                    <?= $form->field($model, 'template_content')->textarea([
                        'rows' => 15,
                        'placeholder' => 'Введите содержимое шаблона с использованием плейсхолдеров...',
                        'class' => 'form-control template-editor',
                        'id' => 'template-content'
                    ]) ?>

                    <div class="form-group">
                        <?= Html::submitButton('<i class="fas fa-save"></i> Создать шаблон', [
                            'class' => 'btn btn-success btn-lg',
                            'name' => 'create-button'
                        ]) ?>
                        <?= Html::a('<i class="fas fa-times"></i> Отмена', ['index'], [
                            'class' => 'btn btn-secondary btn-lg'
                        ]) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="placeholders-card">
                <div class="card-header">
                    <h3>
                        <i class="fas fa-code"></i>
                        Доступные плейсхолдеры
                    </h3>
                </div>
                <div class="card-body">
                    <div id="placeholders-list">
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Загрузка...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="help-card">
                <div class="card-header">
                    <h3>
                        <i class="fas fa-question-circle"></i>
                        Справка
                    </h3>
                </div>
                <div class="card-body">
                    <div class="help-content">
                        <h5>Как использовать плейсхолдеры:</h5>
                        <ol>
                            <li>Выберите нужный плейсхолдер из списка</li>
                            <li>Нажмите на него, чтобы вставить в шаблон</li>
                            <li>Плейсхолдер автоматически заменится на данные из БД</li>
                        </ol>
                        
                        <h5>Примеры плейсхолдеров:</h5>
                        <ul>
                            <li><code>{SELLER_NAME}</code> - название продавца</li>
                            <li><code>{BUYER_FULL_NAME}</code> - полное имя покупателя</li>
                            <li><code>{PRODUCT_NAME}</code> - название товара</li>
                            <li><code>{CURRENT_DATE}</code> - текущая дата</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.dashboard-header {
    background: linear-gradient(135deg, #10B981 0%, #059669 100%);
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
    margin-bottom: 0;
    opacity: 0.9;
}

.template-form-card,
.placeholders-card,
.help-card {
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

.template-form .form-group {
    margin-bottom: 20px;
}

.control-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
    display: block;
}

.form-control {
    border: 2px solid #E5E7EB;
    border-radius: 8px;
    padding: 12px 15px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #8B5CF6;
    box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
    outline: none;
}

.template-editor {
    font-family: 'Courier New', monospace;
    font-size: 0.9rem;
    line-height: 1.5;
    resize: vertical;
    min-height: 300px;
}

.btn {
    border-radius: 8px;
    font-weight: 600;
    padding: 12px 25px;
    font-size: 1rem;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    border: none;
}

.btn-success {
    background: linear-gradient(135deg, #10B981, #059669);
    color: white;
}

.btn-success:hover {
    background: linear-gradient(135deg, #059669, #047857);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
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

.placeholders-card .card-header {
    background: linear-gradient(135deg, #3B82F6, #1D4ED8);
}

.help-card .card-header {
    background: linear-gradient(135deg, #F59E0B, #D97706);
}

.placeholder-category {
    margin-bottom: 20px;
}

.placeholder-category h5 {
    color: #374151;
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 10px;
    padding-bottom: 5px;
    border-bottom: 2px solid #E5E7EB;
}

.placeholder-item {
    background: #F9FAFB;
    border: 1px solid #E5E7EB;
    border-radius: 6px;
    padding: 8px 12px;
    margin-bottom: 5px;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.placeholder-item:hover {
    background: #E5E7EB;
    border-color: #8B5CF6;
    transform: translateX(5px);
}

.placeholder-code {
    font-family: 'Courier New', monospace;
    font-weight: 600;
    color: #8B5CF6;
    font-size: 0.85rem;
}

.placeholder-description {
    color: #6B7280;
    font-size: 0.8rem;
    text-align: right;
}

.help-content h5 {
    color: #374151;
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 10px;
}

.help-content ol,
.help-content ul {
    padding-left: 20px;
    margin-bottom: 15px;
}

.help-content li {
    margin-bottom: 5px;
    color: #6B7280;
    font-size: 0.9rem;
}

.help-content code {
    background: #F3F4F6;
    color: #8B5CF6;
    padding: 2px 6px;
    border-radius: 4px;
    font-family: 'Courier New', monospace;
    font-size: 0.85rem;
}

/* Адаптивные стили */
@media (max-width: 768px) {
    .dashboard-title {
        font-size: 2rem;
        flex-direction: column;
        gap: 10px;
    }
    
    .card-body {
        padding: 20px;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
        margin-bottom: 10px;
    }
    
    .template-editor {
        min-height: 200px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Загружаем плейсхолдеры
    loadPlaceholders();
    
    // Обработчик для вставки плейсхолдеров
    document.addEventListener('click', function(e) {
        if (e.target.closest('.placeholder-item')) {
            const placeholderCode = e.target.closest('.placeholder-item').querySelector('.placeholder-code').textContent;
            insertPlaceholder(placeholderCode);
        }
    });
    
    // Сохраняем позицию курсора при изменении содержимого
    const textarea = document.getElementById('template-content');
    if (textarea) {
        textarea.addEventListener('input', function() {
            // Небольшая задержка для корректного обновления
            setTimeout(() => {
                // Здесь можно добавить дополнительную логику при необходимости
            }, 10);
        });
        
        // Сохраняем позицию курсора при вставке
        textarea.addEventListener('paste', function() {
            setTimeout(() => {
                // Здесь можно добавить дополнительную логику при необходимости
            }, 10);
        });
    }
});

function loadPlaceholders() {
    fetch('/claim-template/get-placeholders')
        .then(response => response.json())
        .then(data => {
            displayPlaceholders(data);
        })
        .catch(error => {
            console.error('Ошибка загрузки плейсхолдеров:', error);
            document.getElementById('placeholders-list').innerHTML = 
                '<div class="text-center text-danger">Ошибка загрузки плейсхолдеров</div>';
        });
}

function displayPlaceholders(placeholders) {
    const container = document.getElementById('placeholders-list');
    let html = '';
    
    for (const [categoryKey, category] of Object.entries(placeholders)) {
        html += `
            <div class="placeholder-category">
                <h5>${category.name}</h5>
        `;
        
        for (const [placeholder, description] of Object.entries(category.placeholders)) {
            html += `
                <div class="placeholder-item">
                    <span class="placeholder-code">${placeholder}</span>
                    <span class="placeholder-description">${description}</span>
                </div>
            `;
        }
        
        html += '</div>';
    }
    
    container.innerHTML = html;
}

function insertPlaceholder(placeholder) {
    const textarea = document.getElementById('template-content');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const text = textarea.value;
    
    const newText = text.substring(0, start) + placeholder + text.substring(end);
    textarea.value = newText;
    
    // Устанавливаем курсор после вставленного плейсхолдера
    const newPosition = start + placeholder.length;
    
    // Используем requestAnimationFrame для корректного обновления позиции курсора
    requestAnimationFrame(() => {
        textarea.setSelectionRange(newPosition, newPosition);
        textarea.focus();
    });
}
</script>
