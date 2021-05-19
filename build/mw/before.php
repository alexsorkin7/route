<?php
namespace Also;

// Set model
$model = new Model($env->con);
// $model->hash = $env->passwordHash;
$migrate = new Migration($env->tablePath,$model);
$migrate->cli();

// function model($tableName) {
//     global $model;
//     return $model->model($tableName);
// }

function model($tableName) {
    global $env;
    $model = new Model($env->con);
    return $model->table($tableName);
}


function validate($obj,$data) {
    global $model;
    // global $env;
    $validate = new Validate($obj,$data,$model);
    // $validate->hash = $env->passwordHash;
    return $validate->run();

}


function pre($var) {
    echo '<pre>';
    print_r($var);
    echo '</pre>';
}

function out($var) {
    $P = new jPretty($var);
    echo '
    <div style="max-width:90vw; background-color:#F5F5F5; padding:1rem">
    <div style="font-weight:600; font-size:1.2rem; margin-bottom:.5rem;">data: </div>
    <div style="margin-left:.5rem;">'.$P->run().'</div>
    </div>
    ';
}

function redirect($location) {
    $location = $_REQUEST['HTTP_HOST'].$location;
    header("Location: $location");
    exit();
}

// function out($var){
//     $string = json_encode($var);
//     $string = str_replace('"','\\"',$string);
//     echo shell_exec('node ..\mw\jpretty '.$string);
// }

// function mount($path,$variables = []) {
//     global $env;
//     $mount = new Mount($env->mountPath);
//     return $mount->mount($path,$variables);
// }
