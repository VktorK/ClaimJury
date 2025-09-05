<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $user app\models\User */

$verifyLink = Url::to(['auth/verify-email', 'token' => $user->email_verification_token], true);
?>
<div style="text-align: center;">
    <h2>Добро пожаловать в ClaimJury!</h2>
    <p>Спасибо за регистрацию. Для завершения регистрации, пожалуйста, подтвердите ваш email адрес, нажав на кнопку ниже:</p>
    
    <a href="<?= $verifyLink ?>" class="btn">Подтвердить Email</a>
    
    <p>Если кнопка не работает, скопируйте и вставьте следующую ссылку в ваш браузер:</p>
    <p style="word-break: break-all; color: #667eea;"><?= $verifyLink ?></p>
    
    <p><strong>Ваши данные для входа:</strong></p>
    <p>Имя пользователя: <strong><?= Html::encode($user->username) ?></strong></p>
    <p>Email: <strong><?= Html::encode($user->email) ?></strong></p>
    
    <p style="margin-top: 30px; color: #666; font-size: 14px;">
        Если вы не регистрировались на нашем сайте, просто проигнорируйте это письмо.
    </p>
</div>
