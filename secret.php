<?php

require_once "common/Page.php";
use common\Page;
use common\DbHelper;
class secret extends Page
{


    protected function showContent()
    {
        $name = DbHelper::getInstance()->getUserName($_SESSION['login']);
        print "<div>Приветствуем, ".$name."</div>";
        print "<div>Личный кабинет...</div>";
    }
}

(new secret())->show();