<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Article extends Controller_Base
{

    public function action_index()
    {
        $article_model = new Model_Article();

        $limit = 10;

        //分页
        $pagination = Pagination::factory(array(
            'total_items' => $article_model->count(),
            'items_per_page' => $limit,
        )
        );

        $arr = array(
            'controller' => strtolower($this->request->controller()),
            'action' => $this->request->action(),
        );
        $pagination->route_params($arr);

        $start = $pagination->offset;

        $model_article_category = new Model_Article_Category();
        $model_article = new Model_Article();
        $articles = $article_model->find($limit, $start);
        foreach ($articles as $key => $art) {
            if (!empty($art->tabs_detail)) {
                $tabs_detail = json_decode($art->tabs_detail, 1);
                if (!empty($tabs_detail)) {
                    $articles[$key]->tabs_detail = $tabs_detail;
                } else {
                    $articles[$key]->tabs_detail = [];
                }
            } else {
                $articles[$key]->tabs_detail = [];
            }
        }
        $arr = [
            'articles' => $articles,
            'pagination' => $pagination,
            'categories' => $model_article_category->find_all(),
            'hot_articles' => $model_article->find_hot_articles(),
        ];
        $this->display('site/article/index.html', $arr);
    }

    public function action_category()
    {
        $article_model = new Model_Article();

        $category_id = intval($this->request->param('param1'));

        $limit = 10;

        //分页
        $pagination = Pagination::factory(array(
            'total_items' => $article_model->count_by_category_id($category_id),
            'items_per_page' => $limit,
        )
        );
        $pagination->route_params(array('controller' => $this->request->controller(),
            'action' => $this->request->action(),
            'param1' => $category_id,
        )
        );
        $start = $pagination->offset;

        $model_article_category = new Model_Article_Category();
        $model_article = new Model_Article();

        $model_category = new Model_Article_Category();
        $category = $model_category->get($category_id);

        $arr = [
            'articles' => $article_model->find_by_category_id($category_id, $limit, $start),
            'pagination' => $pagination,
            'categories' => $model_article_category->find_all(),
            'hot_articles' => $model_article->find_hot_articles(),
            'sub_title' => "分类 {$category->name} 下的文章列表",
            'keywords' => $category->name,
            'description' => $category->name,
        ];
        $this->display('site/article/index.html', $arr);
    }

    public function action_tab()
    {
        $article_model = new Model_Article();

        $tab_id = intval($this->request->param('param1'));

        $limit = 10;

        //分页
        $pagination = Pagination::factory(array(
            'total_items' => $article_model->count_by_tab_id($tab_id),
            'items_per_page' => $limit,
        )
        );
        $pagination->route_params(array('controller' => $this->request->controller(),
            'action' => $this->request->action(),
            'param1' => $tab_id,
        )
        );
        $start = $pagination->offset;

        $model_tab = new Model_Article_Tab();
        $tab = $model_tab->get($tab_id);
        $model_article_category = new Model_Article_Category();
        $model_article = new Model_Article();
        $arr = [
            'articles' => $article_model->find_by_tab_id($tab_id, $limit, $start),
            'pagination' => $pagination,
            'categories' => $model_article_category->find_all(),
            'hot_articles' => $model_article->find_hot_articles(),
            'sub_title' => "标签 {$tab->tab} 下的文章列表",
            'keywords' => $tab->tab,
            'description' => $tab->tab,
        ];
        $this->display('site/article/index.html', $arr);
    }

    public function action_detail()
    {
        $model_article = new Model_Article();
        $id = intval($this->request->param('param1'));
        //阅读次数
        $model_article->add_read_times($id);
        $article = $model_article->get($id);

        $keywords = '';
        if (!empty($article->tabs_detail)) {
            $tabs = json_decode($article->tabs_detail, 1);
            if (empty($tabs)) {
                $article->tabs_detail = [];
            } else {
                $article->tabs_detail = $tabs;
            }
            $split = ',';
            foreach ($tabs as $tab) {
                $keywords .= $split . $tab['tab'];
            }
        } else {
            $article->tabs_detail = [];
        }
        $model_article_category = new Model_Article_Category();
        $model_article = new Model_Article();
        $model_article_comment = new Model_Article_Comment();

        $arr = [
            'article' => $article,
            'categories' => $model_article_category->find_all(),
            'hot_articles' => $model_article->find_hot_articles(),
            'sub_title' => $article->title,
            'keywords' => $keywords,
            'description' => $tab['tab'],
            'comments' => $model_article_comment->find_by_article_id($article->id, 20, 0),
        ];
        $this->display('site/article/detail.html', $arr);
    }
} // End Article
