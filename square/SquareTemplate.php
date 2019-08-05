<?php
class SquareTemplate extends Base{
    public static $racine = "module/template/square/";

    public function fade(array $contain = [], $id="", array $class=[], $r=248, $g=249, $b=251){
        echo '<div id="'.$id.'">';
        foreach($contain as $post){
            ?>
            <div <?php if($post->getId() != "")echo'id="'.$post->getId().'"'; ?> class="alignement<?php if($post->getCenter())echo ' square'; ?>" style="background-color: rgb(<?php echo $r.','.$g.','.$b; ?>);<?php if($post->getBackground() != '')echo 'background-image: '.$post->getBackground().';';?>">
                <?php $post->show(); ?>
            </div>
            <?php
            $r -= 4;
            $g -= 4;
            $b -= 4;
        }
        echo '</div>';
    }

    public function horizontalFade(array $contain = [], $maxElements=4, $id="", array $class=[], $r=248, $g=249, $b=251){
        $elements = count($contain);
        $width = 100/$maxElements;
        if($elements < $maxElements)
            $width = 100/$elements;
        echo '<div id="'.$id.'">';
        foreach($contain as $post){
            echo '<div id="'.$post->getId().'" class="alignement horizontal" style="width: '.$width.'%;background-color: rgb('.$r.','.$g.','.$b.');background: '.$post->getBackground().';">';
            $post->show();
            echo '</div>';
            $r -= 4;
            $g -= 4;
            $b -= 4;
        }
        echo '</div>';
    }

    public function biplace(array $contain=[], $left=50, $right=50, $id="", array $class=[], $r=248, $g=249, $b=251){
        if(count($contain) < 2)return;
        ?>
        <div id="<?php echo $id; ?>" <?php if(count($class) != 0)echo' class="'.Widget::toClass($class).'"'; ?>>
            <div <?php if($contain[0]->getId() != "")echo'id="'.$contain[0]->getId().'"'; ?> class="alignement biplace" style="width: <?php echo $left; ?>%;background-color: rgb(<?php echo $r.','.$g.','.$b; ?>);<?php if($contain[0]->getBackground() != '')echo 'background: '.$contain[0]->getBackground().';';?>;background-size: cover;">
                <?php $contain[0]->show(); ?>
            </div><!--
            --><div <?php if($contain[1]->getId() != "")echo'id="'.$contain[1]->getId().'"'; ?> class="alignement biplace" style="width: <?php echo $right; ?>%;background-color: rgb(<?php echo ($r-4).','.($g-4).','.($b-4); ?>);<?php if($contain[1]->getBackground() != '')echo 'background: '.$contain[1]->getBackground().';';?>;background-size: cover;">
                <?php $contain[1]->show(); ?>
            </div>
        </div>
        <?php
    }

    public function header(Html $html1, Html $html2, $wrapperHeight='', $gauche='', $droit=''){
        ?>
        <header>
            <div class="alignement gauche">
                <?php $html1->show(); ?>
            </div><!--
            --><div class="alignement droit">
                <?php $html2->show(); ?>
            </div>
        </header>
        <div class="wrapper" <?php if($wrapperHeight != '')echo'style="height: '.$wrapperHeight.'vh"'; ?>>
        <?php
    }

    public function lock($wrapperHeight=''){
        ?>
        <div class="wrapper" <?php if($wrapperHeight != '')echo'style="height: '.$wrapperHeight.'vh"'; ?>>
        <?php
    }

    public function unlock(){
        echo'</div>';
    }

    public function footer(Html $banner){
        ?>
        </div>
        <footer>
            <p id="copyright"><span>© ohouens 2019</span></p>
        	<div id="banner">
                <div class="large center">
                    <?php $banner->show(); ?>
                </div>
        	</div>
        	<div id="links">
        		<p class="center">
        			<a href="https://oniswo.com/policy/sale/">Sale policy</a>
        			<a href="https://partner.onisowo.com">Collaboration</a>
        			<a href="https://oniswo.com/policy/privacy/">Privacy policy</a>
        		</p>
        	</div>
        </footer>
        <?php
    }

    public function footerLight(){
        ?>
        </div>
        <footer id="footerLight">
            <p id="copyright"><span>© ohouens 2019</span></p>
        </footer>
        <?php
    }
}
