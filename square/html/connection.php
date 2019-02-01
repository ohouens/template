<p><img src="style/logo.png" id="logo" alt="logo"/></p>
<form id="signin" method="post" autocomplete="off">
	<input type="text" name="pseudo" placeholder="pseudo" class="input"/><br/>
	<input type="text" name="email" placeholder="email" class="input"/><br/>
	<input type="password" name="password" placeholder="password" class="input"/><br/>
	<input type="submit" value="Sign Up" class="validation"/>
	<p>
		<a href="#" class="aide" id="helpLog">Log in</a>
	</p>
	<span id="erreurSign"></span>
</form>
<form id="login" method="post" autocomplete="off">
	<input type="text" name="pseudo" placeholder="pseudo" class="input"/><br/>
	<input type="password" name="password" placeholder="password" class="input"/><br/>
	<input type="submit" value="Log in" class="validation"/>
	<p>
		<a href="#" class="aide" id="helpPass">Forgot your password ?</a>
		<a href="#" class="aide" id="helpSign">Sign up</a>
	</p>
	<span id="erreurLog"></span>
</form>
<form id="recup" method="post" autocomplete="off">
	<input type="text" name="email" placeholder="email" class="input"/><br/>
	<input type="submit" value="Send" class="validation"/><br/>
	<p>
		<a href="#" class="aide" id="helpLogBis">Back</a>
	</p>
	<span id="erreurRecup"></span>
</form>
