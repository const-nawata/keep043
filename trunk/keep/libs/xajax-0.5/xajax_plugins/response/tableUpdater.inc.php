<?php
/*
	File: tableUpdater.inc.php

	Contains a class that can be used to invoke DOM calls on the browser which
	will create or update an HTML table.

	Title: clsTableUpdater class

	Please see <copyright.inc.php> for a detailed description, copyright
	and license information.
*/

if (false == class_exists('xajaxPlugin') || false == class_exists('xajaxPluginManager'))
{
	$sBaseFolder = dirname(dirname(dirname(__FILE__)));
	$sXajaxCore = $sBaseFolder . '/xajax_core';

	if (false == class_exists('xajaxPlugin'))
		require $sXajaxCore . '/xajaxPlugin.inc.php';
	if (false == class_exists('xajaxPluginManager'))
		require $sXajaxCore . '/xajaxPluginManager.inc.php';
}

/*
	Class: clsTableUpdater
*/
class clsTableUpdater extends xajaxResponsePlugin
{
	/*
		String: sDefer

		Used to store the state of the scriptDeferral configuration setting.  When
		script deferral is desired, this member contains 'defer' which will request
		that the browser defer loading of the javascript until the rest of the page
		has been loaded.
	*/
	var $sDefer;

	/*
		String: sJavascriptURI

		Used to store the base URI for where the javascript files are located.  This
		enables the plugin to generate a script reference to it's javascript file
		if the javascript code is NOT inlined.
	*/
	var $sJavascriptURI;

	/*
		Boolean: bInlineScript

		Used to store the value of the inlineScript configuration option.  When true,
		the plugin will return it's javascript code as part of the javascript header
		for the page, else, it will generate a script tag referencing the file by
		using the <clsTableUpdater->sJavascriptURI>.
	*/
	var $bInlineScript;

	/*
		Function: clsTableUpdater

		Constructs and initializes an instance of the table updater class.
	*/
	function clsTableUpdater()
	{
		$this->sDefer = '';
		$this->sJavascriptURI = '';
		$this->bInlineScript = true;
	}

	/*
		Function: configure

		Receives configuration settings set by <xajax> or user script calls to
		<xajax->configure>.

		sName - (string):  The name of the configuration option being set.
		mValue - (mixed):  The value being associated with the configuration option.
	*/
	function configure($sName, $mValue)
	{
		if ('scriptDeferral' == $sName) {
			if (true === $mValue || false === $mValue) {
				if ($mValue) $this->sDefer = 'defer ';
				else $this->sDefer = '';
			}
		} else if ('javascript URI' == $sName) {
			$this->sJavascriptURI = $mValue;
		} else if ('inlineScript' == $sName) {
				if (true === $mValue || false === $mValue)
					$this->bInlineScript = $mValue;
			}
	}

	/*
		Function: generateClientScript

		Called by the <xajaxPluginManager> during the script generation phase.  This
		will either inline the script or insert a script tag which references the
		<tableUpdater.js> file based on the value of the <clsTableUpdater->bInlineScript>
		configuration option.
	*/
	function generateClientScript()
	{
		if ($this->bInlineScript)
		{
			echo "\n<script type='text/javascript' " . $this->sDefer . "charset='UTF-8'>\n";
			echo "/* <![CDATA[ */\n";

			include(dirname(__FILE__) . '/tableUpdater.js');

			echo "/* ]]> */\n";
			echo "</script>\n";
		} else {
			echo "\n<script type='text/javascript' src='" . $this->sJavascriptURI . "tableUpdater.js' " . $this->sDefer . "charset='UTF-8'>\n";
		}
	}

	function getName()
	{
		return get_class($this);
	}

