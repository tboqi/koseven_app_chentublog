<?php
class Controller_Base extends Controller
{

    public function before()
    {
        parent::before();
    }

    protected function is_login()
    {
        if (Auth::instance()->logged_in()) {
            return true;
        }
        return false;
    }

    protected function display($tpl, $data = [])
    {
        $view = View::factory($tpl);
        foreach ($data as $key => $value) {
            $view->{$key} = $value;
        }
        $view->is_login = $this->is_login();
        $view->controller = $this->request->controller();
        $view->action = $this->request->action();
        $this->response->body($view);
    }
}
