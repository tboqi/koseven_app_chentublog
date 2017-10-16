<?php defined('SYSPATH') or die('No direct script access.');

// class Controller_Article extends Controller_Template
class Controller_Admin_Article extends Controller_Admin
{

    public function before()
    {
        parent::before();
    }

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
            'directory' => strtolower($this->request->directory()),
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
        ];
        $this->display('admin/article/articles.html', $arr);
    }

    public function action_create()
    {
        $content = '';

        $model_article_category = new Model_Article_Category();
        $arr = [
            'categories' => $model_article_category->find_all(),
        ];
        $this->display('admin/article/article_form_create.html', $arr);
    }

    public function action_edit()
    {
        $content = '';

        $model_article_category = new Model_Article_Category();
        $id = intval($this->request->param('param1'));
        $model_article = new Model_Article();
        $article = $model_article->get($id);
        $article->tabs_detail = json_decode($article->tabs_detail, 1);

        $arr = [
            'categories' => $model_article_category->find_all(),
            'article' => $article,
        ];
        $this->display('admin/article/article_form.html', $arr);
    }

    public function action_save()
    {
        $article = array();
        $article['title'] = trim($_POST['title']);
        $article['content'] = trim($_POST['content']);
        $article['summary'] = trim($_POST['summary']);
        $article['refurl'] = trim($_POST['refurl']);
        if (empty($article['refurl'])) {
            $article['ref'] = 0;
        } else {
            $article['ref'] = 1;
        }

        $article['create_time'] = time();
        $article['update_time'] = time();
        $article['category_id'] = intval($_POST['category_id']);
        $model_article_category = new Model_Article_Category();
        $category = $model_article_category->get($article['category_id']);
        $article['category_name'] = $category->name;

        $tabs = trim($_POST['tabs']);
        $tabs = explode(',', $tabs);

        $model_article = new Model_Article();
        $model_article_tab = new Model_Article_Tab();
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $tabs_detail = array();
        foreach ($tabs as $tab) {
            $tab_id = $model_article_tab->get_tab_id($tab);
            $tabs_detail[] = array('id' => $tab_id, 'tab' => $tab);
        }
        if (count($tabs_detail) > 0) {
            $article['tabs_detail'] = json_encode($tabs_detail);
        } else {
            $article['tabs_detail'] = '';
        }
        if ($id < 1) {
            $id = $model_article->insert($article);
        } else {
            $model_article->update($article, $id);
        }
        $model_article->update_article_tab_link($tabs_detail, $id);

        header("location:" . URL::site('/admin/article/index'));exit;
    }

    public function action_del()
    {
        $model_article = new Model_Article();
        $id = intval($this->request->param('param1'));
        $model_article->del($id);

        header("location:" . URL::site('admin/article/index'));exit;
    }

} // End Article
