<?php

require_once "common/Page.php";
use common\Page;

class graph
{
    private $vtx=array();
    public function __construct(string $fileName){
        $graphData =file($fileName);
        foreach ($graphData as $num=>$value){
            $this->vtx[$num]=new vertex($num+1,explode( ' ',$value));
        }

    }
    public function findPath($start,$finish){
        $this->vtx[$start-1]->setPathLength(0);

    }
    private function findMinVertex(){
        $min=PHP_INT_MAX;
        foreach ($this->vtx as $i=>$v){

        }
    }

}

class vertex
{

    private int $number;
    private array $edges=array();
    private bool $isVisited=false;
    private int $pathLength=PHP_INT_MAX;
    private int $prevVertex=-1;

    public function __construct($num,$edges){
        $this->number=$num;
        foreach ($edges as $i=>$e){
            $edges[$i]=(int)$e;
        }

    }

    /**
     * @return bool
     */
    public function isVisited(): bool
    {
        return $this->isVisited;
    }

    /**
     * @param bool $isVisited
     */
    public function setIsVisited(bool $isVisited): void
    {
        $this->isVisited = $isVisited;
    }

    /**
     * @return int
     */
    public function getPathLength(): int
    {
        return $this->pathLength;
    }

    /**
     * @param int $pathLength
     */
    public function setPathLength(int $pathLength): void
    {
        $this->pathLength = $pathLength;
    }

    /**
     * @return int
     */
    public function getPrevVertex(): int
    {
        return $this->prevVertex;
    }

    /**
     * @param int $prevVertex
     */
    public function setPrevVertex(int $prevVertex): void
    {
        $this->prevVertex = $prevVertex;
    }
}

class second extends Page
{

    protected function showContent()
    {
        print "<b>ОСНОВНОЙ КОНТЕНТ ВТОРОЙ СТРАНИЦЫ</b>";
    }
}

(new second())->show();