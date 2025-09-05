<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\Profile;

/* @var $this yii\web\View */
/* @var $model app\models\Profile */

$this->title = 'Мой профиль';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="profile-view">
    <div class="row">
        <div class="col-lg-4">
            <div class="profile-card">
                <div class="profile-avatar">
                    <img src="<?= $model->getAvatarUrl() ?>" alt="Аватар" class="avatar-img">
                    <div class="avatar-overlay">
                        <?= Html::a('<i class="fas fa-camera"></i>', ['update'], ['class' => 'btn btn-sm btn-primary avatar-edit-btn']) ?>
                    </div>
                </div>
                
                <div class="profile-info">
                    <h2 class="profile-name"><?= Html::encode($model->getFullName()) ?></h2>
                    <p class="profile-username">@<?= Html::encode($model->user->username) ?></p>
                    
                    <?php if ($model->bio): ?>
                        <p class="profile-bio"><?= Html::encode($model->bio) ?></p>
                    <?php endif; ?>
                </div>

                <div class="profile-actions">
                    <?= Html::a('Редактировать профиль', ['update'], ['class' => 'btn btn-primary btn-block']) ?>
                </div>
            </div>

            <?php if ($model->website || $model->linkedin || $model->twitter || $model->facebook): ?>
                <div class="profile-social">
                    <h4>Социальные сети</h4>
                    <div class="social-links">
                        <?php if ($model->website): ?>
                            <a href="<?= Html::encode($model->website) ?>" target="_blank" class="social-link">
                                <i class="fas fa-globe"></i> Веб-сайт
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($model->linkedin): ?>
                            <a href="<?= Html::encode($model->linkedin) ?>" target="_blank" class="social-link">
                                <i class="fab fa-linkedin"></i> LinkedIn
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($model->twitter): ?>
                            <a href="<?= Html::encode($model->twitter) ?>" target="_blank" class="social-link">
                                <i class="fab fa-twitter"></i> Twitter
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($model->facebook): ?>
                            <a href="<?= Html::encode($model->facebook) ?>" target="_blank" class="social-link">
                                <i class="fab fa-facebook"></i> Facebook
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-8">
            <div class="profile-details">
                <h3>Личная информация</h3>
                
                <div class="detail-grid">
                    <?php if ($model->first_name || $model->last_name): ?>
                        <div class="detail-item">
                            <label>Полное имя:</label>
                            <span><?= Html::encode($model->getFullName()) ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="detail-item">
                        <label>Email:</label>
                        <span><?= Html::encode($model->user->email) ?></span>
                    </div>

                    <?php if ($model->phone): ?>
                        <div class="detail-item">
                            <label>Телефон:</label>
                            <span><?= Html::encode($model->phone) ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($model->birth_date): ?>
                        <div class="detail-item">
                            <label>Дата рождения:</label>
                            <span><?= Html::encode($model->birth_date) ?></span>
                            <?php if ($model->getAge()): ?>
                                <small class="text-muted">(<?= $model->getAge() ?> лет)</small>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($model->gender): ?>
                        <div class="detail-item">
                            <label>Пол:</label>
                            <span><?= Html::encode(Profile::getGenderOptions()[$model->gender] ?? $model->gender) ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($model->address): ?>
                        <div class="detail-item">
                            <label>Адрес:</label>
                            <span><?= Html::encode($model->address) ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($model->city): ?>
                        <div class="detail-item">
                            <label>Город:</label>
                            <span><?= Html::encode($model->city) ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($model->country): ?>
                        <div class="detail-item">
                            <label>Страна:</label>
                            <span><?= Html::encode($model->country) ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="detail-item">
                        <label>Дата регистрации:</label>
                        <span><?= Yii::$app->formatter->asDate($model->user->created_at) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.profile-view {
    padding: 20px 0;
}

.profile-card {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    text-align: center;
    margin-bottom: 30px;
}

.profile-avatar {
    position: relative;
    display: inline-block;
    margin-bottom: 20px;
}

.avatar-img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #f8f9fa;
    transition: all 0.3s ease;
}

.avatar-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.profile-avatar:hover .avatar-overlay {
    opacity: 1;
}

.avatar-edit-btn {
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.profile-name {
    font-size: 1.8rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 5px;
}

.profile-username {
    color: #666;
    font-size: 1rem;
    margin-bottom: 15px;
}

.profile-bio {
    color: #555;
    font-style: italic;
    margin-bottom: 20px;
}

.profile-actions {
    margin-top: 20px;
}

.profile-social {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.profile-social h4 {
    margin-bottom: 15px;
    color: #333;
}

.social-links {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.social-link {
    display: flex;
    align-items: center;
    padding: 10px;
    border-radius: 8px;
    text-decoration: none;
    color: #666;
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
}

.social-link:hover {
    background: #f8f9fa;
    color: #333;
    text-decoration: none;
}

.social-link i {
    margin-right: 10px;
    width: 20px;
}

.profile-details {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.profile-details h3 {
    margin-bottom: 25px;
    color: #333;
    border-bottom: 2px solid #f8f9fa;
    padding-bottom: 10px;
}

.detail-grid {
    display: grid;
    gap: 20px;
}

.detail-item {
    display: flex;
    flex-direction: column;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #667eea;
}

.detail-item label {
    font-weight: 600;
    color: #555;
    margin-bottom: 5px;
    font-size: 0.9rem;
}

.detail-item span {
    color: #333;
    font-size: 1rem;
}

@media (max-width: 768px) {
    .profile-card {
        padding: 20px;
    }
    
    .profile-details {
        padding: 20px;
    }
    
    .detail-item {
        padding: 10px;
    }
}
</style>
