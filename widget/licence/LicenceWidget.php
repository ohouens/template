<?php
class LicenceWidget extends Widget{
    public function __construct(User $user, PointManager $manager){
        parent::__construct(
            '',
            $this->subConstruct($user, $manager),
            '',
            'LicenceWidget',
            '',
            false,
            false
        );
        $this->build();
    }

    private function subConstruct($user, $manager){
        if(LicenceControl::isValide($user, $manager)){
            return
            '<div id="childV">
                <div id="licenceV" class="center">
                    <div class="square">
                        <div class="center">
                            <h1>Licence</h1>
                            <img src="media/licence/ecusson.png" alt="ecusson">
                            <p id="name">
                                ONISOWO
                            </p>
                            <p id="intro" class="alignement">
                                '.$user->getData()['licence'].'
                            </p>
                        </div>
                    </div>
                </div>
            </div>';
        }
        return
        '<div id="licence" style="background-image: url(\'media/fond/store/licence.jpg\');">
            <div class="super">
                <div class="center">
                    <img id="example" src="media/licence/onisowoLicence.png" alt="licence example">
                    <div id="desc" class="alignement">
                        <h1>Onisowo Licence</h1>
                        <p>
                            Support our team and unlock 77 thread slots.
                            You will be able to make your own distributed network
                            and do whatever you want with it.
                        </p>
                        <button id="logPaypal" class="space">
                            <img src="style/button/paypalCheckout(1).svg" alt="buy now"/>
                            <span>Checkout</span>
                        </button><br>
                        <p id="price" class="alignement"><strong>50.00 €</strong></p>
                    </div>
                </div>
            </div>
        </div>';
    }
}
