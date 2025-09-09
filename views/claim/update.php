<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Claim */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Редактировать претензию по покупке: ' . ($model->purchase ? $model->purchase->product_name : 'Не указана');
$this->params['breadcrumbs'][] = ['label' => 'Панель управления', 'url' => ['/purchases']];
$this->params['breadcrumbs'][] = ['label' => 'Претензии', 'url' => ['/claim/index']];
$this->params['breadcrumbs'][] = ['label' => 'Претензия по покупке: ' . ($model->purchase ? $model->purchase->product_name : 'Не указана'), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>

<div class="claim-update">
    <div class="row">
        <div class="col-lg-8">
            <div class="claim-form-card">
                <div class="card-header">
                    <h2>
                        <i class="fas fa-edit"></i>
                        Редактировать претензию
                    </h2>
                </div>
                
                <div class="card-body">
                    <?php $form = ActiveForm::begin(); ?>

                    <div class="form-group">
                        <?= $form->field($model, 'purchase_id')->dropDownList(
                            \app\models\Purchase::find()
                                ->where(['user_id' => Yii::$app->user->id])
                                ->select(['product_name'])
                                ->indexBy('id')
                                ->column(),
                            [
                                'class' => 'form-control',
                                'prompt' => 'Выберите покупку...'
                            ]
                        )->label('Покупка') ?>
                    </div>

                    <div class="form-group">
                        <?= $form->field($model, 'claim_type')->dropDownList([
                            $model::TYPE_REPAIR => 'Ремонт',
                            $model::TYPE_REFUND => 'Возврат денежных средств',
                            $model::TYPE_REPLACEMENT => 'Замена товара на аналогичный товар',
                        ], [
                            'class' => 'form-control',
                            'prompt' => 'Выберите тип претензии...'
                        ])->label('Тип претензии') ?>
                    </div>

                    <div class="form-group">
                        <?= $form->field($model, 'status')->dropDownList([
                            $model::STATUS_PENDING => 'Ожидает рассмотрения',
                            $model::STATUS_IN_PROGRESS => 'В процессе',
                            $model::STATUS_RESOLVED => 'Решена',
                            $model::STATUS_REJECTED => 'Отклонена',
                            $model::STATUS_CLOSED => 'Закрыта',
                        ], [
                            'class' => 'form-control'
                        ])->label('Статус') ?>
                    </div>

                    <div class="form-group">
                        <?= $form->field($model, 'description')->textarea(['rows' => 4, 'placeholder' => 'Опишите подробности претензии...'])->label('Описание претензии') ?>
                    </div>


                    <div class="form-group">
                        <?= $form->field($model, 'amount_resolved')->textInput(['type' => 'number', 'step' => '0.01', 'placeholder' => '0.00'])->label('Сумма решения (руб.)') ?>
                    </div>

                    <div class="form-group">
                        <?= $form->field($model, 'resolution_notes')->textarea(['rows' => 3, 'placeholder' => 'Примечания по решению...'])->label('Примечания по решению') ?>
                    </div>

                    <div class="form-group">
                        <?= $form->field($model, 'resolution_date')->textInput(['type' => 'date'])->label('Дата решения') ?>
                    </div>

                    <div class="form-group">
                        <?= $form->field($model, 'tracking_number')->textInput(['maxlength' => true, 'placeholder' => 'Введите трек-номер'])->label('Трек-номер отправления') ?>
                    </div>

                    <div class="form-group">
                        <?= $form->field($model, 'document_sent_date')->textInput(['type' => 'datetime-local'])->label('Дата отправки документов') ?>
                    </div>

                    <div class="form-group">
                        <?= $form->field($model, 'document_received_date')->textInput(['type' => 'datetime-local'])->label('Дата получения документов') ?>
                    </div>

                    <div class="form-actions">
                        <?= Html::submitButton('<i class="fas fa-save"></i> Сохранить изменения', ['class' => 'btn btn-success']) ?>
                        <?= Html::a('<i class="fas fa-eye"></i> Просмотр', ['view', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
                        <?= Html::a('<i class="fas fa-times"></i> Отмена', ['index'], ['class' => 'btn btn-secondary']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="help-card">
                <div class="card-header">
                    <h3>
                        <i class="fas fa-info-circle"></i>
                        Справка
                    </h3>
                </div>
                <div class="card-body">
                    <h5>Статусы претензий:</h5>
                    <ul>
                        <li><span class="badge badge-warning">Ожидает рассмотрения</span> - новая претензия</li>
                        <li><span class="badge badge-info">В процессе</span> - претензия рассматривается</li>
                        <li><span class="badge badge-success">Решена</span> - претензия удовлетворена</li>
                        <li><span class="badge badge-danger">Отклонена</span> - претензия отклонена</li>
                        <li><span class="badge badge-secondary">Закрыта</span> - претензия закрыта</li>
                    </ul>
                    
                    <h5>Правила редактирования:</h5>
                    <ul>
                        <li>Можно редактировать только претензии со статусом "Ожидает рассмотрения" или "В процессе"</li>
                        <li>При изменении статуса на "Решена" или "Отклонена" рекомендуется указать дату решения</li>
                        <li>Сумма решения указывается только при положительном решении</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.claim-form-card,
.help-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    overflow: hidden;
}

.card-header {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
    padding: 20px 30px;
    margin: 0;
}

.card-header h2,
.card-header h3 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-body {
    padding: 30px;
}

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
    display: block;
}

.form-control {
    border: 2px solid #e1e5e9;
    border-radius: 10px;
    padding: 12px 15px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #f59e0b;
    box-shadow: 0 0 0 0.2rem rgba(245, 158, 11, 0.25);
}

.form-actions {
    display: flex;
    gap: 15px;
    align-items: center;
    margin-top: 30px;
    flex-wrap: wrap;
}

.btn {
    padding: 12px 24px;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-success {
    background: linear-gradient(135deg, #10B981, #059669);
    color: white;
}

.btn-success:hover {
    background: linear-gradient(135deg, #059669, #047857);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
}

.btn-info {
    background: linear-gradient(135deg, #3B82F6, #1D4ED8);
    color: white;
}

.btn-info:hover {
    background: linear-gradient(135deg, #1D4ED8, #1E40AF);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
    color: white;
    text-decoration: none;
}

.btn-secondary {
    background: linear-gradient(135deg, #6B7280, #4B5563);
    color: white;
}

.btn-secondary:hover {
    background: linear-gradient(135deg, #4B5563, #374151);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(107, 114, 128, 0.3);
    color: white;
    text-decoration: none;
}

.help-card .card-body h5 {
    color: #333;
    font-weight: 600;
    margin-top: 20px;
    margin-bottom: 10px;
}

.help-card .card-body h5:first-child {
    margin-top: 0;
}

.help-card .card-body ul {
    margin: 15px 0;
    padding-left: 20px;
}

.help-card .card-body li {
    margin-bottom: 8px;
    color: #666;
}

.badge {
    font-size: 0.8rem;
    padding: 4px 8px;
    border-radius: 15px;
    font-weight: 500;
}

.badge-warning { background: linear-gradient(135deg, #F59E0B, #D97706); color: white; }
.badge-info { background: linear-gradient(135deg, #3B82F6, #1D4ED8); color: white; }
.badge-success { background: linear-gradient(135deg, #10B981, #059669); color: white; }
.badge-danger { background: linear-gradient(135deg, #EF4444, #DC2626); color: white; }
.badge-secondary { background: linear-gradient(135deg, #6B7280, #4B5563); color: white; }
</style>
