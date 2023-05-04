<?php

require_once "common/Page.php";
use common\Page;

class index extends Page
{

    protected function showContent()
    {
        print "<b>ОСНОВНОЙ КОНТЕНТ СТАРТОВОЙ СТРАНИЦЫ</b>";
    }
}

(new index())->show();