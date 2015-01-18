<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Example Content Plugin
 *
 * @package		Joomla.Plugin
 * @subpackage	Content.joomla
 * @since		1.6
 */
class plgContentVisforms extends JPlugin
{

	/**
	 * Delete field when deleting a form and delete field in data table when deleting a field
	 * @param	string	The context for the content passed to the plugin.
	 * @param	object	The data relating to the content that was deleted.
	 * @return	boolean
	 * @since	1.6
	 */
	public function onContentBeforeDelete($context, $data)
	{
		
		// Skip plugin if we are deleting something other than a visforms form or field
		if (($context != 'com_visforms.visfield') && ($context != 'com_visforms.visform')) {
			return true;
        }
        
        //Delete field in data table when deleting a field
        if ($context == 'com_visforms.visfield')
		{
            $fid = $data->fid;
            $id = $data->id;
			$db = JFactory::getDbo();
			$tnfull = $db->getPrefix() . "visforms_".$fid;
			$tablesAllowed = $db->getTableList(); 	

			if (!in_array($tnfull, $tablesAllowed)) {
				//no datatable exists, nothing to do
				return true;
			}
			else
			{
				$tn = "#__visforms_".$fid;
				$tableFields = $db->getTableColumns($tn,false);
				$fieldname = "F" . $id;
				
				if (isset( $tableFields[$fieldname] ))  
				{

					$query = "ALTER TABLE ".$tn." DROP ".$fieldname;
					$db->setQuery($query);
                    try
                    {
                        $db->execute();
                    }
                    catch (RuntimeException $e)
                    {
                        JError::raiseWarning(500, $e->getMessage);
                        return false;
                    }
				return true;
				}
			}
			return true;
        }
        
        //Delete fields in visfields table when deleting a form and datatable if table exists
        if ($context == 'com_visforms.visform')
		{
            $fid = $data->id;
			$db = JFactory::getDbo();
            $query = $db->getQuery(true);
			$query->delete($db->quoteName('#__visfields'))
                ->where($db->quoteName('fid') . " = " . $fid);
		
            $db->setQuery($query);
            try
            {
                $db->execute();
            }
            catch (RuntimeException $e)
            {
                JError::raiseWarning(500, $e->getMessage);
                return false;
            }
            $tablesAllowed = $db->getTableList();
            $tnfull = $db->getPrefix() . "visforms_".$fid;
            if (in_array($tnfull, $tablesAllowed)) {
				//no datatable exists, nothing to do
                $tn = "#__visforms_".$fid;
                $db->setQuery("drop table if exists ".$tn);
                try
                {
                    $db->execute();
                }
                catch (RuntimeException $e)
                {
                    JError::raiseWarning(500, $e->getMessage);
                    return false;
                }
            }
        }
        return true;
	}
}
