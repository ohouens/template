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
            '<div id="childV" style="background-image: url(\'media/fond/store/licence.jpg\');">
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
                <h1>Licence</h1>
                <p id="intro" class="alignement">
                    <img src="media/licence/secureView.png" alt="PayPal Secure Mark">
                </p><p id="price" class="alignement"><strong>50€</strong></p>
                <div class="square" id="desc">
                    <div class="center">
                        <div class="alignement">
                            <h2>Flux <img src="style/icon/flux.png" alt="flux_icon"></h2>
                            <p>
                                Mail system by qr code subscribption to fastly enable your
                                followers about your updates, reductions, meetings..
                            </p>
                        </div><!--
                        --><div class="alignement">
                            <h2>Ticketing <img src="style/icon/ticket.png" alt="flux_icon"></h2>
                            <p>
                                Dematerialize ticketing system by qr code to fastly organize
                                event for your followers.
                            </p>
                        </div><!--
                        --><div class="alignement">
                            <h2>Forum <img src="style/icon/forum.png" alt="flux_icon"></h2>
                            <p>
                                Forum system with exclusive actions allowing your followers
                                to exchange with you from an organized way
                            </p>
                        </div>
                    </div>
                </div>
                <button id="logPaypal" class="space">
                    <img src="style/button/paypalCheckout(1).svg" alt="buy now"/>
                    <span>Checkout</span>
                </button>
            </div>
        </div>';
    }
}
