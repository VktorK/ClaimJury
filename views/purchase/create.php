<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Purchase;

/* @var $this yii\web\View */
/* @var $model app\models\Purchase */

$this->title = 'Добавить покупку';
$this->params['breadcrumbs'][] = ['label' => 'Панель управления', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="purchase-create">
    <div class="row">
        <div class="col-lg-8">
            <div class="create-card">
                <div class="card-header">
                    <h2>
                        <i class="fas fa-plus-circle"></i>
                        Добавить новую покупку
                    </h2>
                    <p>Заполните информацию о вашей покупке</p>
                </div>
                
                <div class="card-body">
                    <?php $form = ActiveForm::begin([
                        'options' => ['enctype' => 'multipart/form-data'],
                        'fieldConfig' => [
                            'template' => '<div class="form-group">{label}{input}{error}</div>',
                            'labelOptions' => ['class' => 'form-label'],
                            'inputOptions' => ['class' => 'form-control'],
                        ],
                    ]); ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Товар</label>
                                <div class="product-selection">
                                    <div class="product-actions">
                                        <button type="button" class="btn btn-primary" id="add-product-btn">
                                            <i class="fas fa-plus"></i> Добавить товар
                                        </button>
                                    </div>
                                    <div id="selected-product-info" class="selected-product-info" style="display: none;">
                                        <div class="selected-product-card">
                                            <div class="product-preview">
                                                <img id="selected-product-image" src="" alt="" class="product-thumbnail">
                                                <div class="product-details">
                                                    <h5 id="selected-product-title"></h5>
                                                    <p id="selected-product-description"></p>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" id="change-product-btn">
                                                <i class="fas fa-edit"></i> Изменить
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Продавец</label>
                                <div class="seller-selection">
                                    <?= $form->field($model, 'seller_id')->dropDownList(
                                        Purchase::getSellersDropdown(Yii::$app->user->id),
                                        [
                                            'prompt' => 'Выберите продавца',
                                            'id' => 'seller-dropdown',
                                            'class' => 'form-control'
                                        ]
                                    )->label(false) ?>
                                    
                                    <div class="seller-actions mt-2">
                                        <button type="button" class="btn btn-primary" id="add-seller-btn">
                                            <i class="fas fa-plus"></i> Добавить нового продавца
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Покупатель</label>
                                <div class="buyer-selection">
                                    <?= $form->field($model, 'buyer_id')->dropDownList(
                                        Purchase::getBuyersDropdown(),
                                        [
                                            'prompt' => 'Выберите покупателя',
                                            'id' => 'buyer-dropdown',
                                            'class' => 'form-control'
                                        ]
                                    )->label(false) ?>
                                    
                                    <div class="buyer-actions mt-2">
                                        <button type="button" class="btn btn-primary" id="add-buyer-btn">
                                            <i class="fas fa-plus"></i> Добавить нового покупателя
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <?= $form->field($model, 'product_id')->hiddenInput(['id' => 'product-id-input'])->label(false) ?>
                    <input type="hidden" id="purchase-id-input" name="purchase_id" value="">
                    
                    <div class="row">
                        <div class="col-md-3">
                            <?= $form->field($model, 'purchase_date')->input('date') ?>
                        </div>
                        <div class="col-md-3">
                            <?= $form->field($model, 'amount')->input('number', [
                                'step' => '0.01',
                                'min' => '0',
                                'placeholder' => '0.00'
                            ]) ?>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Валюта</label>
                                <div class="currency-display">
                                    <span class="currency-symbol">₽</span>
                                </div>
                                <?= $form->field($model, 'currency')->hiddenInput(['value' => 'RUB'])->label(false) ?>
                            </div>
                        </div>
                    </div>

                    <?= $form->field($model, 'description')->textarea([
                        'rows' => 4,
                        'placeholder' => 'Дополнительная информация о покупке (необязательно)'
                    ]) ?>

                    <div class="form-group">
                        <label class="form-label">Чек (необязательно)</label>
                        <div class="file-upload-area">
                            <?= $form->field($model, 'receipt_image')->fileInput([
                                'accept' => 'image/*',
                                'class' => 'file-input'
                            ])->label(false) ?>
                            <div class="file-upload-text">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p>Перетащите файл сюда или нажмите для выбора</p>
                                <small>Поддерживаются форматы: JPG, PNG, PDF (макс. 5MB)</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <?= Html::submitButton('<i class="fas fa-save"></i> Сохранить покупку', [
                            'class' => 'btn btn-primary btn-lg'
                        ]) ?>
                        <?= Html::a('<i class="fas fa-times"></i> Отмена', ['index'], [
                            'class' => 'btn btn-secondary btn-lg'
                        ]) ?>
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
                    <div class="help-item">
                        <h4>Название товара</h4>
                        <p>Укажите точное название приобретенного товара или услуги.</p>
                    </div>
                    
                    <div class="help-item">
                        <h4>Продавец</h4>
                        <p>Название магазина, компании или физического лица, у которого была совершена покупка.</p>
                    </div>
                    
                    <div class="help-item">
                        <h4>Покупатель</h4>
                        <p>Физическое лицо, для которого совершается покупка. Обязательное поле для идентификации получателя товара.</p>
                    </div>
                    
                    <div class="help-item">
                        <h4>Дата покупки</h4>
                        <p>Дата, когда была совершена покупка. Это поможет в отслеживании и анализе.</p>
                    </div>
                    
                    <div class="help-item">
                        <h4>Сумма</h4>
                        <p>Стоимость покупки в указанной валюте. Указывайте точную сумму.</p>
                    </div>
                    
                    <div class="help-item">
                        <h4>Чек</h4>
                        <p>Прикрепите фотографию или скан чека для подтверждения покупки.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно для создания нового продавца -->
<div class="modal fade" id="sellerModal" tabindex="-1" aria-labelledby="sellerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sellerModalLabel">Добавить нового продавца</h5>
                <button type="button" class="btn-close" onclick="closeSellerModal()" aria-label="Закрыть">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="seller-form">
                    <div class="form-group mb-3">
                        <label class="form-label">Название продавца *</label>
                        <input type="text" class="form-control" id="seller-title" name="Seller[title]" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Адрес</label>
                        <textarea class="form-control" id="seller-address" name="Seller[address]" rows="3"></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">ОГРН</label>
                        <input type="text" class="form-control" id="seller-ogrn" name="Seller[ogrn]" maxlength="13">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Дата создания</label>
                        <input type="date" class="form-control" id="seller-date-creation" name="Seller[date_creation]">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeSellerModal()">Отмена</button>
                <button type="button" class="btn btn-primary" onclick="saveSeller()">
                    <i class="fas fa-save"></i> Сохранить
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно для создания нового покупателя -->
<div class="modal fade" id="buyerModal" tabindex="-1" aria-labelledby="buyerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="buyerModalLabel">Добавить нового покупателя</h5>
                <button type="button" class="btn-close" onclick="closeBuyerModal()" aria-label="Закрыть">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="buyer-form" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="buyer-lastName">Фамилия *</label>
                                <input type="text" class="form-control" id="buyer-lastName" name="Buyer[lastName]" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="buyer-firstName">Имя *</label>
                                <input type="text" class="form-control" id="buyer-firstName" name="Buyer[firstName]" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="buyer-middleName">Отчество *</label>
                                <input type="text" class="form-control" id="buyer-middleName" name="Buyer[middleName]" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="buyer-address">Адрес</label>
                        <textarea class="form-control" id="buyer-address" name="Buyer[address]" rows="3"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="buyer-birthday">Дата рождения</label>
                                <input type="date" class="form-control" id="buyer-birthday" name="Buyer[birthday]">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="buyer-passport">Паспорт</label>
                                <input type="text" class="form-control" id="buyer-passport" name="Buyer[passport]" placeholder="1234 567890">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="buyer-image">Фотография паспорта</label>
                        <input type="file" class="form-control" id="buyer-image" name="Buyer[image]" accept="image/*">
                        <small class="form-text text-muted">Поддерживаются форматы: JPG, PNG, GIF</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeBuyerModal()">Отмена</button>
                <button type="button" class="btn btn-primary" onclick="saveBuyer()">
                    <i class="fas fa-save"></i> Сохранить
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно для создания нового товара -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Добавить новый товар</h5>
                <button type="button" class="btn-close" onclick="closeProductModal()" aria-label="Закрыть">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="product-form" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group mb-3">
                                <label class="form-label">Название товара *</label>
                                <input type="text" class="form-control" id="product-title" name="Product[title]" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Категория *</label>
                                <select class="form-control" id="product-category" name="Product[category_id]" required>
                                    <option value="">Выберите категорию</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Описание</label>
                        <textarea class="form-control" id="product-description" name="Product[description]" rows="3"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Гарантийный срок (месяцы)</label>
                                <input type="number" class="form-control" id="product-warranty" name="Product[warranty_period]" min="0" placeholder="0">
                                <small class="form-text text-muted">Укажите гарантийный срок в месяцах</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3" id="serial-number-group" style="display: none;">
                                <label class="form-label">Серийный номер</label>
                                <input type="text" class="form-control" id="product-serial" name="Product[serial_number]" placeholder="Серийный номер">
                                <small class="form-text text-muted">Требуется для бытовой техники</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Изображение товара</label>
                        <input type="file" class="form-control" id="product-image" name="Product[image]" accept="image/*">
                        <small class="form-text text-muted">Поддерживаются форматы: JPG, PNG, GIF (макс. 5MB)</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeProductModal()">Отмена</button>
                <button type="button" class="btn btn-primary" onclick="saveProduct()">
                    <i class="fas fa-save"></i> Сохранить
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.purchase-create {
    padding: 20px 0;
}

.create-card,
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

.card-header h2,
.card-header h3 {
    margin: 0 0 10px 0;
    font-weight: 600;
}

.card-header h2 i,
.card-header h3 i {
    margin-right: 10px;
}

.card-header p {
    margin: 0;
    opacity: 0.9;
}

.card-body {
    padding: 30px;
}

.form-label {
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
    display: block;
}

.form-control {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 12px 15px;
    font-size: 1rem;
    height: 48px;
    box-sizing: border-box;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.file-upload-area {
    border: 2px dashed #e9ecef;
    border-radius: 8px;
    padding: 30px;
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
    cursor: pointer;
}

.file-upload-area:hover {
    border-color: #667eea;
    background: #f8f9fa;
}

.file-input {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

.file-upload-text i {
    font-size: 2rem;
    color: #667eea;
    margin-bottom: 10px;
}

.file-upload-text p {
    margin: 10px 0 5px 0;
    font-weight: 500;
    color: #333;
}

.file-upload-text small {
    color: #666;
}

.form-actions {
    margin-top: 30px;
    display: flex;
    gap: 15px;
    justify-content: center;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    padding: 12px 25px;
}

.btn i {
    margin-right: 8px;
}

.help-item {
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid #f8f9fa;
}

.help-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.help-item h4 {
    color: #667eea;
    font-size: 1rem;
    margin-bottom: 8px;
    font-weight: 600;
}

.help-item p {
    color: #666;
    margin: 0;
    font-size: 0.9rem;
    line-height: 1.5;
}

.seller-selection,
.product-selection {
    position: relative;
}

.seller-actions,
.product-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.seller-actions .btn,
.buyer-actions .btn,
.product-actions .btn {
    font-size: 0.85rem;
    padding: 6px 12px;
}

/* Стили для модального окна товара */
#productModal .form-group {
    margin-bottom: 1.5rem;
}

#productModal .row {
    margin-bottom: 0;
}

#productModal .col-md-6 {
    padding-left: 8px;
    padding-right: 8px;
}

