<?php

class bdImage_Installer
{
	/* Start auto-generated lines of code. Change made will be overwriten... */

	protected static $_tables = array();
	protected static $_patches = array(
		array(
			'table' => 'xf_thread',
			'field' => 'bdimage_image',
			'showTablesQuery' => 'SHOW TABLES LIKE \'xf_thread\'',
			'showColumnsQuery' => 'SHOW COLUMNS FROM `xf_thread` LIKE \'bdimage_image\'',
			'alterTableAddColumnQuery' => 'ALTER TABLE `xf_thread` ADD COLUMN `bdimage_image` TEXT',
			'alterTableDropColumnQuery' => 'ALTER TABLE `xf_thread` DROP COLUMN `bdimage_image`',
		),
		array(
			'table' => 'xf_forum',
			'field' => 'bdimage_last_post_image',
			'showTablesQuery' => 'SHOW TABLES LIKE \'xf_forum\'',
			'showColumnsQuery' => 'SHOW COLUMNS FROM `xf_forum` LIKE \'bdimage_last_post_image\'',
			'alterTableAddColumnQuery' => 'ALTER TABLE `xf_forum` ADD COLUMN `bdimage_last_post_image` TEXT',
			'alterTableDropColumnQuery' => 'ALTER TABLE `xf_forum` DROP COLUMN `bdimage_last_post_image`',
		),
	);

	public static function install($existingAddOn, $addOnData)
	{
		$db = XenForo_Application::get('db');

		foreach (self::$_tables as $table)
		{
			$db->query($table['createQuery']);
		}

		foreach (self::$_patches as $patch)
		{
			$tableExisted = $db->fetchOne($patch['showTablesQuery']);
			if (empty($tableExisted))
			{
				continue;
			}

			$existed = $db->fetchOne($patch['showColumnsQuery']);
			if (empty($existed))
			{
				$db->query($patch['alterTableAddColumnQuery']);
			}
		}

		self::installCustomized($existingAddOn, $addOnData);
	}

	public static function uninstall()
	{
		$db = XenForo_Application::get('db');

		foreach (self::$_patches as $patch)
		{
			$tableExisted = $db->fetchOne($patch['showTablesQuery']);
			if (empty($tableExisted))
			{
				continue;
			}

			$existed = $db->fetchOne($patch['showColumnsQuery']);
			if (!empty($existed))
			{
				$db->query($patch['alterTableDropColumnQuery']);
			}
		}

		foreach (self::$_tables as $table)
		{
			$db->query($table['dropQuery']);
		}

		self::uninstallCustomized();
	}

	/* End auto-generated lines of code. Feel free to make changes below */

	private static function installCustomized($existingAddOn, $addOnData)
	{
		if (XenForo_Application::$versionId < 1020000)
		{
			throw new XenForo_Exception('[bd] Image requires XenForo 1.2.0+');
		}
	}

	private static function uninstallCustomized()
	{
		// customized uninstall script goes here
	}

}
