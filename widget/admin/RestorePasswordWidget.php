<?php
class RestorePasswordWidget extends Widget{
    public function __construct($forgot, $key){
        parent::__construct(
            '',
            '<div id="restore" class="grand square">
                <div class="center">
                    <form method="post" action="index.php?forgot='.$forgot.'&key='.$key.'">
                        <input class="input" type="password" name="new" placeholder="new password" /><br/>
                        <input class="input" type="password" name="confirm" placeholder="confirm new password" /><br/>
                        <input class="button space" type="submit" value="restore"/></br>
                        <span id="error"></span>
                    </form>
                </div>
            </div>',
            '',
            'RestorePassword',
            '',
            false,
            false
        );
        $this->build();
    }
}
