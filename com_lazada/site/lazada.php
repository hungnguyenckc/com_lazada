<?php 
defined('_JEXEC') or die('Restricted access');

//Get instance of the controller prefixed by  lazada
$controller 	= JcontrollerLegacy::getInstance('lazada');

//Preform the request task
$input 	= JFactory::getApplication()->input;
$controller->execute($input->getCmd('task'));

//Redirect if set by the controller
$controller->redirect();