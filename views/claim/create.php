<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Claim */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Создать претензию';
$this->params['breadcrumbs'][] = ['label' => 'Главная', 'url' => ['/purchases']];
$this->params['breadcrumbs'][] = ['label' => 'Претензии', 'url' => ['/claim/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="claim-create">
    <div class="row">
        <div class="col-lg-8">
            <div class="claim-form-card">
                <div class="card-header">
                    <h2>
                        <i class="fas fa-plus"></i>
                        Создать новую претензию
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
                                'prompt' => 'Выберите покупку...',
                                'disabled' => $model->purchase_id ? true : false,
                                'id' => 'purchase-select'
                            ]
                        )->label('Покупка') ?>
                        
                        <!-- Скрытое поле для передачи ID покупки -->
                        <?= Html::hiddenInput('purchase_id', $model->purchase_id, ['id' => 'purchase_id']) ?>
                        <?php if ($model->purchase_id): ?>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> 
                                Покупка выбрана автоматически. 
                                <?= Html::a('Изменить покупку', ['create'], ['class' => 'text-primary']) ?>
                            </small>
                        <?php endif; ?>
                    </div>

                    <div class="form-group" id="claim-type-group" style="display: none;">
                        <?= $form->field($model, 'claim_type')->dropDownList($model::getClaimTypes(), [
                            'class' => 'form-control',
                            'prompt' => 'Выберите тип претензии...',
                            'id' => 'claim-type-select'
                        ])->label('Тип претензии') ?>
                    </div>

                    <div class="form-group" id="template-selector" style="display: none;">
                        <label class="control-label">Шаблон претензии</label>
                        <select class="form-control" id="template-select" onchange="loadTemplate()">
                            <option value="">Выберите шаблон...</option>
                        </select>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> 
                            Выберите подходящий шаблон для автоматического заполнения претензии
                        </small>
                    </div>

                    <div class="form-group" id="description-group" style="display: none;">
                        <label class="control-label">Образец претензии</label>
                        <div class="description-preview" id="description-preview">
                            <p class="text-muted">Описание не заполнено</p>
                        </div>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="openDescriptionModal()">
                                <i class="fas fa-edit"></i> Редактировать описание
                            </button>
                            <button type="button" class="btn btn-outline-success btn-sm" onclick="saveAsUserTemplate()" id="save-template-btn" style="display: none;">
                                <i class="fas fa-save"></i> Сохранить как шаблон
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="downloadTemplate()" id="download-btn" style="display: none;" disabled>
                                <i class="fas fa-file-word"></i> Скачать в Word
                            </button>
                        </div>
                        
                        <!-- Скрытое поле для сохранения шаблона в претензии -->
                        <?= Html::hiddenInput('Claim[description]', '', ['id' => 'claim-description-hidden']) ?>
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i> 
                                После редактирования вы можете сохранить текст как персональный шаблон
                            </small>
                        </div>
                        <small class="form-text text-muted">
                            <i class="fas fa-lightbulb"></i> 
                            Вы можете использовать готовый шаблон или написать претензию самостоятельно
                        </small>
                    </div>


                    <div class="form-actions">
                        <?= Html::submitButton('<i class="fas fa-save"></i> Создать претензию', ['class' => 'btn btn-success']) ?>
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
                    <h5>Типы претензий:</h5>
                    <ul>
                        <li><strong>Ремонт</strong> - ремонт товара по гарантии</li>
                        <li><strong>Возврат денежных средств</strong> - возврат уплаченных денег</li>
                        <li><strong>Замена товара на аналогичный товар</strong> - обмен на такой же товар</li>
                    </ul>
                    
                    <h5>Статусы претензий:</h5>
                    <ul>
                        <li><span class="badge badge-warning">Ожидает рассмотрения</span></li>
                        <li><span class="badge badge-info">В процессе</span></li>
                        <li><span class="badge badge-success">Решена</span></li>
                        <li><span class="badge badge-danger">Отклонена</span></li>
                        <li><span class="badge badge-secondary">Закрыта</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно для редактирования описания -->
