<?php
class Controller_Admin_Article_Category extends Controller_Base
{

    public function action_index()
    {
        //验证是否登录状态
        if ($user = Auth::instance()->logged_in()) {
            $model_article_category = new Model_Article_Category();
            $this->display('admin/article/categories.html', [
                'sub_title' => '文章分类',
                'title' => '文章分类列表',
                'categories' => $model_article_category->find_all(),
            ]);
        } else {
            die('没有权限');
        }
    }

    public function action_create()
    {
        if ($user = Auth::instance()->logged_in()) {
            $category = new stdClass();
            $category->name = '';
            $category->id = '';
            $category->show_order = '';
            $category->description = '';

            $model_article_category = new Model_Article_Category();
            $this->display('admin/article/category_form.html', [
                'sub_title' => '文章分类',
                'title' => '添加文章分类',
                'categories' => $model_article_category->find_all(),
                'category' => $category,
            ]);
        } else {
            die('没有权限');
        }
    }

    public function action_edit()
    {
        //验证是否登录状态
        if ($user = Auth::instance()->logged_in()) {
            $id = intval($this->request->param('param1'));
            $model_article_category = new Model_Article_Category();

            $this->display('admin/article/category_form.html', [
                'sub_title' => '文章分类',
                'title' => '编辑文章分类',
                'categories' => $model_article_category->find_all(),
                'category' => $model_article_category->get($id),
            ]);
        } else {
            die('没有权限');
        }
    }

    public function action_save()
    {
        //验证是否登录状态
        if ($user = Auth::instance()->logged_in()) {
            $category = array();
            $category['name'] = trim($_POST['category_name']);
            $category['description'] = trim($_POST['description']);
            $category['show_order'] = intval($_POST['show_order']);
            $model_article_category = new Model_Article_Category();
            $id = intval($_POST['id']);
            if ($id < 1) {
                $model_article_category->insert($category);
            } else {
                $model_article_category->update($category, $id);
            }
            // die(json_encode(array('status' => 1, 'url' => URL::site('admin/article_category/index'))));
            header("location:" . URL::site('admin/article_category/index'));
        } else {
            die(json_encode(array('errors' => '没有权限')));
        }
    }

    public function action_del()
    {
        //验证是否登录状态
        if ($user = Auth::instance()->logged_in()) {
            $id = intval($this->request->param('param1'));
            DB::update('articles')->set(array('category_id' => 0,
                'category_name' => '未分类'))
                ->where('category_id', '=', $id)->execute();
            $model_article_category = new Model_Article_Category();
            $var = $model_article_category->del($id);
            header("location:" . URL::site('admin/article_category/index'));exit;
        } else {
            die('没有权限');
        }
    }
}
