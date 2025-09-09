<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $claim app\models\Claim */

$this->title = 'Отслеживание документов по претензии';
$this->params['breadcrumbs'][] = ['label' => 'Главная', 'url' => ['/purchases']];
$this->params['breadcrumbs'][] = ['label' => 'Претензии', 'url' => ['/claim/index']];
$this->params['breadcrumbs'][] = ['label' => 'Претензия #' . $claim->id, 'url' => ['/claim/view', 'id' => $claim->id]];
$this->params['breadcrumbs'][] = 'Отслеживание';
?>

<div class="tracking-index">
    <div class="row">
        <div class="col-lg-8">
            <div class="tracking-card">
                <div class="card-header">
                    <h2>
                        <i class="fas fa-shipping-fast"></i>
                        Отслеживание документов
                    </h2>
                </div>
                
                <div class="card-body">
                    <div class="claim-info">
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Претензия:</label>
                                <span class="claim-link">
                                    <?= Html::a('Претензия #' . $claim->id, ['/claim/view', 'id' => $claim->id]) ?>
                                </span>
                            </div>
                            
                            <div class="info-item">
                                <label>Тип претензии:</label>
                                <span class="claim-type-badge"><?= $claim->getClaimTypeLabel() ?></span>
                            </div>
                            
                            <div class="info-item">
                                <label>Статус претензии:</label>
                                <span class="badge <?= $claim->getStatusClass() ?>"><?= $claim->getStatusLabel() ?></span>
                            </div>
                            
                            <?php if ($claim->hasTrackingNumber()): ?>
                                <div class="info-item">
                                    <label>Трек-номер:</label>
                                    <span class="tracking-number"><?= Html::encode($claim->tracking_number) ?></span>
                                </div>
                                
                                <div class="info-item">
                                    <label>Дата отправки:</label>
                                    <span class="sent-date"><?= $claim->getFormattedDocumentSentDate() ?></span>
                                </div>
                                
                                <div class="info-item">
                                    <label>Статус отслеживания:</label>
                                    <span class="badge <?= $claim->getTrackingStatusClass() ?>" id="tracking-status">
                                        <?= Html::encode($claim->tracking_status ?: 'Неизвестно') ?>
                                    </span>
                                </div>
                                
                                <div class="info-item">
                                    <label>Последнее обновление:</label>
                                    <span class="last-update" id="last-update"><?= $claim->getFormattedLastTrackingUpdate() ?></span>
                                </div>
                                
                                <?php if ($claim->isDocumentDelivered()): ?>
                                    <div class="info-item">
                                        <label>Дата получения:</label>
                                        <span class="received-date"><?= $claim->getFormattedDocumentReceivedDate() ?></span>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="info-item full-width">
                                    <div class="no-tracking">
                                        <i class="fas fa-exclamation-triangle text-warning"></i>
                                        <h5>Трек-номер не указан</h5>
                                        <p>Для отслеживания документов необходимо добавить трек-номер отправления.</p>
                                        <?= Html::a('<i class="fas fa-plus"></i> Добавить трек-номер', ['add-tracking', 'id' => $claim->id], [
                                            'class' => 'btn btn-primary'
                                        ]) ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php if ($claim->hasTrackingNumber()): ?>
                        <div class="tracking-actions">
                            <button type="button" class="btn btn-success" onclick="updateTracking()">
                                <i class="fas fa-sync-alt"></i> Обновить статус
                            </button>
                            <?= Html::a('<i class="fas fa-external-link-alt"></i> Открыть на сайте Почты России', 
                                'https://www.pochta.ru/tracking#' . $claim->tracking_number, 
                                ['class' => 'btn btn-outline-primary', 'target' => '_blank']) ?>
                        </div>
                        
                        <div class="tracking-details" id="tracking-details">
                            <?php if ($claim->tracking_details): ?>
                                <?= $this->render('_tracking_events', ['claim' => $claim]) ?>
                            <?php else: ?>
                                <div class="text-center text-muted">
                                    <i class="fas fa-info-circle"></i>
                                    <p>Детали отслеживания будут загружены после обновления статуса.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
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
                    <h5>Статусы отслеживания:</h5>
                    <ul>
                        <li><span class="badge badge-info">Принято в отделении связи</span></li>
                        <li><span class="badge badge-warning">В пути</span></li>
                        <li><span class="badge badge-primary">Прибыло в место вручения</span></li>
                        <li><span class="badge badge-success">Вручено</span></li>
                        <li><span class="badge badge-danger">Возвращено отправителю</span></li>
                        <li><span class="badge badge-secondary">На таможне</span></li>
                        <li><span class="badge badge-danger">Утеряно</span></li>
                    </ul>
                    
                    <h5>Формат трек-номера:</h5>
                    <p>Российские трек-номера обычно содержат 13-14 цифр, иногда с буквой в конце.</p>
                    
                    <h5>Обновление статуса:</h5>
                    <p>Статус обновляется автоматически при нажатии кнопки "Обновить статус".</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.tracking-card,
