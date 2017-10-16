<?php defined('SYSPATH') or die('No direct script access.');

return array(

    // Application defaults
    'default' => array(
        'current_page' => array('source' => 'route', 'key' => 'param1'), // source: "query_string" or "route"
        'total_items' => 0,
        'items_per_page' => 10,
        'view' => 'pagination/bootstrap',
        'auto_hide' => true,
        'first_page_in_url' => false,
    ),

);
