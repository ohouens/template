<?php
class CatalogueTemplate extends Base{
    public function Header($option=true, $wrapperHeight=''){
    ?>
    <header>
    	<div class="alignement gauche">
    		<div id="home" class="<?php if($option)echo'first '; ?>alignement" style="background: url('style/icon/<?php if(incatalogue($this->_$bdd))echo'flag_Catalogue.png'; else echo'person.png'; ?>'); background-size: cover;"></div>
    		<span id="pseudo"><?php echo get($this->_$bdd, $_SESSION['id'], 'pseudo'); ?></span>
    		<div id="plus">
    			<a href="index.php?setting"><span>Setting</span></a>
    			<a href="index.php?deconnexion"><span>Log out</span></a>
    		</div>
    	</div><!--
    	--><div class="alignement droit">
    		<?php
    		if(inCatalogue($this->_$bdd)){
    			if($option)echo'<a href="index.php?create=poster"><img src="style/icon/add.png" alt="create item"/></a>';
    		}else
    			if($option)echo'<a href="index.php?upgrade=account"><img src="style/icon/prenium.png" alt="upgrade to merchant account"/></a>';
    		?>
    	</div>
    </header>
    <div class="wrapper" <?php if($wrapperHeight != '')echo'style="height: '.$wrapperHeight.'vh"'; ?>>
    <?php
    }

    public function Footer(){
    	global $link;
    ?>
    </div>
    <footer>
    	<p id="copyright"><span>© ohouens 2018</span></p>
    	<div id="onisowo" class="alignement">
    		<div class="center">
    			<img src="<?php echo $link; ?>/style/logo.png" alt="onisowo logo"/><br/>
    			<a href="<?php echo $link; ?>">onisowo.com</a>
    		</div>
    	</div><!--
    	--><div id="contact" class="alignement">
    		<div class="center">
    			<p>
    				<img src="style/icon/twitter.png" alt="twitter"/>
    				<img src="style/icon/instagram.png" alt="instagram"/>
    				<a href="mailto:ryan@ohouens.com"><img src="style/icon/mail.png" alt="mail"/></a>
    				<img src="style/icon/android.png" alt="android app"/>
    			</p>
    		</div>
    	</div><!--
    	--><div id="presentation" class="alignement">
    		<div class="center">
    			<h2>Le Catalogue</h2>
    			<p>
    				"L'objectif de ce catalogue est de simplifier au maximum le commerce de proximité.
    				La croissance de cet outil reposera sur le partage des clients grace à la base commune d'un catalogue.
    				Cela permettra aux commerçants de tout horizons d'attirer de nouveaux clients et de fidéliser les anciens."
    			</p>
    		</div>
    	</div>
    	<div id="partner">
    		<div class="center">
    			<p>
    				<a href="https://play.google.com/store/apps/details?id=com.ohouens.catalogue" target="_blank"><img src="style/icon/playstore.png" alt="playstore"/></a>
    			</p>
    		</div>
    	</div>
    	<div id="links">
    		<p class="center">
    			<a href="policy/sale/">Sale policy</a>
    			<a href="https://jobs.onisowo.com">Jobs</a>
    			<a href="https://partner.onisowo.com">Collaboration</a>
    			<a href="policy/privacy/">Privacy policy</a>
    		</p>
    	</div>
    </footer>
    <?php
    }
}
