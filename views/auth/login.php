<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = 'Вход в систему';
?>

<div class="auth-form">
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"form-group\">{input}</div>\n<div class=\"help-block\">{error}</div>",
            'labelOptions' => ['class' => 'control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => 'Введите имя пользователя']) ?>

    <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Введите пароль']) ?>

    <?= $form->field($model, 'rememberMe')->checkbox() ?>

    <div class="form-group">
        <div class="auth-actions">
            <?= Html::submitButton('Войти', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>
        </div>
    </div>

    <div class="auth-links">
        <div class="row">
            <div class="col-sm-6">
                <?= Html::a('Забыли пароль?', ['request-password-reset'], ['class' => 'auth-link']) ?>
            </div>
            <div class="col-sm-6 text-right">
                <?= Html::a('Регистрация', ['signup'], ['class' => 'auth-link']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
