<?php
class OneWidget extends Widget{
    public function __construct(){
        parent::__construct(
            '',
            $this->subConstruct(),
            '',
            'OneWidget',
            '',
            false,
            false
        );
        $this->build();
    }

    private function subConstruct(){
        return
        '<div id="one" class="store" style="background-image: url(\'media/fond/store/one.jpg\');">
            <div class="super">
                <div class="center">
                    <div id="desc" class="alignement">
                        <h1>Onisowo One</h1>
                        <p>
                            Support our team and unlock 1 thread slot.
                        </p>
                        <button id="logPaypal" class="space">
                            <img src="style/button/paypalCheckout(1).svg" alt="buy now"/>
                            <span>Checkout</span>
                        </button><br>
                        <p id="price" class="alignement"><strong>1.00 €</strong></p>
                    </div>
                </div>
            </div>
        </div>';
    }
}
