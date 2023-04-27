<?php

require_once "common/Page.php";
use common\Page;

class index extends Page
{
    /*
    private string $x;
    function __construct(string $name){
        $this->x = $name;
    }

    function show(int $t = -1): void{
        $t = ($t > 0) ? "$t курс" : $this->x;
        print "Привет, $t!";
    }*/

    protected function showContent()
    {
        print "<b>ОСНОВНОЙ КОНТЕНТ СТРАНИЦЫ</b>";
    }
}

(new index())->show();