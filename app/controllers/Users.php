<?php
class Users extends Controller {
    public function __construct()
    {
        $this->userModel = $this->model('User');
    }

    public function register(){
        //To check for POST
        if($_SERVER['REQUEST_METHOD']=='POST'){
        //Process the form

        // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            // Init data
            $data = [
                'name'=>trim($_POST['name']),
                'email'=>trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' =>trim($_POST['confirm_password']),
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' =>''
            ];

        // Validate Email
            if(empty($data['email'])){
                $data['email_err'] = 'Please enter valid email';
            }else{
                // Check email
                if($this->userModel->findUserByEmail($data['email'])){
                    $data['email_err'] = 'Email is already taken';
                }
            }
        // Validate name
            if(empty($data['name'])){
                $data['name_err'] = 'Please enter name';
            }
        // Validate password
            if(empty($data['password'])){
                $data['password_err'] = 'Please enter password';
            }else{
                if(strlen($data['password']) < 6){
                    $data['password_err'] = 'Password must be min 6 characters.';
                }
            }
        // Validate confirm_password
            if(empty($data['confirm_password'])){
                $data['confirm_password_err'] = 'Please enter confirm password';
            }else
                if($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Password do not match.';
                }


        // Make sure no errors
        if(empty($data['email_err']) && empty($data['name_err']) &&
            empty($data['password_err']) && empty($data['confirm_password_err'])){
        //Validated

        // Hash the password
            //die('SUCCESS');

            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

            //REGISTER THE USER
            if($this->userModel->register($data)){
                flash('register_success','You are registered and can log in');
               redirect('users/login');
            }else{
                die('Something went wrong....');
            }

        }else{
            // Load view with errors
            $this->view('users/register', $data);
        }

        }else{
        // Load the from
        //    echo "Load the form";

            // Init data
            $data = [
                'name'=>'',
                'email'=>'',
                'password' => '',
                'confirm_password' =>'',
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' =>''

            ];

        //Load the view
            $this->view('users/register', $data);
        }
    }

    public function login(){
        //To check for POST
        if($_SERVER['REQUEST_METHOD']=='POST'){
            //Process the form

            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            // Init data
            $data = [

                'email'=>trim($_POST['email']),
                'password' => trim($_POST['password']),

                'email_err' => '',
                'password_err' => ''

            ];

            // Validate Email
            if(empty($data['email'])){
                $data['email_err'] = 'Please enter valid email';
            }
            // Validate password
            if(empty($data['password'])){
                $data['password_err'] = 'Please enter password';
            }

            //Check for user/email
            if($this->userModel->findUserByEmail($data['email'])){
                //User found

            }else{
                $data['email_err'] = 'No User Found';
            }

            // Make sure no errors
            if(empty($data['email_err']) && empty($data['password_err'])){
                //Validated
               //check and set logged in user

                $loggedInUser = $this->userModel->login($data['email'], $data['password']);

                if($loggedInUser){
                    //create the session
                    $this->createUserSession($loggedInUser);
                }else{
                    $data['password_err'] = 'Password in correct';
                    $this->view('users/login', $data);
                }
            }else{
                // Load view with errors
                $this->view('users/login', $data);
            }




        }else{
            // Load the from
            //    echo "Load the form";

            // Init data
            $data = [

                'email'=>'',
                'password' => '',
                'email_err' => '',
                'password_err' => ''

            ];

            //Load the view
            $this->view('users/login', $data);
        }
    }

    public function createUserSession($user){
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->name;
        redirect('posts');
    }

    public  function logout(){
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        session_destroy();
        redirect('users/login');
    }


}

