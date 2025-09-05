<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Profile;


/* @var $this yii\web\View */
/* @var $model app\models\Profile */

$this->title = 'Редактирование профиля';
$this->params['breadcrumbs'][] = ['label' => 'Мой профиль', 'url' => ['view']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="profile-update">
    <div class="row">
        <div class="col-lg-8">
            <div class="profile-form-card">
                <h2>Редактирование профиля</h2>
                
                <?php $form = ActiveForm::begin([
                    'id' => 'profile-form',
                    'options' => ['enctype' => 'multipart/form-data'],
                    'fieldConfig' => [
                        'template' => "{label}\n<div class=\"form-group\">{input}</div>\n<div class=\"help-block\">{error}</div>",
                        'labelOptions' => ['class' => 'control-label'],
                    ],
                ]); ?>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'first_name')->textInput(['maxlength' => true, 'placeholder' => 'Введите имя']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'last_name')->textInput(['maxlength' => true, 'placeholder' => 'Введите фамилию']) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'middle_name')->textInput(['maxlength' => true, 'placeholder' => 'Введите отчество']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'placeholder' => '+7 (999) 123-45-67']) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'birth_date')->input('date') ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'gender')->dropDownList(Profile::getGenderOptions(), ['prompt' => 'Выберите пол']) ?>
                    </div>
                </div>

                <?= $form->field($model, 'address')->textarea(['rows' => 3, 'placeholder' => 'Введите полный адрес']) ?>

                <div class="row">
                    <div class="col-md-4">
                        <?= $form->field($model, 'city')->textInput(['maxlength' => true, 'placeholder' => 'Город']) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'country')->textInput(['maxlength' => true, 'placeholder' => 'Страна']) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'postal_code')->textInput(['maxlength' => true, 'placeholder' => 'Почтовый индекс']) ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">Аватар</label>
                    <div class="avatar-upload">
                        <div class="current-avatar">
                            <img src="<?= $model->getAvatarUrl() ?>" alt="Текущий аватар" class="avatar-preview">
                            <?php if ($model->avatar): ?>
                                <?= Html::a('Удалить аватар', ['delete-avatar'], [
                                    'class' => 'btn btn-sm btn-danger',
                                    'data' => [
                                        'confirm' => 'Вы уверены, что хотите удалить аватар?',
                                        'method' => 'post',
                                    ]
                                ]) ?>
                            <?php endif; ?>
                        </div>
                        <div class="avatar-upload-input">
                            <?= $form->field($model, 'avatar')->fileInput(['accept' => 'image/*'])->label(false) ?>
                            <small class="form-text text-muted">Рекомендуемый размер: 200x200 пикселей. Поддерживаемые форматы: JPG, PNG, GIF</small>
                        </div>
                    </div>
                </div>

                <?= $form->field($model, 'bio')->textarea(['rows' => 4, 'placeholder' => 'Расскажите о себе...']) ?>

                <h4>Социальные сети</h4>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'website')->textInput(['maxlength' => true, 'placeholder' => 'https://example.com']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'linkedin')->textInput(['maxlength' => true, 'placeholder' => 'https://linkedin.com/in/username']) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'twitter')->textInput(['maxlength' => true, 'placeholder' => 'https://twitter.com/username']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'facebook')->textInput(['maxlength' => true, 'placeholder' => 'https://facebook.com/username']) ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="profile-actions">
                        <?= Html::submitButton('Сохранить изменения', ['class' => 'btn btn-primary btn-lg']) ?>
                        <?= Html::a('Отмена', ['view'], ['class' => 'btn btn-secondary btn-lg']) ?>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="profile-tips">
                <h4>Советы по заполнению профиля</h4>
                <ul>
                    <li>Добавьте настоящее фото для лучшего восприятия</li>
                    <li>Заполните информацию о себе - это поможет другим пользователям</li>
                    <li>Укажите актуальные контактные данные</li>
                    <li>Добавьте ссылки на ваши социальные сети</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
.profile-update {
    padding: 20px 0;
}

.profile-form-card {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.profile-form-card h2 {
    margin-bottom: 30px;
    color: #333;
    border-bottom: 2px solid #f8f9fa;
    padding-bottom: 15px;
}

.avatar-upload {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    margin-bottom: 20px;
}

.current-avatar {
    text-align: center;
}

.avatar-preview {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #f8f9fa;
    margin-bottom: 10px;
}

.avatar-upload-input {
    flex: 1;
}

.profile-actions {
    display: flex;
    gap: 15px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e9ecef;
}

.profile-tips {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 25px;
    border-left: 4px solid #667eea;
}

.profile-tips h4 {
    color: #333;
    margin-bottom: 15px;
}

.profile-tips ul {
    margin: 0;
    padding-left: 20px;
}

.profile-tips li {
    margin-bottom: 10px;
    color: #555;
    line-height: 1.5;
}

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    font-weight: 600;
    color: #555;
    margin-bottom: 8px;
}

.form-control {
    border: 2px solid #e1e5e9;
    border-radius: 8px;
    padding: 12px 15px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.help-block {
    color: #dc3545;
    font-size: 12px;
    margin-top: 5px;
}

.btn {
    border-radius: 8px;
    padding: 12px 25px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

@media (max-width: 768px) {
    .profile-form-card {
        padding: 20px;
    }
    
    .avatar-upload {
        flex-direction: column;
        align-items: center;
    }
    
    .profile-actions {
        flex-direction: column;
    }
}
</style>
