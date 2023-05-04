<?php

require_once "common/Page.php";
use common\Page;

class second extends Page
{

    protected function showContent()
    {
        print "<b>ОСНОВНОЙ КОНТЕНТ ВТОРОЙ СТРАНИЦЫ</b>";
    }
}

(new second())->show();