#productModal .form-control {
    margin-bottom: 0.5rem;
}

#productModal .form-text {
    margin-top: 0.25rem;
    margin-bottom: 0;
}

/* Стили для поля файла */
#productModal input[type="file"] {
    margin-bottom: 0.5rem;
}

#productModal input[type="file"] + .form-text {
    margin-top: 0.5rem;
}

.selected-product-info {
    margin-top: 15px;
}

.selected-product-card {
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.product-preview {
    display: flex;
    align-items: center;
    gap: 15px;
    flex: 1;
}

.product-thumbnail {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid #e9ecef;
}

.product-details h5 {
    margin: 0 0 5px 0;
    color: #333;
    font-size: 1rem;
}

.product-details p {
    margin: 0;
    color: #666;
    font-size: 0.9rem;
    line-height: 1.3;
}

.currency-display {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 12px 15px;
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 1rem;
    height: 48px;
    box-sizing: border-box;
    transition: all 0.3s ease;
}

.currency-display:hover {
    border-color: #667eea;
    background: #f0f2ff;
}

.currency-symbol {
    font-size: 2.2rem;
    font-weight: bold;
    color: #28a745;
    text-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.currency-text {
    color: #495057;
    font-weight: 600;
    font-size: 0.95rem;
    white-space: nowrap;
    letter-spacing: 0.3px;
}

/* Исправление шрифтов в выпадающих списках */
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

/* Дополнительные стили для улучшения отображения */
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


.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1050;
    display: none;
}

.modal.show {
    display: block;
}

.modal-dialog {
    position: relative;
    width: auto;
    max-width: 500px;
    margin: 1.75rem auto;
    pointer-events: none;
}

.modal-content {
    position: relative;
    display: flex;
    flex-direction: column;
    width: 100%;
    pointer-events: auto;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid rgba(0,0,0,.2);
    border-radius: 0.3rem;
    outline: 0;
}

.modal-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    padding: 1rem 1rem;
    border-bottom: 1px solid #dee2e6;
    border-top-left-radius: calc(0.3rem - 1px);
    border-top-right-radius: calc(0.3rem - 1px);
}

