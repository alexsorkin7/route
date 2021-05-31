<?php
namespace Also;

function register($data) {
    $errors = validate([
        'email'=>'exists:user',
        'username'=>'exists:user',
    ],$data);
    if(isAjax()) return json_encode($errors);
    if(count($errors) == 0) {
        unset($data['confirm']);
        $user = model('user')->create($data);
        if(setUser($user)) return redirect('/home');
        else return mount('auth.register',['errors'=>'db error']);
    } else {
        $old = $data;
        return mount('auth.register',compact('errors','old'));
    }
}

function setUser($user) {
    if(isset($user['id'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = $user['is_admin'];
        return true;
    } else return null;
}

function login($data) {
    $data = $data;
    $errors = validate([
        'password'=>'required|password:user',
        'email'=>'required|email'
    ],$data);
    if(count($errors) == 0) {
        $user = model('user')->where('email',$data['email'])->get();
        if(setUser($user)) return redirect('/home');
        else return mount('auth.login',['errors'=>'db error']);
    } else return mount('auth.login',compact('errors'));
}

function logout($data) {
    unset($_SESSION['user_id']);
    return redirect('/login');
}

function loginGet($data) {
    $errors = [];
    if(isset($_SESSION['user_id'])) {
        return redirect('/home');
    } else return mount('auth.login',['auth'=>false,'errors' => $errors]);
}

function registerGet($data) {
    $errors = [];
    if(isset($_SESSION['user_id'])) return redirect('/home');
    else return mount('auth.register',['auth'=>false,'errors' => $errors]);
}

?>
