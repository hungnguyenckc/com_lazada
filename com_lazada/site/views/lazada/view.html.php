<?php
//No direct access to this file
defined('_JEXEC') or die('Restricted access');

class LazadaViewLazada extends JViewLegacy 
{
	function display($tpl = null) 
	{
		//Assign dada to the view
		$this->msg = $this->get('Msg');

		//Check for error
		if(count($errors = $this->get('Errors'))) {
			JLog::add(implode('<br/>', $errors), JLog::WARNING, 'jerror');
			return false;
		}

		//Display the view
		parent::display($tpl);
	}
}