<div class="modal fade" id="descriptionModal" tabindex="-1" aria-labelledby="descriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="descriptionModalLabel">
                    <i class="fas fa-edit"></i> Редактирование описания претензии
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="legal-document">
                    <!-- Заголовок по центру -->
                    <div class="document-title-section">
                        <h1 class="document-title">ПРЕТЕНЗИЯ</h1>
                    </div>
                    
                    <!-- Основной текст -->
                    <div class="document-content">
                        <!-- Панель инструментов Word-стиль -->
                        <div class="word-toolbar">
                            <div class="toolbar-group">
                                <button type="button" class="toolbar-btn" onclick="formatText('bold')" title="Жирный">
                                    <i class="fas fa-bold"></i>
                                </button>
                                <button type="button" class="toolbar-btn" onclick="formatText('italic')" title="Курсив">
                                    <i class="fas fa-italic"></i>
                                </button>
                                <button type="button" class="toolbar-btn" onclick="formatText('underline')" title="Подчеркнутый">
                                    <i class="fas fa-underline"></i>
                                </button>
                            </div>
                            
                            <div class="toolbar-separator"></div>
                            
                            <div class="toolbar-group">
                                <select class="toolbar-select" onchange="formatText('fontSize', this.value)" title="Размер шрифта">
                                    <option value="">Размер</option>
                                    <option value="12">12</option>
                                    <option value="14" selected>14</option>
                                    <option value="16">16</option>
                                    <option value="18">18</option>
                                    <option value="20">20</option>
                                </select>
                                
                                <select class="toolbar-select" onchange="formatText('fontFamily', this.value)" title="Шрифт">
                                    <option value="Times New Roman" selected>Times New Roman</option>
                                    <option value="Arial">Arial</option>
                                    <option value="Calibri">Calibri</option>
                                    <option value="Georgia">Georgia</option>
                                </select>
                            </div>
                            
                            <div class="toolbar-separator"></div>
                            
                            <div class="toolbar-group">
                                <button type="button" class="toolbar-btn" onclick="formatText('justifyLeft')" title="По левому краю">
                                    <i class="fas fa-align-left"></i>
                                </button>
                                <button type="button" class="toolbar-btn" onclick="formatText('justifyCenter')" title="По центру">
                                    <i class="fas fa-align-center"></i>
                                </button>
                                <button type="button" class="toolbar-btn" onclick="formatText('justifyRight')" title="По правому краю">
                                    <i class="fas fa-align-right"></i>
                                </button>
                                <button type="button" class="toolbar-btn" onclick="formatText('justifyFull')" title="По ширине">
                                    <i class="fas fa-align-justify"></i>
                                </button>
                            </div>
                            
                            <div class="toolbar-separator"></div>
                            
                            <div class="toolbar-group">
                                <button type="button" class="toolbar-btn" onclick="formatText('insertUnorderedList')" title="Маркированный список">
                                    <i class="fas fa-list-ul"></i>
                                </button>
                                <button type="button" class="toolbar-btn" onclick="formatText('insertOrderedList')" title="Нумерованный список">
                                    <i class="fas fa-list-ol"></i>
                                </button>
                            </div>
                            
                            <div class="toolbar-separator"></div>
                            
                            <div class="toolbar-group">
                                <button type="button" class="toolbar-btn" onclick="insertTab()" title="Вставить табуляцию (4 пробела)">
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                                <button type="button" class="toolbar-btn" onclick="formatText('indent')" title="Увеличить отступ">
                                    <i class="fas fa-indent"></i>
                                </button>
                                <button type="button" class="toolbar-btn" onclick="formatText('outdent')" title="Уменьшить отступ">
                                    <i class="fas fa-outdent"></i>
                                </button>
                            </div>
                            
                            <div class="toolbar-separator"></div>
                            
                            <div class="toolbar-group">
                                <button type="button" class="toolbar-btn" onclick="showTemplateManager()" title="Управление шаблонами">
                                    <i class="fas fa-cog"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Редактируемая область -->
                        <div class="word-editor-container">
                            <div class="word-editor" id="word-editor" contenteditable="true" tabindex="-1" data-placeholder="Введите текст претензии...">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-text mt-3">
                    <i class="fas fa-info-circle"></i> 
                    Вы можете использовать готовый шаблон или написать претензию самостоятельно
                </div>
                
                <!-- Кнопка для создания нового шаблона (показывается когда редактор пустой) -->
                <div id="create-template-prompt" class="text-center mt-3" style="display: none;">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Редактор пуст</strong><br>
                        Начните печатать или выберите готовый шаблон
                    </div>
                    <button type="button" class="btn btn-primary" onclick="createNewTemplate()">
                        <i class="fas fa-plus"></i> Создать новый шаблон
                    </button>
                </div>
                
                <!-- Кнопка скачивания в модальном окне -->
                <div class="mt-3 text-center">
                    <button type="button" class="btn btn-outline-primary" onclick="downloadTemplate()" id="modal-download-btn" disabled>
                        <i class="fas fa-file-word"></i> Скачать в Word
                    </button>
                </div>
                
                
                <!-- DOCX файлы теперь генерируются на сервере с помощью PHP -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Отмена
                </button>
                <button type="button" class="btn btn-outline-info" onclick="saveAsUserTemplateFromModal()">
                    <i class="fas fa-save"></i> Сохранить как шаблон
                </button>
                <button type="button" class="btn btn-success" onclick="saveDescription()">
                    <i class="fas fa-save"></i> Сохранить
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно для сохранения шаблона -->
<div class="modal fade" id="saveTemplateModal" tabindex="-1" aria-labelledby="saveTemplateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="saveTemplateModalLabel">
                    <i class="fas fa-save"></i> Сохранить как шаблон
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="save-template-form">
                    <div class="form-group mb-3">
                        <label for="template-name" class="form-label">Название шаблона</label>
                        <input type="text" class="form-control" id="template-name" required placeholder="Введите название шаблона...">
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="template-type-select" class="form-label">Тип претензии *</label>
                        <select class="form-control" id="template-type-select" required>
                            <option value="">Выберите тип претензии...</option>
                            <option value="repair">Ремонт</option>
                            <option value="refund">Возврат денежных средств</option>
                            <option value="replacement">Замена товара</option>
                            <option value="delivery">Проблемы с доставкой</option>
                            <option value="custom">Свой вариант</option>
                        </select>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="template-description" class="form-label">Описание (необязательно)</label>
                        <textarea class="form-control" id="template-description" rows="2" placeholder="Краткое описание шаблона..."></textarea>
                    </div>
                    
                    <div class="form-group mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="template-favorite">
                            <label class="form-check-label" for="template-favorite">
                                Добавить в избранное
                            </label>
                        </div>
                    </div>
                    
                    <input type="hidden" id="template-original-id" value="">
                    <input type="hidden" id="template-type" value="">
                    <input type="hidden" id="template-content" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Отмена
                </button>
                <button type="button" class="btn btn-success" onclick="confirmSaveTemplate()">
                    <i class="fas fa-save"></i> Сохранить шаблон
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Максимально специфичные стили для дропдаунов */
html body div.container div.row div.col-lg-8 div.claim-form-card div.card-body form div.form-group select.form-control,
html body div.container div.row div.col-lg-8 div.claim-form-card div.card-body form div.form-group select,
select.form-control,
select {
    color: #000 !important;
    background-color: #fff !important;
    font-size: 16px !important;
    font-weight: 600 !important;
    text-shadow: none !important;
    -webkit-text-stroke: 0 !important;
    opacity: 1 !important;
    width: 100% !important;
    min-width: 300px !important;
    max-width: 100% !important;
    height: auto !important;
    min-height: 56px !important;
    line-height: 1.5 !important;
    vertical-align: middle !important;
    padding: 16px 20px !important;
    display: flex !important;
    align-items: center !important;
}

html body div.container div.row div.col-lg-8 div.claim-form-card div.card-body form div.form-group select.form-control option,
html body div.container div.row div.col-lg-8 div.claim-form-card div.card-body form div.form-group select option,
select.form-control option,
select option {
    color: #000 !important;
    background-color: #fff !important;
    font-size: 16px !important;
    font-weight: 600 !important;
    text-shadow: none !important;
    -webkit-text-stroke: 0 !important;
    opacity: 1 !important;
    padding: 8px 12px !important;
}

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

.btn-secondary {
    background: linear-gradient(135deg, #6B7280, #4B5563);
    color: white;
}

.btn-secondary:hover {
    background: linear-gradient(135deg, #4B5563, #374151);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(107, 114, 128, 0.3);
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

.template-loading {
    opacity: 0.6;
    pointer-events: none;
}

/* Стили для дропдаунов */
.form-control {
    color: #333 !important;
    background-color: #fff !important;
    border: 2px solid #e5e7eb !important;
    border-radius: 8px !important;
    padding: 12px 16px !important;
    font-size: 16px !important;
    font-weight: 500 !important;
    transition: all 0.3s ease !important;
}

.form-control:focus {
    border-color: #10B981 !important;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1) !important;
    outline: none !important;
}

.form-control option {
    color: #333 !important;
    background-color: #fff !important;
    padding: 8px 12px !important;
    font-size: 16px !important;
    font-weight: 500 !important;
}

.form-control option:hover {
    background-color: #f3f4f6 !important;
}

.form-control option:checked {
    background-color: #10B981 !important;
    color: white !important;
}

/* Стили для селекта шаблонов */
#template-select {
    color: #000 !important;
    background-color: #fff !important;
    border: 2px solid #e5e7eb !important;
    border-radius: 8px !important;
    padding: 12px 16px !important;
    font-size: 16px !important;
    font-weight: 600 !important;
    transition: all 0.3s ease !important;
    text-shadow: none !important;
    -webkit-text-stroke: 0 !important;
    opacity: 1 !important;
}

#template-select:focus {
    border-color: #10B981 !important;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1) !important;
    outline: none !important;
    color: #000 !important;
}

#template-select option {
    color: #000 !important;
    background-color: #fff !important;
    padding: 10px 12px !important;
    font-size: 16px !important;
    font-weight: 600 !important;
    text-shadow: none !important;
    -webkit-text-stroke: 0 !important;
    opacity: 1 !important;
}

#template-select option:hover {
    background-color: #f3f4f6 !important;
    color: #000 !important;
}

#template-select option:checked {
    background-color: #10B981 !important;
    color: white !important;
}

/* Стили для превью описания */
.description-preview {
    background-color: #f8f9fa;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 12px;
    min-height: 60px;
    max-height: 200px;
    overflow-y: auto;
    white-space: pre-wrap;
    font-family: inherit;
    line-height: 1.5;
}

.description-preview p {
    margin: 0;
    color: #6b7280;
    font-style: italic;
}

.description-preview.has-content {
    background-color: #fff;
    border-color: #10B981;
}

.description-preview.has-content p {
    color: #333;
    font-style: normal;
}

/* Стили для модального окна */
.modal-xl {
    max-width: 90%;
}

.modal-body textarea {
    font-size: 16px;
    line-height: 1.6;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    padding: 16px;
    transition: all 0.3s ease;
}

.modal-body textarea:focus {
    border-color: #10B981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    outline: none;
}

