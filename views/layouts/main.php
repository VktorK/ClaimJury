<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header>
    <?php
    NavBar::begin([
        'brandLabel' => 'ClaimJury',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark fixed-top',
            'style' => 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 2px 10px rgba(0,0,0,0.1);',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav mr-auto'],
        'items' => [
            ['label' => 'Главная', 'url' => ['/site/index']],
            ['label' => 'О нас', 'url' => ['/site/about']],
            ['label' => 'Контакты', 'url' => ['/site/contact']],
        ],
    ]);
    
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => [
            Yii::$app->user->isGuest ? (
                ['label' => 'Войти', 'url' => ['/auth/login']]
            ) : (
                '<li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        ' . Yii::$app->user->identity->username . '
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/profile">Мой профиль</a>
                        <a class="dropdown-item" href="/profile/edit">Редактировать профиль</a>
                        <div class="dropdown-divider"></div>
                        ' . Html::beginForm(['/auth/logout'], 'post', ['class' => 'form-inline'])
                        . Html::submitButton('Выйти', ['class' => 'dropdown-item', 'style' => 'border: none; background: none; width: 100%; text-align: left;'])
                        . Html::endForm() . '
                    </div>
                </li>'
            )
        ],
    ]);
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer class="footer mt-auto py-4" style="background: #f8f9fa; border-top: 1px solid #e9ecef;">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <p class="mb-0">&copy; ClaimJury <?= date('Y') ?>. Все права защищены.</p>
            </div>
            <div class="col-md-6 text-right">
                <p class="mb-0">Powered by <?= Yii::powered() ?></p>
            </div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
