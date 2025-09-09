<?php

use yii\helpers\Html;

/* @var $claim app\models\Claim */

$trackingDetails = $claim->getTrackingDetailsArray();
?>

<?php if (!empty($trackingDetails['events'])): ?>
    <h5><i class="fas fa-route"></i> История перемещений</h5>
    <div class="tracking-timeline">
        <?php foreach (array_reverse($trackingDetails['events']) as $index => $event): ?>
            <div class="timeline-item <?= $index === 0 ? 'active' : '' ?>">
                <div class="timeline-marker">
                    <i class="fas fa-circle"></i>
                </div>
                <div class="timeline-content">
                    <div class="event-header">
                        <span class="event-date">
                            <?php if ($event['date']): ?>
                                <?= date('d.m.Y', strtotime($event['date'])) ?>
                                <?php if ($event['time']): ?>
                                    <?= date('H:i', strtotime($event['time'])) ?>
                                <?php endif; ?>
                            <?php else: ?>
                                Дата не указана
                            <?php endif; ?>
                        </span>
                        <?php if ($event['location']): ?>
                            <span class="event-location">
                                <i class="fas fa-map-marker-alt"></i>
                                <?= Html::encode($event['location']) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="event-description">
                        <?= Html::encode($event['description']) ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="text-center text-muted">
        <i class="fas fa-info-circle"></i>
        <p>История перемещений недоступна</p>
    </div>
<?php endif; ?>

<style>
.tracking-timeline {
    position: relative;
    padding-left: 30px;
}

.tracking-timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, #3B82F6, #E5E7EB);
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
    padding-left: 20px;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: -25px;
    top: 5px;
    width: 12px;
    height: 12px;
    background: #E5E7EB;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1;
}

.timeline-item.active .timeline-marker {
    background: #3B82F6;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
}

.timeline-marker i {
    font-size: 6px;
    color: white;
}

.timeline-content {
    background: white;
    padding: 15px 20px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-left: 4px solid #3B82F6;
}

.timeline-item.active .timeline-content {
    border-left-color: #10B981;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
}

.event-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
    flex-wrap: wrap;
    gap: 10px;
}

.event-date {
    font-weight: 600;
    color: #3B82F6;
    font-size: 0.9rem;
}

.timeline-item.active .event-date {
    color: #10B981;
}

.event-location {
    color: #6B7280;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 5px;
}

.event-description {
    color: #374151;
    line-height: 1.5;
}

@media (max-width: 768px) {
    .tracking-timeline {
        padding-left: 20px;
    }
    
    .timeline-marker {
        left: -15px;
    }
    
    .timeline-content {
        padding: 12px 15px;
    }
    
    .event-header {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>
