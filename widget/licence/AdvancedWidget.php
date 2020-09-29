<?php
class AdvancedWidget extends Widget{
    public function __construct(){
        parent::__construct(
            '',
            $this->subConstruct(),
            '',
            'AdvancedWidget',
            '',
            false,
            false
        );
        $this->build();
    }

    private function subConstruct(){
        return
        '<div id="advanced" class="store" style="background-image: url(\'media/fond/store/advanced.jpg\');">
            <div class="super">
                <div class="center">
                    <div id="desc" class="alignement">
                        <h1>Onisowo Advanced</h1>
                        <p>
                            Support our team and unlock 14 thread slots.
                        </p>
                        <button id="logPaypal" class="space">
                            <img src="style/button/paypalCheckout(1).svg" alt="buy now"/>
                            <span>Checkout</span>
                        </button><br>
                        <p id="price" class="alignement"><strong>5.00 €</strong></p>
                    </div>
                </div>
            </div>
        </div>';
    }
}