.modal-title {
    margin-bottom: 0;
    line-height: 1.5;
}

.btn-close {
    padding: 0.25rem 0.25rem;
    margin: -0.25rem -0.25rem -0.25rem auto;
    background: transparent;
    border: 0;
    border-radius: 0.25rem;
    opacity: 0.5;
    cursor: pointer;
}

.btn-close:hover {
    opacity: 0.75;
}

.modal-body {
    position: relative;
    flex: 1 1 auto;
    padding: 1rem;
}

.modal-footer {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: flex-end;
    padding: 0.75rem;
    border-top: 1px solid #dee2e6;
    border-bottom-right-radius: calc(0.3rem - 1px);
    border-bottom-left-radius: calc(0.3rem - 1px);
}

.modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1040;
    width: 100vw;
    height: 100vh;
    background-color: #000;
}

.modal-backdrop.fade {
    opacity: 0;
}

.modal-backdrop.show {
    opacity: 0.5;
}

@media (max-width: 768px) {
    .form-actions {
        flex-direction: column;
    }
    
    .card-body {
        padding: 20px;
    }
    
    .file-upload-area {
        padding: 20px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.querySelector('.file-input');
    const fileUploadArea = document.querySelector('.file-upload-area');
    const fileUploadText = document.querySelector('.file-upload-text');
    
    fileUploadArea.addEventListener('click', function() {
        fileInput.click();
    });
    
    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            const fileName = this.files[0].name;
            fileUploadText.innerHTML = `
                <i class="fas fa-check-circle text-success"></i>
                <p>Выбран файл: ${fileName}</p>
                <small>Нажмите для выбора другого файла</small>
            `;
        }
    });
    
    // Drag and drop functionality
    fileUploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.style.borderColor = '#667eea';
        this.style.backgroundColor = '#f8f9fa';
    });
    
    fileUploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.style.borderColor = '#e9ecef';
        this.style.backgroundColor = 'transparent';
    });
    
    fileUploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.style.borderColor = '#e9ecef';
        this.style.backgroundColor = 'transparent';
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            const fileName = files[0].name;
            fileUploadText.innerHTML = `
                <i class="fas fa-check-circle text-success"></i>
                <p>Выбран файл: ${fileName}</p>
                <small>Нажмите для выбора другого файла</small>
            `;
        }
    });

    // Seller management
    const addSellerBtn = document.getElementById('add-seller-btn');
    const sellerDropdown = document.getElementById('seller-dropdown');

    addSellerBtn.addEventListener('click', function() {
        openSellerModal();
    });

    // Buyer management
    const addBuyerBtn = document.getElementById('add-buyer-btn');
    const buyerDropdown = document.getElementById('buyer-dropdown');

    addBuyerBtn.addEventListener('click', function() {
        openBuyerModal();
    });

    // Product management
    const addProductBtn = document.getElementById('add-product-btn');
    const changeProductBtn = document.getElementById('change-product-btn');
    const selectedProductInfo = document.getElementById('selected-product-info');

    addProductBtn.addEventListener('click', function() {
        openProductModal();
    });

    changeProductBtn.addEventListener('click', function() {
        openProductModal();
    });


    // Load categories for product modal
    loadCategories();
    
    // Handle category change for serial number field
    const productCategorySelect = document.getElementById('product-category');
    const serialNumberGroup = document.getElementById('serial-number-group');
    const productSerialInput = document.getElementById('product-serial');
    
    productCategorySelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const categoryName = selectedOption.text.toLowerCase();
        
        // Check if category contains "техника" or "бытовая"
        if (categoryName.includes('техника') || categoryName.includes('бытовая')) {
            serialNumberGroup.style.display = 'block';
            productSerialInput.required = false; // Необязательное поле
        } else {
            serialNumberGroup.style.display = 'none';
            productSerialInput.required = false;
            productSerialInput.value = ''; // Очищаем поле
        }
    });
});

