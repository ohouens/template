<?php
class DescHomeWidget extends Widget{
    public function __construct(){
        parent::__construct(
            '',
            '<div id="descHome" class="grand" style="background:url(\'media/fond/home/homeDesc.jpg\'); background-size:cover;">
                <div class="super">
                    <div class="center">
                        <img src="style/logo(1).png" alt="big logo">
                    </div>
                </div>
            </div>',
            '',
            'DescriptionHome',
            '',
            false,
            false
        );
        $this->build();
    }
}
