<?php
namespace Also;

function validate($obj,$data) {
    global $model;
    $validate = new Validate($obj,$data,$model);
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
    if($location == 'back') $location = $_SERVER['HTTP_REFERER'];
    // else $location = $_REQUEST['HTTP_HOST'].$location;
    header("Location: $location");
    exit();
}
