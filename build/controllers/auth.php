<?php
namespace Also;

function register($req) {
    $errors = validate([
        'email'=>'required|email|exists:users',
        'username'=>'required|min:3|exists:users',
        'password'=>'required|num|uppers|symbols',
        'confirm'=>'required|confirm:password'
    ],$req['data']);

    if(count($errors) == 0) {
        unset($req['data']['confirm']);
        $result = $model.insert([$req['data']]);
        // get last id
        // add id to session
        return redirect('/home');
    } else {
        $old = $req['data'];
        return mount('/auth.auth.register',compact('errors','old'));
    }
}

function login($req) {
    global $model;
    $data = $req['data'];
    $errors = validate([
        'password'=>'required|password:user',
        'email'=>'required|email|notexists:user'
    ],$data);
    if(count($errors) == 0) {
        $result = $model->model('user')->where(["email='".$data['email']."'"]);
        if(isset($result['result'])) {
            $userData = $result['result'][0];
            if(isset($userData)) {
                $_SESSION['user_id'] = $userData['id'];
                return redirect('/home');
            } else return mount('auth.auth.login',['errors'=>'no such user']);
        } else return mount('auth.login',['errors'=>'db error']);
    } else return mount('auth.auth.login',compact('errors'));
}


function logout($req) {
    $_SESSION['user_id'] = 0;
    return redirect('/login');
}

function loginGet($req) {
    $errors = [];
    if($_SESSION['user_id']) return redirect('/home');
    else return mount('auth.auth.login',['auth'=>false,'errors' => $errors]);
}

function registerGet($req) {
    $errors = [];
    if($_SESSION['user_id']) return redirect('/home');
    else return mount('auth.auth.register',['auth'=>false,'errors' => $errors]);
}

function isAuth($req) {
    global $model;
    $id = $_SESSION['user_id'];
    if($id) {
        $result = $model->model('user')->where(["id='$id'"]);
        if(isset($result['result'])) return $result['result'][0];
    } else return null;
}

function home($req) {
    $user = isAuth($req);
    if($user !== null) {
        $auth = true;
        if($user['is_admin']) return mount('auth.home.admin',compact('user','auth'));
        else if(!$user['is_admin']) return mount('auth.home.home',compact('user','auth'));
    } else return redirect('/login');
}

function settings($req) {
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

function settingsGet($req) {
    $user = isAuth($req);
    $auth = true;
    if($user !== null) return mount('auth.home.settings',compact('user','auth'));
}


?>
