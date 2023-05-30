<?php
namespace common;
require_once "DbHelper.php";
abstract class Page
{

    private $dbh;

    public function __construct(){
        session_start();
        $this->dbh = DbHelper::getInstance("localhost", 3306, "root", "");
        if ($this->dbh->isSecure($this->getUrl())){
            if (!isset($_SESSION['login'])){
                $_SESSION['requested_page'] = $_SERVER['REQUEST_URI'];
                header("Location: /auth.php");
            }
        }
    }
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
        $pages_info = $this->dbh->getPagesInfo();
        foreach ($pages_info as $index => $page_info){
            $curr_page = ($page_info['url'] === $this->getUrl()) || ($page_info['alias'] === $this->getUrl());
            print "<div class='menuitem'>";
            if (!$curr_page)
                print "<a class='l_menuitem' href='{$page_info['url']}'>";
            print $page_info['name'];
            if (!$curr_page) print "</a>";
            print "</div>";
        }
        print "</div>";
    }

    private function showFooter()
    {
        print "<div class='footer'>";
        if (isset($_SESSION['login'])){
            print "<a href='/auth.php?exit=1'>Выход</a>";
        }
        print "<div> DMA 2023</div>";
        print "</div>";
    }

    private function getTitle(): string
    {
        return $this->dbh->getTitle($this->getUrl());
    }

    private function getUrl(): string {
        return mb_split("/?/", $_SERVER['REQUEST_URI'], 1)[0];
    }
}