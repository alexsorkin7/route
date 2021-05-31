<?php
namespace Also;
$ifAuth = true;

function isUser() {
    $id = $_SESSION['user_id'];
    if($id) {
        $user = model('user')->id($id)->get();
        if(!isset($user['error'])) return $user;
    } else return null;
}

function home() {
    $user = isUser();
    if($user !== null) {
        return mount('home.home');
    } else return redirect('login');
}

function settings($data) {
    if(isset($data['username'])) {
        if($data['username'] == $_SESSION['username']) {
            $errors = validate(['username'=>'min:3',],$data);
        } else $errors = validate(['username'=>'min:3|exists:user',],$data);
        
        if(count($errors) > 0) return mount('home.settings',compact('errors'));
        else {
            foreach ($data as $key => $value) {
                if($value == '') unset($data[$key]);
            }
            $result = model('user')->id($data['id'])->set($data);
            $_SESSION['username'] = $data['username'];
            return redirect('back');
        }
    } else if(isset($data['password'])) {
        $errors = validate([
            'password'=>'password:user',
            'newpassword'=>'min:8|nums',
        ],$data);
        if(count($errors) > 0) return mount('home.settings',compact('errors'));
        else {
            $result = model('user')->id($data['id'])->set(['password' => $data['newpassword']]);
            return redirect('back');
        }
    }
}


function settingsGet() {
    $user = isUser();
    if($user !== null) return mount('home.settings',compact('user','auth'));
}
