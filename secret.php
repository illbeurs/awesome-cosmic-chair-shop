<?php

require_once "common/Page.php";
use common\Page;
class secret extends Page
{


    protected function showContent()
    {
        print "<b>Секретная страница сайта</b>";
    }
}

(new secret())->show();