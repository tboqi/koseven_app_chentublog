<?php
class Controller_Admin extends Controller_Base
{

    public function before()
    {
        parent::before();
        if (!$this->is_login()) {
            header('location:/user/login');
        }
    }
}
