<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Seller */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Редактировать продавца: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Главная', 'url' => ['/purchases']];
$this->params['breadcrumbs'][] = ['label' => 'Продавцы', 'url' => ['/seller/index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>

<div class="seller-update">
    <div class="row">
        <div class="col-lg-8">
            <div class="seller-form-card">
                <div class="card-header">
                    <div class="seller-header">
                        <div class="seller-avatar">
                            <i class="fas fa-store"></i>
                        </div>
                        <div class="seller-info">
                            <h1 class="seller-title">Редактировать продавца</h1>
                            <p class="seller-subtitle">Обновите информацию о продавце</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <?php $form = ActiveForm::begin(); ?>

                    <div class="form-group">
                        <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'placeholder' => 'Название продавца']) ?>
                    </div>

                    <div class="form-group">
                        <?= $form->field($model, 'address')->textarea(['rows' => 3, 'placeholder' => 'Адрес продавца']) ?>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= $form->field($model, 'ogrn')->textInput(['maxlength' => true, 'placeholder' => 'ОГРН']) ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= $form->field($model, 'date_creation')->input('date') ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <?= Html::submitButton('<i class="fas fa-save"></i> Сохранить изменения', [
                            'class' => 'btn btn-success btn-lg'
                        ]) ?>
                        
                        <?= Html::a('<i class="fas fa-times"></i> Отмена', ['view', 'id' => $model->id], [
                            'class' => 'btn btn-secondary btn-lg ml-2'
                        ]) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="help-card">
                <div class="card-header">
                    <h3><i class="fas fa-info-circle"></i> Справка</h3>
                </div>
                <div class="card-body">
                    <div class="help-section">
                        <h4>Поля формы</h4>
                        <div class="help-item">
                            <h5>Название продавца</h5>
                            <p>Обязательное поле. Укажите полное название организации или ИП.</p>
                        </div>
                        
                        <div class="help-item">
                            <h5>Адрес</h5>
                            <p>Необязательное поле. Физический адрес продавца.</p>
                        </div>
                        
                        <div class="help-item">
                            <h5>ОГРН</h5>
                            <p>Необязательное поле. Основной государственный регистрационный номер.</p>
                        </div>
                        
                        <div class="help-item">
                            <h5>Дата создания</h5>
                            <p>Необязательное поле. Дата регистрации организации.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.seller-update {
    padding: 20px 0;
}

.seller-form-card,
.help-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    overflow: hidden;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 25px 30px;
}

.seller-header {
    display: flex;
    align-items: center;
    gap: 20px;
}

.seller-avatar {
    width: 80px;
    height: 80px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
}

.seller-info h1 {
    margin: 0 0 5px 0;
    font-size: 2rem;
    font-weight: 700;
}

.seller-info p {
    margin: 0;
    opacity: 0.9;
    font-size: 1.1rem;
}

.card-body {
    padding: 30px;
}

.form-group {
    margin-bottom: 25px;
}

.form-control {
    border-radius: 8px;
    border: 2px solid #e9ecef;
    padding: 12px 15px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
}

.form-actions {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e9ecef;
}

.btn {
    border-radius: 8px;
    padding: 12px 24px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
}

.btn-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    border: none;
}

.btn-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(108, 117, 125, 0.3);
}

.btn i {
    margin-right: 8px;
}

.help-card .card-header {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
}

.help-section h4 {
    color: #17a2b8;
    margin-bottom: 20px;
    font-size: 1.2rem;
}

.help-item {
    margin-bottom: 20px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #17a2b8;
}

.help-item h5 {
    color: #17a2b8;
    margin-bottom: 8px;
    font-size: 1rem;
    font-weight: 600;
}

.help-item p {
    margin: 0;
    color: #666;
    font-size: 0.9rem;
    line-height: 1.4;
}

.invalid-feedback {
    display: block;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875rem;
    color: #dc3545;
}

.is-invalid {
    border-color: #dc3545;
}

.is-invalid:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

@media (max-width: 768px) {
    .seller-header {
        flex-direction: column;
        text-align: center;
    }
    
    .seller-info h1 {
        font-size: 1.5rem;
    }
    
    .form-actions {
        text-align: center;
    }
    
    .btn {
        display: block;
        width: 100%;
        margin-bottom: 10px;
    }
    
    .btn.ml-2 {
        margin-left: 0 !important;
    }
}
</style>
