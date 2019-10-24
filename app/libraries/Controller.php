<?php
/*
 * Base Controller
 * Loads the model and views
 *
 */

class Controller{
    // Load the models ----------- they are the actual pages eg. about.php, add.php, show.php....
    public function model($model){
        //Require that model file from the model folder
        require_once '../app/models/'.$model.'.php';

        //instantiate the model
        return new $model();
    }

    // Load the view
    public function view($view, $data = []){
        //Check for view file
        if(file_exists('../app/views/'.$view.'.php')){
            require_once '../app/views/'.$view.'.php';
        }else{
            //View does not exists
            die('View does not exits');
        }
    }
}