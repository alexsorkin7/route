<?php
namespace Also;

function register($data) {
    $errors = validate([
        'email'=>'required|email|notExists:user',
        'username'=>'required|min:3|notExists:user',
        'password'=>'required|num|uppers|symbols',
        'confirm'=>'required|confirm:password'
    ],$data);
    if(count($errors) == 0) {
        unset($data['confirm']);
        $user = model('user')->create($data);
        if(isset($user['id'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
        }
        return redirect('/home');
    } else {
        $old = $data;
        return mount('/auth.auth.register',compact('errors','old'));
    }
}

function login($data) {
    $data = $data;
    $errors = validate([
        'password'=>'required|password:user',
        'email'=>'required|email'
    ],$data);
    if(count($errors) == 0) {
        $user = model('user')->where('email',$data['email'])->get();
        if(!isset($user['error'])) {
            if(isset($user['id'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                return redirect('/home');
            } else return mount('auth.auth.login',['errors'=>'no such user']);
        } else return mount('auth.login',['errors'=>'db error']);
    } else return mount('auth.auth.login',compact('errors'));
}


function logout($data) {
    unset($_SESSION['user_id']);
    return redirect('/login');
}

function loginGet($data) {
    $errors = [];
    if(isset($_SESSION['user_id'])) {
        return redirect('/home');
    } else return mount('auth.auth.login',['auth'=>false,'errors' => $errors]);
}

function registerGet($data) {
    $errors = [];
    if(isset($_SESSION['user_id'])) return redirect('/home');
    else return mount('auth.auth.register',['auth'=>false,'errors' => $errors]);
}

function isUser() {
    $id = isAuth();
    if($id) {
        $user = model('user')->id($id)->get();
        if(!isset($user['error'])) return $user;
    } else return null;
}

function home($data) {
    $user = isUser();
    if($user !== null) {
        $auth = true;
        if($user['is_admin']) return mount('auth.home.admin');
        else if(!$user['is_admin']) return mount('auth.home.home');
    } else return redirect('/login');
}

function isAuth() {
    if(isset($_SESSION['user_id'])) {
        return $_SESSION['user_id'];
    } else return false;
}

function settings($data) {
//     if(req.data.password !== undefined) {
//         let errors = await new Validator({
//             password:'required|password:users',
//             newpassword:'required|num|uppers|symbols',
//             confirm:'required|confirm:newpassword'
//         },req.data,model).async()
//         if(errors.errors == 0) {
//             let result = await users.update({password:req.data.password},{id:req.data.id}).async()
//             if(result.changes > 0 || result.rows.changedRows > 0) {
//                 res.end(req.mount('auth.home.settings',{errors:['password changed']}))
//             } else res.end(req.mount('auth.home.settings',{errors:['something wrong - password not changed']}))     
//         } else res.end(req.mount('auth.home.settings',{errors}))
//     } else if(req.data.username !== undefined) {
//         let obj = {}
//         for(key in req.data) {
//             if(req.data[key] !== '') obj[key] = req.data[key]
//         }
//         if(Object.keys.length !== 0) {
//             let result = await users.update(obj,{id:req.data.id}).async()
//             if(result.changes > 0 || result.rows.changedRows > 0) {
//                 if(obj.username !== undefined) req.session.set({username:obj.username})
//                 res.end(req.mount('auth.home.settings',{user:await isAuth(req),errors:['personal data changed']})) // in sqlite
//             }
//         } else res.end(req.mount('auth.home.settings',{user:await isAuth(req),errors:['something wrong. data not updated']}))
//     }
}

function settingsGet($data) {
    $user = isAuth($data);
    $auth = true;
    if($user !== null) return mount('auth.home.settings',compact('user','auth'));
}


?>
