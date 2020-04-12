<?php
class NoAccountWidget extends Widget{
    public function __construct(Post $post){
        global $hash;
        parent::__construct(
            '',
            '<div class="grand square">
                <div class="center">
                    <form method="get" id="nonMail" action="index.php">
                        <p>Please enter your email</p>
                        <input type="hidden" name="thread" value="'.$hash->get($post->getId()).'">
                        <input type="hidden" name="request" value="3">
                        <input type="email" name="email" class="input" placeholder="email"/><br/>
                        <span>By clicking the send button, you accept the <a href="https://onisowo.com/policy/privacy/#nonUser">privacy policy</a></span><br/>
                        <input type="submit" value="send" class="buttonC space"/><br/>
                        <span id="erreur"></span>
                    </form>
                </div>
            </div>',
            '',
            'NoAccount',
            '',
            false,
            false,
            false
        );
        $this->build();
    }
}