// Seller modal functions
function openSellerModal() {
    const modal = document.getElementById('sellerModal');
    modal.style.display = 'block';
    modal.classList.add('show');
    document.body.classList.add('modal-open');
    
    // Add backdrop
    const existingBackdrop = document.getElementById('sellerModalBackdrop');
    if (existingBackdrop) {
        existingBackdrop.remove();
    }
    
    const backdrop = document.createElement('div');
    backdrop.className = 'modal-backdrop fade show';
    backdrop.id = 'sellerModalBackdrop';
    backdrop.style.position = 'fixed';
    backdrop.style.top = '0';
    backdrop.style.left = '0';
    backdrop.style.width = '100%';
    backdrop.style.height = '100%';
    backdrop.style.backgroundColor = 'rgba(0,0,0,0.5)';
    backdrop.style.zIndex = '1040';
    document.body.appendChild(backdrop);
    
    // Close on backdrop click
    backdrop.addEventListener('click', closeSellerModal);
}

function closeSellerModal() {
    const modal = document.getElementById('sellerModal');
    modal.style.display = 'none';
    modal.classList.remove('show');
    document.body.classList.remove('modal-open');
    
    // Remove backdrop
    const backdrop = document.getElementById('sellerModalBackdrop');
    if (backdrop) {
        backdrop.remove();
    }
    
    // Clear form
    document.getElementById('seller-form').reset();
}

