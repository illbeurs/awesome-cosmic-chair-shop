<?php

require_once "common/Page.php";
use common\Page;
use common\DbHelper;
class secret extends Page
{
    private $prods = array("Млечный стул", "Кресло ориона");
    private $data;
    private string $unaval = "";
    public function __construct()
    {
        
        parent::__construct();
        $dbh = DbHelper::getInstance();
        $this->data = $dbh->selectCart($_SESSION['login']);
        if (isset($_POST['кв']))
        {
            foreach ($this->data as $row){
                if(!$dbh->buyProd($_SESSION['login'], $row[0], $row[1]))
                {
                    if (!$this->unaval)
                    {
                        $this->unaval = "Недоступны следующие товары: ".$row[0];
                    }
                    else{
                        $this->unaval = $this->unaval.", $row[0]";
                    }
                }
            }
        }
        elseif(isset($_POST['ув']))
        {
            foreach ($this->data as $row){
                $dbh->delProdFromCart($_SESSION['login'], $row[0]);
            }
        }
        else{
        foreach ($this->data as $row){
            $buy_str = str_replace(' ', '', 'куп'.$row[0]);
            $del_str = str_replace(' ', '', 'уд'.$row[0]);
            if (isset($_POST[$buy_str]))  
            {
                if(!$dbh->buyProd($_SESSION['login'], $row[0], $row[1]))
                {
                    if (!$this->unaval)
                    {
                        $this->unaval = "Недоступны следующие товары: ".$row[0];
                    }
                    else{
                        $this->unaval = $this->unaval.", $row[0]";
                    }
                }
                
            }
            if (isset($_POST[$del_str]))
            {   
                $dbh->delProdFromCart($_SESSION['login'], $row[0]);
            }
        }
         }
        $this->data = $dbh->selectCart($_SESSION['login']);

    }
    protected function showContent()
    {
        ?> <form method="post" class="cart" action="secret.php">
            <?php
        if (!$this->data) print("Товары пока не выбраны");
        else{
            foreach ($this->data as $row)
            {  
                print("Товар: $row[0]; количество: ".$row[1]."; финальная цена: <b>$row[2] </b>");
                ?> <button type="submit" name="куп<?php print(str_replace(' ', '', $row[0]));?>"> Купить </button>
                <button type="submit" name="уд<?php print(str_replace(' ', '', $row[0]));?>"> Удалить </button> 
                <br>
                <?php
            }
            print('<br>');

            print('<button class="buyall" type="submit" name="кв"><b>Купить всё</b></button> ');
            print('<button class="delall" type="submit" name="ув"><b>Удалить всё</b></button>');
            print("<br> $this->unaval");
        }
         ?>    
        </form>
         <?php
    }
}

(new secret())->show();
