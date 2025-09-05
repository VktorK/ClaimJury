<?php
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $user app\models\User */

$verifyLink = Url::to(['auth/verify-email', 'token' => $user->email_verification_token], true);
?>
Добро пожаловать в ClaimJury!

Спасибо за регистрацию. Для завершения регистрации, пожалуйста, подтвердите ваш email адрес, перейдя по ссылке:

<?= $verifyLink ?>

Ваши данные для входа:
Имя пользователя: <?= $user->username ?>
Email: <?= $user->email ?>

Если вы не регистрировались на нашем сайте, просто проигнорируйте это письмо.

---
ClaimJury
