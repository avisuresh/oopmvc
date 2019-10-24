<?php
class Posts extends Controller{
    public  function __construct()
    {
        if(!isLoggedIn()){
            redirect('users/login');
        }

        $this->postModel = $this->model('Post');
        $this->userModel = $this->model('User');
    }


    public function index(){
        // get Post
        $post = $this->postModel->getPosts();
        $data = [
            'posts' => $post
        ];

        $this->view('posts/index', $data);
    }

    public function add(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            //Sanitize POST array
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'title' => trim($_POST['title']),
                'body' => trim($_POST['body']),
                'user_id' => $_SESSION['user_id'],
                'title_err' => '',
                'body_err' => ''
            ];

            //Validate title
            if(empty($data['title'])){
                $data['title_err'] = 'Please enter title';
            }
            //Validate body
            if(empty($data['body'])){
                $data['body_err'] = 'Please body text';
            }

            //Make sure no errors are found
            if(empty($data['title_err']) && empty($data['body_err'])){
                //validated
                if($this->postModel->addPost($data)){
                    flash('post_message', 'Post Added');
                    redirect('posts/');
                }else{
                    die('Something went wrong');
                }

            }else{
                //Load view with error
                $this->view('posts/add', $data);
            }


        }else {

            $data = [
                'title' => '',
                'body' => ''
            ];

            $this->view('posts/add', $data);
        }

    }

    //edit post

    public function edit($id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            //Sanitize POST array
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'id'=>$id,
                'title' => trim($_POST['title']),
                'body' => trim($_POST['body']),
                'user_id' => $_SESSION['user_id'],
                'title_err' => '',
                'body_err' => ''
            ];

            //Validate title
            if(empty($data['title'])){
                $data['title_err'] = 'Please enter title';
            }
            //Validate body
            if(empty($data['body'])){
                $data['body_err'] = 'Please body text';
            }

            //Make sure no errors are found
            if(empty($data['title_err']) && empty($data['body_err'])){
                //validated
                if($this->postModel->updatePost($data)){
                    flash('post_message', 'Post Updated');
                    redirect('posts/');
                }else{
                    die('Something went wrong');
                }

            }else{
                //Load view with error
                $this->view('posts/edit', $data);
            }


        }else {
            //Get existing post from model
            $post = $this->postModel->getPostById($id);
            //Check for owner
            if($post->user_id != $_SESSION['user_id']){
                redirect('posts');
            }

            $data = [
                'id' => $id,
                'title' => $post->title,
                'body' => $post->body
            ];

            $this->view('posts/edit', $data);
        }

    }


    public function show($id){
        //goes to the model (database) and gets the value and returns as array data which is passed to the view to show
        //the result
        $post = $this->postModel->getPostById($id);
        $user = $this->userModel->getUserById($post->user_id);


        $data = [
            'post' =>$post,
            'user' =>$user
        ];

        $this->view('/posts/show', $data);
    }

    public function delete($id){
        //goes to the model (database) and gets the value and returns as array data which is passed to the view to show
        //the result
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            if($this->postModel->deletePost($id)){
                flash('post_message', 'Post deleted!');
                redirect('posts');
            }else{
                die('Something went wrong.');
            }

        }else{
            redirect('posts');
        }

    }
}