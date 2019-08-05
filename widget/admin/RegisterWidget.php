<?php
class RegisterWidget extends Widget{
    public function __construct(){
        parent::__construct(
            "",
             '<p><img src="style/logo.png" id="logo" alt="logo"/></p>
             <form id="signin" method="post" autocomplete="off">
             	<input type="text" name="pseudo" placeholder="pseudo" class="input"/><br/>
             	<input type="text" name="email" placeholder="email" class="input"/><br/>
             	<input type="password" name="password" placeholder="password" class="input"/><br/>
             	<input type="submit" value="Sign Up" class="validation buttonA"/>
             	<p>
             		<a href="#" class="aide" id="helpLog">Log in</a>
             	</p>
             	<div id="erreurSign" class="erreur"></div>
             </form>
             <form id="login" method="post" autocomplete="off">
             	<input type="text" name="pseudo" placeholder="pseudo" class="input"/><br/>
             	<input type="password" name="password" placeholder="password" class="input"/><br/>
                <span>
                    <input type="checkbox" name="prolonged" id="prolonged" checked/>
                    <label for="prolonged">stay logged in</label>
                </span><br/>
             	<input type="submit" value="Log in" class="validation buttonA"/>
             	<p>
             		<a href="#" class="aide" id="helpPass">Forgot your password ?</a>
             		<a href="#" class="aide" id="helpSign">Sign up</a>
             	</p>
             	<div id="erreurLog" class="erreur"></div>
             </form>
             <form id="recup" method="post" autocomplete="off">
             	<input type="text" name="email" placeholder="email" class="input"/><br/>
             	<input type="submit" value="Send" class="validation buttonA"/><br/>
             	<p>
             		<a href="#" class="aide" id="helpLogBis">Back</a>
             	</p>
             	<div id="erreurRecup" class="erreur"></div>
             </form>',
             "",
             "register"
         );
         $this->build();
    }
}
