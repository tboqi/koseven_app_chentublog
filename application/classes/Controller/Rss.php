<?php
class Controller_Rss extends Controller_base
{

    public function action_index()
    {
        $article_model = new Model_Article();
        $this->display('rss.html', ['articles' => $article_model->find(30, 0)]);
    }
}
