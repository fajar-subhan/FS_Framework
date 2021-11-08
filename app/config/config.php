<?php 
/**
 * Require for helper files
 * 
 */
require_once './app/helpers/My_Helper.php';

/**
 * This route indicates which controller class should be loaded if the
 * 
 */
if(!defined('CONTROLLER')) define('CONTROLLER','welcome');

/**
 * Define base url 
 * 
 * Example https://www.example.com/ 
 */
if(!defined('BASE_URL')) define('BASE_URL','Hostname or path address');

/**
 * Default time zone Asia/Jakarta 
 * 
 */
date_default_timezone_set('Asia/Jakarta');