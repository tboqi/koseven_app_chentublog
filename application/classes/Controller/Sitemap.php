<?php
class Controller_Sitemap extends Controller_base
{

    public function action_index()
    {
        $article_model = new Model_Article();
        $this->display('sitemap.html', ['domain' => URL::base(), 'articles' => $article_model->find(300, 0)]);
    }
}
