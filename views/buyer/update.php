<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Buyer */

$this->title = 'Редактировать покупателя: ' . $model->getFullName();
$this->params['breadcrumbs'][] = ['label' => 'Главная', 'url' => ['/purchases']];
$this->params['breadcrumbs'][] = ['label' => 'Покупатели', 'url' => ['/buyer/index']];
$this->params['breadcrumbs'][] = ['label' => $model->getFullName(), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>

<div class="buyer-update">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <div class="header-top">
                            <?= Html::a('<i class="fas fa-arrow-left"></i> Назад к просмотру', ['view', 'id' => $model->id], [
                                'class' => 'btn btn-outline-light btn-sm back-btn'
                            ]) ?>
                        </div>
                        <h1 class="card-title">
                            <i class="fas fa-user-edit"></i> <?= Html::encode($this->title) ?>
                        </h1>
                    </div>
                    
                    <div class="card-body">
                        <?= $this->render('_form', [
                            'model' => $model,
                        ]) ?>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-info-circle"></i> Справка</h5>
                    </div>
                    <div class="card-body">
                        <div class="help-content">
                            <div class="help-item">
                                <h6>Имя и Фамилия</h6>
                                <p>Обязательные поля для идентификации покупателя.</p>
                            </div>
                            
                            <div class="help-item">
                                <h6>Адрес</h6>
                                <p>Место проживания покупателя для контактной информации.</p>
                            </div>
                            
                            <div class="help-item">
                                <h6>Дата рождения</h6>
                                <p>Поможет в анализе возрастных групп покупателей.</p>
                            </div>
                            
                            <div class="help-item">
                                <h6>Паспорт</h6>
                                <p>Документ для идентификации покупателя.</p>
                            </div>
                            
                            <div class="help-item">
                                <h6>Фотография паспорта</h6>
                                <p>Фотография паспорта покупателя для документооборота и идентификации.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.buyer-update .card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-bottom: none;
}

.buyer-update .header-top {
    margin-bottom: 15px;
}

.buyer-update .back-btn {
    background: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    transition: all 0.3s ease;
}

.buyer-update .back-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    border-color: rgba(255, 255, 255, 0.5);
    color: white;
    transform: translateY(-2px);
}

.buyer-update .card-title {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
}

.buyer-update .help-content {
    padding: 0;
}

.buyer-update .help-item {
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #e9ecef;
}

.buyer-update .help-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.buyer-update .help-item h6 {
    color: #495057;
    font-weight: 600;
    margin-bottom: 8px;
}

.buyer-update .help-item p {
    color: #6c757d;
    font-size: 0.9rem;
    margin: 0;
    line-height: 1.4;
}
</style>
