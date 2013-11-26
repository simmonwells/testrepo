<?php
    require_once 'Autoloader.php';
    
    Loader\Autoloader::register();
    $app =  Base\Application::createWebApplication();
    
    try {
        $app->processOutput();
        
        //throw new Base\Exception("dsadasda","dasdasd",404);
    } catch (Base\Exception $e){
        
        echo $app->processError($e);
        exit;
    
    }
