<?php 
/**
 * Require for helper files
 * 
 */
require_once './app/helpers/My_Helper.php';

/**
 * Define layout name
 * 
 */
if(!defined('LAYOUT')) define('LAYOUT','');

/**
 * This route indicates which controller class should be loaded if the
 * 
 */
if(!defined('CONTROLLER')) define('CONTROLLER','welcome');