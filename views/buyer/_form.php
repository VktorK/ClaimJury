<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Buyer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="buyer-form">
    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'lastName')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'firstName')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'middleName')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <?= $form->field($model, 'address')->textarea(['rows' => 3]) ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'birthday')->input('date') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'passport')->textInput(['maxlength' => true, 'placeholder' => '1234 567890']) ?>
        </div>
    </div>

    <?= $form->field($model, 'image')->fileInput(['accept' => 'image/*']) ?>
    <small class="form-text text-muted">Поддерживаются форматы: JPG, PNG, GIF. Максимальный размер: 5 МБ.</small>

    <div class="form-group mt-4">
        <?= Html::submitButton('<i class="fas fa-save"></i> ' . ($model->isNewRecord ? 'Создать' : 'Обновить'), [
            'class' => 'btn btn-success btn-lg'
        ]) ?>
        
        <?= Html::a('<i class="fas fa-times"></i> Отмена', ['index'], [
            'class' => 'btn btn-secondary btn-lg ml-2'
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<style>
.buyer-form .form-group {
    margin-bottom: 20px;
}

.buyer-form .form-control {
    border-radius: 8px;
    border: 1px solid #ced4da;
    padding: 12px 15px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.buyer-form .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.buyer-form .form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
}

.buyer-form .btn {
    border-radius: 8px;
    padding: 12px 24px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.buyer-form .btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
}

.buyer-form .btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
}

.buyer-form .btn-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    border: none;
}

.buyer-form .btn-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(108, 117, 125, 0.3);
}

.buyer-form .form-text {
    font-size: 0.85rem;
    color: #6c757d;
    margin-top: 5px;
}

.buyer-form .invalid-feedback {
    display: block;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875rem;
    color: #dc3545;
}

.buyer-form .is-invalid {
    border-color: #dc3545;
}

.buyer-form .is-invalid:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}
</style>
