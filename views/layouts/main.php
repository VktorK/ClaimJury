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
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
    /* Глобальные стили для исправления выпадающих списков */
    .form-control {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        font-size: 1rem;
        line-height: 1.5;
    }

    select.form-control {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3E%3Cpath fill='%23666' d='M2 0L0 2h4zm0 5L0 3h4z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 12px;
        padding-right: 35px;
    }

    select.form-control:focus {
        background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3E%3Cpath fill='%23667eea' d='M2 0L0 2h4zm0 5L0 3h4z'/%3E%3C/svg%3E");
    }

    select.form-control option {
        padding: 8px 12px;
        font-size: 1rem;
        line-height: 1.4;
        color: #333;
        background: white;
    }

    select.form-control option:hover {
        background: #f8f9fa;
    }

    select.form-control option:checked {
        background: #667eea;
        color: white;
    }
    </style>
    
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
                        <a class="dropdown-item" href="/dashboard"><i class="fas fa-tachometer-alt"></i> Панель управления</a>
                        <a class="dropdown-item" href="/profile"><i class="fas fa-user"></i> Мой профиль</a>
                        <a class="dropdown-item" href="/profile/edit"><i class="fas fa-edit"></i> Редактировать профиль</a>
                        <div class="dropdown-divider"></div>
                        ' . Html::beginForm(['/auth/logout'], 'post', ['class' => 'form-inline'])
                        . Html::submitButton('<i class="fas fa-sign-out-alt"></i> Выйти', ['class' => 'dropdown-item', 'style' => 'border: none; background: none; width: 100%; text-align: left;'])
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

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
<?php $this->endPage() ?>
