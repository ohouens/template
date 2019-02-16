<?php
class ThreadMessageWidget extends Widget{

    public function __construct($num, $user, $message, $cote){
        parent::__construct(
            "",
            '<div num="'.$num.'" class="answer cote_'.$cote.'">
                <p class="user">
                    '.$user.'
                </p>
                <p class="message">
                    '.$message.'
                </p>
            </div>',
            "",
            "",
            false,
            false
        );
        $this->build();
    }
}