function saveSeller() {
    const form = document.getElementById('seller-form');
    const formData = new FormData(form);
    
    // Добавляем purchases_id (будет установлен после создания покупки)
    const purchaseId = document.getElementById('purchase-id-input');
    if (purchaseId && purchaseId.value) {
        formData.append('purchases_id', purchaseId.value);
    }
    
    // Show loading
    const saveBtn = document.querySelector('#sellerModal .btn-primary');
    const originalText = saveBtn.innerHTML;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Сохранение...';
    saveBtn.disabled = true;
    
    fetch('/seller/create-ajax', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Add new seller to dropdown
            const dropdown = document.getElementById('seller-dropdown');
            const option = document.createElement('option');
            option.value = data.id;
            option.textContent = data.title;
            dropdown.appendChild(option);
            
            // Select the new seller
            dropdown.value = data.id;
            
            // Close modal
            closeSellerModal();
            
            // Show success message
            showNotification('Продавец успешно добавлен!', 'success');
        } else {
            showNotification('Ошибка при создании продавца: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Ошибка при создании продавца', 'error');
    })
    .finally(() => {
        // Restore button
        saveBtn.innerHTML = originalText;
        saveBtn.disabled = false;
    });
}

// Buyer modal functions
function openBuyerModal() {
    const modal = document.getElementById('buyerModal');
    modal.style.display = 'block';
    modal.classList.add('show');
    document.body.classList.add('modal-open');
    
    // Add backdrop
    const existingBackdrop = document.getElementById('buyerModalBackdrop');
    if (existingBackdrop) {
        existingBackdrop.remove();
    }
    
    const backdrop = document.createElement('div');
    backdrop.className = 'modal-backdrop fade show';
    backdrop.id = 'buyerModalBackdrop';
    backdrop.style.position = 'fixed';
    backdrop.style.top = '0';
    backdrop.style.left = '0';
    backdrop.style.width = '100%';
    backdrop.style.height = '100%';
    backdrop.style.backgroundColor = 'rgba(0,0,0,0.5)';
    backdrop.style.zIndex = '1040';
    document.body.appendChild(backdrop);
    
    // Close on backdrop click
    backdrop.addEventListener('click', closeBuyerModal);
}

