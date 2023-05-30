<?php

require_once "common/Page.php";
use common\Page;
use common\DbHelper;

class second extends Page
{
    private bool $is_aval1 = true;
    private bool $is_aval2 = true;
    private string $is_over1 = "";
    private string $is_over2 = "";
    private $amount1;
    private $amount2;
    public function __construct()
    {
        
        parent::__construct();
        
        $dbh = DbHelper::getInstance();
        $this->amount1 = intval($dbh->getProductInfo(1, "amount"));
        $this->amount2 = intval($dbh->getProductInfo(2, "amount"));
        if (isset($_POST['product1']))
        {
            if (!isset($_SESSION['login']))
            {   $_SESSION['requested_page'] = $_SERVER['REQUEST_URI'];
                header("Location: /auth.php");
            }
            else
            {
            if ($this->amount1 > 0)
            {
                if ($dbh->getCartAmount($_SESSION['login'], $dbh->getProductInfo(1, 'name')) == $this->amount1)
                {
                    $this->is_over1 = "В корзину добавлено максимальное кол-во товара!";
                }
                else{
            //$dbh->updateProduct(1, $this->amount1);
                $dbh->updateCart($_SESSION['login'], $dbh->getProductInfo(1, 'name'));
                }
            }
            else
            $this->is_aval1 = false;
            }
        }
        if (isset($_POST['product2']))
        {
            if (!isset($_SESSION['login']))
            {
                $_SESSION['requested_page'] = $_SERVER['REQUEST_URI'];
                header("Location: /auth.php");}
            else
            {
            if ($this->amount2 > 0)
            {
                if ($dbh->getCartAmount($_SESSION['login'], $dbh->getProductInfo(2, 'name')) == $this->amount2)
                {
                    $this->is_over2 = "В корзину добавлено максимальное кол-во товара!";
                }
            else{
                $dbh->updateCart($_SESSION['login'], $dbh->getProductInfo(2, 'name'));
            }
            }
            else
            $this->is_aval2 = false;
            }
        }
    }

    protected function showContent()
    {
        ?>
        <form method="post" action="second.php">
        <main action="second.php">
            <section class="product-list">
                <div class="product">
                    <img src="css/product1.jpg" alt="Product 1">
                    <h2>Млечный стул</h2>
                    <p>"Млечный стул" - это высокотехнологичное и элегантное кресло, вдохновленное космической эстетикой и 
                        современными инновациями. Оно является идеальным сочетанием функциональности и стиля, 
                        позволяющим вам погрузиться в атмосферу космического путешествия, даже не покидая вашего дома или офиса.
                        <br><br> <b>Цена товара: <?php print(DbHelper::getInstance()->getProductInfo(1,'price'));?>$</b></p>
                            <button type="submit" name="product1"> Добавить в корзину</button> <h5><?php print($this->is_over1);?> <h5>
                    <h5>Осталось на складе: <?php if ($this->is_aval1) print($this->amount1); else print("Товар закончился!"); ?> </h5>
                </div>
    
                <div class="product">
                    <img src="css/product2.jpg" alt="Product 2">
                    <h2>Кресло Ориона</h2>
                    <p>"Кресло Ориона" - это передовое космическое кресло, созданное для тех, кто мечтает о великих 
                        космических приключениях и стремится к комфорту во время путешествий. 
                        Оно сочетает инновационные технологии, изысканный дизайн и непревзойденный уровень комфорта, 
                        чтобы предложить вам неповторимый опыт. 
                        <br><br> <b>Цена товара: <?php print(DbHelper::getInstance()->getProductInfo(2,'price'));?>$</b></p>
                        <button type="submit" name="product2"> Добавить в корзину</button>  <h5><?php print($this->is_over2);?> <h5>
                    <h5>Осталось на складе: <?php if ($this->is_aval2) print($this->amount2); else print("Товар закончился!"); ?> </h5>
                </div>
                <!-- Добавьте больше товаров по аналогии -->
            </section>
        </main>
        </form>
        <?php
    }
}

(new second())->show();