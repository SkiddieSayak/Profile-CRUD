<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// Load the Rest Controller library
require APPPATH . '/libraries/REST_Controller.php';

class User extends REST_Controller {
    
    public function signin_post() {
        
        $email = $this->post('email');
        $password = md5($this->post('password'));
        
        if(!empty($email) && !empty($password)){

            $con['returnType'] = 'single';
            $con['conditions'] = array(
                'email' => $email,
                'password' => $password,
                'status' => 1
            );
            $user = $this->user_model->getRows($con);
            
            if($user){

                $this->response([
                    'status' => TRUE,
                    'message' => 'User login successful.',
                    'data' => $user
                ], REST_Controller::HTTP_OK);
            }else{
                $this->response("Wrong email or password.", REST_Controller::HTTP_BAD_REQUEST);
            }
        }else{
            $this->response("Provide email and password.", REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    
    public function signup_post() {
        $name = strip_tags($this->post('name'));
        $parts = explode(" ", $name);
        $lastname = array_pop($parts);
        $firstname = implode(" ", $parts);
        $email = strip_tags($this->post('email'));
        $username = strip_tags($this->post('username'));
        $location = strip_tags($this->post('city'));
        $phone = $this->post('phone');
        $password = md5($this->post('password'));
        
        if(!empty($name) && !empty($email) && !empty($password)){
            
            $con['returnType'] = 'count';
            $con['conditions'] = array(
                'email' => $email,
            );
            $userCount = $this->user_model->getRows($con);
            
            if($userCount > 0){
                $this->response("The given email already exists.", REST_Controller::HTTP_BAD_REQUEST);
            }else{
                $userData = array(
                    'first_name' => $firstname,
                    'last_name' => $lastname,
                    'email' => $email,
                    'username' => $username,
                    'password' => $password,
                    'city' => $location,
                    'phone' => $phone
                );
                $insert = $this->user_model->insert($userData);
                
                if($insert){

                    $this->response([
                        'status' => TRUE,
                        'message' => 'The user has been added successfully.',
                        'data' => $insert
                    ], REST_Controller::HTTP_OK);
                }else{

                    $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
                }
            }
        }else{

            $this->response("Provide complete user info to add.", REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    
    public function profile_get($id = 0) {

        $con = $id?array('id' => $id):'';
        $users = $this->user_model->getRows($con);
        

        if(!empty($users)){

            if($users['status'] != 1 ){
                $this->response([
                    'status' => FALSE,
                    'message' => 'User is Not Active.'
                ], REST_Controller::HTTP_NOT_FOUND);
            }
            $this->response($users, REST_Controller::HTTP_OK);
        }else{

            $this->response([
                'status' => FALSE,
                'message' => 'No user was found.'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
    
    public function update_put() {
        
        $id = $this->put('id');
        $password = md5($this->put('password'));

        $name = strip_tags($this->put('name'));
        $parts = explode(" ", $name);
        $lastname = array_pop($parts);
        $firstname = implode(" ", $parts);
        $email = strip_tags($this->put('email'));
        $username = strip_tags($this->put('username'));
        $location = strip_tags($this->put('city'));
        $phone = $this->post('phone');

        if(!empty($id) && !empty($password) && (!empty($name) || !empty($email) || !empty($username) || !empty($phone) || !empty($location)) ){
            $userData = array();
            
            if(!empty($first_name)){
                $userData['first_name'] = $firstname;
            }
            if(!empty($last_name)){
                $userData['last_name'] = $lastname;
            }
            if(!empty($email)){
                $userData['email'] = $email;
            }
            if(!empty($username)){
                $userData['username'] = $username;
            }
            if(!empty($password)){
                $userData['password'] = $password;
            }
            if(!empty($location)){
                $userData['city'] = $location;
            }
            if(!empty($location)){
                $userData['phone'] = $phone;
            }
            $update = $this->user_model->update($userData, $id);
            
            if($update){

                $this->response([
                    'status' => TRUE,
                    'message' => 'The user info has been updated successfully.'
                ], REST_Controller::HTTP_OK);
            }else{

                $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
            }
        }else{
            $this->response("Provide at least one user info to update.", REST_Controller::HTTP_BAD_REQUEST);
        }
    }

}