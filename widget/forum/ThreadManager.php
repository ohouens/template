<?php
class ThreadManager extends PostManager{
    public function getThreadChildren(Post $post){
        $final = [];
        $list = $this->listFormatFilter($this->getList(), [
            Constant::FORMAT_SIMPLE,
            Constant::FORMAT_VOTE,
            Constant::FORMAT_REQUEST,
            Constant::FORMAT_BARRIER,
            Constant::FORMAT_CONTRACT
        ]);
        foreach($list as $inter){
            if($inter->getVar()['parent'] == $post->getId())
                array_push($final, $inter);
        }
        return $final;
    }
}
