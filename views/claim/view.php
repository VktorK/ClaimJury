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
                            
                            <div class="info-item">
                                <label>Статус:</label>
                                <span class="badge <?= $model->getStatusClass() ?>"><?= $model->getStatusLabel() ?></span>
                            </div>
                            
                            <div class="info-item">
                                <label>Дата подачи:</label>
                                <span class="claim-date"><?= $model->getFormattedClaimDate() ?></span>
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
                            
                            <?php if ($model->description): ?>
                                <div class="info-item full-width">
                                    <label>Описание:</label>
                                    <span class="claim-description"><?= Html::encode($model->description) ?></span>
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
                            <label>Дата создания:</label>
                            <span><?= $model->getFormattedCreatedDate() ?></span>
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
    background: linear-gradient(135deg, #3B82F6, #1D4ED8);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
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
</style>
