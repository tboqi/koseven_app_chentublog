<?php
class Controller_Admin_Welcome extends Controller_Admin
{
    public function before()
    {
        parent::before();
    }

    public function action_index()
    {
        $this->display('admin/welcome/index.html');
    }
}
