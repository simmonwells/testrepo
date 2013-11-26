<?php
namespace Base;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 class Application
 {
     
    public static function createWebApplication()
    {
        return self::createApplication('WebApplication');
    }
    
    public static function createApplication($class)
    {
	return new $class();
    }
    
 }
