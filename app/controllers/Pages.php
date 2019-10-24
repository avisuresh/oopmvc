<?php

class Pages extends Controller {
    public function __construct()
    {

    }

    public function index(){
        if(isLoggedIn()){
            redirect('/posts/');
        }
      //   $this->view('hello');  // to see if the hello.php exitst in the view folder...
        $data =  [
            'title'=>'Suresh & Sons. Share Posts',
            'description' =>'Social network built on the OOP PHP MVC Framework!'

        ];
        $this->view('pages/index',$data);

    }

    public function about(){
       // echo 'this is about page '. $id;
        $data = [
            'title'=>'This is about us',
            'description' => 'App to share post with other users.'
        ];
        $this->view('pages/about', $data);
    }
}