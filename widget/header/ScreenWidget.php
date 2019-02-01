<?php
class ScreenWidget extends Widget{
    protected $_matrice;
    protected $_slide;

    public function __construct($width, $height){
        parent::__construct('', '', '', 'screen', '', false, false);
        $this->_matrice = new Matrice($width, $height);
        $this->_slide = false;
    }
}
