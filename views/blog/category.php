<?php

/** @var yii\web\View $this */
/** @var app\models\Category $category */
/** @var app\models\Article[] $articles */
/** @var app\models\Category[] $categories */
/** @var app\models\Article[] $recentArticles */
/** @var yii\data\Pagination $pagination */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $category->name . ' - Блог ClaimJury';
$this->params['breadcrumbs'][] = ['label' => 'Блог', 'url' => ['/blog/index']];
$this->params['breadcrumbs'][] = $category->name;
?>

<style>
/* Стили для страницы категории */
.category-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 80px 0 60px;
    margin-top: -20px;
    position: relative;
    overflow: hidden;
}

.category-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="10" cy="60" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="90" cy="40" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.category-hero .container {
    position: relative;
    z-index: 2;
}

.category-hero h1 {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 1rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.category-hero .lead {
    font-size: 1.2rem;
    opacity: 0.95;
    margin-bottom: 0;
}

.category-description {
    background: rgba(255,255,255,0.1);
    padding: 1.5rem;
    border-radius: 15px;
    margin-top: 2rem;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
}

.category-content {
    padding: 60px 0;
}

.category-stats {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    text-align: center;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.category-stats h3 {
    color: #333;
    margin-bottom: 1rem;
}

.category-stats .stats-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: #667eea;
    display: block;
}

.category-stats .stats-label {
    color: #666;
    font-size: 1.1rem;
}

.blog-sidebar {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.sidebar-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #667eea;
}

.category-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.category-list li {
    margin-bottom: 0.8rem;
}

.category-list a {
    color: #666;
    text-decoration: none;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    display: block;
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
}

.category-list a:hover {
    background: white;
    color: #667eea;
    border-left-color: #667eea;
    transform: translateX(5px);
}

.category-list a.active {
    background: #667eea;
    color: white;
    border-left-color: #5a6fd8;
}

.recent-articles {
    list-style: none;
    padding: 0;
    margin: 0;
}

.recent-articles li {
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e9ecef;
}

.recent-articles li:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.recent-articles a {
    color: #333;
    text-decoration: none;
    font-weight: 500;
    line-height: 1.4;
    transition: color 0.3s ease;
}

.recent-articles a:hover {
    color: #667eea;
}

.recent-articles .article-date {
    font-size: 0.85rem;
    color: #666;
    margin-top: 0.3rem;
}

.article-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid #e9ecef;
}

.article-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.article-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.article-content {
    padding: 1.5rem;
}

.article-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
    font-size: 0.9rem;
    color: #666;
}

.article-category {
    background: #667eea;
    color: white;
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    text-decoration: none;
    transition: background 0.3s ease;
}

.article-category:hover {
    background: #5a6fd8;
    color: white;
    text-decoration: none;
}

.article-date {
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.article-title {
    font-size: 1.4rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 1rem;
    line-height: 1.3;
}

.article-title a {
    color: inherit;
    text-decoration: none;
    transition: color 0.3s ease;
}

.article-title a:hover {
    color: #667eea;
}

.article-excerpt {
    color: #666;
    line-height: 1.6;
    margin-bottom: 1rem;
}

.article-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1rem;
    border-top: 1px solid #e9ecef;
}

.read-more {
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: color 0.3s ease;
}

.read-more:hover {
    color: #5a6fd8;
    text-decoration: none;
}

.article-views {
    color: #666;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 3rem;
}

.pagination {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
    gap: 0.5rem;
}

.pagination li {
    display: flex;
}

.pagination a,
.pagination span {
    padding: 0.8rem 1.2rem;
    border-radius: 8px;
    text-decoration: none;
    color: #666;
    background: white;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.pagination a:hover {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

.pagination .active span {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

@media (max-width: 768px) {
    .category-hero h1 {
        font-size: 2.2rem;
    }
    
    .article-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .article-footer {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
}
</style>

<!-- Героическая секция категории -->
<section class="category-hero">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1><?= Html::encode($category->name) ?></h1>
                <p class="lead">Статьи по категории "<?= Html::encode($category->name) ?>"</p>
                
                <?php if ($category->description): ?>
                    <div class="category-description">
                        <?= Html::encode($category->description) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Основной контент -->
<section class="category-content">
    <div class="container">
        <div class="row">
            <!-- Основной контент -->
            <div class="col-lg-8">
                <!-- Статистика категории -->
                <div class="category-stats">
                    <h3>Статистика категории</h3>
                    <span class="stats-number"><?= count($articles) ?></span>
                    <div class="stats-label">статей в категории</div>
                </div>
                
                <?php if (empty($articles)): ?>
                    <div class="text-center py-5">
                        <h3>Статьи не найдены</h3>
                        <p class="text-muted">В данной категории пока нет опубликованных статей.</p>
                        <?= Html::a('Вернуться в блог', ['/blog/index'], ['class' => 'btn btn-primary']) ?>
                    </div>
                <?php else: ?>
                    <?php foreach ($articles as $article): ?>
                        <article class="article-card">
                            <?php if ($article->image): ?>
                                <img src="<?= Html::encode($article->image) ?>" alt="<?= Html::encode($article->title) ?>" class="article-image">
                            <?php else: ?>
                                <div class="article-image d-flex align-items-center justify-content-center text-white">
                                    <i class="fas fa-newspaper fa-3x"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div class="article-content">
                                <div class="article-meta">
                                    <?= Html::a($article->category->name, $article->category->getUrl(), ['class' => 'article-category']) ?>
                                    <div class="article-date">
                                        <i class="fas fa-calendar-alt"></i>
                                        <?= $article->getFormattedDate() ?>
                                    </div>
                                </div>
                                
                                <h2 class="article-title">
                                    <?= Html::a($article->title, $article->getUrl()) ?>
                                </h2>
                                
                                <div class="article-excerpt">
                                    <?= $article->getShortExcerpt(200) ?>
                                </div>
                                
                                <div class="article-footer">
                                    <?= Html::a('Читать далее <i class="fas fa-arrow-right"></i>', $article->getUrl(), ['class' => 'read-more']) ?>
                                    <div class="article-views">
                                        <i class="fas fa-eye"></i>
                                        <?= $article->views ?>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                    
                    <!-- Пагинация -->
                    <?php if ($pagination->pageCount > 1): ?>
                        <div class="pagination-wrapper">
                            <?= yii\widgets\LinkPager::widget([
                                'pagination' => $pagination,
                                'options' => ['class' => 'pagination'],
                                'linkOptions' => ['class' => 'page-link'],
                                'activePageCssClass' => 'active',
                            ]) ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            
            <!-- Боковая панель -->
            <div class="col-lg-4">
                <!-- Категории -->
                <div class="blog-sidebar">
                    <h3 class="sidebar-title">
                        <i class="fas fa-folder"></i> Категории
                    </h3>
                    <ul class="category-list">
                        <?php foreach ($categories as $cat): ?>
                            <li>
                                <?= Html::a($cat->name . ' (' . $cat->getArticlesCount() . ')', $cat->getUrl(), [
                                    'class' => $cat->id == $category->id ? 'active' : ''
                                ]) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <!-- Последние статьи -->
                <div class="blog-sidebar">
                    <h3 class="sidebar-title">
                        <i class="fas fa-clock"></i> Последние статьи
                    </h3>
                    <ul class="recent-articles">
                        <?php foreach ($recentArticles as $recentArticle): ?>
                            <li>
                                <?= Html::a($recentArticle->title, $recentArticle->getUrl()) ?>
                                <div class="article-date">
                                    <i class="fas fa-calendar-alt"></i>
                                    <?= $recentArticle->getFormattedDate() ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
