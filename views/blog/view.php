<?php

/** @var yii\web\View $this */
/** @var app\models\Article $article */
/** @var app\models\Category[] $categories */
/** @var app\models\Article[] $recentArticles */
/** @var app\models\Article[] $relatedArticles */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $article->title . ' - Блог ClaimJury';
$this->params['breadcrumbs'][] = ['label' => 'Блог', 'url' => ['/blog/index']];
$this->params['breadcrumbs'][] = $article->title;

// Увеличиваем количество просмотров
$article->incrementViews();
?>

<style>
/* Стили для просмотра статьи */
.article-view-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 80px 0 60px;
    margin-top: -20px;
    position: relative;
    overflow: hidden;
}

.article-view-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="10" cy="60" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="90" cy="40" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.article-view-hero .container {
    position: relative;
    z-index: 2;
}

.article-view-hero h1 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    line-height: 1.2;
}

.article-meta-large {
    display: flex;
    align-items: center;
    gap: 2rem;
    margin-bottom: 1.5rem;
    font-size: 1rem;
    opacity: 0.9;
}

.article-category-large {
    background: rgba(255,255,255,0.2);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-size: 0.9rem;
    font-weight: 500;
    text-decoration: none;
    transition: background 0.3s ease;
    backdrop-filter: blur(10px);
}

.article-category-large:hover {
    background: rgba(255,255,255,0.3);
    color: white;
    text-decoration: none;
}

.article-date-large {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.article-views-large {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.article-content-section {
    padding: 60px 0;
}

.article-main-image {
    width: 100%;
    height: 400px;
    object-fit: cover;
    border-radius: 15px;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.article-text {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #333;
    margin-bottom: 2rem;
}

.article-text h2 {
    font-size: 1.8rem;
    font-weight: 600;
    color: #333;
    margin: 2rem 0 1rem 0;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #667eea;
}

.article-text h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #333;
    margin: 1.5rem 0 1rem 0;
}

.article-text p {
    margin-bottom: 1.5rem;
}

.article-text ul,
.article-text ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
}

.article-text li {
    margin-bottom: 0.5rem;
}

.article-text blockquote {
    background: #f8f9fa;
    border-left: 4px solid #667eea;
    padding: 1rem 1.5rem;
    margin: 1.5rem 0;
    border-radius: 0 8px 8px 0;
    font-style: italic;
    color: #555;
}

.article-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #e9ecef;
}

.article-tag {
    background: #667eea;
    color: white;
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.8rem;
    text-decoration: none;
    transition: background 0.3s ease;
}

.article-tag:hover {
    background: #5a6fd8;
    color: white;
    text-decoration: none;
}

.related-articles {
    background: #f8f9fa;
    padding: 60px 0;
}

.related-articles h3 {
    font-size: 1.8rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 2rem;
    text-align: center;
}

.related-article-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid #e9ecef;
    height: 100%;
}

.related-article-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.related-article-image {
    width: 100%;
    height: 150px;
    object-fit: cover;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.related-article-content {
    padding: 1.5rem;
}

.related-article-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 0.8rem;
    line-height: 1.3;
}

.related-article-title a {
    color: inherit;
    text-decoration: none;
    transition: color 0.3s ease;
}

.related-article-title a:hover {
    color: #667eea;
}

.related-article-excerpt {
    color: #666;
    font-size: 0.9rem;
    line-height: 1.5;
    margin-bottom: 1rem;
}

.related-article-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.8rem;
    color: #666;
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

@media (max-width: 768px) {
    .article-view-hero h1 {
        font-size: 2rem;
    }
    
    .article-meta-large {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .article-main-image {
        height: 250px;
    }
    
    .article-text {
        font-size: 1rem;
    }
    
    .article-text h2 {
        font-size: 1.5rem;
    }
    
    .article-text h3 {
        font-size: 1.3rem;
    }
}
</style>

<!-- Героическая секция статьи -->
<section class="article-view-hero">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-10">
                <h1><?= Html::encode($article->title) ?></h1>
                
                <div class="article-meta-large">
                    <?= Html::a($article->category->name, $article->category->getUrl(), ['class' => 'article-category-large']) ?>
                    <div class="article-date-large">
                        <i class="fas fa-calendar-alt"></i>
                        <?= $article->getFormattedDate() ?>
                    </div>
                    <div class="article-views-large">
                        <i class="fas fa-eye"></i>
                        <?= $article->views ?> просмотров
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Основной контент -->
<section class="article-content-section">
    <div class="container">
        <div class="row">
            <!-- Основной контент статьи -->
            <div class="col-lg-8">
                <?php if ($article->image): ?>
                    <img src="<?= Html::encode($article->image) ?>" alt="<?= Html::encode($article->title) ?>" class="article-main-image">
                <?php endif; ?>
                
                <div class="article-text">
                    <?= $article->content ?>
                </div>
                
                <!-- Теги статьи (если есть) -->
                <?php if (!empty($article->tags)): ?>
                    <div class="article-tags">
                        <?php foreach (explode(',', $article->tags) as $tag): ?>
                            <?= Html::a(trim($tag), ['/blog/index', 'tag' => trim($tag)], ['class' => 'article-tag']) ?>
                        <?php endforeach; ?>
                    </div>
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
                        <?php foreach ($categories as $category): ?>
                            <li>
                                <?= Html::a($category->name . ' (' . $category->getArticlesCount() . ')', $category->getUrl()) ?>
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

<!-- Похожие статьи -->
<?php if (!empty($relatedArticles)): ?>
<section class="related-articles">
    <div class="container">
        <h3>Похожие статьи</h3>
        <div class="row">
            <?php foreach ($relatedArticles as $relatedArticle): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="related-article-card">
                        <?php if ($relatedArticle->image): ?>
                            <img src="<?= Html::encode($relatedArticle->image) ?>" alt="<?= Html::encode($relatedArticle->title) ?>" class="related-article-image">
                        <?php else: ?>
                            <div class="related-article-image d-flex align-items-center justify-content-center text-white">
                                <i class="fas fa-newspaper fa-2x"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="related-article-content">
                            <h4 class="related-article-title">
                                <?= Html::a($relatedArticle->title, $relatedArticle->getUrl()) ?>
                            </h4>
                            <div class="related-article-excerpt">
                                <?= $relatedArticle->getShortExcerpt(100) ?>
                            </div>
                            <div class="related-article-meta">
                                <span><?= $relatedArticle->getFormattedDate() ?></span>
                                <span><i class="fas fa-eye"></i> <?= $relatedArticle->views ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
