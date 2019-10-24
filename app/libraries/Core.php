<?php
/*
 * App Core Class
 * Creates URL & loads core controller
 * URL FORMAT - /controller/method/params
 */

class Core{
    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $params =[];

    public function __construct()
    {
     //print_r($this->getUrl());
        $url = $this->getUrl();

    // Look in Controllers for first value
        if(file_exists('../app/controllers/'.ucfirst($url[0]).'.php')){
            // If Exists, set as currrent controller
            $this->currentController = ucfirst($url[0]);
            //Unset 0 Index
            unset($url[0]);
        }
    // Require the controller
        require_once '../app/controllers/'.$this->currentController.'.php';

    // Instatiate the controller class
        $this->currentController = new $this->currentController;

    // Check for the second part of the URL
        if(isset($url[1])){
            //check to see if the method exists or the second part of the URL
            if(method_exists($this->currentController, $url[1])){
                $this->currentMethod = $url[1];
                //Unset 1 index
                unset($url[1]);
            }
        }

    //  echo $this->currentMethod;  // to check if the second part of the url exists
    // Get Params - Parameters....
        $this->params = $url ? array_values($url) : []; // if there is parameters it will be added or empty array will be added

        // Call a callback with array of params
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);

    }

    public function getUrl(){
        if(isset($_GET['url'])){
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
    }

}