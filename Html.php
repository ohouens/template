<?php
class Html{
    protected $_type;
    protected $_content;
    protected $_id;
    protected $_background;
    protected $_super;
    protected $_center;

    public function __construct($type=0, $content="", $id="", $background="", $super=false, $center=true){
        $this->_type = $type;
        $this->_content = $content;
        $this->_id = $id;
        $this->_background = $background;
        $this->_super = $super;
        $this->_center = $center;
    }

    public function show(){
        if($this->_super)echo '<div class="super">';
        if($this->_center)echo '<div class="center">';
        switch ($this->_type){
            case 0:
                echo $this->_content;
                break;
            case 1:
                include($this->_content);
                break;
            default:
                // code...
                break;
        }
        if($this->_center)echo '</div>';
        if($this->_super)echo '</div>';
    }

    public function getContent(){return $this->_content;}
    public function getType(){return $this->_type;}
    public function getId(){return $this->_id;}
    public function getBackground(){return $this->_background;}
    public function getSuper(){return $this->_super;}
    public function getCenter(){return $this->_center;}

    public function setContent($content){
        $this->_content = $content;
    }

    public function setType($type){
        $this->_type = $type;
    }

    public function setId($id){
        $this->_id = $id;
    }

    public function setBackground($background){
        $this->_background = $background;
    }

    public function setSuper($super){
        $this->_super = $super;
    }

    public function setCenter($center){
        $this->_center = $center;
    }
}