	// tables
	function appendTable($table, $parent) {
		$command = array(
				'cmd'=>'et_at',
				'id'=>$parent
				);
		$this->addCommand($command, $table);
	}
	function insertTable($table, $parent, $position) {
		$command = array(
				'cmd'=>'et_it',
				'id'=>$parent,
				'pos'=>$position
				);
		$this->addCommand($command, $table);
	}
	function deleteTable($table) {
		$this->addCommand(
				array(
					'cmd'=>'et_dt'
					),
				$table
				);
	}
	// rows
	function appendRow($row, $parent, $position = null) {
		$command = array(
				'cmd'=>'et_ar',
				'id'=>$parent
				);
		if (null != $position)
			$command['pos'] = $position;
		$this->addCommand($command, $row);
	}
	function insertRow($row, $parent, $position = null, $before = null) {
		$command = array(
				'cmd'=>'et_ir',
				'id'=>$parent
				);
		if (null != $position)
			$command['pos'] = $position;
		if (null != $before)
			$command['type'] = $before;
		$this->addCommand($command, $row);
	}
	function replaceRow($row, $parent, $position = null, $before = null) {
		$command = array(
				'cmd'=>'et_rr',
				'id'=>$parent
				);
		if (null != $position)
			$command['pos'] = $position;
		if (null != $before)
			$command['type'] = $before;
		$this->addCommand($command, $row);
	}
	function deleteRow($parent, $position = null) {
		$command = array(
				'cmd'=>'et_dr',
				'id'=>$parent
				);
		if (null != $position)
			$command['pos'] = $position;
		$this->addCommand($command, null);
	}
	function assignRow($values, $parent, $position = null, $start_column = null) {
		$command = array(
				'cmd'=>'et_asr',
				'id'=>$parent
				);
		if (null != $position)
			$command['pos'] = $position;
		if (null != $start_column)
			$command['type'] = $start_column;
		$this->addCommand($command, $values);
	}
	function assignRowProperty($property, $value, $parent, $position = null) {
		$command = array(
				'cmd'=>'et_asrp',
				'id'=>$parent,
				'prop'=>$property
				);
		if (null != $position)
			$command['pos'] = $position;
		$this->addCommand($command, $value);
	}
	// columns
	function appendColumn($column, $parent, $position = null) {
		$command = array(
				'cmd'=>'et_acol',
				'id'=>$parent
				);
		if (null != $position)
			$command['pos'] = $position;
		$this->addCommand($command, $column);
	}
	function insertColumn($column, $parent, $position = null) {
		$command = array(
				'cmd'=>'et_icol',
				'id'=>$parent
				);
		if (null != $position)
			$command['pos'] = $position;
		$this->addCommand($command, $column);
	}
	function replaceColumn($column, $parent, $position = null) {
		$command = array(
				'cmd'=>'et_rcol',
				'id'=>$parent
				);
		if (null != $position)
			$command['pos'] = $position;
		$this->addCommand($command, $column);
	}
	function deleteColumn($parent, $position = null) {
		$command = array(
				'cmd'=>'et_dcol',
				'id'=>$parent
				);
		if (null != $position)
			$command['pos'] = $position;
		$this->addCommand($command, null);
	}
	function assignColumn($values, $parent, $position = null, $start_row = null) {
		$command = array(
				'cmd'=>'et_ascol',
				'id'=>$parent
				);
		if (null != $position)
			$command['pos'] = $position;
		if (null != $start_row)
			$command['type'] = $start_row;
		$this->addCommand($command, $values);
	}
	function assignColumnProperty($property, $value, $parent, $position = null) {
		$command = array(
				'cmd'=>'et_ascolp',
				'id'=>$parent,
				'prop'=>$property
				);
		if (null != $position)
			$command['pos'] = $position;
		$this->addCommand($command, $value);
	}
	function assignCell($row, $column, $value) {
		$this->addCommand(
				array(
					'cmd'=>'et_asc',
					'id'=>$row,
					'pos'=>$column
					),
				$value
				);
	}
	function assignCellProperty($row, $column, $property, $value) {
		$this->addCommand(
				array(
					'cmd'=>'et_ascp',
					'id'=>$row,
					'pos'=>$column,
					'prop'=>$property
					),
				$value
				);
	}
}

$objPluginManager = xajaxPluginManager::getInstance();
$objPluginManager->registerPlugin(new clsTableUpdater());
