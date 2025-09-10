<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'О нас - ClaimJury';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
/* Стили для страницы "О нас" */
.about-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 100px 0 80px;
    margin-top: -20px;
    position: relative;
    overflow: hidden;
}

.about-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="10" cy="60" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="90" cy="40" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.about-hero .container {
    position: relative;
    z-index: 2;
}

.about-hero h1 {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.about-hero .lead {
    font-size: 1.3rem;
    margin-bottom: 2rem;
    opacity: 0.95;
}

.stats-section {
    padding: 80px 0;
    background: #f8f9fa;
}

.stat-card {
    text-align: center;
    padding: 2rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    margin-bottom: 2rem;
    border: 1px solid #e9ecef;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.stat-number {
    font-size: 3rem;
    font-weight: 700;
    color: #667eea;
    margin-bottom: 0.5rem;
    display: block;
}

.stat-label {
    font-size: 1.1rem;
    color: #6c757d;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.stat-description {
    font-size: 0.9rem;
    color: #868e96;
    line-height: 1.5;
}

.advantages-section {
    padding: 80px 0;
    background: white;
}

.advantage-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 3rem;
    padding: 2rem;
    background: #f8f9fa;
    border-radius: 15px;
    transition: all 0.3s ease;
    border-left: 5px solid #667eea;
}

.advantage-item:hover {
    background: white;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    transform: translateX(10px);
}

.advantage-icon {
    font-size: 3rem;
    color: #667eea;
    margin-right: 2rem;
    flex-shrink: 0;
}

.advantage-content h4 {
    color: #333;
    font-weight: 600;
    margin-bottom: 1rem;
    font-size: 1.3rem;
}

.advantage-content p {
    color: #666;
    line-height: 1.6;
    margin: 0;
}

.team-section {
    padding: 80px 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.team-member {
    text-align: center;
    padding: 2rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
    margin-bottom: 2rem;
}

.team-member:hover {
    transform: translateY(-5px);
}

.team-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    margin: 0 auto 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: white;
    font-weight: 700;
}

.team-name {
    font-size: 1.3rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 0.5rem;
}

.team-role {
    color: #667eea;
    font-weight: 500;
    margin-bottom: 1rem;
}

.team-description {
    color: #666;
    line-height: 1.5;
    font-size: 0.9rem;
}

.mission-section {
    padding: 80px 0;
    background: white;
    text-align: center;
}

.mission-content {
    max-width: 800px;
    margin: 0 auto;
}

.mission-text {
    font-size: 1.2rem;
    line-height: 1.8;
    color: #555;
    margin-bottom: 2rem;
    font-style: italic;
}

.values-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-top: 3rem;
}

.value-item {
    padding: 2rem;
    background: #f8f9fa;
    border-radius: 15px;
    text-align: center;
    transition: transform 0.3s ease;
}

.value-item:hover {
    transform: translateY(-5px);
    background: white;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.value-icon {
    font-size: 2.5rem;
    color: #667eea;
    margin-bottom: 1rem;
}

.value-title {
    font-size: 1.2rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 1rem;
}

.value-description {
    color: #666;
    line-height: 1.5;
    font-size: 0.9rem;
}

.cta-section {
    padding: 80px 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-align: center;
}

.cta-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
}

.cta-text {
    font-size: 1.2rem;
    margin-bottom: 2rem;
    opacity: 0.95;
}

