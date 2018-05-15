<?php
defined('_JEXEC');

abstract class LazadaHelper extends JHelperContent
{
	public static function addSubmenu($submenu) 
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_LAZADA_SUBMENU_LAZADA'),
			'index.php?option=com_lazada',
			$submenu == 'lazada'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_LAZADA_SUBMENU_REDSHOP'),
			'index.php?option=com_lazada&view=product_redshop',
			$submenu == 'product_redshop'
		);

		// Set some global property
		$document = JFactory::getDocument();
		$document->addStyleDeclaration('.icon-48-helloworld ' .
										'{background-image: url(../media/com_lazada/images/tux-48x48.png);}');
		if ($submenu == 'categories') 
		{
			$document->setTitle(JText::_('COM_LAZADA_ADMINISTRATION_CATEGORIES'));
		}
	}

	
}