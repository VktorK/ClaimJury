<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */

$this->title = 'ClaimJury - Система управления исками';
?>
<div class="site-index">
    <div class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="hero-title">Добро пожаловать в ClaimJury</h1>
                    <p class="hero-subtitle">Современная система управления исками и правовыми процессами. Упростите работу с документами и отслеживайте прогресс ваших дел.</p>
                    
                    <?php if (Yii::$app->user->isGuest): ?>
                        <div class="hero-actions">
                            <?= Html::a('Войти в систему', ['auth/login'], ['class' => 'btn btn-primary btn-lg']) ?>
                            <?= Html::a('Регистрация', ['auth/signup'], ['class' => 'btn btn-outline-primary btn-lg']) ?>
                        </div>
                    <?php else: ?>
                        <div class="hero-actions">
                            <h3>Привет, <?= Html::encode(Yii::$app->user->identity->username) ?>!</h3>
                            <p>Добро пожаловать в вашу панель управления.</p>
                            <?= Html::a('Панель управления', ['#'], ['class' => 'btn btn-primary btn-lg']) ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image">
                        <div class="feature-card">
                            <i class="fas fa-gavel"></i>
                            <h4>Правовая система</h4>
                            <p>Управляйте исками и правовыми процессами</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="features-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3>Безопасность</h3>
                        <p>Ваши данные защищены современными методами шифрования и безопасной аутентификацией.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3>Аналитика</h3>
                        <p>Отслеживайте прогресс ваших дел с помощью подробной аналитики и отчетов.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h3>Мобильность</h3>
                        <p>Доступ к системе с любого устройства. Работайте где угодно и когда угодно.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 100px 0;
    margin-bottom: 80px;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 20px;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.hero-subtitle {
    font-size: 1.25rem;
    margin-bottom: 40px;
    opacity: 0.9;
    line-height: 1.6;
}

.hero-actions {
    margin-top: 40px;
}

.hero-actions .btn {
    margin-right: 15px;
    margin-bottom: 15px;
    padding: 15px 30px;
    font-weight: 600;
    border-radius: 50px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.hero-image {
    text-align: center;
}

.feature-card {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    padding: 40px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.feature-card i {
    font-size: 4rem;
    margin-bottom: 20px;
    color: #fff;
}

.feature-card h4 {
    font-size: 1.5rem;
    margin-bottom: 15px;
    font-weight: 600;
}

.features-section {
    padding: 80px 0;
}

.feature-item {
    text-align: center;
    padding: 40px 20px;
    border-radius: 15px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.feature-item:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.feature-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 30px;
}

.feature-icon i {
    font-size: 2rem;
    color: white;
}

.feature-item h3 {
    font-size: 1.5rem;
    margin-bottom: 20px;
    font-weight: 600;
    color: #333;
}

.feature-item p {
    color: #666;
    line-height: 1.6;
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-section {
        padding: 60px 0;
    }
    
    .features-section {
        padding: 60px 0;
    }
}
</style>
