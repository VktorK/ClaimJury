<?php
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $user app\models\User */

$resetLink = Url::to(['auth/reset-password', 'token' => $user->password_reset_token], true);
?>
Восстановление пароля

Вы запросили восстановление пароля для вашего аккаунта в ClaimJury.

Для создания нового пароля, перейдите по ссылке:

<?= $resetLink ?>

Важно: Эта ссылка действительна в течение 1 часа.

Если вы не запрашивали восстановление пароля, просто проигнорируйте это письмо.

---
ClaimJury
