<?php
namespace Also;

function mount($path,$variables = []) {
    global $env;
    $token = $_SESSION['token'];
    $csrf = '<input type="hidden" name="token" value="'.$token.'">';
    $mount = new Mount($env->mountPath);
    $variables['csrf'] = $csrf;
    $variables['token'] = $token;
    $variables['appName'] = $env->appName;
    return $mount->mount($path,$variables);
}


// pre($req);

// $mount = function($path,$variables = []) use ($req) {
//     pre($req);
//     global $env;
//     $mount = new Mount($env->mountPath);
//     return $mount->mount($path,$variables);
// }


?>