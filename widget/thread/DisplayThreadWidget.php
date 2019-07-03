<?php
class DisplayThreadWidget extends Widget{
    public function __construct(){
        parent::__construct(
            "",
            '<div id="threadGrid"></div>',
            "",
            "displayThread",
            "",
            false,
            false
        );
        $this->build();
    }
}
