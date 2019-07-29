<?php
class FluxWidget extends Widget{
    public function __construct(Post $post, PostManager $manager){
        parent::__construct(
            '',
            $this->subConstruct($post, $manager),
            '',
            'FluxWidget',
            '',
            false,
            false
        );
        $this->build();
    }

    private function subConstruct(Post $post, PostManager $manager){
        return
        '<div id="messages"  class="width" style="text-align: left; font-size: 1.2em; font-family: source;">
            '.FluxControl::read($post, $manager).'
        </div>';
    }
}