.modal-header {
    background: linear-gradient(135deg, #10B981, #059669);
    color: white;
    border-bottom: none;
}

.modal-header .btn-close {
    filter: invert(1);
}

.modal-footer {
    border-top: 1px solid #e5e7eb;
    background-color: #f8f9fa;
}

/* Стили для юридического документа */
.legal-document {
    background: white;
    padding: 40px;
    font-family: 'Times New Roman', serif;
    line-height: 1.6;
    position: relative;
    min-height: 600px;
    border: 1px solid #ddd;
    border-radius: 8px;
}

/* Удалены стили для адресата */

/* Заголовок по центру */
.document-title-section {
    text-align: center;
    margin: 20px 0 40px 0;
    clear: both;
}

.document-title {
    font-size: 20px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin: 0;
    color: #333;
    font-family: 'Times New Roman', serif;
}

/* Основной контент */
.document-content {
    margin: 40px 0 20px 0;
    clear: both;
}

/* Стили для Word-редактора */
.word-toolbar {
    background: #f8f9fa;
    border: 1px solid #ddd;
    border-bottom: none;
    padding: 8px 12px;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    border-radius: 4px 4px 0 0;
}

.toolbar-group {
    display: flex;
    align-items: center;
    gap: 4px;
}

.toolbar-separator {
    width: 1px;
    height: 24px;
    background: #ddd;
    margin: 0 8px;
}

.toolbar-btn {
    background: transparent;
    border: 1px solid transparent;
    border-radius: 4px;
    padding: 6px 8px;
    cursor: pointer;
    color: #333;
    font-size: 14px;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 32px;
}

.toolbar-btn:hover {
    background: #e9ecef;
    border-color: #adb5bd;
}

.toolbar-btn.active {
    background: #007bff;
    color: white;
    border-color: #007bff;
}

.toolbar-select {
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 4px 8px;
    font-size: 12px;
    color: #333;
    cursor: pointer;
    min-width: 80px;
}

.toolbar-select:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
}

.word-editor-container {
    border: 1px solid #ddd;
    border-top: none;
    border-radius: 0 0 4px 4px;
    background: white;
    min-height: 300px;
    position: relative;
}

.word-editor {
    font-family: 'Times New Roman', serif;
    font-size: 14px;
    line-height: 1.8;
    padding: 20px;
    min-height: 300px;
    outline: none;
    overflow-y: auto;
    color: #333;
    -webkit-user-select: text;
    -moz-user-select: text;
    -ms-user-select: text;
    user-select: text;
}

.word-editor:empty:before {
    content: attr(data-placeholder);
    color: #999;
    font-style: italic;
}

.word-editor:focus {
    background: white;
}

.word-editor p {
    margin: 0 0 12px 0;
}

.word-editor ul, .word-editor ol {
    margin: 12px 0;
    padding-left: 30px;
}

.word-editor li {
    margin: 4px 0;
}

/* Стили для табуляции */
.word-editor {
    tab-size: 4;
    -moz-tab-size: 4;
    -webkit-tab-size: 4;
    white-space: pre-wrap;
}

/* Удалены стили для подписи */

/* Стили для модального окна сохранения шаблона */
#saveTemplateModal .form-control {
    color: #000 !important;
    background-color: #fff !important;
    font-weight: 500 !important;
    width: 100% !important;
    min-width: 200px !important;
    max-width: 100% !important;
    height: auto !important;
    min-height: 48px !important;
    line-height: 1.5 !important;
    vertical-align: middle !important;
    display: flex !important;
    align-items: center !important;
    text-shadow: none !important;
    -webkit-text-stroke: 0 !important;
    opacity: 1 !important;
    padding: 12px 16px !important;
    border: 1px solid #ddd !important;
    border-radius: 8px !important;
    font-size: 14px !important;
}

#saveTemplateModal .form-control:focus {
    border-color: #007bff !important;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
}

