<?php
abstract class OptionWidget extends Widget{
    private $_name;
    private $_view;
    private $_color;
    private $_links;

    protected $_hash = [];

    public function __construct($name, $view, $color, array $links){
        parent::__construct("", "", "", "", "", false, false);
        $this->setName($name);
        $this->setView($view);
        $this->setColor($color);
        $this->_links = $this->setLinks($links);
        $this->setSection($this->action());
        $this->build();
    }

    private function action(){
        return
        '<div id="'.$this->_name.'" class="action alignement plein" style="background: '.$this->_color.';">
            <div class="square plein">
                <div class="center">
                    <a href="#">'.$this->_view.'</a>
                </div>
            </div>
            <div class="plus">
                '.$this->buildLinks().'
            </div>
        </div>';
    }

    public abstract function screen(Manager $manager);

    public function getName(){return $this->_name;}
    public function getView(){return $this->_view;}
    public function getColor(){return $this->_color;}
    public function getLinks(){return $this->_links;}

    public function setName($name){
        $this->_name = $name;
    }

    public function setView($view){
        $this->_view = $view;
    }

    public function setColor($color){
        $this->_color = $color;
    }

    public function setLinks(array $links){
        $result = [];
        foreach($links as $link){
            if(!is_string($link))return;
            if(preg_match("#^[a-z]{2,20}$#", $link)){
                array_push($result, $link);
            }
        }
        return $result;
    }

    private function toLink($link){
        if(!array_key_exists($link, $this->_hash))
            return $link;
        return $this->_hash[$link];
    }

    private function buildLinks(){
        $result = "";
        foreach($this->_links as $link){
            $result .= '<a href="index.php?'.$this->toLink($link).'"><span>'.ucfirst($link).'</span></a>
            ';
        }
        return $result;
    }
}
