<?php
defined('_JEXEC');

// Set some global property
$document	= JFactory::getDocument();
$document->addStyleDeclaration('.icon-lazada {background-image: url(../media/com_lazada/images/Tux-16x16.png);}');

JLoader::register('LazadaHelper', JPATH_COMPONENT . '/helpers/lazada.php');
$controller = JControllerLegacy::getInstance('Lazada');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();