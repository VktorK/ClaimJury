<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\SignupForm */

$this->title = 'Регистрация';
?>

<div class="auth-form">
    <?php $form = ActiveForm::begin([
        'id' => 'signup-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"form-group\">{input}</div>\n<div class=\"help-block\">{error}</div>",
            'labelOptions' => ['class' => 'control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => 'Введите имя пользователя']) ?>

    <?= $form->field($model, 'email')->textInput(['placeholder' => 'Введите email']) ?>

    <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Введите пароль']) ?>

    <?= $form->field($model, 'password_repeat')->passwordInput(['placeholder' => 'Повторите пароль']) ?>

    <div class="form-group">
        <div class="auth-actions">
            <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary btn-block', 'name' => 'signup-button']) ?>
        </div>
    </div>

    <div class="auth-links">
        <div class="text-center">
            <?= Html::a('Уже есть аккаунт? Войти', ['login'], ['class' => 'auth-link']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
