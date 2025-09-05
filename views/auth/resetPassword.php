<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ResetPasswordForm */

$this->title = 'Сброс пароля';
?>

<div class="auth-form">
    <div class="auth-info">
        <p>Пожалуйста, введите новый пароль:</p>
    </div>

    <?php $form = ActiveForm::begin([
        'id' => 'reset-password-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"form-group\">{input}</div>\n<div class=\"help-block\">{error}</div>",
            'labelOptions' => ['class' => 'control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'password')->passwordInput(['autofocus' => true, 'placeholder' => 'Введите новый пароль']) ?>

    <?= $form->field($model, 'password_repeat')->passwordInput(['placeholder' => 'Повторите новый пароль']) ?>

    <div class="form-group">
        <div class="auth-actions">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary btn-block']) ?>
        </div>
    </div>

    <div class="auth-links">
        <div class="text-center">
            <?= Html::a('Вернуться к входу', ['login'], ['class' => 'auth-link']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
