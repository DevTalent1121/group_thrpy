<?php 
  
 /**
 * @package Form Creator
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
defined('_JEXEC') or die('Restricted access');
class JElementTheme extends JElement
{
	var	$_name = 'Theme';
	function fetchElement($name, $value, &$node, $control_name)
	{
		$db			=& JFactory::getDBO();
		$fieldName	= $control_name.'['.$name.']';
		$query = 'SELECT id, title FROM #__player_theme';
		$db->setQuery( $query );
		$array2 = $db->loadObjectList();
		if($db->getErrorNum()){
			echo $db->stderr();
			return false;
		}
		
		$query = 'SELECT id FROM #__player_theme WHERE `default`=1 LIMIT 1';
		$db->setQuery( $query );
		$def = $db->loadResult();
		if($db->getErrorNum()){
			echo $db->stderr();
			return false;
		}
		
		$query = 'SELECT id FROM #__player_theme WHERE id='.$value.' LIMIT 1';
		$db->setQuery( $query );
		$is = $db->loadResult();
		if($db->getErrorNum()){
			echo $db->stderr();
			return false;
		}
		
		if(!$is)
			$value=$def;
		
		$array1[]=JHTML::_('select.option', $id = '-1', $title= JText::_( 'Select Theme' ), 'id', 'title', $disable=true );
		$rows = array_merge($array1, $array2);
		//JHTML::_('select.genericlist',  Array('id'=>-1,'title'=>'Select Theme'), $fieldName,'', 'id', 'title', $value);
		return  JHTML::_('select.genericlist', $rows, $fieldName,'', 'id', 'title', $value);
		
		//return JHTML::_('list.users', $control_name.'['.$name.']', $value);
	}
}
?>