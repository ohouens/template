<?php
class DoneWidget extends Widget{
    public function __construct($succeed){
        parent::__construct(
            '',
            '<div id="operation" class="grand square">
                <div class="center">
                    <p>
                        '.$this->subConstruct($succeed).'
                    </p>
                </div>
            </div>',
            '',
            'DoneWidget',
            '',
            false,
            false
        );
        $this->build();
    }

    private function subConstruct($succeed){
        if($succeed == 0)
            return
                '<img src="style/icon/check.png" alt="operation succeed"><br/>
                (you can check your email)';
        return
            '<img src="style/icon/nonCheck.png" alt="operation succeed"><br/>
            (you might retry later..)';

    }
}
