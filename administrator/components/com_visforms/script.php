<?php

/**
 * @version		$Id: script.php 22354 2011-11-07 05:01:16Z github_bot $
 * @package		com_visforms
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die( 'Restricted access' );

class com_visformsInstallerScript
{
    /*
     * Version that will be installed by this installer run
     * 
     * @var string
     */
    private $release;
    
    /*
     * Version that was installed before this installer run
     * 
     * @var string
     */
    private $oldRelease;
    
    /*
     * Minimum Joomla! release Version for this installation
     * 
     * @var string
     */
    private $minimum_joomla_release;
   
     /**
   * Object with instalation status information
   *
   * @var object
   */
   private $status;
    
    /**
	 * Constructor
	 *
	 * @param   JAdapterInstance  $adapter  The object responsable for running this script
	 */
   public function __construct(JAdapterInstance $adapter) 
    {
        $this->status = new stdClass();
        $this->status->fixTableVisforms = array();
        $this->status->modules = array();
        $this->status->plugins = array();
		$this->status->tables = array();
        $this->status->folders = array();
        $this->status->component = array();
		$this->release = $adapter->get( "manifest" )->version; 
		$this->minimum_joomla_release = $adapter->get( "manifest" )->attributes()->version;
        $this->oldRelease = "";
   }

	/**
	 * Called before any type of action
	 *
	 * @param   string  $route  Which action is happening (install|uninstall|discover_install)
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
    public function preflight($route, JAdapterInstance $adapter) {
		$jversion = new JVersion();
        $msg = "";

		// abort if the current Joomla release is to old
		if( version_compare( $jversion->getShortVersion(), $this->minimum_joomla_release, 'lt' ) ) {
			Jerror::raiseWarning(null, JText::_('COM_VISFORMS_WRONG_JOOMLA_VERSION') .$this->minimum_joomla_release);
			return false;
		}

		// abort if the component being installed is not newer than the currently installed version
		if ( $route == 'update' ) 
		{
			@chmod(JPath::clean(JPATH_ROOT . '/media/com_visforms/css/visforms.css'), 0644);
			$this->oldRelease = $this->getParam('version');
			$rel = $this->oldRelease . JText::_('COM_VISFORMS_TO') . $this->release;
			if ( version_compare( $this->release, $this->oldRelease, 'le' ) ) {
				Jerror::raiseWarning(null, JText::_('COM_VISFORMS_WRONG_VERSION') . $rel);
				return false;
			}
            else
            {
                //process preflight for specific versions
                if(version_compare($this->oldRelease, '2.0.0', 'lt'))
                {
                    Jerror::raiseWarning(null, JText::_('COM_VISFORMS_INCOPATIBLE_VERSION') . $rel);
                }
			
		if (((version_compare($this->oldRelease, '3.0.0', 'ge')) && (version_compare($this->oldRelease, '3.1.0', 'lt'))) || (version_compare($this->oldRelease, '2.1.0', 'lt')))
                {
                    $this->preFlightForVersion3_1_0();
                }
            }
		}
        else 
		{ 
			$rel = $this->release; 
		}
		//create installation success message (only display if complete installation is executed successfully)
		if ($route == 'update') 
        {
			$msg =  JText::_('COM_VISFORMS_UPDATE_VERSION') . $rel . JText::_('COM_VISFORMS_SUCESSFULL');
		}
		else if ($route == 'install') {
			$msg = JText::_('COM_VISFORMS_INSTALL_VERSION') . $rel . JText::_('COM_VISFORMS_SUCESSFULL');
		}

        $this->status->component = array('name' => 'visForms', 'type' => $route, 'msg' => $msg);
	}

	/**
	 * Called after any type of action
	 *
	 * @param   string  $route  Which action is happening (install|uninstall|discover_install)
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
    public function postflight($route, JAdapterInstance $adapter) 
    {
        if($route == 'update')
        {
            //run specific component adaptation for specific update versions
			if (((version_compare($this->oldRelease, '3.0.0', 'ge')) && (version_compare($this->oldRelease, '3.1.0', 'lt'))) || (version_compare($this->oldRelease, '2.1.0', 'lt')))
            {
                $this->postFlightForVersion3_1_0();
            }  
        }
        
        if($route == 'install')
        {
            $this->createFolder(array('images', 'visforms'));
        }
        
        //Install or update all extensions that come with component visForms
        $this->installExtensions($route, $adapter);
	}

	/**
	 * Called on installation
	 *
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
    public function install(JAdapterInstance $adapter) 
	{
		//JFactory::getApplication()->enqueueMessage(JText::_('COM_VISFORMS_INSTALL_MESSAGE'));
	}

	/**
	 * Called on update
	 *
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	//public function update(JAdapterInstance $adapter);

	/**
	 * Called on uninstallation
	 *
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 */
	public function uninstall(JAdapterInstance $adapter)
	{

		$db = JFactory::getDbo();
		$app = JFactory::getApplication();
		$tablesAllowed = $db->getTableList(); 	

		if ($db) 
		{
            //delete all visforms related tables in database
			$db->setQuery("SELECT * FROM #__visforms");
			$forms = $db->loadObjectList();
	
			$n=count($forms);
			for ($i=0; $i < $n; $i++)
			{
				$row = $forms[$i];
				$tnfull = $db->getPrefix() . "visforms_".$row->id;
				if (in_array($tnfull, $tablesAllowed)) {
                    $tn = "#__visforms_".$row->id;
                    $db->setQuery("drop table if exists ".$tn);
                    try
                    {
                        $db->execute();
                        $this->status->tables[] = array('message' => JText::sprintf('COM_VISFORMS_DATA_TABLE_DROPPED', $row->id));
                    }
                    catch (RuntimeException $e)
                    {
                        $this->status->tables[] = array('message' => JText::sprintf('DB function failed with error <br /><span style="_QQ_"color: red;"_QQ_">%s</span>', $this->setError($e->getMessage())));
                    }
                }
			}

			$db->setQuery("drop table if exists #__visfields");
			try
            {
                $db->execute();
                $this->status->tables[] = array('message' => JText::_('COM_VISFORMS_FIELD_TABLE_DROPPED'));
            }
            catch (RuntimeException $e)
            {
                $this->status->tables[] = array('message' => JText::sprintf('DB function failed with error <br /><span style="_QQ_"color: red;"_QQ_">%s</span>', $this->setError($e->getMessage())));
            }			
			
			$db->setQuery("drop table if exists #__visforms");
			try
            {
                $db->execute();
                $this->status->tables[] = array('message' => JText::_('COM_VISFORMS_FORMS_TABLE_DROPPED'));
            }
            catch (RuntimeException $e)
            {
                $this->status->tables[] = array('message' => JText::sprintf('DB function failed with error <br /><span style="_QQ_"color: red;"_QQ_">%s</span>', $this->setError($e->getMessage())));
            }
		}
		
        //uninstall plugins
        $manifest = $adapter->getParent()->manifest;
        $plugins = $manifest->xpath('plugins/plugin');
        foreach ($plugins as $plugin)
        {
            $name = (string)$plugin->attributes()->plugin;
            $group = (string)$plugin->attributes()->group;
            $plgWhere = $db->quoteName('type') . ' = ' . $db->quote('plugin') . ' AND ' . $db->quoteName('element') . ' = ' . $db->quote($name) . ' AND ' . $db->quoteName('folder') . ' = ' . $db->quote($group);
            $query = $db->getQuery(true);
            $query
                ->select($db->quoteName('extension_id'))
                ->from($db->quoteName('#__extensions'))
                ->where($plgWhere);
            $db->setQuery($query);
            $extensions = $db->loadColumn();
            if (count($extensions))
            {
                foreach ($extensions as $id)
                {
                    $installer = new JInstaller;
                    $result = $installer->uninstall('plugin', $id);
                }
                $this->status->plugins[] = array('name' => $name, 'group' => $group, 'result' => $result);
            }
            
        }
        //uninstall modules
        $modules = $manifest->xpath('modules/module');
        foreach ($modules as $module)
        {
            $name = (string)$module->attributes()->module;
            $client = (string)$module->attributes()->client;
            if (is_null($client))
            {
                $client = 'site';
            }
            if($client == 'site')
            {
                $client_id = 0;
            }
            else
            {
                $client_id = 1;
            }
            $db = JFactory::getDbo();
            $modWhere = $db->quoteName('type') . ' = ' . $db->quote('module') . ' AND ' . $db->quoteName('element') . ' = ' . $db->quote($name) . ' AND ' . $db->quoteName('client_id') . ' = ' . $db->quote($client_id);
            $query = $db->getQuery(true);
            $query
                ->select($db->quoteName('extension_id'))
                ->from($db->quoteName('#__extensions'))
                ->where($modWhere);
            $db->setQuery($query);
            $extensions = $db->loadColumn();
            if (count($extensions))
            {
                foreach ($extensions as $id)
                {
                    $installer = new JInstaller;
                    $result = $installer->uninstall('module', $id);
                }
                $this->status->modules[] = array('name' => $name, 'client' => $client, 'result' => $result);
            }
            
        }
        
        //delete folders in image folder
        jimport('joomla.filesystem.file');      
        $folder  = JPATH_ROOT.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'visforms';
        if(JFolder::exists($folder))
        {
            $result = array();
            $result[]     = JFolder::delete($folder);
            $this->status->folders[] = array('folder' => $folder, 'result' => $result[0]);  
        }
        
        //delete visuploads folder
        $folder  = JPATH_ROOT.DIRECTORY_SEPARATOR.'visuploads';
        if(JFolder::exists($folder))
        {
            $result = array();
            $result[]     = JFolder::delete($folder);
            $this->status->folders[] = array('folder' => $folder, 'result' => $result[0]);  
        }
        
        $this->uninstallationResults();
	}
    
    /*
     * Method to show installation results in a nicely formatted html table
     * HTML is captured by content buffer in adapter install function
     * 
     * @return void
     */
	
	 private function installationResults()
    {
        $language = JFactory::getLanguage();
        $language->load('com_visforms');
        $rows = 0; ?>
		<h2 style="text-align: center;"><?php echo JText::_('COM_VISFORMS_INSTALL_MESSAGE'); ?></h2>
        <img src="<?php echo JURI::base(); ?>/components/com_visforms/images/logo-banner.png" alt="visForms" align="right" />
        <h2><?php echo JText::_('COM_VISFORMS_INSTALLATION_STATUS'); ?></h2>
        <table class="adminlist table table-striped">
            <thead>
                <tr>
                    <th class="title" colspan="2" style="text-align: left;"><?php echo JText::_('COM_VISFORMS_EXTENSION'); ?></th>
                    <th width="30%" style="text-align: left;"><?php echo JText::_('COM_VISFORMS_STATUS'); ?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
            <tbody>
                <tr class="row0">
                    <td class="key" colspan="2"><?php echo JText::_('COM_VISFORMS_COMPONENT'); ?></td>
                    <td><strong><?php echo $this->status->component['msg']; ?></strong></td>
                </tr>
                <?php if (count($this->status->modules)): ?>
                <tr>
                    <th><?php echo JText::_('COM_VISFORMS_MODULE'); ?></th>
                    <th><?php echo JText::_('COM_VISFORMS_CLIENT'); ?></th>
                    <th></th>
                </tr>
                <?php foreach ($this->status->modules as $module): ?>
                <tr class="row<?php echo(++$rows % 2); ?>">
                    <td class="key"><?php echo $module['name']; ?></td>
                    <td class="key"><?php echo ucfirst($module['client']); ?></td>
                    <td><?php echo ($module['result']) ? (($module['type'] == 'install') ? '<strong>' . JText::_('COM_VISFORMS_INSTALLED') : '<strong>' . JText::_('COM_VISFORMS_UPDATED')) : (($module['type'] == 'install') ? '<strong style="color: red">' . JText::_('COM_VISFORMS_NOT_INSTALLED') : '<strong style="color: red">' . JText::_('COM_VISFORMS_NOT_UPDATED')); ?></strong></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
                <?php if (count($this->status->plugins)): ?>
                <tr>
                    <th><?php echo JText::_('COM_VISFORMS_PLUGIN'); ?></th>
                    <th><?php echo JText::_('COM_VISFORMS_GROUP'); ?></th>
                    <th></th>
                </tr>
                <?php foreach ($this->status->plugins as $plugin): ?>
                <tr class="row<?php echo(++$rows % 2); ?>">
                    <td class="key"><?php echo ucfirst($plugin['name']); ?></td>
                    <td class="key"><?php echo ucfirst($plugin['group']); ?></td>
                    <td><?php echo ($plugin['result']) ? (($plugin['type'] == 'install') ? '<strong>' . JText::_('COM_VISFORMS_INSTALLED') : '<strong>' . JText::_('COM_VISFORMS_UPDATED')) : (($plugin['type'] == 'install') ? '<strong style="color: red">' . JText::_('COM_VISFORMS_NOT_INSTALLED') : '<strong style="color: red">' . JText::_('COM_VISFORMS_NOT_UPDATED')); ?></strong></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
                 <?php if (count($this->status->folders)): ?>
                <tr>
                    <th colspan="2"><?php echo JText::_('COM_VISFORMS_FILESYSTEM'); ?></th>
                    <th></th>
                </tr>
                <?php foreach ($this->status->folders as $folder): ?>
                <tr class="row<?php echo(++$rows % 2); ?>">
                    <td class="key" colspan="2"><?php echo ucfirst($folder['folder']); ?></td>
                    <td><?php echo ($folder['result']) ? '<strong>' . JText::_('COM_VISFORMS_CREATED') :  '<strong style="color: red">' . JText::_('COM_VISFORMS_NOT_CREATED'); ?></strong></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
                <?php if (count($this->status->fixTableVisforms)): ?>
                <tr>
                    <th colspan="2"><?php echo JText::_('COM_VISFORMS_UPDATE_FIX_FOR_FORM_DATA'); ?></th>
                    <th></th>
                </tr>
                <?php foreach ($this->status->fixTableVisforms as $recordset): ?>
                <tr class="row<?php echo(++$rows % 2); ?>">
                    <td class="key" colspan="2"><?php echo JText::_('COM_VISFORMS_FORM_WITH_ID') . $recordset['form']; ?></td>
                    <td><?php echo ($recordset['result']) ? '<strong>' . $recordset['resulttext'] :  '<strong style="color: red">' . $recordset['resulttext']; ?></strong></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    <?php
    }
	
	private function uninstallationResults()
    {
    $language = JFactory::getLanguage();
    $language->load('com_visforms');
    $rows = 0;
 ?>
        <h2><?php echo JText::_('COM_VISFORMS_REMOVAL_STATUS'); ?></h2>
        <table class="adminlist table table-striped">
            <thead>
                <tr>
                    <th class="title" colspan="2" style="text-align: left;"><?php echo JText::_('COM_VISFORMS_EXTENSION'); ?></th>
                    <th width="30%" style="text-align: left;"><?php echo JText::_('COM_VISFORMS_STATUS'); ?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
            <tbody>
                <tr class="row0">
                    <td class="key" colspan="2"><?php echo JText::_('COM_VISFORMS_COMPONENT'); ?></td>
                    <td><strong><?php echo JText::_('COM_VISFORMS_REMOVED'); ?></strong></td>
                </tr>
                <?php if (count($this->status->modules)): ?>
                <tr>
                    <th><?php echo JText::_('COM_VISFORMS_MODULE'); ?></th>
                    <th><?php echo JText::_('COM_VISFORMS_CLIENT'); ?></th>
                    <th></th>
                </tr>
                <?php foreach ($this->status->modules as $module): ?>
                <tr class="row<?php echo(++$rows % 2); ?>">
                    <td class="key"><?php echo $module['name']; ?></td>
                    <td class="key"><?php echo ucfirst($module['client']); ?></td>
                    <td><?php echo ($module['result']) ? '<strong>' . JText::_('COM_VISFORMS_REMOVED') : '<strong style="color: red">' . JText::_('COM_VISFORMS_NOT_REMOVED'); ?></strong></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
        
                <?php if (count($this->status->plugins)): ?>
                <tr>
                    <th><?php echo JText::_('COM_VISFORMS_PLUGIN'); ?></th>
                    <th><?php echo JText::_('COM_VISFORMS_GROUP'); ?></th>
                    <th></th>
                </tr>
                <?php foreach ($this->status->plugins as $plugin): ?>
                <tr class="row<?php echo(++$rows % 2); ?>">
                    <td class="key"><?php echo ucfirst($plugin['name']); ?></td>
                    <td class="key"><?php echo ucfirst($plugin['group']); ?></td>
                    <td><?php echo ($plugin['result']) ? '<strong>'. JText::_('COM_VISFORMS_REMOVED') : '<strong style="color: red">' . JText::_('COM_VISFORMS_NOT_REMOVED'); ?></strong></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
				<?php if (count($this->status->tables)){ ?>
				<tr>
                    <th><?php echo JText::_('COM_VISFORMS_TABLES'); ?></th>
                    <th></th>
                    <th></th>
                </tr>
					<?php foreach ($this->status->tables as $table){ ?>
					<tr class="row<?php echo(++$rows % 2); ?>">
						<td class="key" colspan="3"><?php echo ucfirst($table['message']); ?></td>
					</tr>
					<?php } ?>
				<?php } ?>
                <?php if (count($this->status->folders)): ?>
                <tr>
                    <th colspan="2"><?php echo JText::_('COM_VISFORMS_FILESYSTEM'); ?></th>
                    <th></th>
                </tr>
                <?php foreach ($this->status->folders as $folder): ?>
                <tr class="row<?php echo(++$rows % 2); ?>">
                    <td class="key" colspan="2"><?php echo ucfirst($folder['folder']); ?></td>
                    <td><?php echo ($folder['result']) ? '<strong>' . JText::_('COM_VISFORMS_DELETED') :  '<strong style="color: red">' . JText::_('COM_VISFORMS_NOT_DELETED'); ?></strong></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    <?php
    }
    
    private function createFolder($folders = array())
    {
        //create visforms folder in image directory and copy an index.html into it
        jimport('joomla.filesystem.file');
        $folder  = JPATH_ROOT;//.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'visforms';
        foreach ($folders as $name) 
        {
            $folder .= DIRECTORY_SEPARATOR . $name;
        }
        
        if (($folder != JPATH_ROOT) && !(JFolder::exists($folder)))
        {
            $result = array();
            $result[]     = JFolder::create($folder);
            $this->status->folders[] = array('folder' => $folder, 'result' => $result[0]);
            $src  = JPATH_ROOT.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_visforms'.DIRECTORY_SEPARATOR.'index.html';
            $dest = JPath::clean($folder.DIRECTORY_SEPARATOR.'index.html');

            $result[] = JFile::copy($src, $dest);
            $this->status->folders[] = array('folder' => $folder.DIRECTORY_SEPARATOR.'index.html', 'result' => $result[1]);
        }
    }
    
    public function installExtensions($route, JAdapterInstance $adapter) {
		$db = JFactory::getDbo();
        $src = $adapter->getParent()->getPath('source');
        $manifest = $adapter->getParent()->manifest;
        $plugins = $manifest->xpath('plugins/plugin');
        foreach ($plugins as $plugin)
        {
            $name = (string)$plugin->attributes()->plugin;
            $group = (string)$plugin->attributes()->group;
            $newVersion = (string) $plugin->attributes()->version;
            $version = "";
            $plgWhere = $db->quoteName('type') . ' = ' . $db->quote('plugin') . ' AND ' . $db->quoteName('element') . ' = ' . $db->quote($name) . ' AND ' . $db->quoteName('folder') . ' = ' . $db->quote($group);
            $query = $db->getQuery(true);
            $query
                ->select($db->quoteName('extension_id'))
                ->from($db->quoteName('#__extensions'))
                ->where($plgWhere);
            $db->setQuery($query);
            $extension = $db->loadColumn();
            $path = $src.'/plugins/'.$group;
            if (JFolder::exists($src.'/plugins/'.$group.'/'.$name))
            {
                $path = $src.'/plugins/'.$group.'/'.$name;
            }
            $installer = new JInstaller;
            //Plugin does already exist
			if (count($extension))
			{
                //make sure we have got only on id, if not use the first
                if (is_array($extension))
                {
                    $extension = $extension[0];
                }
                //check if we need to update
				$version = $this->getParam('version', (int) $extension);
                if ( version_compare( $newVersion, $version, 'gt' ))
                {
                    $result = $installer->update($path);
                    $this->status->plugins[] = array('name' => $name, 'group' => $group, 'result' => $result, 'type' => 'update');
                }
			}
			else
			{
                //we install the plugin
				$result = $installer->install($path);
                //and enable it
                $query = 'UPDATE ' . $db->quoteName('#__extensions') . ' SET ' . $db->quoteName('enabled') . ' = 1 WHERE ' . $plgWhere;
                $db->setQuery($query);
                $db->execute();
                $this->status->plugins[] = array('name' => $name, 'group' => $group, 'result' => $result, 'type' => 'install');
            }
        }		
        $modules = $manifest->xpath('modules/module');
        foreach ($modules as $module)
        {
            $name = (string)$module->attributes()->module;
            $client = (string)$module->attributes()->client;
            $newVersion = (string) $module->attributes()->version;
            $version = "";
            if (is_null($client))
            {
                $client = 'site';
            }
            if($client == 'site')
            {
                $client_id = 0;
            }
            else
            {
                $client_id = 1;
            }
            $modWhere = $db->quoteName('type') . ' = ' . $db->quote('module') . ' AND ' . $db->quoteName('element') . ' = ' . $db->quote($name) . ' AND ' . $db->quoteName('client_id') . ' = ' . $db->quote($client_id);
            $query = $db->getQuery(true);
            $query
                ->select($db->quoteName('extension_id'))
                ->from($db->quoteName('#__extensions'))
                ->where($modWhere);
            //$query = "SELECT `extension_id` FROM `#__extensions` WHERE `type`='module' AND element = ".$db->Quote($name) . "AND client_id = " .($client == site) ? $db->Quote(0) : $db->Quote(1);
            $db->setQuery($query);
            $extension = $db->loadColumn();
            ($client == 'administrator') ? $path = $src.'/administrator/modules/'.$name : $path = $src.'/modules/'.$name;
			
            $installer = new JInstaller;
            if (count($extension))
			{
                //make sure we have got only on id, if not use the first
                if (is_array($extension))
                {
                    $extension = $extension[0];
                }
                //check if we need to update
				$version = $this->getParam('version', (int) $extension);
                if ( version_compare( $newVersion, $version, 'gt' ))
                {
                    $result = $installer->update($path);
                    $this->status->modules[] = array('name' => $name, 'client' => $client, 'result' => $result, 'type' => 'update');
                }
			}
			else
			{
				$result = $installer->install($path);
                $this->status->modules[] = array('name' => $name, 'client' => $client, 'result' => $result, 'type' => 'install');
            }

        }
		$this->installationResults();
	}
    
    /*
	 * get a variable from the manifest cache of the version that is to be updated.
     * 
     * @param string name Parametername
     * @param int id    extension id
     * 
     * @return string parameter value
	 */
	private function getParam( $name, $eid = 0) {
		$db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select($db->quoteName('manifest_cache'));
        $query->from($db->quoteName('#__extensions'));
        //check if a extenstion id is given. If yes we want a parameter from this extension
        if($eid != 0)
        {
            $query->where($db->quoteName('extension_id') . ' = ' . $db->quote($eid)); 
        }
        else
        {
            //we want a parameter from component visForms
            $query->where($this->getComponentWhereStatement());
        }
            
		$db->setQuery($query);
		$manifest = json_decode( $db->loadResult(), true );
		return $manifest[ $name ];
	}
    
    /*
	 * sets parameter values in the component's row of the extension table
	 */
	private function setParams($param_array) {
		if ( count($param_array) > 0 ) {
			// read the existing component value(s)
			$db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query
                ->select($db->quoteName('params'))
                ->from($db->quoteName('#__extensions'))
                ->where($this->getComponentWhereStatement());
			$db->setQuery($query);
			$params = json_decode( $db->loadResult(), true );
			// add the new variable(s) to the existing one(s)
			foreach ( $param_array as $name => $value ) {
				$params[ (string) $name ] = (string) $value;
			}
			// store the combined new and existing values back as a JSON string
			$paramsString = json_encode( $params );
			$db->setQuery('UPDATE #__extensions SET params = ' .
				$db->quote( $paramsString ) . ' WHERE ' . $this->getComponentWhereStatement());
				$db->execute();
		}
	}
    
    /*
     * Methode to create where statement to manipulate component dataset in extensions table
     * 
     * @return  string  where statement to select the visForms component dataset in extensions table
     */
    
    private function getComponentWhereStatement()
    {
        $db = JFactory::getDbo();
        $where = $db->quoteName('type') . ' = ' . $db->quote('component') . ' AND ' . $db->quoteName('element') . ' = ' . $db->quote('com_visforms') . ' AND ' . $db->quoteName('name') . ' = ' . $db->quote('visforms');
        return $where;
    }
	
	
    
    private function deleteOldFiles($filesToDelete = array())
    {
       
       jimport( 'joomla.filesystem.file' );
       foreach ($filesToDelete as $fileToDelete)
       {
           $oldfile =Jpath::clean(JPATH_ROOT . $fileToDelete);
            if ( JFile::exists($oldfile) ) 
            {
                JFile::delete($oldfile);
            }
       }

    }
    
    private function preFlightForVersion3_1_0()
    {
        
    }
    
    private function postFlightForVersion3_1_0()
    {
		//Add new fields to table visforms
		$columnsToAdd = array('emailreceiptsettings', 'frontendsettings');
		$db = JFactory::getDbo();
		foreach ($columnsToAdd as $columnToAdd)
		{
			$queryStr = $db->getQuery(true);
			$queryStr = ("ALTER TABLE " . $db->quoteName('#__visforms') . "ADD COLUMN " . $db->quoteName($columnToAdd) . " text");
			$db->setQuery($queryStr);
			$db->execute();
		}
		//delete old files no longer used
	   $filesToDelete = array (
           '/administrator/com_visforms/images/icon-16-visforms.png',
           '/adminstrator/com_visforms/vies/vistools/tmpl/css.php',
           '/components/com_visforms/captcha/images/audio_icon.gif');
       $this->deleteOldFiles($filesToDelete);
	   
        //fix recordsets in visforms table
        $this->fixTableVisforms3_1_0();
		//add new menu params
		$menu_params = array('sortorder' => 'id', 'display_num' => '20');
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
			->select($db->quoteName(array('id', 'link','params')))
			->from($db->quoteName('#__menu'))
			->where($db->quoteName('type') . ' = ' . $db->quote('component'));
		$db->setQuery($query);
		if ($menus = $db->loadObjectList())
		{
			foreach ($menus as $menu)
			{
				if ((isset($menu->link)) && ($menu->link != "") && (strpos($menu->link, "view=visformsdata") !== false))
				{
					$params = json_decode( $menu->params, true );
					// add the new variable(s) to the existing one(s)
					foreach ( $menu_params as $name => $value ) 
					{
						$params[ (string) $name ] = (string) $value;
						// store the combined new and existing values back as a JSON string
						$paramsString = json_encode( $params );
						$db->setQuery('UPDATE #__menu SET params = ' .
						$db->quote( $paramsString ) . ' WHERE ' . $db->quoteName('id') . ' = ' . $db->quote($menu->id));
						$db->execute();
					}
				}
			}
		}
    }
    
    private function fixTableVisforms3_1_0()
    {
       $db = JFactory::getDbo();
       $query = $db->getQuery(true);
	   //Former emailreceipt params into new param field emeilreceiptsettings
       $query
           ->select($db->quoteName(array('id', 'emailreceiptincfield', 'emailreceiptincfile', 'emailrecipientincfilepath')))
           ->from($db->quoteName('#__visforms'));
       $db->setQuery($query);
       $forms = $db->loadObjectList();
       if (count($forms) > 0)
       {
           foreach ($forms as $form)
           {
               $emailreceiptsettings = array();
               if (isset($form->emailreceiptincfield))
               {
                   $emailreceiptsettings['emailreceiptincfield'] = $form->emailreceiptincfield;
               }
               else
               {
                   $emailreceiptsettings['emailreceiptincfield'] = 0;
               }
               if (isset($form->emailreceiptincfile))
               {
                   $emailreceiptsettings['emailreceiptincfile'] = $form->emailreceiptincfile;
               }
               else
               {
                   $emailreceiptsettings['emailreceiptincfile'] = 0;
               }
               if (isset($form->emailrecipientincfilepath))
               {
                   $emailreceiptsettings['emailrecipientincfilepath'] = $form->emailrecipientincfilepath;
               }
               else
               {
                   $emailreceiptsettings['emailrecipientincfilepath'] = 0;
               }
			   $emailreceiptsettings['emailreceiptinccreated'] = 1;
			   $emailreceiptsettings['emailreceiptincformtitle'] = 1;
               if (is_array($emailreceiptsettings))
               {
                   $registry = new JRegistry;
                   $registry->loadArray($emailreceiptsettings);
                   $emailreceiptsettings = (string)$registry;
                   $query = $db->getQuery(true);
                   $query->update($db->quoteName('#__visforms'))
                       ->set($db->quoteName('emailreceiptsettings') . " = " . $db->quote($emailreceiptsettings))
                       ->where($db->quoteName('id') . " = " . $db->quote($form->id));
                   $db->setQuery($query);
                   $result = $db->execute();
                   if (!$result)
                   {
                       //add a message that update went wrong
                   }

               }
           }
		   //drop fields no longer used from table visforms
            $columnsToDelete = array('emailreceiptincfield', 'emailreceiptincfile', 'emailrecipientincfilepath');
            foreach ($columnsToDelete as $columnToDelete)
            {
                $queryStr = ("ALTER TABLE " . $db->quoteName('#__visforms') . "DROP COLUMN " . $db->quoteName($columnToDelete));
                $db->setQuery($queryStr);
                $db->execute();
            }
		}
		   //Former emailreceipt params into new param field emeilreceiptsettings
		   $query = $db->getQuery(true);
       $query
           ->select($db->quoteName(array('id', 'displayip', 'displaydetail', 'autopublish')))
           ->from($db->quoteName('#__visforms'));
       $db->setQuery($query);
       $forms = $db->loadObjectList();
       if (count($forms) > 0)
       {
           foreach ($forms as $form)
           {
               $frontendsettings = array();
               if (isset($form->displayip))
               {
                   $frontendsettings['displayip'] = $form->displayip;
               }
               else
               {
                   $frontendsettings['displayip'] = 0;
               }
               if (isset($form->displaydetail))
               {
                   $frontendsettings['displaydetail'] = $form->displaydetail;
               }
               else
               {
                   $frontendsettings['displaydetail'] = 0;
               }
                if (isset($form->autopublish))
               {
                   $frontendsettings['autopublish'] = $form->autopublish;
               }
               else
               {
                   $frontendsettings['autopublish'] = 1;
               }
			   $frontendsettings['displayid'] = 0;
               if (is_array($frontendsettings))
               {
                   $registry = new JRegistry;
                   $registry->loadArray($frontendsettings);
                   $frontendsettings = (string)$registry;
                   $query = $db->getQuery(true);
                   $query->update($db->quoteName('#__visforms'))
                       ->set($db->quoteName('frontendsettings') . " = " . $db->quote($frontendsettings))
                       ->where($db->quoteName('id') . " = " . $db->quote($form->id));
                   $db->setQuery($query);
                   $result = $db->execute();
                   if (!$result)
                   {
                       //add a message that update went wrong
                   }

               }
           }
           //drop fields no longer used from table visforms
            $columnsToDelete = array('displayip', 'displaydetail', 'autopublish');
            foreach ($columnsToDelete as $columnToDelete)
            {
                $queryStr = ("ALTER TABLE " . $db->quoteName('#__visforms') . "DROP COLUMN " . $db->quoteName($columnToDelete));
                $db->setQuery($queryStr);
                $db->execute();
            }
       }
    }
}

?>
