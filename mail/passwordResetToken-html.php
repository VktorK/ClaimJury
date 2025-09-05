<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $user app\models\User */

$resetLink = Url::to(['auth/reset-password', 'token' => $user->password_reset_token], true);
?>
<div style="text-align: center;">
    <h2>Восстановление пароля</h2>
    <p>Вы запросили восстановление пароля для вашего аккаунта в ClaimJury.</p>
    <p>Для создания нового пароля, нажмите на кнопку ниже:</p>
    
    <a href="<?= $resetLink ?>" class="btn">Сбросить пароль</a>
    
    <p>Если кнопка не работает, скопируйте и вставьте следующую ссылку в ваш браузер:</p>
    <p style="word-break: break-all; color: #667eea;"><?= $resetLink ?></p>
    
    <p style="margin-top: 30px; color: #666; font-size: 14px;">
        <strong>Важно:</strong> Эта ссылка действительна в течение 1 часа.
    </p>
    
    <p style="color: #666; font-size: 14px;">
        Если вы не запрашивали восстановление пароля, просто проигнорируйте это письмо.
    </p>
</div>
