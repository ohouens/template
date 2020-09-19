<?php
class CreatePosterWidget extends Widget{
    private $_user;

    public function __construct(User $user){
        parent::__construct(
            "",
            $this->subConstruct($user),
            "",
            "posterCreation",
            "",
            false,
            false
        );
        $this->_user = $user;
        $this->build();
    }

    private function subConstruct(User $user){
        return
        '<div id="createPoster">
            <h1>New Poster</h1>
            <form enctype="multipart/form-data" method="post" action="index.php?katalogi=creation">
                <input type="hidden" name="extra" value=""/>
                <input class="input" type="text" name="title" placeholder="Title"/><br>
                <select name="subtype" class="input">
                    <option value="0">reading</option>
                    <option value="1">gps</option>
                    <option value="2">code</option>
                    <option value="3">link</option>
                </select><br>
                <textarea name="desc" class="input"></textarea><br>
				<input type="text" name="extra" class="input vide"/><br>
                <input type="text" name="address" class="input vide" placeholder="address"/><br><br>
                <input type="file" name="cover" accept="image/x-png,image/jpeg"/>
            </form>
            <button id="submit" class="button space">Create</button>
            <div id="erreurCreate" class="erreur"></div>
        </div>
        ';
    }
}
