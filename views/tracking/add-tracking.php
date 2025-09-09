<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $claim app\models\Claim */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Добавить трек-номер';
$this->params['breadcrumbs'][] = ['label' => 'Главная', 'url' => ['/purchases']];
$this->params['breadcrumbs'][] = ['label' => 'Претензии', 'url' => ['/claim/index']];
$this->params['breadcrumbs'][] = ['label' => 'Претензия #' . $claim->id, 'url' => ['/claim/view', 'id' => $claim->id]];
$this->params['breadcrumbs'][] = ['label' => 'Отслеживание', 'url' => ['index', 'id' => $claim->id]];
$this->params['breadcrumbs'][] = 'Добавить трек-номер';
?>

<div class="tracking-add">
    <div class="row">
        <div class="col-lg-8">
            <div class="tracking-form-card">
                <div class="card-header">
                    <h2>
                        <i class="fas fa-plus"></i>
                        Добавить трек-номер
                    </h2>
                </div>
                
                <div class="card-body">
                    <div class="claim-info mb-4">
                        <h5>Претензия #<?= $claim->id ?></h5>
                        <p class="text-muted">Тип: <?= $claim->getClaimTypeLabel() ?></p>
                    </div>

                    <?php $form = ActiveForm::begin(); ?>

                    <div class="form-group">
                        <label for="tracking_number" class="form-label">Трек-номер отправления</label>
                        <input type="text" 
                               id="tracking_number" 
                               name="tracking_number" 
                               class="form-control" 
                               placeholder="Введите трек-номер (например: 12345678901234)"
                               maxlength="50"
                               required>
                        <div class="form-text">
                            <i class="fas fa-info-circle"></i>
                            Введите трек-номер отправления документов по претензии
                        </div>
                    </div>

                    <div class="form-actions">
                        <?= Html::submitButton('<i class="fas fa-save"></i> Добавить трек-номер', ['class' => 'btn btn-success']) ?>
                        <?= Html::a('<i class="fas fa-times"></i> Отмена', ['index', 'id' => $claim->id], ['class' => 'btn btn-secondary']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="help-card">
                <div class="card-header">
                    <h3>
                        <i class="fas fa-info-circle"></i>
                        Справка
                    </h3>
                </div>
                <div class="card-body">
                    <h5>Формат трек-номера:</h5>
                    <ul>
                        <li><strong>13 цифр</strong> - стандартный формат</li>
                        <li><strong>14 цифр</strong> - расширенный формат</li>
                        <li><strong>13 цифр + буква</strong> - международные отправления</li>
                    </ul>
                    
                    <h5>Примеры:</h5>
                    <ul>
                        <li><code>1234567890123</code></li>
                        <li><code>12345678901234</code></li>
                        <li><code>1234567890123A</code></li>
                    </ul>
                    
                    <h5>Где найти трек-номер:</h5>
                    <ul>
                        <li>На квитанции об отправке</li>
                        <li>В SMS-уведомлении</li>
                        <li>На сайте Почты России</li>
                        <li>В мобильном приложении</li>
                    </ul>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-lightbulb"></i>
                        <strong>Совет:</strong> После добавления трек-номера статус отслеживания обновится автоматически.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.tracking-form-card,
.help-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    overflow: hidden;
}

.card-header {
    background: linear-gradient(135deg, #3B82F6, #1D4ED8);
    color: white;
    padding: 20px 30px;
    margin: 0;
}

.card-header h2,
.card-header h3 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-body {
    padding: 30px;
}

.claim-info {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    border-left: 4px solid #3B82F6;
}

.claim-info h5 {
    margin: 0 0 5px 0;
    color: #333;
    font-weight: 600;
}

.form-group {
    margin-bottom: 25px;
}

.form-label {
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
    display: block;
}

.form-control {
    border: 2px solid #e1e5e9;
    border-radius: 10px;
    padding: 12px 15px;
    font-size: 1rem;
    transition: all 0.3s ease;
    font-family: 'Courier New', monospace;
    font-weight: 600;
}

.form-control:focus {
    border-color: #3B82F6;
    box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
}

.form-text {
    margin-top: 8px;
    color: #6c757d;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 5px;
}

.form-actions {
    display: flex;
    gap: 15px;
    align-items: center;
    margin-top: 30px;
}

.btn {
    padding: 12px 24px;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-success {
    background: linear-gradient(135deg, #10B981, #059669);
    color: white;
}

.btn-success:hover {
    background: linear-gradient(135deg, #059669, #047857);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
}

.btn-secondary {
    background: linear-gradient(135deg, #6B7280, #4B5563);
    color: white;
}

.btn-secondary:hover {
    background: linear-gradient(135deg, #4B5563, #374151);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(107, 114, 128, 0.3);
    color: white;
    text-decoration: none;
}

.help-card .card-body h5 {
    color: #333;
    font-weight: 600;
    margin-top: 20px;
    margin-bottom: 10px;
}

.help-card .card-body h5:first-child {
    margin-top: 0;
}

.help-card .card-body ul {
    margin: 15px 0;
    padding-left: 20px;
}

.help-card .card-body li {
    margin-bottom: 8px;
    color: #666;
}

.help-card .card-body code {
    background: #f1f3f4;
    padding: 2px 6px;
    border-radius: 4px;
    font-family: 'Courier New', monospace;
    font-size: 0.9rem;
    color: #d63384;
}

.alert {
    padding: 15px 20px;
    border-radius: 10px;
    margin-top: 20px;
    border-left: 4px solid;
}

.alert-info {
    background: #e7f3ff;
    border-left-color: #3B82F6;
    color: #1e40af;
}

.alert i {
    margin-right: 8px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const trackingInput = document.getElementById('tracking_number');
    
    // Валидация трек-номера в реальном времени
    trackingInput.addEventListener('input', function() {
        const value = this.value.trim();
        const isValid = /^[0-9]{13}[A-Z]?$|^[0-9]{14}$/.test(value);
        
        if (value.length > 0) {
            if (isValid) {
                this.style.borderColor = '#10B981';
                this.style.backgroundColor = '#f0f9ff';
            } else {
                this.style.borderColor = '#EF4444';
                this.style.backgroundColor = '#fef2f2';
            }
        } else {
            this.style.borderColor = '#e1e5e9';
            this.style.backgroundColor = 'white';
        }
    });
    
    // Автоматическое форматирование
    trackingInput.addEventListener('keypress', function(e) {
        // Разрешаем только цифры и буквы
        if (!/[0-9A-Za-z]/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Enter'].includes(e.key)) {
            e.preventDefault();
        }
    });
});
</script>
