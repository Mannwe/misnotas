<?php

include_once 'app/config.inc.php';

/* El documento index.php nos servirá como router para mantener
   una url limpia */

// Array de un único elemento ('path') con la url actual
$urlComponents = parse_url($_SERVER['REQUEST_URI']);

//echo $_SERVER['REQUEST_URI'];

$currentPath = $urlComponents['path']; 
$pathArray = explode('/', $currentPath); 
$pathArray = array_filter($pathArray); // Los índices a elementos en blanco apuntan a null (quedan como no definidos)
$pathArray = array_slice($pathArray, 0); // Elimina los índices vacíos

// Nos guardamos la ruta del nodo actual en la variable $currentNodePath
$currentNodePath = '';
for($i = 3; $i < count($pathArray); $i++){
    if($currentNodePath == ''){
        $currentNodePath .= $pathArray[$i];
    }else{
        $currentNodePath .= '/' . $pathArray[$i];
    }
}   

/*echo $_SERVER['REQUEST_URI'] . '<br>';
print_r($pathArray);*/

if (count($pathArray) == 1){
    $url = 'views/login.php';
}else if (count($pathArray) == 2){
    switch($pathArray[1]){
        case 'login':
            $url = 'views/login.php';
            break;
        case 'logout':
            $url = 'views/logout.php';
            break;
        case 'signup':
            $url = 'views/signup.php';
            break;
        case 'main':
            $url = 'views/main.php';
            break;
        case 'newNode':
            $url = 'app/newNode.inc.php';
            break;
        case 'nodeDeletion':
            $url = 'app/nodeDeletion.php';
            break;
        case 'noteDeletion':
            $url = 'app/noteDeletion.php';
            break;
        case 'editNode':
            $url = 'app/editNode.php';
            break;
        case 'errorsPage':
            $url = 'views/errorsPage.php';
            break;
    }
}else if(count($pathArray) >= 3){ // Para páginas con el nombre de usuario en la url
    if($pathArray[1] == 'main'){
        $url = 'views/main.php';
    }else if($pathArray[1] == 'errorsPage'){
        $url = 'views/errorsPage.php';
    }
}

if(isset($url)){
    include_once $url;
}


  