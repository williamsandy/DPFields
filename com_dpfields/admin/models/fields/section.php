<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2016 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

JFormHelper::loadFieldClass('list');
JLoader::import('joomla.filesystem.folder');

class JFormFieldSection extends JFormFieldList
{

	public $type = 'Section';

	protected function getOptions ()
	{
		$options = parent::getOptions();

		foreach (JHtmlSidebar::getEntries() as $entry)
		{
			if (strpos($entry[1], 'com_categories') === false)
			{
				continue;
			}

			$uri = JUri::getInstance($entry[1]);
			$extension = $uri->getVar('extension');

			if ($extension)
			{
				$options[] = JHtml::_('select.option', $extension . '.category', JText::_('JCategory'));
			}
		}

		return $options;
	}

	public function setup (SimpleXMLElement $element, $value, $group = null)
	{
		$return = parent::setup($element, $value, $group);

		// On change must always be the change context function
		$this->onchange = 'dpFieldsChangeContext(jQuery(this).val());';

		return $return;
	}

	protected function getInput ()
	{
		// Add the change context function to the document
		JFactory::getDocument()->addScriptDeclaration(
				"function dpFieldsChangeContext(context)
{
	var regex = new RegExp(\"([?;&])context[^&;]*[;&]?\");
	var url = window.location.href;
    var query = url.replace(regex, \"$1\").replace(/&$/, '');

    window.location.href = (query.length > 2 ? query + \"&\" : \"?\") + (context ? \"context=\" + context : '');
}");

		return parent::getInput();
	}
}
