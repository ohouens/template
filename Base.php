<?php
class Base{
    protected $_title;
    protected $_styles;
    protected $_scripts;

    public static $racine = "";

    public function __construct($title='', array $styles = [], array $scripts = []){
        $this->_title = $title;
        $this->_styles = $styles;
        $this->_scripts = $scripts;
    }

    public function top(){
    ?>
    <!DOCTYPE html>
    <html>
    	<head>
    		<meta charset="utf-8"/>
    		<meta name="viewport" content="width=320"/>
            <link rel="stylesheet" href="style/affichage.css"/>
    		<?php foreach($this->_styles as $style)echo'<link rel="stylesheet" href="'.static::$racine.'style/'.$style.'.css"/>'; ?>
    		<link rel="icon" type="image/png" href="style/icon.png" />
    		<script src="script/jquery.js"></script>
    		<title><?php echo $this->_title; ?></title>
    	</head>

    	<body>
            <div id="conteneur">
    <?php
    }

    public function bottom(){
    ?>
            </div>
            <script src="script/affichage.js"></script>
        <?php foreach($this->_scripts as $script)echo'<script src="'.static::$racine.'script/'.$script.'.js"></script>'; ?>
        </body>
    </html>
    <?php
    }

    public static function staticBody($bdd, $html, $title='', array $styles = [], array $scripts = []){
    ?>
        <!DOCTYPE html>
        <html>
        	<head>
        		<meta charset="utf-8"/>
        		<meta name="viewport" content="width=320"/>
        		<?php foreach($styles as $style)echo'<link rel="stylesheet" href="style/'.$style.'"/>'; ?>
        		<link rel="icon" type="image/png" href="style/logo.png" />
        		<script src="script/jquery.js"></script>
        		<title><?php echo $title; ?></title>
        	</head>

        	<body>
        		<div id="conteneur">
        			<?php include($html); ?>
        		</div>
        		<?php foreach($scripts as $script)echo'<script src="script/'.$script.'"></script>'; ?>
        	</body>
        </html>
    <?php
    }

    public static function html($file){
        return static::$racine.'html/'.$file.'.php';
    }
}