function closeBuyerModal() {
    const modal = document.getElementById('buyerModal');
    modal.style.display = 'none';
    modal.classList.remove('show');
    document.body.classList.remove('modal-open');
    
    // Remove backdrop
    const backdrop = document.getElementById('buyerModalBackdrop');
    if (backdrop) {
        backdrop.remove();
    }
    
    // Clear form
    document.getElementById('buyer-form').reset();
}

function saveBuyer() {
    const form = document.getElementById('buyer-form');
    const formData = new FormData(form);
    
    // Добавляем CSRF-токен
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    formData.append('_csrf', csrfToken);
    
    // Добавляем purchases_id (будет установлен после создания покупки)
    const purchaseId = document.getElementById('purchase-id-input');
    if (purchaseId && purchaseId.value) {
        formData.append('purchases_id', purchaseId.value);
    }
    
    // Show loading
    const saveBtn = document.querySelector('#buyerModal .btn-primary');
    const originalText = saveBtn.innerHTML;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Сохранение...';
    saveBtn.disabled = true;
    
    fetch('/buyer/create-ajax', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Add new buyer to dropdown
            const dropdown = document.getElementById('buyer-dropdown');
            const option = document.createElement('option');
            option.value = data.id;
            option.textContent = data.fullName;
            dropdown.appendChild(option);
            
            // Select the new buyer
            dropdown.value = data.id;
            
            // Close modal
            closeBuyerModal();
            
            // Show success message
            showNotification('Покупатель успешно добавлен!', 'success');
        } else {
            showNotification('Ошибка при создании покупателя: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Ошибка при создании покупателя', 'error');
    })
    .finally(() => {
        // Restore button
        saveBtn.innerHTML = originalText;
        saveBtn.disabled = false;
    });
}

function showNotification(message, type) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.style.minWidth = '300px';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}

// Product modal functions
function openProductModal() {
    const modal = document.getElementById('productModal');
    modal.style.display = 'block';
    modal.classList.add('show');
    document.body.classList.add('modal-open');
    
    // Add backdrop
    const existingBackdrop = document.getElementById('productModalBackdrop');
    if (existingBackdrop) {
        existingBackdrop.remove();
    }
    
    const backdrop = document.createElement('div');
    backdrop.className = 'modal-backdrop fade show';
    backdrop.id = 'productModalBackdrop';
    backdrop.style.position = 'fixed';
    backdrop.style.top = '0';
    backdrop.style.left = '0';
    backdrop.style.width = '100%';
    backdrop.style.height = '100%';
    backdrop.style.backgroundColor = 'rgba(0,0,0,0.5)';
    backdrop.style.zIndex = '1040';
    document.body.appendChild(backdrop);
    
    // Close on backdrop click
    backdrop.addEventListener('click', closeProductModal);
}

