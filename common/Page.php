<?php
namespace common;
require_once "DbHelper.php";
abstract class Page
{
    public function show(): void{
        print "<html lang='ru'>";
        $this->createHeading();
        $this->createBody();
        print "</html>";
    }

    private function createHeading(){
        ?>
        <head>
            <link rel="stylesheet" type="text/css" href="/css/main.css">
            <meta charset="utf-8"/>
            <title><?php print($this->getTitle());?></title>
        </head>
        <?php
    }

    private function createBody()
    {
        print "<body>";
        print "<div class='main'>";
        $this->showHeader();
        $this->showMenu();
        print "<div class='content'>";
        $this->showContent();
        print "</div>";
        $this->showFooter();
        print "</div>";
        print "</body>";
    }

    protected abstract function showContent();

    private function showHeader()
    {
        ?>
        <div class='header'>
            <?php print ($this->getTitle()); ?>
        </div>
        <?php
    }

    private function showMenu()
    {
        print "<div class='menu'>";
        $dbh = new DbHelper("localhost", 3306, "root", "");
        $pages_info = $dbh->getPagesInfo();
        foreach ($pages_info as $index => $page_info){
            if ($page_info['alias']) continue;
            print "<div class='menuitem'><a href='{$page_info['url']}'>{$page_info['title']}</a></div>";
        }
        print "</div>";
    }

    private function showFooter()
    {
        print "<div class='footer'>© Сергей Маклецов, 2023</div>";
    }

    private function getTitle(): string
    {
        $dbh = new DbHelper("localhost", 3306, "root", "");
        return $dbh->getTitle($this->getUrl());
    }

    private function getUrl(): string {
        return $_SERVER['SCRIPT_NAME'];
    }
}