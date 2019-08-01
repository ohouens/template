<?php
class DesactivateWidget extends Widget{
    public function __construct($forgot, $key){
        parent::__construct(
            '',
            '<div id="desactivate" class="children square">
                <div class="center">
                    <h1>Delete account</h1>
                    <p>Are you sure about your choice ? <strong>This will be permanent.</strong></p>
                    <form method="post" action="index.php?desactivate='.$forgot.'&key='.$key.'">
                        <input class="input" type="password" name="password" placeholder="password" /><br/>
                        <input class="button space" type="submit" value="Desactivate"/></br>
                        <span id="error"></span>
                    </form>
                </div>
            </div>',
            '',
            'deleteAccount',
            '',
            false,
            false
        );
        $this->build();
    }
}
