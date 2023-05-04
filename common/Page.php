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
            <?php $this->getTitle() ?>
        </div>
        <?php
    }

    private function showMenu()
    {
        print "<div class='menu'>";
        print "Здесь будет меню";
        print "</div>";
    }

    private function showFooter()
    {
        print "<div class='footer'>© Сергей Маклецов, 2023</div>";
    }

    private function getTitle()
    {
        print "Название странички";
    }
}