<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Product */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Редактирование товара';
$this->params['breadcrumbs'][] = ['label' => 'Панель управления - Покупки', 'url' => ['/purchases']];
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['/product/index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>

<div class="product-update">
    <div class="row">
        <div class="col-lg-8">
            <div class="update-card">
                <div class="card-header">
                    <h2>
                        <i class="fas fa-edit"></i>
                        Редактирование товара
                    </h2>
                </div>
                
                <div class="card-body">
                    <?php $form = ActiveForm::begin(); ?>

                    <div class="row">
                        <div class="col-md-8">
                            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Категория</label>
                                <div class="category-display">
                                    <?php if ($model->category): ?>
                                        <span class="category-name">
                                            <i class="fas fa-folder"></i>
                                            <?= Html::encode($model->category->title) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">Категория не указана</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4 col-sm-6">
                            <?= $form->field($model, 'warranty_period')->input('number', [
                                'min' => '0',
                                'placeholder' => '0',
                                'class' => 'form-control'
                            ]) ?>
                        </div>
                        <div class="form-group col-md-4 col-sm-6">
                            <?= $form->field($model, 'serial_number')->textInput([
                                'maxlength' => true,
                                'class' => 'form-control'
                            ]) ?>
                        </div>
                        <div class="form-group col-md-4 col-sm-12">
                            <?= $form->field($model, 'model')->textInput([
                                'maxlength' => true,
                                'class' => 'form-control'
                            ]) ?>
                        </div>
                    </div>


                    <div class="form-actions">
                        <?= Html::submitButton('<i class="fas fa-save"></i> Сохранить изменения', [
                            'class' => 'btn btn-primary btn-lg'
                        ]) ?>
                        
                        <?= Html::a('<i class="fas fa-times"></i> Отмена', ['view', 'id' => $model->id], [
                            'class' => 'btn btn-secondary btn-lg'
                        ]) ?>
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
                    <div class="help-content">
                        <h4>Поля формы:</h4>
                        <ul>
                            <li><strong>Название товара</strong> - обязательное поле</li>
                            <li><strong>Категория</strong> - обязательное поле</li>
                            <li><strong>Гарантийный срок</strong> - укажите в месяцах</li>
                            <li><strong>Серийный номер</strong> - для бытовой техники</li>
                            <li><strong>Модель</strong> - модель товара</li>
                        </ul>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-lightbulb"></i>
                            <strong>Совет:</strong> Заполните все поля для полной информации о товаре.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<style>
.product-update {
    padding: 20px 0;
}

.update-card,
.help-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    overflow: hidden;
}

.card-header {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    color: white;
    padding: 25px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
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

.card-body {
    padding: 30px;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
}

.form-row .form-group {
    padding-right: 15px;
    padding-left: 15px;
    flex: 0 0 auto;
    width: 100%;
}

.form-row .col-md-4 {
    flex: 0 0 33.333333%;
    max-width: 33.333333%;
}

.form-row .col-sm-6 {
    flex: 0 0 50%;
    max-width: 50%;
}

.form-row .col-sm-12 {
    flex: 0 0 100%;
    max-width: 100%;
}

.form-label {
    font-weight: 600;
    color: #333;
    margin-bottom: 0.5rem;
    display: block;
}

.form-control {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 12px 15px;
    font-size: 1rem;
    transition: all 0.3s ease;
    width: 100%;
    box-sizing: border-box;
}

.form-control:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
}

.form-text {
    font-size: 0.875rem;
    color: #6c757d;
    margin-top: 0.25rem;
}

.category-display {
    padding: 12px 15px;
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    margin-top: 0.5rem;
}

.category-name {
    color: #6f42c1;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
}

.category-name i {
    color: #6f42c1;
}



.form-actions {
    display: flex;
    gap: 15px;
    justify-content: flex-start;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 2px solid #e9ecef;
}

.btn {
    padding: 12px 25px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-lg {
    padding: 15px 30px;
    font-size: 1.1rem;
}

.btn-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
}

.btn-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #545b62 100%);
    color: white;
}

.btn-secondary:hover {
    background: linear-gradient(135deg, #545b62 0%, #3d4449 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
}

.help-content h4 {
    color: #333;
    margin-bottom: 15px;
    font-size: 1.1rem;
}

.help-content ul {
    list-style: none;
    padding: 0;
}

.help-content li {
    padding: 8px 0;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    align-items: center;
}

.help-content li:last-child {
    border-bottom: none;
}

.help-content li strong {
    color: #333;
    margin-right: 8px;
    min-width: 120px;
}

.alert {
    padding: 15px;
    border-radius: 8px;
    margin-top: 20px;
    border-left: 4px solid;
}

.alert-info {
    background-color: #d1ecf1;
    border-color: #17a2b8;
    color: #0c5460;
}

.alert i {
    margin-right: 8px;
}


@media (max-width: 768px) {
    .card-body {
        padding: 20px;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
    
    .form-row .col-md-4 {
        flex: 0 0 50%;
        max-width: 50%;
    }
    
    .form-row .col-sm-12 {
        flex: 0 0 100%;
        max-width: 100%;
    }
}

@media (max-width: 576px) {
    .form-row .col-md-4,
    .form-row .col-sm-6 {
        flex: 0 0 100%;
        max-width: 100%;
    }
    
    .form-control {
        font-size: 16px; /* Предотвращает зум на iOS */
    }
}
</style>

