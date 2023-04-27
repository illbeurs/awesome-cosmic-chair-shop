<?php

namespace common;

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
            <title><?php $this->getTitle()?></title>
        </head>
        <?php
    }

    private function createBody()
    {
        print "<body>";
        $this->showHeader();
        $this->showMenu();
        $this->showContent();
        $this->showFooter();
        print "</body>";
    }

    protected abstract function showContent();

    private function showHeader()
    {
        ?>
        <div class='header'>
            <?php $this->getTitle() ?>
        </div>
        <?php
    }

    private function showMenu()
    {
        print "Здесь будет меню.";
    }

    private function showFooter()
    {
        print "Окончание страницы";
    }

    private function getTitle()
    {
        print "Название странички";
    }
}