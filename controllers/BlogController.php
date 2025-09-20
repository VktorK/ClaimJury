<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use app\models\Article;
use app\models\Category;

class BlogController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Список всех статей блога
     */
    public function actionIndex()
    {
        $query = Article::find()->where(['status' => Article::STATUS_PUBLISHED])->orderBy(['created_at' => SORT_DESC]);
        
        $pagination = new Pagination([
            'defaultPageSize' => 6,
            'totalCount' => $query->count(),
        ]);
        
        $articles = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
            
        $categories = Category::find()->where(['status' => Category::STATUS_ACTIVE])->all();
        $recentArticles = Article::find()
            ->where(['status' => Article::STATUS_PUBLISHED])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(5)
            ->all();

        return $this->render('index', [
            'articles' => $articles,
            'categories' => $categories,
            'recentArticles' => $recentArticles,
            'pagination' => $pagination,
        ]);
    }

    /**
     * Просмотр отдельной статьи
     */
    public function actionView($slug)
    {
        $article = Article::find()->where(['slug' => $slug, 'status' => Article::STATUS_PUBLISHED])->one();
        
        if (!$article) {
            throw new NotFoundHttpException('Статья не найдена.');
        }
        
        $categories = Category::find()->where(['status' => Category::STATUS_ACTIVE])->all();
        $recentArticles = Article::find()
            ->where(['status' => Article::STATUS_PUBLISHED])
            ->andWhere(['!=', 'id', $article->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(5)
            ->all();
            
        $relatedArticles = Article::find()
            ->where(['status' => Article::STATUS_PUBLISHED])
            ->andWhere(['category_id' => $article->category_id])
            ->andWhere(['!=', 'id', $article->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(3)
            ->all();

        return $this->render('view', [
            'article' => $article,
            'categories' => $categories,
            'recentArticles' => $recentArticles,
            'relatedArticles' => $relatedArticles,
        ]);
    }

    /**
     * Статьи по категории
     */
    public function actionCategory($slug): string
    {
        $category = Category::find()->where(['slug' => $slug, 'status' => Category::STATUS_ACTIVE])->one();
        
        if (!$category) {
            throw new NotFoundHttpException('Категория не найдена.');
        }
        
        $query = Article::find()
            ->where(['status' => Article::STATUS_PUBLISHED, 'category_id' => $category->id])
            ->orderBy(['created_at' => SORT_DESC]);
        
        $pagination = new Pagination([
            'defaultPageSize' => 6,
            'totalCount' => $query->count(),
        ]);
        
        $articles = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
            
        $categories = Category::find()->where(['status' => Category::STATUS_ACTIVE])->all();
        $recentArticles = Article::find()
            ->where(['status' => Article::STATUS_PUBLISHED])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(5)
            ->all();

        return $this->render('category', [
            'category' => $category,
            'articles' => $articles,
            'categories' => $categories,
            'recentArticles' => $recentArticles,
            'pagination' => $pagination,
        ]);
    }
}