.cta-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.cta-btn {
    padding: 15px 30px;
    font-size: 1.1rem;
    font-weight: 600;
    border-radius: 10px;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.cta-btn-primary {
    background: white;
    color: #667eea;
    border: 2px solid white;
}

.cta-btn-primary:hover {
    background: transparent;
    color: white;
    border-color: white;
    transform: translateY(-2px);
}

.cta-btn-secondary {
    background: transparent;
    color: white;
    border: 2px solid white;
}

.cta-btn-secondary:hover {
    background: white;
    color: #667eea;
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .about-hero h1 {
        font-size: 2.5rem;
    }
    
    .about-hero .lead {
        font-size: 1.1rem;
    }
    
    .advantage-item {
        flex-direction: column;
        text-align: center;
    }
    
    .advantage-icon {
        margin-right: 0;
        margin-bottom: 1rem;
    }
    
    .cta-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .cta-btn {
        width: 100%;
        max-width: 300px;
        justify-content: center;
    }
}
</style>

<!-- Героическая секция -->
<section class="about-hero">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1>О системе ClaimJury</h1>
                <p class="lead">Современная платформа для управления исками и правовыми процессами. Мы помогаем юристам и юридическим компаниям эффективно вести дела и добиваться успешных результатов.</p>
            </div>
        </div>
    </div>
</section>

<!-- Секция статистики -->
<section class="stats-section">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="display-4 font-weight-bold text-dark mb-3">Наши достижения</h2>
                <p class="lead text-muted">Цифры, которые говорят сами за себя</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <span class="stat-number">95%</span>
                    <div class="stat-label">Успешных дел</div>
                    <div class="stat-description">Более 95% дел, ведущихся через нашу систему, завершаются успешно</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <span class="stat-number">500+</span>
                    <div class="stat-label">Активных пользователей</div>
                    <div class="stat-description">Более 500 юристов и юридических компаний доверяют нам</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <span class="stat-number">3+</span>
                    <div class="stat-label">Года опыта</div>
                    <div class="stat-description">Непрерывное развитие и совершенствование системы</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <span class="stat-number">24/7</span>
                    <div class="stat-label">Техподдержка</div>
                    <div class="stat-description">Круглосуточная поддержка наших клиентов</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Секция преимуществ -->
<section class="advantages-section">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="display-4 font-weight-bold text-dark mb-3">Почему выбирают ClaimJury</h2>
                <p class="lead text-muted">Преимущества, которые делают нашу систему незаменимой</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="advantage-item">
                    <div class="advantage-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="advantage-content">
                        <h4>Безопасность данных</h4>
                        <p>Все данные защищены современными методами шифрования. Мы гарантируем конфиденциальность и безопасность информации наших клиентов.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="advantage-item">
                    <div class="advantage-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="advantage-content">
                        <h4>Экономия времени</h4>
                        <p>Автоматизация рутинных процессов позволяет экономить до 70% времени на ведении дел и документообороте.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="advantage-item">
                    <div class="advantage-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="advantage-content">
                        <h4>Аналитика и отчеты</h4>
                        <p>Детальная аналитика по всем делам, автоматическое формирование отчетов и отслеживание прогресса.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="advantage-item">
                    <div class="advantage-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="advantage-content">
                        <h4>Командная работа</h4>
                        <p>Возможность совместной работы над делами, распределение задач между членами команды и контроль выполнения.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Секция команды -->
<section class="team-section">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="display-4 font-weight-bold text-dark mb-3">Наша команда</h2>
                <p class="lead text-muted">Профессионалы, которые создают будущее юридических технологий</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="team-member">
                    <div class="team-avatar">АИ</div>
                    <h4 class="team-name">Александр Иванов</h4>
                    <div class="team-role">Главный разработчик</div>
                    <p class="team-description">Опыт разработки более 8 лет. Специализируется на создании высоконагруженных систем и архитектуре приложений.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="team-member">
                    <div class="team-avatar">МП</div>
                    <h4 class="team-name">Мария Петрова</h4>
                    <div class="team-role">UX/UI дизайнер</div>
                    <p class="team-description">Создает интуитивно понятные интерфейсы. Опыт работы с юридическими системами более 5 лет.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="team-member">
                    <div class="team-avatar">ДС</div>
                    <h4 class="team-name">Дмитрий Сидоров</h4>
                    <div class="team-role">Юрист-консультант</div>
                    <p class="team-description">Опытный юрист с 10-летним стажем. Помогает адаптировать систему под потребности юридической практики.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Секция миссии -->
<section class="mission-section">
    <div class="container">
        <div class="mission-content">
            <h2 class="display-4 font-weight-bold text-dark mb-4">Наша миссия</h2>
            <p class="mission-text">
                "Мы верим, что технологии должны служить правосудию. Наша миссия — создать инструменты, 
                которые помогут юристам эффективнее защищать права граждан и бизнеса, делая правовую систему 
                более доступной и справедливой для всех."
            </p>
            
            <div class="values-grid">
                <div class="value-item">
                    <div class="value-icon">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                    <h4 class="value-title">Справедливость</h4>
                    <p class="value-description">Мы стремимся к созданию справедливой правовой системы</p>
                </div>
                <div class="value-item">
                    <div class="value-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h4 class="value-title">Инновации</h4>
                    <p class="value-description">Постоянно внедряем новые технологии для улучшения работы</p>
                </div>
                <div class="value-item">
                    <div class="value-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h4 class="value-title">Партнерство</h4>
                    <p class="value-description">Строим долгосрочные отношения с нашими клиентами</p>
                </div>
                <div class="value-item">
                    <div class="value-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h4 class="value-title">Качество</h4>
                    <p class="value-description">Обеспечиваем высочайшее качество наших решений</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Призыв к действию -->
<section class="cta-section">
    <div class="container">
        <h2 class="cta-title">Готовы начать работу с ClaimJury?</h2>
        <p class="cta-text">Присоединяйтесь к сотням юристов, которые уже используют нашу систему для эффективного ведения дел</p>
        <div class="cta-buttons">
            <?= Html::a('<i class="fas fa-rocket"></i> Начать работу', ['/auth/signup'], ['class' => 'cta-btn cta-btn-primary']) ?>
            <?= Html::a('<i class="fas fa-phone"></i> Связаться с нами', ['/site/contact'], ['class' => 'cta-btn cta-btn-secondary']) ?>
        </div>
    </div>
</section>
