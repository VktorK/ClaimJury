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

    /* Улучшенные стили для кнопок */
    .btn {
        border-radius: 8px;
        font-weight: 600;
        padding: 10px 20px;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        text-decoration: none;
    }

    .btn:active {
        transform: translateY(0);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .btn:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.25);
    }

    /* Основные кнопки */
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: #667eea;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        border-color: #5a6fd8;
        color: white;
    }

    .btn-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border-color: #28a745;
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #218838 0%, #1ea085 100%);
        border-color: #218838;
        color: white;
    }

    .btn-info {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
        border-color: #17a2b8;
    }

    .btn-info:hover {
        background: linear-gradient(135deg, #138496 0%, #117a8b 100%);
        border-color: #138496;
        color: white;
    }

    .btn-warning {
        background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
        color: #212529;
        border-color: #ffc107;
    }

    .btn-warning:hover {
        background: linear-gradient(135deg, #e0a800 0%, #d39e00 100%);
        border-color: #e0a800;
        color: #212529;
    }

    .btn-danger {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        border-color: #dc3545;
    }

    .btn-danger:hover {
        background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
        border-color: #c82333;
        color: white;
    }

    .btn-secondary {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
        color: white;
        border-color: #6c757d;
    }

    .btn-secondary:hover {
        background: linear-gradient(135deg, #5a6268 0%, #545b62 100%);
        border-color: #5a6268;
        color: white;
    }

    /* Outline кнопки */
    .btn-outline-primary {
        background: transparent;
        color: #667eea;
        border-color: #667eea;
    }

    .btn-outline-primary:hover {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }

    .btn-outline-success {
        background: transparent;
        color: #28a745;
        border-color: #28a745;
    }

    .btn-outline-success:hover {
        background: #28a745;
        color: white;
        border-color: #28a745;
    }

    .btn-outline-danger {
        background: transparent;
        color: #dc3545;
        border-color: #dc3545;
    }

    .btn-outline-danger:hover {
        background: #dc3545;
        color: white;
        border-color: #dc3545;
    }

    .btn-outline-warning {
        background: transparent;
        color: #ffc107;
        border-color: #ffc107;
    }

    .btn-outline-warning:hover {
        background: #ffc107;
        color: #212529;
        border-color: #ffc107;
    }

    .btn-outline-info {
        background: transparent;
        color: #17a2b8;
        border-color: #17a2b8;
    }

    .btn-outline-info:hover {
        background: #17a2b8;
        color: white;
        border-color: #17a2b8;
    }

    .btn-outline-secondary {
        background: transparent;
        color: #6c757d;
        border-color: #6c757d;
    }

    .btn-outline-secondary:hover {
        background: #6c757d;
        color: white;
        border-color: #6c757d;
    }

    /* Размеры кнопок */
    .btn-sm {
        padding: 6px 12px;
        font-size: 0.875rem;
    }

    .btn-lg {
        padding: 12px 24px;
        font-size: 1.125rem;
    }

    .btn-xl {
        padding: 16px 32px;
        font-size: 1.25rem;
    }

    /* Кнопки с иконками */
    .btn i {
        font-size: 1em;
    }

    .btn i:first-child {
        margin-right: 4px;
    }

    .btn i:last-child {
        margin-left: 4px;
    }

    /* Анимация загрузки */
    .btn .fa-spinner {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Отключенные кнопки */
    .btn:disabled,
    .btn.disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
    }

    .btn:disabled:hover,
    .btn.disabled:hover {
        transform: none !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
    }

    /* Стили для breadcrumbs */
    .breadcrumb {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 12px;
        padding: 12px 20px;
        margin: 20px 0;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        font-size: 0.9rem;
        font-weight: 500;
    }

    .breadcrumb-item {
        display: inline-flex;
        align-items: center;
    }

    .breadcrumb-item + .breadcrumb-item::before {
        content: "›";
        color: #667eea;
        font-weight: bold;
        font-size: 1.2rem;
        margin: 0 8px;
        display: inline-flex;
        align-items: center;
    }

    .breadcrumb-item a {
        color: #667eea;
        text-decoration: none;
        padding: 4px 8px;
        border-radius: 6px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .breadcrumb-item a:hover {
        background: rgba(102, 126, 234, 0.1);
        color: #5a6fd8;
        text-decoration: none;
        transform: translateY(-1px);
    }

    .breadcrumb-item.active {
        color: #495057;
        font-weight: 600;
        padding: 4px 8px;
        background: rgba(102, 126, 234, 0.1);
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .breadcrumb-item.active::before {
        content: "🏠";
        font-size: 0.9rem;
    }

    /* Стили для иконок в breadcrumbs */
    .breadcrumb-item a i {
        font-size: 0.8rem;
        opacity: 0.8;
    }

    .breadcrumb-item.active i {
        font-size: 0.8rem;
        opacity: 0.9;
    }

    /* Адаптивность для breadcrumbs */
    @media (max-width: 768px) {
        .breadcrumb {
            padding: 10px 15px;
            font-size: 0.85rem;
            margin: 15px 0;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            margin: 0 6px;
            font-size: 1rem;
        }

        .breadcrumb-item a,
        .breadcrumb-item.active {
            padding: 3px 6px;
        }
    }

    /* Стили для пустых breadcrumbs */
    .breadcrumb:empty {
        display: none;
    }

    /* Анимация появления breadcrumbs */
    .breadcrumb {
        animation: slideInDown 0.5s ease-out;
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
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
            ['label' => 'Блог', 'url' => ['/blog/index']],
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
                        ' . Yii::$app->user->identity->profile->getFullName() . '
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
            'links' => $this->params['breadcrumbs'] ?? [],
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