function closeProductModal() {
    const modal = document.getElementById('productModal');
    modal.style.display = 'none';
    modal.classList.remove('show');
    document.body.classList.remove('modal-open');
    
    // Remove backdrop
    const backdrop = document.getElementById('productModalBackdrop');
    if (backdrop) {
        backdrop.remove();
    }
    
    // Clear form
    document.getElementById('product-form').reset();
}

function saveProduct() {
    const form = document.getElementById('product-form');
    const formData = new FormData(form);
    
    // Добавляем purchases_id (будет установлен после создания покупки)
    const purchaseId = document.getElementById('purchase-id-input');
    if (purchaseId && purchaseId.value) {
        formData.append('purchases_id', purchaseId.value);
    }
    
    // Show loading
    const saveBtn = document.querySelector('#productModal .btn-primary');
    const originalText = saveBtn.innerHTML;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Сохранение...';
    saveBtn.disabled = true;
    
    fetch('/product/create-ajax', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Set product_id in hidden input
            document.getElementById('product-id-input').value = data.id;
            
            // Show selected product info
            showSelectedProduct(data);
            
            // Close modal
            closeProductModal();
            
            // Show success message
            showNotification('Товар успешно добавлен!', 'success');
        } else {
            showNotification('Ошибка при создании товара: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Ошибка при создании товара', 'error');
    })
    .finally(() => {
        // Restore button
        saveBtn.innerHTML = originalText;
        saveBtn.disabled = false;
    });
}

function showSelectedProduct(productData) {
    const selectedProductInfo = document.getElementById('selected-product-info');
    const selectedProductImage = document.getElementById('selected-product-image');
    const selectedProductTitle = document.getElementById('selected-product-title');
    const selectedProductDescription = document.getElementById('selected-product-description');
    
    // Update product info
    selectedProductImage.src = productData.image || '/images/no-product.svg';
    selectedProductImage.alt = productData.title;
    selectedProductTitle.textContent = productData.title;
    
    // Build description with warranty info
    let description = productData.description || 'Описание не указано';
    if (productData.warranty_period) {
        const months = productData.warranty_period;
        const years = Math.floor(months / 12);
        const remainingMonths = months % 12;
        
        let warrantyText = 'Гарантия: ';
        const parts = [];
        if (years > 0) parts.push(years + ' ' + (years === 1 ? 'год' : years < 5 ? 'года' : 'лет'));
        if (remainingMonths > 0) parts.push(remainingMonths + ' ' + (remainingMonths === 1 ? 'месяц' : remainingMonths < 5 ? 'месяца' : 'месяцев'));
        
        warrantyText += parts.join(', ');
        description += '<br><small class="text-muted">' + warrantyText + '</small>';
    }
    
    selectedProductDescription.innerHTML = description;
    
    // Show the selected product info
    selectedProductInfo.style.display = 'block';
    
    // Hide the add button
    document.getElementById('add-product-btn').style.display = 'none';
}

function loadCategories() {
    fetch('/category/get-categories')
    .then(response => response.json())
    .then(data => {
        const categorySelect = document.getElementById('product-category');
        data.forEach(category => {
            const option = document.createElement('option');
            option.value = category.id;
            option.textContent = category.title;
            categorySelect.appendChild(option);
        });
    })
    .catch(error => {
        console.error('Error loading categories:', error);
    });
}


// Close modal on Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeSellerModal();
        closeBuyerModal();
        closeProductModal();
    }
});

// Устанавливаем ID покупки после её создания
// Это нужно для связывания товаров и продавцов с покупкой
document.addEventListener('DOMContentLoaded', function() {
    // Если мы находимся на странице редактирования покупки, устанавливаем ID
    const url = window.location.pathname;
    const match = url.match(/\/purchase\/update\/(\d+)/);
    if (match) {
        document.getElementById('purchase-id-input').value = match[1];
    }
});
</script>
