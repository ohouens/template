<?php
class Widget extends Html{
    protected $_title;
    protected $_section;
    protected $_button;
    protected $_script;
    protected $_style;

    public function __construct($title = "", $section = "", $button = "", $id="", $background="", $super=false, $center=true){
        parent::__construct(0, "", $id, $background, $super, $center);
        $this->_section = $section;
        $this->_title = $title;
        $this->_button = $button;
    }

    public function build(){
        $title = $this->_title;
        $section = $this->_section;
        $button = $this->_button;
        if($title != "" and !preg_match("#<h[1-5]( .+)?>.+</h[1-5]>#", $this->_title))
            $title = '<h1>'.$this->_title.'</h1>';
        if($section != "" and !preg_match("#^<(p|div|section)( .+)?>.+</(p|div|section)>$#s", $this->_section))
            $section = '<div>'.$this->_section.'</div>';
        if($button != "" and !preg_match("#<button( .+)?>.+</button>#", $this->_button))
            $button = '<button>'.$this->_button.'</button>';
        $this->setContent($title.$section.$button);
    }

    public function getTitle(){return $this->_title;}
    public function getSection(){return $this->_section;}
    public function getButton(){return $this->_button;}

    public function setTitle($title){
        $this->_title = $title;
    }

    public function setSection($section){
        $this->_section = $section;
    }

    public function setButton($button){
        $this->_button = $button;
    }

    public static function toClass(array $class){
        $string = "";
        for($i=0; $i<count($class)-1; $i++)
            $string .= $class[$i]." ";
        $string .= $class[count($class)-1];
        return $string;
    }
}