/* Стили для кнопки скачивания */
.btn-outline-primary {
    border: 2px solid #007bff;
    color: #007bff;
    background-color: transparent;
    border-radius: 8px;
    padding: 10px 20px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-outline-primary:hover {
    background-color: #007bff;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
}

.btn-outline-primary i {
    margin-right: 8px;
}

/* Адаптивность для мобильных устройств */
@media (max-width: 768px) {
    .legal-document {
        padding: 20px;
        min-height: 500px;
    }
    
    /* Удалены стили для адресата */
    
    .document-title-section {
        margin: 30px 0 20px 0;
    }
    
    .document-title {
        font-size: 18px;
    }
    
    .document-content {
        margin: 20px 0 20px 0;
    }
    
    .legal-textarea {
        min-height: 200px;
        padding: 15px;
    }
    
    /* Удалены стили для подписи */
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const purchaseSelect = document.getElementById('purchase-select');
    const claimTypeGroup = document.getElementById('claim-type-group');
    const claimTypeSelect = document.getElementById('claim-type-select');
    const templateSelector = document.getElementById('template-selector');
    const templateSelect = document.getElementById('template-select');
    const descriptionGroup = document.getElementById('description-group');
    const descriptionPreview = document.getElementById('description-preview');
    const modalDescription = document.getElementById('modal-description');

    // Принудительное применение стилей к дропдаунам
    function applyDropdownStyles() {
        const selects = document.querySelectorAll('select, .form-control');
        selects.forEach(select => {
            select.style.color = '#000';
            select.style.backgroundColor = '#fff';
            select.style.fontSize = '16px';
            select.style.fontWeight = '600';
            select.style.textShadow = 'none';
            select.style.webkitTextStroke = '0';
            select.style.opacity = '1';
            select.style.width = '100%';
            select.style.minWidth = '300px';
            select.style.maxWidth = '100%';
            select.style.height = 'auto';
            select.style.minHeight = '56px';
            select.style.lineHeight = '1.5';
            select.style.verticalAlign = 'middle';
            select.style.padding = '16px 20px';
            select.style.display = 'flex';
            select.style.alignItems = 'center';
            
            // Стили для опций
            const options = select.querySelectorAll('option');
            options.forEach(option => {
                option.style.color = '#000';
                option.style.backgroundColor = '#fff';
                option.style.fontSize = '16px';
                option.style.fontWeight = '600';
                option.style.textShadow = 'none';
                option.style.webkitTextStroke = '0';
                option.style.opacity = '1';
                option.style.padding = '8px 12px';
                option.style.lineHeight = '1.5';
            });
        });
    }

    // Применить стили сразу и при изменениях
    applyDropdownStyles();
    setTimeout(applyDropdownStyles, 100);
    setTimeout(applyDropdownStyles, 500);

    // Функция для обновления превью описания
    function updateDescriptionPreview(content) {
        const downloadBtn = document.getElementById('download-btn');
        
        if (content && content.trim()) {
            descriptionPreview.innerHTML = '<p>' + content.replace(/\n/g, '<br>') + '</p>';
            descriptionPreview.classList.add('has-content');
            
            // Показываем и активируем кнопку скачивания
            if (downloadBtn) {
                downloadBtn.style.display = 'inline-block';
                downloadBtn.disabled = false;
            }
        } else {
            descriptionPreview.innerHTML = '<p>Описание не заполнено</p>';
            descriptionPreview.classList.remove('has-content');
            
            // Скрываем и деактивируем кнопку скачивания
            if (downloadBtn) {
                downloadBtn.style.display = 'none';
                downloadBtn.disabled = true;
            }
        }
    }

    // Функция для открытия модального окна
    window.openDescriptionModal = function() {
        const wordEditor = document.getElementById('word-editor');
        const currentContent = descriptionPreview.querySelector('p').textContent;
        
        // Заполняем Word-редактор содержимым
        if (currentContent && currentContent !== 'Описание не заполнено') {
            wordEditor.innerHTML = currentContent.replace(/\n/g, '<br>');
        } else {
            wordEditor.innerHTML = '';
        }
        
        // Данные продавца и покупателя больше не отображаются в редакторе
        
        const modal = new bootstrap.Modal(document.getElementById('descriptionModal'));
        modal.show();
        
        // Фокусируемся на редакторе после открытия модального окна
        setTimeout(() => {
            wordEditor.focus();
            // Проверяем содержимое редактора
            checkEditorContent();
        }, 300);
    };

    // Функция для сохранения описания
    window.saveDescription = function() {
        const wordEditor = document.getElementById('word-editor');
        const content = wordEditor.innerHTML;
        
        // Конвертируем HTML в текст для превью
        const textContent = wordEditor.textContent || wordEditor.innerText || '';
        updateDescriptionPreview(textContent);
        
        // Создаем скрытое поле для формы
        let hiddenField = document.getElementById('claim-description-hidden');
        if (!hiddenField) {
            hiddenField = document.createElement('input');
            hiddenField.type = 'hidden';
            hiddenField.name = 'Claim[description]';
            hiddenField.id = 'claim-description-hidden';
            document.querySelector('form').appendChild(hiddenField);
        }
        hiddenField.value = content;
        
        // Закрываем модальное окно
        const modal = bootstrap.Modal.getInstance(document.getElementById('descriptionModal'));
        modal.hide();
    };

    // Функция для форматирования текста в Word-редакторе
    window.formatText = function(command, value) {
        const wordEditor = document.getElementById('word-editor');
        
        // Фокусируемся на редакторе
        wordEditor.focus();
        
        if (command === 'fontSize') {
            document.execCommand('fontSize', false, '7');
            const fontElements = wordEditor.querySelectorAll('font[size="7"]');
            fontElements.forEach(el => {
                el.removeAttribute('size');
                el.style.fontSize = value + 'px';
            });
        } else if (command === 'fontFamily') {
            document.execCommand('fontName', false, value);
        } else {
            document.execCommand(command, false, null);
        }
        
        // Обновляем состояние кнопок
        updateToolbarState();
    };

    // Функция для вставки табуляции
    window.insertTab = function() {
        const wordEditor = document.getElementById('word-editor');
        wordEditor.focus();
        
        const selection = window.getSelection();
        if (selection.rangeCount > 0) {
            const range = selection.getRangeAt(0);
            const tabNode = document.createTextNode('    '); // 4 пробела
            range.deleteContents();
            range.insertNode(tabNode);
            
            // Перемещаем курсор после вставленных пробелов
            range.setStartAfter(tabNode);
            range.setEndAfter(tabNode);
            selection.removeAllRanges();
            selection.addRange(range);
        }
    };

    // Функция для обновления состояния панели инструментов
    function updateToolbarState() {
        const wordEditor = document.getElementById('word-editor');
        const selection = window.getSelection();
        
        if (selection.rangeCount > 0) {
            const range = selection.getRangeAt(0);
            const container = range.commonAncestorContainer;
            
            // Проверяем состояние форматирования
            const isBold = document.queryCommandState('bold');
            const isItalic = document.queryCommandState('italic');
            const isUnderline = document.queryCommandState('underline');
            
            // Обновляем кнопки
            document.querySelector('[onclick*="bold"]').classList.toggle('active', isBold);
            document.querySelector('[onclick*="italic"]').classList.toggle('active', isItalic);
            document.querySelector('[onclick*="underline"]').classList.toggle('active', isUnderline);
        }
    }

    // Добавляем обработчики событий для Word-редактора
    document.addEventListener('DOMContentLoaded', function() {
        const wordEditor = document.getElementById('word-editor');
        if (wordEditor) {
            wordEditor.addEventListener('input', function() {
                updateToolbarState();
                checkEditorContent();
            });
            wordEditor.addEventListener('selectionchange', updateToolbarState);
            wordEditor.addEventListener('keyup', updateToolbarState);
            wordEditor.addEventListener('mouseup', updateToolbarState);
            
            // Обработка клавиши Tab для вставки 4 пробелов
            wordEditor.addEventListener('keydown', function(e) {
                if (e.key === 'Tab') {
                    e.preventDefault(); // Предотвращаем стандартное поведение Tab
                    e.stopPropagation(); // Останавливаем всплытие события
                    e.stopImmediatePropagation(); // Останавливаем немедленное всплытие
                    
                    // Вставляем 4 пробела
                    const selection = window.getSelection();
                    if (selection.rangeCount > 0) {
                        const range = selection.getRangeAt(0);
                        const tabNode = document.createTextNode('    '); // 4 пробела
                        range.deleteContents();
                        range.insertNode(tabNode);
                        
                        // Перемещаем курсор после вставленных пробелов
                        range.setStartAfter(tabNode);
                        range.setEndAfter(tabNode);
                        selection.removeAllRanges();
                        selection.addRange(range);
                    }
                    
                    return false; // Дополнительная защита
                }
            });
        }
        
        // Дополнительная защита от Tab на уровне документа
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Tab' && e.target.id === 'word-editor') {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                return false;
            }
        }, true); // Используем capture phase для раннего перехвата
        
        // Обработчики для модального окна сохранения шаблона
        const saveTemplateModal = document.getElementById('saveTemplateModal');
        if (saveTemplateModal) {
            saveTemplateModal.addEventListener('hidden.bs.modal', function() {
                // Убираем фокус с любых элементов внутри модального окна
                const focusedElement = document.activeElement;
                if (focusedElement && saveTemplateModal.contains(focusedElement)) {
                    focusedElement.blur();
                }
            });
            
            saveTemplateModal.addEventListener('shown.bs.modal', function() {
                // Фокусируемся на поле названия при открытии
                const nameInput = document.getElementById('template-name');
                if (nameInput) {
                    nameInput.focus();
                    nameInput.select();
                }
            });
        }
    });

    // Инициализация: если покупка уже выбрана, показать тип претензии
    if (purchaseSelect.value) {
        claimTypeGroup.style.display = 'block';
        
        // Если тип претензии уже выбран
        if (claimTypeSelect.value) {
            if (claimTypeSelect.value === 'custom') {
                // Для "Свой вариант" показать описание
                descriptionGroup.style.display = 'block';
            } else {
                // Для остальных типов показать шаблоны
                loadTemplates(claimTypeSelect.value);
                templateSelector.style.display = 'block';
                
                // Если шаблон уже выбран, показать описание
                if (templateSelect.value) {
                    descriptionGroup.style.display = 'block';
                }
            }
        }
    }

    // Обработчик выбора покупки
    purchaseSelect.addEventListener('change', function() {
        const selectedPurchase = this.value;
        
        // Обновляем скрытое поле
        document.getElementById('purchase_id').value = selectedPurchase;
        
        if (selectedPurchase) {
            // Проверяем гарантийный срок и срок подачи претензии
            checkWarrantyAndAppealDeadline(selectedPurchase);
            
            // Показать выбор типа претензии
            claimTypeGroup.style.display = 'block';
            // Скрыть шаблоны и сбросить их
            templateSelector.style.display = 'none';
            templateSelect.innerHTML = '<option value="">Выберите шаблон...</option>';
            claimTypeSelect.value = '';
            // Скрыть описание
            descriptionGroup.style.display = 'none';
            updateDescriptionPreview('');
        } else {
            // Скрыть все последующие шаги
            claimTypeGroup.style.display = 'none';
            templateSelector.style.display = 'none';
            templateSelect.innerHTML = '<option value="">Выберите шаблон...</option>';
            claimTypeSelect.value = '';
            descriptionGroup.style.display = 'none';
            updateDescriptionPreview('');
        }
        
        // Применить стили после изменений
        setTimeout(applyDropdownStyles, 50);
    });

    // Функция для проверки гарантийного срока и срока подачи претензии
    function checkWarrantyAndAppealDeadline(purchaseId) {
        console.log('Проверка гарантийного срока для покупки:', purchaseId);
        
        fetch('/claim/check-warranty?purchase_id=' + purchaseId, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => {
                console.log('Ответ сервера:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Данные от сервера:', data);
                
                if (data.success) {
                    // Сохраняем дату окончания срока подачи претензии в глобальной переменной
                    window.appealDeadlineDate = data.appeal_deadline_date;
                    
                    // Сохраняем все данные о гарантийном сроке в глобальной переменной
                    window.warrantyData = data;
                    
                    console.log('Гарантийный срок истек:', data.warranty_expired);
                    console.log('Срок подачи претензии истек:', data.appeal_deadline_expired);
                    
                    // Показываем модальное окно с информацией всегда
                    showWarrantyInfoModal(data);
                } else {
                    console.error('Ошибка проверки гарантийного срока:', data.message);
                }
            })
            .catch(error => {
                console.error('Ошибка проверки гарантийного срока:', error);
            });
    }

    // Функция для показа модального окна с информацией о гарантийном сроке
    function showWarrantyInfoModal(data) {
        // Сначала показываем вопрос о ремонте
        showRepairQuestionModal(data);
    }

    // Функция для показа модального окна с вопросом о ремонте
    function showRepairQuestionModal(data) {
        const modalHtml = `
            <div class="modal fade" id="repairQuestionModal" tabindex="-1" aria-labelledby="repairQuestionModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="repairQuestionModalLabel">Информация о товаре</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <h6>Ремонтировался ли ранее товар в официальном сервисном центре продавца либо изготовителя?</h6>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="wasRepaired" id="wasRepairedYes" value="1">
                                    <label class="form-check-label" for="wasRepairedYes">
                                        Да
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="wasRepaired" id="wasRepairedNo" value="0">
                                    <label class="form-check-label" for="wasRepairedNo">
                                        Нет
                                    </label>
                                </div>
                            </div>
                            
                            <div id="repairDocumentFields" style="display: none;">
                                <h6>Реквизиты акта выполненных работ:</h6>
                                <div class="mb-3">
                                    <label for="repairDocumentDescription" class="form-label">Описание и номер документа</label>
                                    <input type="text" class="form-control" id="repairDocumentDescription" placeholder="Введите описание и номер акта выполненных работ">
                                </div>
                                <div class="mb-3">
                                    <label for="repairDocumentDate" class="form-label">Дата выдачи документа</label>
                                    <input type="date" class="form-control" id="repairDocumentDate">
                                </div>
                                <div class="mb-3">
                                    <label for="repairDefectDescription" class="form-label">Недостаток согласно акту выполненных работ</label>
                                    <textarea class="form-control" id="repairDefectDescription" rows="3" placeholder="Опишите недостаток согласно акту выполненных работ"></textarea>
                                </div>
                            </div>
                            
                            <div id="currentDefectFields" style="display: none;">
                                <h6>Описание текущего недостатка:</h6>
                                <div class="mb-3">
                                    <label for="currentDefectDescription" class="form-label">Опишите текущий недостаток</label>
                                    <textarea class="form-control" id="currentDefectDescription" rows="3" placeholder="Опишите текущий недостаток товара"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" onclick="handleRepairQuestion()">Продолжить</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Удаляем предыдущее модальное окно, если оно есть
        const existingModal = document.getElementById('repairQuestionModal');
        if (existingModal) {
            existingModal.remove();
        }
        
        // Добавляем новое модальное окно
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        
        // Показываем модальное окно
        const modal = new bootstrap.Modal(document.getElementById('repairQuestionModal'));
        modal.show();
        
        // Обработчик изменения радио кнопок
        document.querySelectorAll('input[name="wasRepaired"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const repairFields = document.getElementById('repairDocumentFields');
                const currentDefectFields = document.getElementById('currentDefectFields');
                
                if (this.value === '1') {
                    repairFields.style.display = 'block';
                    currentDefectFields.style.display = 'none';
                } else {
                    repairFields.style.display = 'none';
                    currentDefectFields.style.display = 'block';
                }
            });
        });
    }

    // Функция для обработки вопроса о ремонте
    window.handleRepairQuestion = function() {
        const selectedRepair = document.querySelector('input[name="wasRepaired"]:checked');
        
        if (!selectedRepair) {
            console.error('Пожалуйста, выберите один из вариантов');
            return;
        }
        
        const purchaseId = document.getElementById('purchase_id').value;
        const wasRepairedOfficially = selectedRepair.value === '1';
        const repairDocumentDescription = document.getElementById('repairDocumentDescription').value;
        const repairDocumentDate = document.getElementById('repairDocumentDate').value;
        const repairDefectDescription = document.getElementById('repairDefectDescription').value;
        const currentDefectDescription = document.getElementById('currentDefectDescription').value;
        
        // Определяем описание недостатка в зависимости от выбора
        const defectDescription = wasRepairedOfficially ? repairDefectDescription : currentDefectDescription;
        
        // Сохраняем информацию о ремонте
        saveRepairInfo(purchaseId, wasRepairedOfficially, repairDocumentDescription, repairDocumentDate, defectDescription);
        
        // Закрываем текущее модальное окно
        const modal = bootstrap.Modal.getInstance(document.getElementById('repairQuestionModal'));
        modal.hide();
        
        // Показываем следующее модальное окно с вопросом о доказательствах недостатка
        showDefectProofModal();
    };

    // Функция для показа модального окна с вопросом о доказательствах недостатка
    window.showDefectProofModal = function() {
        const modalHtml = `
            <div class="modal fade" id="defectProofModal" tabindex="-1" aria-labelledby="defectProofModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="defectProofModalLabel">Доказательства недостатка</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <h6>Есть ли у Вас подтверждение недостатка?</h6>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="defect_proof" id="quality_check" value="quality_check">
                                    <label class="form-check-label" for="quality_check">
                                        Акт проверки качества
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="defect_proof" id="independent_expertise" value="independent_expertise">
                                    <label class="form-check-label" for="independent_expertise">
                                        Независимая экспертиза
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="defect_proof" id="no_proof" value="no_proof">
                                    <label class="form-check-label" for="no_proof">
                                        Экспертиза не проводилась
                                    </label>
                                </div>
                            </div>
                            
                            <div id="defectProofDocumentFields" style="display: none;">
                                <h6>Реквизиты документа:</h6>
                                <div class="mb-3">
                                    <label for="defectProofDocumentDescription" class="form-label">Описание и номер документа</label>
                                    <input type="text" class="form-control" id="defectProofDocumentDescription" placeholder="Введите описание и номер документа">
                                </div>
                                <div class="mb-3">
                                    <label for="defectProofDocumentDate" class="form-label">Дата выдачи документа</label>
                                    <input type="date" class="form-control" id="defectProofDocumentDate">
                                </div>
                            </div>
                            
                            <div id="defectSimilarityQuestion" style="display: none;">
                                <h6>Недостаток аналогичный указанному?</h6>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="defect_similarity" id="defectSimilarYes" value="1">
                                        <label class="form-check-label" for="defectSimilarYes">
                                            Да
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="defect_similarity" id="defectSimilarNo" value="0">
                                        <label class="form-check-label" for="defectSimilarNo">
                                            Нет
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="defectDescriptionField" style="display: none;">
                                <h6>Описание недостатка:</h6>
                                <div class="mb-3">
                                    <label for="defectDescription" class="form-label">Краткое описание текущего недостатка</label>
                                    <textarea class="form-control" id="defectDescription" rows="3" placeholder="Опишите текущий недостаток товара"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" onclick="handleDefectProofSelection()">Продолжить</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Удаляем предыдущее модальное окно, если оно есть
        const existingModal = document.getElementById('defectProofModal');
        if (existingModal) {
            existingModal.remove();
        }
        
        // Добавляем новое модальное окно
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        
        // Показываем модальное окно
        const modal = new bootstrap.Modal(document.getElementById('defectProofModal'));
        modal.show();
        
        // Обработчик изменения радио кнопок
        document.querySelectorAll('input[name="defect_proof"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const documentFields = document.getElementById('defectProofDocumentFields');
                const similarityQuestion = document.getElementById('defectSimilarityQuestion');
                const descriptionField = document.getElementById('defectDescriptionField');
                
                if (this.value === 'quality_check' || this.value === 'independent_expertise') {
                    documentFields.style.display = 'block';
                    similarityQuestion.style.display = 'block';
                    descriptionField.style.display = 'none';
                } else if (this.value === 'no_proof') {
                    documentFields.style.display = 'none';
                    similarityQuestion.style.display = 'block';
                    descriptionField.style.display = 'none';
                } else {
                    documentFields.style.display = 'none';
                    similarityQuestion.style.display = 'none';
                    descriptionField.style.display = 'none';
                }
            });
        });
        
        // Обработчик изменения радио кнопок для вопроса о схожести недостатка
        document.querySelectorAll('input[name="defect_similarity"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const descriptionField = document.getElementById('defectDescriptionField');
                
                if (this.value === '0') {
                    descriptionField.style.display = 'block';
                } else {
                    descriptionField.style.display = 'none';
                }
            });
        });
    };

    // Функция для обработки выбора доказательства недостатка
    window.handleDefectProofSelection = function() {
        const selectedProof = document.querySelector('input[name="defect_proof"]:checked');
        
        if (!selectedProof) {
            console.error('Пожалуйста, выберите один из вариантов');
            return;
        }
        
        // Проверяем ответ на вопрос о схожести недостатка (если он показывается)
        const similarityQuestion = document.getElementById('defectSimilarityQuestion');
        if (similarityQuestion.style.display !== 'none') {
            const selectedSimilarity = document.querySelector('input[name="defect_similarity"]:checked');
            if (!selectedSimilarity) {
                console.error('Пожалуйста, ответьте на вопрос о схожести недостатка');
                return;
            }
        }
        
        const purchaseId = document.getElementById('purchase_id').value;
        const defectProofType = selectedProof.value;
        const defectProofDocumentDescription = document.getElementById('defectProofDocumentDescription').value;
        const defectProofDocumentDate = document.getElementById('defectProofDocumentDate').value;
        
        // Проверяем ответ на вопрос о схожести недостатка
        const selectedSimilarity = document.querySelector('input[name="defect_similarity"]:checked');
        let defectDescription = '';
        
        if (selectedSimilarity && selectedSimilarity.value === '0') {
            // Если выбрано "Нет", берем описание из поля
            defectDescription = document.getElementById('defectDescription').value;
        }
        // Если выбрано "Да", defectDescription остается пустым
        
        // Сохраняем информацию о доказательствах недостатка
        saveDefectProofInfo(purchaseId, defectProofType, defectProofDocumentDescription, defectProofDocumentDate, defectDescription);
        
        if (selectedProof.value === 'no_proof') {
            // Проверяем условия для показа модального окна с информацией о законе
            // Оно показывается только если гарантийный срок истек, но срок подачи претензии не истек
            if (window.warrantyData && window.warrantyData.warranty_expired && !window.warrantyData.appeal_deadline_expired) {
                // Показываем модальное окно с информацией о законе
                showLawInfoModal();
            } else {
                // Закрываем текущее модальное окно и продолжаем создание претензии
                const modal = bootstrap.Modal.getInstance(document.getElementById('defectProofModal'));
                modal.hide();
            }
        } else {
            // Закрываем текущее модальное окно и продолжаем создание претензии
            const modal = bootstrap.Modal.getInstance(document.getElementById('defectProofModal'));
            modal.hide();
        }
    };

    // Функция для показа модального окна с информацией о законе
    function showLawInfoModal() {
        const modalHtml = `
            <div class="modal fade" id="lawInfoModal" tabindex="-1" aria-labelledby="lawInfoModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header" style="background: linear-gradient(135deg, #3B82F6, #1D4ED8); color: white;">
                            <h5 class="modal-title" id="lawInfoModalLabel">
                                <i class="fas fa-gavel"></i> Информация о правах потребителя
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color: white;"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle"></i> Согласно ч.5 статьи 19 Закона РФ "О защите прав потребителей"</h6>
                                <p>В случае обнаружения недостатков товара по истечении гарантийного срока, но в пределах двух лет со дня передачи товара потребителю, продавец (изготовитель) обязан удовлетворить требования потребителя, если потребитель докажет, что недостатки товара возникли до его передачи потребителю или по причинам, возникшим до этого момента.</p>
                            </div>
                            
                            <div class="alert alert-warning">
                                <h6><i class="fas fa-exclamation-triangle"></i> Рекомендации</h6>
                                <p>Рекомендуем потребителю получить доказательства недостатка товара. Вы можете подать претензию до <strong>${getAppealDeadlineDate()}</strong>.</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" onclick="redirectToClaims()">Понятно</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Удаляем существующее модальное окно, если есть
        const existingModal = document.getElementById('lawInfoModal');
        if (existingModal) {
            existingModal.remove();
        }
        
        // Добавляем новое модальное окно
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        
        // Показываем модальное окно
        const modal = new bootstrap.Modal(document.getElementById('lawInfoModal'));
        modal.show();
    }

    // Функция для получения даты окончания срока подачи претензии
    function getAppealDeadlineDate() {
        // Получаем дату из глобальной переменной, установленной при проверке гарантийного срока
        return window.appealDeadlineDate || 'даты окончания срока';
    }

    // Функция для перенаправления на страницу претензий
    window.redirectToClaims = function() {
        window.location.href = '/claims';
    };

    // Функция для сохранения информации о ремонте
    window.saveRepairInfo = function(purchaseId, wasRepairedOfficially, repairDocumentDescription, repairDocumentDate, defectDescription) {
        const formData = new FormData();
        formData.append('purchase_id', purchaseId);
        formData.append('was_repaired_officially', wasRepairedOfficially ? '1' : '0');
        formData.append('repair_document_description', repairDocumentDescription || '');
        formData.append('repair_document_date', repairDocumentDate || '');
        formData.append('defect_description', defectDescription || '');
        formData.append('_csrf', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        fetch('/claim/save-repair-info', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Информация о ремонте сохранена успешно');
            } else {
                console.error('Ошибка сохранения информации о ремонте:', data.message);
            }
        })
        .catch(error => {
            console.error('Ошибка сохранения информации о ремонте:', error);
        });
    };

    // Функция для сохранения информации о доказательствах недостатка
    window.saveDefectProofInfo = function(purchaseId, defectProofType, defectProofDocumentDescription, defectProofDocumentDate, defectDescription) {
        const formData = new FormData();
        formData.append('purchase_id', purchaseId);
        formData.append('defect_proof_type', defectProofType);
        formData.append('defect_proof_document_description', defectProofDocumentDescription || '');
        formData.append('defect_proof_document_date', defectProofDocumentDate || '');
        formData.append('defect_description', defectDescription || '');
        formData.append('_csrf', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        fetch('/claim/save-defect-proof-info', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Информация о доказательствах недостатка сохранена успешно');
            } else {
                console.error('Ошибка сохранения информации о доказательствах недостатка:', data.message);
            }
        })
        .catch(error => {
            console.error('Ошибка сохранения информации о доказательствах недостатка:', error);
        });
    };

    // Обработчик изменения типа претензии
    claimTypeSelect.addEventListener('change', function() {
        const selectedType = this.value;
        
        if (selectedType && selectedType !== 'custom') {
            // Показать шаблоны для всех типов кроме "Свой вариант"
            loadTemplates(selectedType);
            templateSelector.style.display = 'block';
            // Скрыть описание (будет показано после выбора шаблона)
            descriptionGroup.style.display = 'none';
            updateDescriptionPreview('');
        } else if (selectedType === 'custom') {
            // Для "Свой вариант" показать описание и скрыть шаблоны
            templateSelector.style.display = 'none';
            templateSelect.innerHTML = '<option value="">Выберите шаблон...</option>';
            descriptionGroup.style.display = 'block';
            updateDescriptionPreview('');
        } else {
            // Скрыть все для пустого выбора
            templateSelector.style.display = 'none';
            templateSelect.innerHTML = '<option value="">Выберите шаблон...</option>';
            descriptionGroup.style.display = 'none';
            updateDescriptionPreview('');
        }
        
        // Применить стили после изменений
        setTimeout(applyDropdownStyles, 50);
    });

    // Загрузка шаблонов по типу
    function loadTemplates(type) {
        templateSelect.innerHTML = '<option value="">Загрузка...</option>';
        
        fetch(`/claim/get-templates?type=${type}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    templateSelect.innerHTML = '<option value="">Выберите шаблон...</option>';
                    data.templates.forEach(template => {
                        const option = document.createElement('option');
                        option.value = template.id;
                        option.textContent = template.name;
                        option.title = template.description;
                        templateSelect.appendChild(option);
                    });
                } else {
                    templateSelect.innerHTML = '<option value="">Шаблоны не найдены</option>';
                }
                
                // Применить стили после загрузки шаблонов
                setTimeout(applyDropdownStyles, 50);
            })
            .catch(error => {
                console.error('Ошибка загрузки шаблонов:', error);
                templateSelect.innerHTML = '<option value="">Ошибка загрузки</option>';
            });
    }

    // Загрузка содержимого шаблона
    window.loadTemplate = function() {
        const templateId = templateSelect.value;
        const purchaseId = purchaseSelect.value;
        
        if (!templateId || !purchaseId) {
            // Если шаблон не выбран, скрыть описание
            descriptionGroup.style.display = 'none';
            updateDescriptionPreview('');
            return;
        }

        // Показать описание перед загрузкой
        descriptionGroup.style.display = 'block';
        updateDescriptionPreview('Загрузка шаблона...');
        
        fetch(`/claim/get-template-content?template_id=${templateId}&purchase_id=${purchaseId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Конвертируем текст в HTML для Word-редактора
                    const htmlContent = data.content.replace(/\n/g, '<br>');
                    updateDescriptionPreview(data.content);
                    
                    // Сохраняем HTML-версию для Word-редактора
                    const wordEditor = document.getElementById('word-editor');
                    if (wordEditor) {
                        wordEditor.innerHTML = htmlContent;
                        // Проверяем содержимое после загрузки шаблона
                        checkEditorContent();
                    }
                } else {
                    console.error('Ошибка загрузки шаблона: ' + data.message);
                    updateDescriptionPreview('');
                }
                
                // Применить стили после загрузки содержимого
                setTimeout(applyDropdownStyles, 50);
            })
            .catch(error => {
                console.error('Ошибка загрузки шаблона:', error);
            })
            .finally(() => {
                // Убираем индикатор загрузки
            });
    };

    // Функция для сохранения как пользовательский шаблон (из основной формы)
    window.saveAsUserTemplate = function() {
        console.log('saveAsUserTemplate вызвана');
        
        // Получаем содержимое из превью
        let content = '';
        const previewText = descriptionPreview.querySelector('p').textContent;
        if (previewText && previewText !== 'Описание не заполнено') {
            content = previewText.replace(/\n/g, '<br>');
            console.log('Содержимое взято из превью');
        }
        
        console.log('Содержимое для сохранения:', content);
        
        if (!content || content.trim() === '') {
            return;
        }

        // Получаем текущий тип претензии
        let currentClaimType = claimTypeSelect.value;
        if (!currentClaimType) {
            return;
        }

        // Получаем ID оригинального шаблона, если он был выбран
        const selectedTemplateId = templateSelect.value;
        let originalTemplateId = '';
        
        if (selectedTemplateId && selectedTemplateId.startsWith('default_')) {
            originalTemplateId = selectedTemplateId.replace('default_', '');
        }

        // Заполняем форму сохранения
        document.getElementById('template-original-id').value = originalTemplateId;
        document.getElementById('template-type').value = currentClaimType;
        document.getElementById('template-content').value = content;
        
        // Предлагаем название на основе типа претензии
        const typeNames = {
            'repair': 'Ремонт',
            'refund': 'Возврат денежных средств',
            'replacement': 'Замена товара',
            'delivery': 'Проблемы с доставкой',
            'custom': 'Пользовательский'
        };
        
        const defaultName = typeNames[currentClaimType] || 'Пользовательский шаблон';
        document.getElementById('template-name').value = defaultName + ' - ' + new Date().toLocaleDateString();
        
        // Устанавливаем тип претензии в дропдаун
        document.getElementById('template-type-select').value = currentClaimType;
        
        // Показываем модальное окно
        const modal = new bootstrap.Modal(document.getElementById('saveTemplateModal'));
        modal.show();
    };

    // Функция для сохранения как пользовательский шаблон (из модального окна)
    window.saveAsUserTemplateFromModal = function() {
        console.log('saveAsUserTemplateFromModal вызвана');
        
        // Получаем содержимое из Word-редактора
        const wordEditor = document.getElementById('word-editor');
        let content = '';
        
        if (wordEditor && wordEditor.innerHTML.trim() !== '') {
            content = wordEditor.innerHTML;
            console.log('Содержимое взято из Word-редактора');
        }
        
        console.log('Содержимое для сохранения:', content);
        
        if (!content || content.trim() === '') {
            return;
        }

        // Получаем текущий тип претензии
        let currentClaimType = claimTypeSelect.value;
        if (!currentClaimType) {
            return;
        }

        // Получаем ID оригинального шаблона, если он был выбран
        const selectedTemplateId = templateSelect.value;
        let originalTemplateId = '';
        
        if (selectedTemplateId && selectedTemplateId.startsWith('default_')) {
            originalTemplateId = selectedTemplateId.replace('default_', '');
        }

        // Заполняем форму сохранения
        document.getElementById('template-original-id').value = originalTemplateId;
        document.getElementById('template-type').value = currentClaimType;
        document.getElementById('template-content').value = content;
        
        // Предлагаем название на основе типа претензии
        const typeNames = {
            'repair': 'Ремонт',
            'refund': 'Возврат денежных средств',
            'replacement': 'Замена товара',
            'delivery': 'Проблемы с доставкой',
            'custom': 'Пользовательский'
        };
        
        const defaultName = typeNames[currentClaimType] || 'Пользовательский шаблон';
        document.getElementById('template-name').value = defaultName + ' - ' + new Date().toLocaleDateString();
        
        // Закрываем текущее модальное окно
        const descriptionModal = bootstrap.Modal.getInstance(document.getElementById('descriptionModal'));
        descriptionModal.hide();
        
        // Устанавливаем тип претензии в дропдаун
        let modalClaimType = document.getElementById('claim-type-select').value;
        document.getElementById('template-type-select').value = modalClaimType;
        
        // Показываем модальное окно сохранения шаблона
        setTimeout(() => {
            const modal = new bootstrap.Modal(document.getElementById('saveTemplateModal'));
            modal.show();
        }, 300);
    };

    // Функция для подтверждения сохранения шаблона
    window.confirmSaveTemplate = function() {
        console.log('confirmSaveTemplate вызвана');
        
        const name = document.getElementById('template-name').value.trim();
        const description = document.getElementById('template-description').value.trim();
        const isFavorite = document.getElementById('template-favorite').checked;
        const originalTemplateId = document.getElementById('template-original-id').value;
        const type = document.getElementById('template-type-select').value;
        const content = document.getElementById('template-content').value;

        console.log('Данные для сохранения:', {
            name: name,
            description: description,
            type: type,
            originalTemplateId: originalTemplateId,
            contentLength: content.length
        });

        if (!name) {
            return;
        }
        
        if (!type) {
            return;
        }

        // Показываем индикатор загрузки
        const saveBtn = document.querySelector('#saveTemplateModal .btn-success');
        const originalText = saveBtn.innerHTML;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Сохранение...';
        saveBtn.disabled = true;

        // Отправляем запрос на сохранение
        const formData = new FormData();
        formData.append('original_template_id', originalTemplateId);
        formData.append('name', name);
        formData.append('description', description);
        formData.append('content', content);
        formData.append('type', type);
        
        // Добавляем CSRF токен
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            formData.append('_csrf', csrfToken.getAttribute('content'));
        }

        fetch('/claim/save-user-template', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                return response.text().then(text => {
                    console.error('Non-JSON response:', text);
                    throw new Error('Server returned non-JSON response');
                });
            }
            
            return response.json();
        })
        .then(data => {
            console.log('Success response:', data);
            if (data.success) {
                // Перезагружаем список шаблонов
                if (claimTypeSelect.value) {
                    loadTemplates(claimTypeSelect.value);
                }
                
                // Очищаем форму
                document.getElementById('save-template-form').reset();
                
                // Закрываем модальное окно
                const modal = bootstrap.Modal.getInstance(document.getElementById('saveTemplateModal'));
                modal.hide();
                
                // Убираем фокус с кнопки
                saveBtn.blur();
            } else {
                console.error('Ошибка сохранения: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Ошибка сохранения шаблона:', error);
        })
        .finally(() => {
            // Восстанавливаем кнопку
            saveBtn.innerHTML = originalText;
            saveBtn.disabled = false;
        });
    };

    // Функция для показа менеджера шаблонов (заглушка)
    window.showTemplateManager = function() {
        console.log('Менеджер шаблонов будет добавлен в следующей версии');
    };

    // Функция для создания нового шаблона
    window.createNewTemplate = function() {
        const wordEditor = document.getElementById('word-editor');
        if (wordEditor) {
            // Очищаем редактор и фокусируемся на нем
            wordEditor.innerHTML = '';
            wordEditor.focus();
            
            // Скрываем подсказку
            const prompt = document.getElementById('create-template-prompt');
            if (prompt) {
                prompt.style.display = 'none';
            }
        }
    };

    // Функция для проверки содержимого редактора
    function checkEditorContent() {
        const wordEditor = document.getElementById('word-editor');
        const prompt = document.getElementById('create-template-prompt');
        const downloadBtn = document.getElementById('download-btn');
        const modalDownloadBtn = document.getElementById('modal-download-btn');
        
        if (wordEditor && prompt) {
            const hasContent = wordEditor.innerHTML.trim() !== '';
            
            if (hasContent) {
                prompt.style.display = 'none';
            } else {
                prompt.style.display = 'block';
            }
        }
        
        // Обновляем состояние кнопок скачивания
        if (wordEditor) {
            const hasContent = wordEditor.innerHTML.trim() !== '';
            
            if (downloadBtn) {
                downloadBtn.disabled = !hasContent;
            }
            if (modalDownloadBtn) {
                modalDownloadBtn.disabled = !hasContent;
            }
        }
    }

    // Функция для скачивания шаблона как документа
    window.downloadTemplate = async function() {
        const wordEditor = document.getElementById('word-editor');
        if (!wordEditor || wordEditor.innerHTML.trim() === '') {
            return;
        }

        // Показываем индикатор загрузки
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Создание документа...';
        button.disabled = true;

        try {
            // Получаем содержимое из редактора
            let content = wordEditor.innerHTML;
            
            // Создаем FormData для отправки на сервер
            const formData = new FormData();
            formData.append('content', content);
            
            // Добавляем ID покупки для формирования имени файла
            const purchaseId = document.getElementById('purchase_id');
            if (purchaseId && purchaseId.value) {
                formData.append('purchase_id', purchaseId.value);
                console.log('Purchase ID sent:', purchaseId.value);
            } else {
                console.log('No purchase ID found');
            }
            
            // Добавляем тип претензии для формирования имени файла
            const claimType = document.getElementById('claim-type-select');
            if (claimType && claimType.value) {
                formData.append('claim_type', claimType.value);
                console.log('Claim type sent:', claimType.value);
            } else {
                console.log('No claim type found');
            }
            
            // Добавляем CSRF токен
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                formData.append('_csrf', csrfToken.getAttribute('content'));
            }
            
            // Отправляем запрос на сервер
            const response = await fetch('/claim/generate-docx', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            // Получаем файл
            const blob = await response.blob();
            
            // Получаем имя файла из заголовков ответа
            const contentDisposition = response.headers.get('Content-Disposition');
            let filename = `claim_${new Date().toISOString().split('T')[0]}.docx`;
            
            if (contentDisposition) {
                const filenameMatch = contentDisposition.match(/filename="(.+)"/);
                if (filenameMatch) {
                    filename = filenameMatch[1];
                }
            }
            
            // Создаем ссылку для скачивания
            const url = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            window.URL.revokeObjectURL(url);
            
        } catch (error) {
            console.error('Ошибка при создании DOCX файла:', error);
        } finally {
            // Восстанавливаем кнопку
            button.innerHTML = originalText;
            button.disabled = false;
        }
    };

    // Резервная функция для скачивания в формате RTF
    function downloadAsRTF() {
        const wordEditor = document.getElementById('word-editor');
        if (!wordEditor || wordEditor.innerHTML.trim() === '') {
            return;
        }

        // Получаем содержимое из редактора
        let content = wordEditor.innerHTML;
        
        // Конвертируем HTML в простой текст для документа
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = content;
        const plainText = tempDiv.textContent || tempDiv.innerText || '';
        
        // Создаем RTF документ (Rich Text Format) - совместимый с Word
        const rtfContent = `{\\rtf1\\ansi\\deff0 {\\fonttbl {\\f0 Times New Roman;}}
{\\colortbl;\\red0\\green0\\blue0;}
\\f0\\fs28\\b ПРЕТЕНЗИЯ\\b0\\fs24\\par\\par
${plainText.replace(/\n/g, '\\par ').replace(/\t/g, '\\tab ')}
\\par\\par
Дата: ${new Date().toLocaleDateString('ru-RU')}\\par
}`;

        // Создаем и скачиваем RTF файл
        const blob = new Blob([rtfContent], { type: 'application/rtf;charset=utf-8' });
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = `претензия_${new Date().toISOString().split('T')[0]}.rtf`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        window.URL.revokeObjectURL(url);
        
        console.log('Документ скачан в формате RTF (совместимый с Word)');
    }

    // Обновляем функцию loadTemplate для показа кнопки сохранения
    const originalLoadTemplate = window.loadTemplate;
    window.loadTemplate = function() {
        originalLoadTemplate();
        
        // Показываем кнопку сохранения шаблона
        const saveTemplateBtn = document.getElementById('save-template-btn');
        if (saveTemplateBtn) {
            saveTemplateBtn.style.display = 'inline-block';
        }
    };
});
</script>
