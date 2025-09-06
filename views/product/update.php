<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Product */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Редактирование товара';
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>

<div class="product-update">
    <div class="row">
        <div class="col-lg-8">
            <div class="update-card">
                <div class="card-header">
                    <h2>
                        <i class="fas fa-edit"></i>
                        Редактирование товара
                    </h2>
                </div>
                
                <div class="card-body">
                    <?php $form = ActiveForm::begin([
                        'options' => ['enctype' => 'multipart/form-data']
                    ]); ?>

                    <div class="row">
                        <div class="col-md-8">
                            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Категория</label>
                                <div class="category-display">
                                    <?php if ($model->category): ?>
                                        <span class="category-name">
                                            <i class="fas fa-folder"></i>
                                            <?= Html::encode($model->category->title) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">Категория не указана</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'warranty_period')->input('number', [
                                'min' => '0',
                                'placeholder' => '0'
                            ]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'serial_number')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>

                    <?= $form->field($model, 'description')->textarea(['rows' => 4]) ?>

                    <div class="form-group">
                        <label class="form-label">Изображение товара</label>
                        <?= $form->field($model, 'image')->fileInput(['accept' => 'image/*'])->label(false) ?>
                        <small class="form-text text-muted">Поддерживаются форматы: JPG, PNG, GIF (макс. 5MB)</small>
                        
                        <?php if ($model->image): ?>
                            <div class="current-image mt-3">
                                <label>Текущее изображение:</label>
                                <div class="image-preview">
                                    <img src="<?= $model->getImageUrl() ?>" 
                                         alt="<?= Html::encode($model->title) ?>" 
                                         class="current-image-preview"
                                         onclick="openImageModal(this)">
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-actions">
                        <?= Html::submitButton('<i class="fas fa-save"></i> Сохранить изменения', [
                            'class' => 'btn btn-primary btn-lg'
                        ]) ?>
                        
                        <?= Html::a('<i class="fas fa-times"></i> Отмена', ['view', 'id' => $model->id], [
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
                    <div class="help-content">
                        <h4>Поля формы:</h4>
                        <ul>
                            <li><strong>Название товара</strong> - обязательное поле</li>
                            <li><strong>Категория</strong> - обязательное поле</li>
                            <li><strong>Гарантийный срок</strong> - укажите в месяцах</li>
                            <li><strong>Серийный номер</strong> - для бытовой техники</li>
                            <li><strong>Описание</strong> - дополнительная информация</li>
                            <li><strong>Изображение</strong> - загрузите фото товара</li>
                        </ul>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-lightbulb"></i>
                            <strong>Совет:</strong> Загружайте качественные изображения товара для лучшего отображения.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно для просмотра изображения -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Изображение товара</h5>
                <button type="button" class="btn-close" onclick="closeImageModal()" aria-label="Закрыть">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="" class="img-fluid">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeImageModal()">Закрыть</button>
                <a id="downloadImage" href="" download class="btn btn-primary">
                    <i class="fas fa-download"></i> Скачать
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.product-update {
    padding: 20px 0;
}

.update-card,
.help-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    overflow: hidden;
}

.card-header {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    color: white;
    padding: 25px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h2,
.card-header h3 {
    margin: 0;
    font-weight: 600;
}

.card-header h2 i,
.card-header h3 i {
    margin-right: 10px;
}

.card-body {
    padding: 30px;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    font-weight: 600;
    color: #333;
    margin-bottom: 0.5rem;
    display: block;
}

.form-control {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 12px 15px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
}

.form-text {
    font-size: 0.875rem;
    color: #6c757d;
    margin-top: 0.25rem;
}

.category-display {
    padding: 12px 15px;
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    margin-top: 0.5rem;
}

.category-name {
    color: #6f42c1;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
}

.category-name i {
    color: #6f42c1;
}


.current-image {
    border: 2px dashed #e9ecef;
    border-radius: 8px;
    padding: 15px;
    text-align: center;
}

.current-image label {
    font-weight: 600;
    color: #333;
    margin-bottom: 10px;
    display: block;
}

.current-image-preview {
    max-width: 200px;
    max-height: 200px;
    border-radius: 8px;
    cursor: pointer;
    transition: transform 0.3s ease;
}

.current-image-preview:hover {
    transform: scale(1.05);
}

.form-actions {
    display: flex;
    gap: 15px;
    justify-content: flex-start;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 2px solid #e9ecef;
}

.btn {
    padding: 12px 25px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-lg {
    padding: 15px 30px;
    font-size: 1.1rem;
}

.btn-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
}

.btn-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #545b62 100%);
    color: white;
}

.btn-secondary:hover {
    background: linear-gradient(135deg, #545b62 0%, #3d4449 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
}

.help-content h4 {
    color: #333;
    margin-bottom: 15px;
    font-size: 1.1rem;
}

.help-content ul {
    list-style: none;
    padding: 0;
}

.help-content li {
    padding: 8px 0;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    align-items: center;
}

.help-content li:last-child {
    border-bottom: none;
}

.help-content li strong {
    color: #333;
    margin-right: 8px;
    min-width: 120px;
}

.alert {
    padding: 15px;
    border-radius: 8px;
    margin-top: 20px;
    border-left: 4px solid;
}

.alert-info {
    background-color: #d1ecf1;
    border-color: #17a2b8;
    color: #0c5460;
}

.alert i {
    margin-right: 8px;
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
    max-width: 800px;
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
    .card-body {
        padding: 20px;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
function openImageModal(img) {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    const downloadLink = document.getElementById('downloadImage');
    
    modalImg.src = img.src;
    modalImg.alt = img.alt;
    downloadLink.href = img.src;
    downloadLink.download = img.alt + '.jpg';
    
    modal.style.display = 'block';
    modal.classList.add('show');
    document.body.classList.add('modal-open');
    
    // Add backdrop
    const existingBackdrop = document.getElementById('imageModalBackdrop');
    if (existingBackdrop) {
        existingBackdrop.remove();
    }
    
    const backdrop = document.createElement('div');
    backdrop.className = 'modal-backdrop fade show';
    backdrop.id = 'imageModalBackdrop';
    backdrop.style.position = 'fixed';
    backdrop.style.top = '0';
    backdrop.style.left = '0';
    backdrop.style.width = '100%';
    backdrop.style.height = '100%';
    backdrop.style.backgroundColor = 'rgba(0,0,0,0.5)';
    backdrop.style.zIndex = '1040';
    document.body.appendChild(backdrop);
    
    // Close on backdrop click
    backdrop.addEventListener('click', closeImageModal);
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.style.display = 'none';
    modal.classList.remove('show');
    document.body.classList.remove('modal-open');
    
    // Remove backdrop
    const backdrop = document.getElementById('imageModalBackdrop');
    if (backdrop) {
        backdrop.remove();
    }
}

// Close modal on Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeImageModal();
    }
});

</script>