.help-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    overflow: hidden;
}

.card-header {
    background: linear-gradient(135deg, #3B82F6, #1D4ED8);
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

.claim-link a {
    color: #3B82F6;
    font-weight: 600;
    text-decoration: none;
}

.claim-link a:hover {
    color: #1D4ED8;
    text-decoration: underline;
}

.claim-type-badge {
    background: linear-gradient(135deg, #8B5CF6, #A855F7);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
    display: inline-block;
}

.tracking-number {
    font-family: 'Courier New', monospace;
    font-weight: 600;
    color: #059669;
    background: #f0f9ff;
    padding: 4px 8px;
    border-radius: 6px;
    border: 1px solid #e0f2fe;
}

.no-tracking {
    text-align: center;
    padding: 40px 20px;
    background: #f8f9fa;
    border-radius: 10px;
    border: 2px dashed #dee2e6;
}

.no-tracking i {
    font-size: 3rem;
    margin-bottom: 15px;
}

.no-tracking h5 {
    color: #495057;
    margin-bottom: 10px;
}

.no-tracking p {
    color: #6c757d;
    margin-bottom: 20px;
}

.tracking-actions {
    display: flex;
    gap: 15px;
    margin: 30px 0;
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

.btn-outline-primary {
    background: transparent;
    color: #3B82F6;
    border: 2px solid #3B82F6;
}

.btn-outline-primary:hover {
    background: #3B82F6;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
    text-decoration: none;
}

.tracking-details {
    margin-top: 30px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 10px;
}

.badge {
    font-size: 0.9rem;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 500;
}

.badge-info { background: linear-gradient(135deg, #3B82F6, #1D4ED8); color: white; }
.badge-warning { background: linear-gradient(135deg, #F59E0B, #D97706); color: white; }
.badge-primary { background: linear-gradient(135deg, #8B5CF6, #A855F7); color: white; }
.badge-success { background: linear-gradient(135deg, #10B981, #059669); color: white; }
.badge-danger { background: linear-gradient(135deg, #EF4444, #DC2626); color: white; }
.badge-secondary { background: linear-gradient(135deg, #6B7280, #4B5563); color: white; }

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
</style>

<script>
function updateTracking() {
    const btn = document.querySelector('.btn-success');
    const originalText = btn.innerHTML;
    
    // Показываем спиннер
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Обновление...';
    btn.disabled = true;
    
    fetch('<?= Url::to(['ajax-update', 'id' => $claim->id]) ?>', {
        method: 'POST',
        headers: {
            'X-CSRF-Token': '<?= Yii::$app->request->csrfToken ?>',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Обновляем статус
            document.getElementById('tracking-status').textContent = data.data.status;
            document.getElementById('tracking-status').className = 'badge ' + data.data.status_class;
            document.getElementById('last-update').textContent = data.data.last_update;
            
            // Показываем уведомление
            showNotification('Статус обновлен успешно', 'success');
            
            // Перезагружаем детали отслеживания
            location.reload();
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Ошибка:', error);
        showNotification('Ошибка при обновлении статуса', 'error');
    })
    .finally(() => {
        // Восстанавливаем кнопку
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.innerHTML = `
        ${message}
        <button type="button" class="close" onclick="this.parentElement.remove()">
            <span>&times;</span>
        </button>
    `;
    
    document.body.appendChild(notification);
    
    // Автоматически удаляем через 5 секунд
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}
</script>
