<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\models\PasswordResetRequestForm */

$this->title = 'Восстановление пароля';
?>

<div class="auth-form">
    <div class="auth-info">
        <p>Введите ваш email адрес. Мы отправим вам ссылку для сброса пароля.</p>
    </div>

    <?php $form = ActiveForm::begin([
        'id' => 'request-password-reset-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"form-group\">{input}</div>\n<div class=\"help-block\">{error}</div>",
            'labelOptions' => ['class' => 'control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'email')->textInput(['autofocus' => true, 'placeholder' => 'Введите ваш email']) ?>

    <div class="form-group">
        <div class="auth-actions">
            <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary btn-block']) ?>
        </div>
    </div>

    <div class="auth-links">
        <div class="text-center">
            <?= Html::a('Вернуться к входу', ['login'], ['class' => 'auth-link']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
