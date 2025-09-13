<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Claim */

$this->title = 'Претензия по покупке: ' . ($model->purchase ? $model->purchase->product_name : 'Не указана');
$this->params['breadcrumbs'][] = ['label' => 'Панель управления', 'url' => ['/dashboard']];
$this->params['breadcrumbs'][] = ['label' => 'Претензии', 'url' => ['/claim/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="claim-view">
    <div class="row">
        <div class="col-lg-8">
            <div class="claim-details-card">
                <div class="card-header">
                    <h2>
                        <i class="fas fa-exclamation-triangle"></i>
                        Претензия по покупке: <?= Html::encode($model->purchase ? $model->purchase->product_name : 'Не указана') ?>
                    </h2>
                </div>
                
                <div class="card-body">
                    <div class="claim-info">
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Покупка:</label>
                                <span class="purchase-link">
                                    <?php if ($model->purchase): ?>
                                        <?= Html::a(
                                            Html::encode($model->purchase->product_name),
                                            ['/purchase/view', 'id' => $model->purchase->id],
                                            ['class' => 'purchase-name-link']
                                        ) ?>
                                    <?php else: ?>
                                        Не указана
                                    <?php endif; ?>
                                </span>
                            </div>
                            
                            <div class="info-item">
                                <label>Тип претензии:</label>
                                <span class="claim-type-badge"><?= $model->getClaimTypeLabel() ?></span>
                            </div>
                            
                            <?php if ($model->resolution_date): ?>
                                <div class="info-item">
                                    <label>Дата решения:</label>
                                    <span class="resolution-date"><?= $model->getFormattedResolutionDate() ?></span>
                                </div>
                            <?php endif; ?>
                            
                            
                            <?php if ($model->amount_resolved): ?>
                                <div class="info-item">
                                    <label>Сумма решения:</label>
                                    <span class="resolution-amount"><?= $model->getFormattedAmountResolved() ?></span>
                                </div>
                            <?php endif; ?>
                            
                            
                            <?php if ($model->resolution_notes): ?>
                                <div class="info-item full-width">
                                    <label>Примечания по решению:</label>
                                    <span class="resolution-notes"><?= Html::encode($model->resolution_notes) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Секция с текстом претензии -->
            <div class="claim-text-card">
                <div class="card-header collapsible-header" onclick="toggleClaimText()">
                    <h3>
                        <i class="fas fa-file-text"></i>
                        Претензия
                        <i class="fas fa-chevron-down collapse-icon"></i>
                    </h3>
                </div>
                <div class="card-body" id="claim-text-body">
                    <div class="claim-text-section">
                        <div class="claim-text-preview" id="claim-text-preview">
                            <div class="claim-text-content">
                                <?php if ($model->description): ?>
                                    <?= $model->description ?>
                                <?php else: ?>
                                    <div class="empty-state">
                                        <i class="fas fa-file-text"></i>
                                        <p>Текст претензии не заполнен</p>
                                        <small>Нажмите "Редактировать" чтобы добавить текст претензии</small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="claim-text-actions">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="openClaimTextModal()">
                                <i class="fas fa-edit"></i> Редактировать
                            </button>
                            <button type="button" class="btn btn-outline-success btn-sm" onclick="downloadClaimText()" <?= !$model->description ? 'disabled' : '' ?>>
                                <i class="fas fa-file-word"></i> Скачать в Word
                            </button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Секция с информацией о ремонте и доказательствах -->
            <?php if ($model->purchase): ?>
                <div class="repair-info-card">
                    <div class="card-header collapsible-header" onclick="toggleRepairInfo()">
                        <h3>
                            <i class="fas fa-tools"></i>
                            Информация о ремонте и доказательствах
                            <i class="fas fa-chevron-down collapse-icon"></i>
                        </h3>
                    </div>
                    <div class="card-body" id="repair-info-body">
                        <div class="repair-info-grid">
                            <!-- Информация о ремонте -->
                            <div class="repair-section">
                                <h4><i class="fas fa-wrench"></i> Информация о ремонте</h4>
                                <?php if ($model->purchase->was_repaired_officially): ?>
                                    <div class="info-item">
                                        <label>Реквизиты акта выполненных работ:</label>
                                        <span class="repair-document"><?= Html::encode($model->purchase->repair_document_description ?: 'Не указано') ?></span>
                                    </div>
                                    
                                    <div class="info-item">
                                        <label>Дата выдачи документа о ремонте:</label>
                                        <span class="repair-date"><?= $model->purchase->repair_document_date ? Yii::$app->formatter->asDate($model->purchase->repair_document_date, 'php:d.m.Y') : 'Не указана' ?></span>
                                    </div>
                                    
                                    <?php if ($model->purchase->repair_defect_description): ?>
                                        <div class="info-item">
                                            <label>Недостаток согласно акту выполненных работ:</label>
                                            <span class="repair-defect"><?= Html::encode($model->purchase->repair_defect_description) ?></span>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php if ($model->purchase->current_defect_description): ?>
                                        <div class="info-item">
                                            <label>Описание текущего недостатка:</label>
                                            <span class="current-defect"><?= Html::encode($model->purchase->current_defect_description) ?></span>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Информация о доказательствах недостатка -->
                            <div class="proof-section">
                                <h4><i class="fas fa-file-medical"></i> Доказательства недостатка</h4>
                                <div class="info-item">
                                    <label>Тип доказательства:</label>
                                    <span class="proof-type"><?= $model->purchase->getDefectProofTypeLabel() ?></span>
                                </div>
                                
                                <?php if ($model->purchase->defect_proof_type === 'quality_check' || $model->purchase->defect_proof_type === 'independent_expertise'): ?>
                                    <div class="info-item">
                                        <label>Реквизиты документа:</label>
                                        <span class="proof-document"><?= Html::encode($model->purchase->defect_proof_document_description ?: 'Не указано') ?></span>
                                    </div>
                                    
                                    <div class="info-item">
                                        <label>Дата выдачи документа:</label>
                                        <span class="proof-date"><?= $model->purchase->defect_proof_document_date ? Yii::$app->formatter->asDate($model->purchase->defect_proof_document_date, 'php:d.m.Y') : 'Не указана' ?></span>
                                    </div>
                                    
                                    <?php if ($model->purchase->expertise_defect_description): ?>
                                        <div class="info-item">
                                            <label>Описание недостатка при экспертизе:</label>
                                            <span class="expertise-defect"><?= Html::encode($model->purchase->expertise_defect_description) ?></span>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php if ($model->purchase->general_defect_description): ?>
                                        <div class="info-item">
                                            <label>Описание недостатка:</label>
                                            <span class="general-defect"><?= Html::encode($model->purchase->general_defect_description) ?></span>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="col-lg-4">
            <div class="claim-actions-card">
                <div class="card-header">
                    <h3>
                        <i class="fas fa-cogs"></i>
                        Действия
                    </h3>
                </div>
                <div class="card-body">
                    <div class="action-buttons">
                        <?php if ($model->canEdit()): ?>
                            <?= Html::a('<i class="fas fa-edit"></i> Редактировать', ['update', 'id' => $model->id], [
                                'class' => 'btn btn-warning btn-block'
                            ]) ?>
                        <?php endif; ?>
                        
                        <?php if ($model->canDelete()): ?>
                            <?= Html::a('<i class="fas fa-trash"></i> Удалить', ['delete', 'id' => $model->id], [
                                'class' => 'btn btn-danger btn-block',
                                'data-confirm' => 'Вы уверены, что хотите удалить эту претензию?',
                                'data-method' => 'post'
                            ]) ?>
                        <?php endif; ?>
                        
                        <?= Html::a('<i class="fas fa-shipping-fast"></i> Отслеживание', ['/tracking/index', 'id' => $model->id], [
                            'class' => 'btn btn-info btn-block'
                        ]) ?>
                        
                        <?= Html::a('<i class="fas fa-list"></i> К списку претензий', ['index'], [
                            'class' => 'btn btn-secondary btn-block'
                        ]) ?>
                        
                        <?= Html::a('<i class="fas fa-shopping-cart"></i> К покупкам', ['/purchase/index'], [
                            'class' => 'btn btn-primary btn-block purchases-btn'
                        ]) ?>
                    </div>
                </div>
            </div>
            
            <div class="claim-stats-card">
                <div class="card-header">
                    <h3>
                        <i class="fas fa-chart-bar"></i>
                        Информация
                    </h3>
                </div>
                <div class="card-body">
                    <div class="stats-grid">
                        <div class="stat-item">
                            <label>Почтовый идентификатор:</label>
                            <div class="tracking-input-group">
                                <input type="text" 
                                       id="tracking-number-input" 
                                       class="tracking-input" 
                                       value="<?= Html::encode($model->tracking_number ?: '') ?>" 
                                       placeholder="Введите номер отслеживания">
                                <button type="button" 
                                        class="btn btn-sm btn-outline-primary" 
                                        onclick="checkTrackingStatus()">
                                    <i class="fas fa-search"></i> Проверить
                                </button>
                            </div>
                        </div>
                        
                        <div class="stat-item">
                            <label>Статус отправления:</label>
                            <span class="tracking-status <?= !$model->tracking_status ? 'no-info' : '' ?>" id="tracking-status-display">
                                <?= $model->tracking_status ? Html::encode($model->tracking_status) : 'Нет информации об отправлении' ?>
                            </span>
                        </div>
                        
                        <div class="stat-item">
                            <label>Дата создания:</label>
                            <span><?= date('d.m.Y H:i', $model->created_at) ?></span>
                        </div>
                        
                        <?php if ($model->updated_at != $model->created_at): ?>
                            <div class="stat-item">
                                <label>Последнее обновление:</label>
                                <span><?= date('d.m.Y H:i', $model->updated_at) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно для редактирования текста претензии -->
<div class="modal fade" id="claimTextModal" tabindex="-1" aria-labelledby="claimTextModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="claimTextModalLabel">
                    <i class="fas fa-edit"></i> Редактирование текста претензии
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Панель инструментов Word-стиль -->
                <div class="word-toolbar">
                    <div class="toolbar-group">
                        <button type="button" class="toolbar-btn" onclick="formatClaimText('bold')" title="Жирный">
                            <i class="fas fa-bold"></i>
                        </button>
                        <button type="button" class="toolbar-btn" onclick="formatClaimText('italic')" title="Курсив">
                            <i class="fas fa-italic"></i>
                        </button>
                        <button type="button" class="toolbar-btn" onclick="formatClaimText('underline')" title="Подчеркнутый">
                            <i class="fas fa-underline"></i>
                        </button>
                    </div>
                    
                    <div class="toolbar-separator"></div>
                    
                    <div class="toolbar-group">
                        <select class="toolbar-select" onchange="formatClaimText('fontSize', this.value)" title="Размер шрифта">
                            <option value="">Размер</option>
                            <option value="12">12</option>
                            <option value="14" selected>14</option>
                            <option value="16">16</option>
                            <option value="18">18</option>
                            <option value="20">20</option>
                        </select>
                        
                        <select class="toolbar-select" onchange="formatClaimText('fontFamily', this.value)" title="Шрифт">
                            <option value="Times New Roman" selected>Times New Roman</option>
                            <option value="Arial">Arial</option>
                            <option value="Calibri">Calibri</option>
                            <option value="Georgia">Georgia</option>
                        </select>
                    </div>
                    
                    <div class="toolbar-separator"></div>
                    
                    <div class="toolbar-group">
                        <button type="button" class="toolbar-btn" onclick="formatClaimText('justifyLeft')" title="По левому краю">
                            <i class="fas fa-align-left"></i>
                        </button>
                        <button type="button" class="toolbar-btn" onclick="formatClaimText('justifyCenter')" title="По центру">
                            <i class="fas fa-align-center"></i>
                        </button>
                        <button type="button" class="toolbar-btn" onclick="formatClaimText('justifyRight')" title="По правому краю">
                            <i class="fas fa-align-right"></i>
                        </button>
                        <button type="button" class="toolbar-btn" onclick="formatClaimText('justifyFull')" title="По ширине">
                            <i class="fas fa-align-justify"></i>
                        </button>
                    </div>
                    
                    <div class="toolbar-separator"></div>
                    
                    <div class="toolbar-group">
                        <button type="button" class="toolbar-btn" onclick="formatClaimText('insertUnorderedList')" title="Маркированный список">
                            <i class="fas fa-list-ul"></i>
                        </button>
                        <button type="button" class="toolbar-btn" onclick="formatClaimText('insertOrderedList')" title="Нумерованный список">
                            <i class="fas fa-list-ol"></i>
                        </button>
                    </div>
                    
                    <div class="toolbar-separator"></div>
                    
                    <div class="toolbar-group">
                        <button type="button" class="toolbar-btn" onclick="insertClaimTextTab()" title="Вставить табуляцию (4 пробела)">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                        <button type="button" class="toolbar-btn" onclick="formatClaimText('indent')" title="Увеличить отступ">
                            <i class="fas fa-indent"></i>
                        </button>
                        <button type="button" class="toolbar-btn" onclick="formatClaimText('outdent')" title="Уменьшить отступ">
                            <i class="fas fa-outdent"></i>
                        </button>
                    </div>
                </div>
                
                <div class="word-editor-container">
                    <div class="word-editor" id="claim-text-editor" contenteditable="true" tabindex="-1" data-placeholder="Введите текст претензии...">
                        <div class="loading-indicator" id="claim-text-loading" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i> Загрузка контента...
                        </div>
                    </div>
                </div>
                
                <div class="form-text mt-3">
                    <i class="fas fa-info-circle"></i> 
                    Вы можете редактировать текст претензии с помощью панели инструментов выше
                </div>
                
                <!-- Кнопка скачивания в модальном окне -->
                <div class="mt-3 text-center">
                    <button type="button" class="btn btn-outline-primary" onclick="downloadClaimText()" id="claim-text-modal-download-btn">
                        <i class="fas fa-file-word"></i> Скачать в Word
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Отмена
                </button>
                <button type="button" class="btn btn-success" onclick="saveClaimText()">
                    <i class="fas fa-save"></i> Сохранить изменения
                </button>
            </div>
        </div>
    </div>
</div>


<style>
.claim-details-card,
.claim-actions-card,
.claim-stats-card {
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

/* Стили для сворачиваемого заголовка */
.collapsible-header {
    cursor: pointer;
    transition: all 0.3s ease;
    user-select: none;
}

.collapsible-header:hover {
    background: linear-gradient(135deg, #e0a800 0%, #c47a00 100%);
}

.collapsible-header h3 {
    justify-content: space-between;
    width: 100%;
}

.collapse-icon {
    transition: transform 0.3s ease;
    font-size: 1.2rem;
}

.collapse-icon.collapsed {
    transform: rotate(-90deg);
}

.card-body {
    padding: 30px;
    transition: max-height 0.3s ease, height 0.3s ease, padding 0.3s ease, opacity 0.3s ease;
    overflow: hidden;
    max-height: none;
    height: auto;
    box-sizing: border-box;
}

.card-body.collapsed {
    max-height: 0 !important;
    height: 0 !important;
    padding: 0 !important;
    opacity: 0;
}

.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.info-item.full-width {
    grid-column: 1 / -1;
}

.info-item label {
    font-weight: 600;
    color: #333;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-item span {
    color: #666;
    font-size: 1rem;
}

.purchase-name-link {
    color: #059669;
    font-weight: 600;
    text-decoration: none;
}

.purchase-name-link:hover {
    color: #047857;
    text-decoration: underline;
}

.claim-type-badge {
    color: #374151;
    font-size: 0.9rem;
    font-weight: 500;
    display: inline-block;
}

.claim-date {
    color: #6B7280;
    font-weight: 500;
}

.resolution-date {
    color: #059669;
    font-weight: 500;
}

.claim-amount {
    color: #EF4444;
    font-weight: 600;
}

.resolution-amount {
    color: #059669;
    font-weight: 600;
}

.claim-description,
.resolution-notes {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 10px;
    border-left: 4px solid #f59e0b;
    line-height: 1.6;
}

.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.btn {
    padding: 12px 20px;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.btn-block {
    width: 100%;
}

.btn-warning {
    background: linear-gradient(135deg, #F59E0B, #D97706);
    color: white;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #D97706, #B45309);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(245, 158, 11, 0.3);
    color: white;
    text-decoration: none;
}

.btn-danger {
    background: linear-gradient(135deg, #EF4444, #DC2626);
    color: white;
}

.btn-danger:hover {
    background: linear-gradient(135deg, #DC2626, #B91C1C);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(239, 68, 68, 0.3);
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

.btn-primary {
    background: linear-gradient(135deg, #3B82F6, #1D4ED8);
    color: white;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #1D4ED8, #1E40AF);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
    color: white;
    text-decoration: none;
}

.purchases-btn {
    text-transform: none !important;
    font-weight: 500 !important;
}

.stats-grid {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.stat-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.stat-item label {
    font-weight: 600;
    color: #333;
    font-size: 0.9rem;
}

.stat-item span {
    color: #666;
    font-size: 0.95rem;
}

.tracking-input-group {
    display: flex;
    gap: 8px;
    margin-top: 5px;
}

.tracking-input {
    flex: 1;
    padding: 8px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 0.9rem;
    font-family: monospace;
    background: #f9fafb;
    color: #374151;
}

.tracking-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    background: white;
}

.tracking-status {
    background: #dbeafe;
    color: #1e40af;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 0.9rem;
    font-weight: 500;
    display: inline-block;
    margin-top: 5px;
}

.tracking-status.no-info {
    background: #f3f4f6;
    color: #6b7280;
}

.badge {
    font-size: 0.9rem;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 500;
}

.badge-warning { background: linear-gradient(135deg, #F59E0B, #D97706); color: white; }
.badge-info { background: linear-gradient(135deg, #3B82F6, #1D4ED8); color: white; }
.badge-success { background: linear-gradient(135deg, #10B981, #059669); color: white; }
.badge-danger { background: linear-gradient(135deg, #EF4444, #DC2626); color: white; }
.badge-secondary { background: linear-gradient(135deg, #6B7280, #4B5563); color: white; }

/* Стили для секции текста претензии */
.claim-text-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    overflow: hidden;
}

.claim-text-section {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    border-left: 4px solid #10B981;
}


.claim-text-preview {
    margin-bottom: 15px;
}

.claim-text-content {
    background: white;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    line-height: 1.6;
    max-height: 200px;
    overflow-y: auto;
}

.claim-text-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.claim-text-actions .btn {
    padding: 8px 16px;
    font-size: 0.9rem;
}

.claim-text-actions .btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #6b7280;
}

.empty-state i {
    font-size: 3rem;
    color: #d1d5db;
    margin-bottom: 15px;
    display: block;
}

.empty-state p {
    font-size: 1.1rem;
    margin-bottom: 10px;
    color: #374151;
}

.empty-state small {
    color: #9ca3af;
    font-style: italic;
}


/* Стили для Word-редактора в модальном окне */
.word-editor-container {
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    background: white;
    min-height: 400px;
    margin-bottom: 20px;
}

.word-editor {
    padding: 20px;
    min-height: 400px;
    font-family: 'Times New Roman', serif;
    font-size: 14px;
    line-height: 1.6;
    outline: none;
    border: none;
    background: white;
}

.word-editor:empty:before {
    content: attr(data-placeholder);
    color: #9ca3af;
    font-style: italic;
}

.word-editor:focus {
    border-color: #3B82F6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.word-editor p {
    margin: 0 0 10px 0;
}

.word-editor ul, .word-editor ol {
    margin: 10px 0;
    padding-left: 30px;
}

.word-editor li {
    margin: 5px 0;
}

/* Стили для секции информации о ремонте и доказательствах */
.repair-info-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    overflow: hidden;
}

.repair-info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
}

.repair-section .info-item,
.proof-section .info-item {
    margin-bottom: 20px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.repair-section .info-item:last-child,
.proof-section .info-item:last-child {
    margin-bottom: 0;
}

.repair-section .info-item label,
.proof-section .info-item label {
    font-weight: 600;
    color: #374151;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 5px;
}

.repair-section,
.proof-section {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 25px;
    border-left: 4px solid #3B82F6;
}

.repair-section h4,
.proof-section h4 {
    color: #1f2937;
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0 0 25px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.repair-section {
    border-left-color: #f59e0b;
}

.proof-section {
    border-left-color: #10b981;
}

.repair-status,
.proof-type {
    font-weight: 600;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 0.9rem;
    display: inline-block;
    margin-top: 5px;
}

.repair-status {
    background: #fef3c7;
    color: #92400e;
}

.proof-type {
    background: #d1fae5;
    color: #065f46;
}

.repair-document,
.proof-document {
    background: #f3f4f6;
    padding: 12px 16px;
    border-radius: 8px;
    font-family: monospace;
    font-size: 0.9rem;
    word-break: break-all;
    margin-top: 5px;
}

.repair-date,
.proof-date {
    color: #6b7280;
    font-weight: 500;
}

.repair-defect,
.current-defect,
.expertise-defect,
.general-defect {
    background: #fef2f2;
    padding: 15px 20px;
    border-radius: 8px;
    border-left: 4px solid #ef4444;
    line-height: 1.6;
    font-style: italic;
    margin-top: 8px;
}

@media (max-width: 768px) {
    .repair-info-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
}

/* Стили для деталей отслеживания */
.tracking-details {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.tracking-details .detail-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.tracking-details .detail-item label {
    font-weight: 600;
    color: #374151;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.tracking-details .status-badge {
    background: #dbeafe;
    color: #1e40af;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 0.9rem;
    font-weight: 500;
    display: inline-block;
}

.tracking-details .tracking-number {
    background: #f3f4f6;
    padding: 8px 12px;
    border-radius: 6px;
    font-family: monospace;
    font-size: 0.9rem;
    color: #374151;
    font-weight: 500;
    display: inline-block;
}

.tracking-history {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 10px;
}

.history-item {
    background: #f8f9fa;
    padding: 12px;
    border-radius: 8px;
    border-left: 4px solid #3b82f6;
}

.history-date {
    font-size: 0.8rem;
    color: #6b7280;
    margin-bottom: 5px;
}

.history-status {
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 3px;
}

.history-location {
    font-size: 0.9rem;
    color: #6b7280;
    font-style: italic;
}

/* Стили для модального окна деталей отслеживания */
.tracking-details-container {
    display: flex;
    flex-direction: column;
    gap: 25px;
}

.tracking-info-section,
.sender-recipient-section,
.dates-section,
.history-section {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 20px;
    border-left: 4px solid #3B82F6;
}

.tracking-info-section h6,
.sender-recipient-section h6,
.dates-section h6,
.history-section h6 {
    color: #1f2937;
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0 0 20px 0;
    display: flex;
    align-items: center;
    gap: 8px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e5e7eb;
}

.tracking-info-section .info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
}

.tracking-info-section .info-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.tracking-info-section .info-item label {
    font-weight: 600;
    color: #374151;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.tracking-number {
    background: #f3f4f6;
    padding: 8px 12px;
    border-radius: 6px;
    font-family: 'Courier New', monospace;
    font-size: 0.9rem;
    color: #374151;
    font-weight: 600;
    display: inline-block;
}

.carrier-name {
    background: #dbeafe;
    color: #1e40af;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 0.9rem;
    font-weight: 500;
    display: inline-block;
}

.status-badge {
    background: linear-gradient(135deg, #10B981, #059669);
    color: white;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    display: inline-block;
}

.last-update {
    color: #6b7280;
    font-weight: 500;
}

/* Стили для секции отправителя и получателя */
.sender-recipient-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.sender-info,
.recipient-info {
    background: white;
    border-radius: 10px;
    padding: 20px;
    border: 1px solid #e5e7eb;
}

.sender-info h7,
.recipient-info h7 {
    color: #1f2937;
    font-size: 1rem;
    font-weight: 600;
    margin: 0 0 15px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.contact-details {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.contact-item {
    color: #374151;
    font-size: 0.95rem;
    line-height: 1.5;
}

.contact-item strong {
    color: #1f2937;
    font-weight: 600;
}

/* Стили для секции дат */
.dates-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.date-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.date-item label {
    font-weight: 600;
    color: #374151;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.ship-date,
.estimated-delivery {
    background: #fef3c7;
    color: #92400e;
    padding: 8px 12px;
    border-radius: 6px;
    font-weight: 500;
    display: inline-block;
}

/* Стили для истории статусов */
.tracking-history {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.history-item {
    display: flex;
    gap: 15px;
    align-items: flex-start;
}

.history-timeline {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex-shrink: 0;
}

.timeline-dot {
    width: 12px;
    height: 12px;
    background: #3B82F6;
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 0 0 2px #3B82F6;
}

.timeline-line {
    width: 2px;
    height: 30px;
    background: #e5e7eb;
    margin-top: 5px;
}

.history-content {
    flex: 1;
    background: white;
    border-radius: 10px;
    padding: 15px;
    border: 1px solid #e5e7eb;
}

.history-date {
    font-size: 0.8rem;
    color: #6b7280;
    margin-bottom: 8px;
    font-weight: 500;
}

.history-status {
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 5px;
    font-size: 0.95rem;
}

.history-location {
    font-size: 0.9rem;
    color: #6b7280;
    margin-bottom: 5px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.history-description {
    font-size: 0.9rem;
    color: #374151;
    line-height: 1.4;
    font-style: italic;
}

/* Адаптивность */
@media (max-width: 768px) {
    .sender-recipient-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .tracking-info-section .info-grid {
        grid-template-columns: 1fr;
    }
    
    .dates-grid {
        grid-template-columns: 1fr;
    }
}

/* Стили для панели инструментов Word-редактора */
.word-toolbar {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px 8px 0 0;
    padding: 10px 15px;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 0;
}

.toolbar-group {
    display: flex;
    align-items: center;
    gap: 5px;
}

.toolbar-separator {
    width: 1px;
    height: 24px;
    background: #dee2e6;
    margin: 0 5px;
}

.toolbar-btn {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    padding: 6px 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 32px;
    font-size: 14px;
    color: #495057;
}

.toolbar-btn:hover {
    background: #e9ecef;
    border-color: #adb5bd;
    color: #212529;
}

.toolbar-btn.active {
    background: #007bff;
    border-color: #007bff;
    color: white;
}

.toolbar-select {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    padding: 4px 8px;
    font-size: 13px;
    color: #495057;
    cursor: pointer;
    min-width: 80px;
}

.toolbar-select:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
}

/* Стили для индикатора загрузки */
.loading-indicator {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    color: #6c757d;
    font-size: 14px;
    background: #f8f9fa;
    border-radius: 4px;
    margin: 10px 0;
}

.loading-indicator i {
    margin-right: 8px;
    font-size: 16px;
}
</style>

<script>
// Функция для открытия модального окна редактирования текста претензии
function openClaimTextModal() {
    const wordEditor = document.getElementById('claim-text-editor');
    const loadingIndicator = document.getElementById('claim-text-loading');
    const claimTextContent = document.querySelector('.claim-text-content');
    
    if (wordEditor && claimTextContent) {
        console.log('Открытие модального окна редактирования');
        
        // Показываем индикатор загрузки
        if (loadingIndicator) {
            loadingIndicator.style.display = 'block';
        }
        
        // Очищаем редактор
        wordEditor.innerHTML = '';
        
        // Показываем модальное окно сразу
        const modal = new bootstrap.Modal(document.getElementById('claimTextModal'));
        modal.show();
        
        // Загружаем HTML контент через AJAX
        fetch('/claim/get-claim-html?id=<?= $model->id ?>', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('Получен HTML контент:', data);
            
            // Скрываем индикатор загрузки
            if (loadingIndicator) {
                loadingIndicator.style.display = 'none';
            }
            
            if (data.success) {
                // Загружаем HTML в редактор
                if (data.html && data.html.trim() !== '') {
                    wordEditor.innerHTML = data.html;
                    console.log('HTML загружен в редактор:', data.html.substring(0, 100) + '...');
                } else {
                    wordEditor.innerHTML = '';
                    console.log('HTML контент пустой');
                }
                
                // Фокусируемся на редакторе и обновляем панель инструментов
                setTimeout(() => {
                    wordEditor.focus();
                    updateClaimTextToolbarState();
                }, 100);
            } else {
                console.error('Ошибка загрузки HTML:', data.message);
                wordEditor.innerHTML = '';
            }
        })
        .catch(error => {
            console.error('Ошибка загрузки HTML контента:', error);
            
            // Скрываем индикатор загрузки при ошибке
            if (loadingIndicator) {
                loadingIndicator.style.display = 'none';
            }
            
            wordEditor.innerHTML = '';
        });
    }
}


// Функция для сохранения изменений текста претензии
function saveClaimText() {
    const wordEditor = document.getElementById('claim-text-editor');
    const content = wordEditor ? wordEditor.innerHTML : '';
    
    console.log('Сохранение текста претензии:', content.substring(0, 100) + '...');
    
    // Конвертируем HTML в простой текст для отображения
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = content;
    const textContent = tempDiv.textContent || tempDiv.innerText || '';
    
    // Обновляем превью
    const claimTextContent = document.querySelector('.claim-text-content');
    const downloadBtn = document.querySelector('.claim-text-actions .btn[onclick="downloadClaimText()"]');
    
    if (claimTextContent) {
        if (textContent.trim() === '') {
            // Если содержимое пустое, показываем пустое состояние
            claimTextContent.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-file-text"></i>
                    <p>Текст претензии не заполнен</p>
                    <small>Нажмите "Редактировать" чтобы добавить текст претензии</small>
                </div>
            `;
            if (downloadBtn) {
                downloadBtn.disabled = true;
            }
        } else {
            // Если есть содержимое, показываем HTML с форматированием
            claimTextContent.innerHTML = content; // Используем HTML напрямую
            if (downloadBtn) {
                downloadBtn.disabled = false;
            }
        }
    }
    
    // Отправляем AJAX запрос для сохранения (отправляем HTML для сохранения форматирования)
    const formData = new FormData();
    formData.append('description', content); // Отправляем HTML, а не текст
    
    // Добавляем CSRF токен
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        formData.append('_csrf', csrfToken.getAttribute('content'));
    }
    
    console.log('Отправка данных на сервер:', {
        description: content.substring(0, 100) + '...',
        csrf: csrfToken ? csrfToken.getAttribute('content') : 'НЕТ'
    });
    
    fetch('/claim/update-template?id=<?= $model->id ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Ответ сервера:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Данные ответа:', data);
        if (data.success) {
            // Показываем уведомление об успешном сохранении
            alert('Текст претензии успешно сохранен!');
            
            // Закрываем модальное окно
            const modal = bootstrap.Modal.getInstance(document.getElementById('claimTextModal'));
            if (modal) {
                modal.hide();
            }
        } else {
            console.error('Ошибка сохранения текста претензии:', data.message);
            alert('Ошибка при сохранении: ' + (data.message || 'Неизвестная ошибка'));
        }
    })
    .catch(error => {
        console.error('Ошибка сохранения текста претензии:', error);
        alert('Ошибка при сохранении: ' + error.message);
    });
}


// Функция для скачивания текста претензии
async function downloadClaimText() {
    const wordEditor = document.getElementById('claim-text-editor');
    const claimTextContent = document.querySelector('.claim-text-content');
    
    let content = '';
    if (wordEditor && wordEditor.innerHTML.trim() !== '') {
        // Если модальное окно открыто, берем содержимое из редактора
        content = wordEditor.innerHTML;
    } else if (claimTextContent) {
        // Иначе берем содержимое из превью
        content = claimTextContent.textContent || claimTextContent.innerText || '';
        
        // Проверяем, не пустое ли это состояние
        if (content.includes('Текст претензии не заполнен')) {
            alert('Текст претензии не заполнен. Сначала добавьте текст претензии.');
            return;
        }
    }
    
    if (!content || content.trim() === '') {
        alert('Текст претензии не заполнен. Сначала добавьте текст претензии.');
        return;
    }
    
    // Показываем индикатор загрузки
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Создание документа...';
    button.disabled = true;
    
    try {
        // Создаем FormData для отправки на сервер
        const formData = new FormData();
        formData.append('content', content);
        formData.append('purchase_id', '<?= $model->purchase ? $model->purchase->id : "" ?>');
        formData.append('claim_type', '<?= $model->claim_type ?: "custom" ?>');
        
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
        let filename = `claim_text_<?= $model->id ?>_${new Date().toISOString().split('T')[0]}.docx`;
        
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
}


// Функция для проверки статуса отслеживания
window.checkTrackingStatus = async function() {
    const trackingInput = document.getElementById('tracking-number-input');
    const statusDisplay = document.getElementById('tracking-status-display');
    const button = event.target;
    
    const trackingNumber = trackingInput.value.trim();
    
    if (!trackingNumber) {
        statusDisplay.textContent = 'Введите номер отслеживания';
        statusDisplay.className = 'tracking-status no-info';
        return;
    }
    
    // Показываем индикатор загрузки
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Проверка...';
    button.disabled = true;
    
    try {
        // Создаем FormData для отправки на сервер
        const formData = new FormData();
        formData.append('tracking_number', trackingNumber);
        formData.append('claim_id', '<?= $model->id ?>');
        
        // Добавляем CSRF токен
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            formData.append('_csrf', csrfToken.getAttribute('content'));
        }
        
        // Отправляем запрос на сервер
        const response = await fetch('/claim/check-tracking', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.success) {
            statusDisplay.textContent = data.tracking_status || 'Нет информации об отправлении';
            statusDisplay.className = data.tracking_status ? 'tracking-status' : 'tracking-status no-info';
            
            // Показываем дополнительную информацию если есть
            if (data.tracker_info) {
                showTrackingDetails(data.tracker_info);
            }
        } else {
            statusDisplay.textContent = data.message || 'Ошибка при проверке статуса';
            statusDisplay.className = 'tracking-status no-info';
        }
        
    } catch (error) {
        console.error('Ошибка при проверке статуса отслеживания:', error);
        statusDisplay.textContent = 'Ошибка при проверке статуса';
        statusDisplay.className = 'tracking-status no-info';
    } finally {
        // Восстанавливаем кнопку
        button.innerHTML = originalText;
        button.disabled = false;
    }
}

// Функция для сворачивания/разворачивания секции информации о ремонте
function toggleRepairInfo() {
    const body = document.getElementById('repair-info-body');
    const icon = document.querySelector('.repair-info-card .collapse-icon');
    
    if (body && icon) {
        if (body.classList.contains('collapsed')) {
            // Разворачиваем
            body.classList.remove('collapsed');
            icon.classList.remove('collapsed');
            
            // Получаем реальную высоту содержимого
            body.style.maxHeight = 'none';
            body.style.height = 'auto';
            const realHeight = body.scrollHeight;
            
            // Начинаем анимацию с 0
            body.style.maxHeight = '0px';
            body.style.height = '0px';
            
            // Запускаем анимацию разворачивания
            requestAnimationFrame(() => {
                body.style.maxHeight = realHeight + 'px';
                body.style.height = 'auto';
                
                // После завершения анимации убираем ограничения
                setTimeout(() => {
                    if (!body.classList.contains('collapsed')) {
                        body.style.maxHeight = 'none';
                        body.style.height = 'auto';
                    }
                }, 300); // Длительность анимации из CSS
            });
        } else {
            // Сворачиваем
            const currentHeight = body.scrollHeight;
            body.style.maxHeight = currentHeight + 'px';
            body.style.height = currentHeight + 'px';
            
            // Даем время для установки высоты
            requestAnimationFrame(() => {
                body.classList.add('collapsed');
                icon.classList.add('collapsed');
                
                // Анимируем до 0
                body.style.maxHeight = '0px';
                body.style.height = '0px';
            });
        }
    }
}

// Функция для сворачивания/разворачивания секции текста претензии
function toggleClaimText() {
    const body = document.getElementById('claim-text-body');
    const icon = document.querySelector('.claim-text-card .collapse-icon');
    
    if (body && icon) {
        if (body.classList.contains('collapsed')) {
            // Разворачиваем
            body.classList.remove('collapsed');
            icon.classList.remove('collapsed');
            
            // Получаем реальную высоту содержимого
            body.style.maxHeight = 'none';
            body.style.height = 'auto';
            const realHeight = body.scrollHeight;
            
            // Начинаем анимацию с 0
            body.style.maxHeight = '0px';
            body.style.height = '0px';
            
            // Запускаем анимацию разворачивания
            requestAnimationFrame(() => {
                body.style.maxHeight = realHeight + 'px';
                body.style.height = 'auto';
                
                // После завершения анимации убираем ограничения
                setTimeout(() => {
                    if (!body.classList.contains('collapsed')) {
                        body.style.maxHeight = 'none';
                        body.style.height = 'auto';
                    }
                }, 300); // Длительность анимации из CSS
            });
        } else {
            // Сворачиваем
            const currentHeight = body.scrollHeight;
            body.style.maxHeight = currentHeight + 'px';
            body.style.height = currentHeight + 'px';
            
            // Даем время для установки высоты
            requestAnimationFrame(() => {
                body.classList.add('collapsed');
                icon.classList.add('collapsed');
                
                // Анимируем до 0
                body.style.maxHeight = '0px';
                body.style.height = '0px';
            });
        }
    }
}

// Функция для сворачивания/разворачивания секции образца претензии

// Инициализация высоты секций при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    const claimTextBody = document.getElementById('claim-text-body');
    const repairBody = document.getElementById('repair-info-body');
    
    // Делаем все секции закрытыми по умолчанию
    if (claimTextBody) {
        const claimTextHeight = claimTextBody.scrollHeight;
        claimTextBody.style.maxHeight = claimTextHeight + 'px';
        claimTextBody.style.height = claimTextHeight + 'px';
        
        // Даем время для установки высоты, затем сворачиваем
        requestAnimationFrame(() => {
            claimTextBody.classList.add('collapsed');
            claimTextBody.style.maxHeight = '0px';
            claimTextBody.style.height = '0px';
        });
    }
    
    if (repairBody) {
        const repairHeight = repairBody.scrollHeight;
        repairBody.style.maxHeight = repairHeight + 'px';
        repairBody.style.height = repairHeight + 'px';
        
        // Даем время для установки высоты, затем сворачиваем
        requestAnimationFrame(() => {
            repairBody.classList.add('collapsed');
            repairBody.style.maxHeight = '0px';
            repairBody.style.height = '0px';
        });
    }
    
    // Поворачиваем иконки вправо (закрытое состояние)
    const collapseIcons = document.querySelectorAll('.collapse-icon');
    collapseIcons.forEach(icon => {
        icon.classList.add('collapsed');
    });
});

// Функция для показа деталей отслеживания
function showTrackingDetails(trackerInfo) {
    // Создаем модальное окно для деталей
    let detailsModal = document.getElementById('tracking-details-modal');
    if (!detailsModal) {
        detailsModal = document.createElement('div');
        detailsModal.id = 'tracking-details-modal';
        detailsModal.className = 'modal fade';
        detailsModal.innerHTML = 
            '<div class="modal-dialog modal-xl">' +
                '<div class="modal-content">' +
                    '<div class="modal-header">' +
                        '<h5 class="modal-title">' +
                            '<i class="fas fa-shipping-fast"></i> Детали отслеживания' +
                        '</h5>' +
                        '<button type="button" class="btn-close" data-bs-dismiss="modal"></button>' +
                    '</div>' +
                    '<div class="modal-body" id="tracking-details-content">' +
                        '<!-- Содержимое будет добавлено динамически -->' +
                    '</div>' +
                    '<div class="modal-footer">' +
                        '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>' +
                    '</div>' +
                '</div>' +
            '</div>';
        document.body.appendChild(detailsModal);
    }
    
    // Заполняем содержимое
    const content = document.getElementById('tracking-details-content');
    let html = '<div class="tracking-details-container">';
    
    // Основная информация о трекере
    html += '<div class="tracking-info-section">';
    html += '<h6><i class="fas fa-info-circle"></i> Основная информация</h6>';
    html += '<div class="info-grid">';
    
    if (trackerInfo.tracking_number) {
        html += '<div class="info-item">';
        html += '<label>Номер отслеживания:</label>';
        html += '<span class="tracking-number">' + trackerInfo.tracking_number + '</span>';
        html += '</div>';
    }
    
    if (trackerInfo.carrier) {
        html += '<div class="info-item">';
        html += '<label>Перевозчик:</label>';
        html += '<span class="carrier-name">' + trackerInfo.carrier + '</span>';
        html += '</div>';
    }
    
    if (trackerInfo.status) {
        html += '<div class="info-item">';
        html += '<label>Текущий статус:</label>';
        html += '<span class="status-badge">' + trackerInfo.status + '</span>';
        html += '</div>';
    }
    
    if (trackerInfo.last_update) {
        html += '<div class="info-item">';
        html += '<label>Последнее обновление:</label>';
        html += '<span class="last-update">' + new Date(trackerInfo.last_update).toLocaleString('ru-RU') + '</span>';
        html += '</div>';
    }
    
    html += '</div></div>';
    
    // Информация об отправителе и получателе
    if (trackerInfo.sender || trackerInfo.recipient) {
        html += '<div class="sender-recipient-section">';
        html += '<h6><i class="fas fa-users"></i> Отправитель и получатель</h6>';
        html += '<div class="sender-recipient-grid">';
        
        if (trackerInfo.sender) {
            html += '<div class="sender-info">';
            html += '<h7><i class="fas fa-paper-plane"></i> Отправитель</h7>';
            html += '<div class="contact-details">';
            if (trackerInfo.sender.name) {
                html += '<div class="contact-item"><strong>Имя:</strong> ' + trackerInfo.sender.name + '</div>';
            }
            if (trackerInfo.sender.address) {
                html += '<div class="contact-item"><strong>Адрес:</strong> ' + trackerInfo.sender.address + '</div>';
            }
            if (trackerInfo.sender.phone) {
                html += '<div class="contact-item"><strong>Телефон:</strong> ' + trackerInfo.sender.phone + '</div>';
            }
            html += '</div></div>';
        }
        
        if (trackerInfo.recipient) {
            html += '<div class="recipient-info">';
            html += '<h7><i class="fas fa-user"></i> Получатель</h7>';
            html += '<div class="contact-details">';
            if (trackerInfo.recipient.name) {
                html += '<div class="contact-item"><strong>Имя:</strong> ' + trackerInfo.recipient.name + '</div>';
            }
            if (trackerInfo.recipient.address) {
                html += '<div class="contact-item"><strong>Адрес:</strong> ' + trackerInfo.recipient.address + '</div>';
            }
            if (trackerInfo.recipient.phone) {
                html += '<div class="contact-item"><strong>Телефон:</strong> ' + trackerInfo.recipient.phone + '</div>';
            }
            html += '</div></div>';
        }
        
        html += '</div></div>';
    }
    
    // Информация о дате отправления
    if (trackerInfo.ship_date || trackerInfo.estimated_delivery) {
        html += '<div class="dates-section">';
        html += '<h6><i class="fas fa-calendar"></i> Даты</h6>';
        html += '<div class="dates-grid">';
        
        if (trackerInfo.ship_date) {
            html += '<div class="date-item">';
            html += '<label>Дата отправления:</label>';
            html += '<span class="ship-date">' + new Date(trackerInfo.ship_date).toLocaleDateString('ru-RU') + '</span>';
            html += '</div>';
        }
        
        if (trackerInfo.estimated_delivery) {
            html += '<div class="date-item">';
            html += '<label>Ожидаемая доставка:</label>';
            html += '<span class="estimated-delivery">' + new Date(trackerInfo.estimated_delivery).toLocaleDateString('ru-RU') + '</span>';
            html += '</div>';
        }
        
        html += '</div></div>';
    }
    
    // История статусов
    if (trackerInfo.history && trackerInfo.history.length > 0) {
        html += '<div class="history-section">';
        html += '<h6><i class="fas fa-history"></i> История статусов</h6>';
        html += '<div class="tracking-history">';
        
        trackerInfo.history.forEach((item, index) => {
            html += '<div class="history-item">';
            html += '<div class="history-timeline">';
            html += '<div class="timeline-dot"></div>';
            if (index < trackerInfo.history.length - 1) {
                html += '<div class="timeline-line"></div>';
            }
            html += '</div>';
            html += '<div class="history-content">';
            html += '<div class="history-date">' + new Date(item.date).toLocaleString('ru-RU') + '</div>';
            html += '<div class="history-status">' + item.status + '</div>';
            if (item.location) {
                html += '<div class="history-location"><i class="fas fa-map-marker-alt"></i> ' + item.location + '</div>';
            }
            if (item.description) {
                html += '<div class="history-description">' + item.description + '</div>';
            }
            html += '</div></div>';
        });
        
        html += '</div></div>';
    }
    
    html += '</div>';
    content.innerHTML = html;
    
    // Показываем модальное окно
    const modal = new bootstrap.Modal(detailsModal);
    modal.show();
}

// Функции для работы с панелью инструментов Word-редактора
function formatClaimText(command, value = null) {
    const editor = document.getElementById('claim-text-editor');
    if (!editor) return;
    
    editor.focus();
    
    if (command === 'fontSize' || command === 'fontFamily') {
        document.execCommand(command, false, value);
    } else {
        document.execCommand(command, false, null);
    }
    
    // Обновляем состояние кнопок
    updateClaimTextToolbarState();
}

function insertClaimTextTab() {
    const editor = document.getElementById('claim-text-editor');
    if (!editor) return;
    
    editor.focus();
    
    // Вставляем 4 пробела вместо табуляции
    const selection = window.getSelection();
    if (selection.rangeCount > 0) {
        const range = selection.getRangeAt(0);
        range.deleteContents();
        range.insertNode(document.createTextNode('    '));
        range.collapse(false);
        selection.removeAllRanges();
        selection.addRange(range);
    }
}

function updateClaimTextToolbarState() {
    const editor = document.getElementById('claim-text-editor');
    if (!editor) return;
    
    // Проверяем состояние форматирования
    const isBold = document.queryCommandState('bold');
    const isItalic = document.queryCommandState('italic');
    const isUnderline = document.queryCommandState('underline');
    
    // Обновляем состояние кнопок
    document.querySelector('[onclick*="formatClaimText(\'bold\')"]').classList.toggle('active', isBold);
    document.querySelector('[onclick*="formatClaimText(\'italic\')"]').classList.toggle('active', isItalic);
    document.querySelector('[onclick*="formatClaimText(\'underline\')"]').classList.toggle('active', isUnderline);
}

// Добавляем обработчики событий для Word-редактора в модальном окне
document.addEventListener('DOMContentLoaded', function() {
    const claimTextEditor = document.getElementById('claim-text-editor');
    if (claimTextEditor) {
        claimTextEditor.addEventListener('input', function() {
            updateClaimTextToolbarState();
        });
        claimTextEditor.addEventListener('selectionchange', updateClaimTextToolbarState);
        claimTextEditor.addEventListener('keyup', updateClaimTextToolbarState);
        claimTextEditor.addEventListener('mouseup', updateClaimTextToolbarState);
        
        // Обработка клавиши Tab для вставки 4 пробелов
        claimTextEditor.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                e.preventDefault(); // Предотвращаем стандартное поведение Tab
                insertClaimTextTab();
            }
        });
    }
});
</script>

