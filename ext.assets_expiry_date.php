<?php if ( ! defined('APP_VER')) exit('No direct script access allowed');


/**
 * Assets Expiry Date
 * 
 * @author    Brandon Kelly <brandon@pixelandtonic.com>
 * @copyright Copyright (c) 2011 Pixel & Tonic, Inc
 * @license   http://creativecommons.org/licenses/by-sa/3.0/ Attribution-Share Alike 3.0 Unported
 */

class Assets_expiry_date_ext {

	var $name           = 'Assets Expiry Date';
	var $version        = '1.0';
	var $description    = 'Adds an “Expiry Date” field to Assets metadata';
	var $settings_exist = 'n';
	var $docs_url       = 'http://github.com/brandonkelly/assets_expiry_date';

	/**
	 * Constructor
	 */
	function __construct()
	{
		// Make a local reference to the ExpressionEngine super object
		$this->EE =& get_instance();
	}

	// --------------------------------------------------------------------

	/**
	 * Activate Extension
	 */
	function activate_extension()
	{
		// -------------------------------------------
		//  Add the row to exp_extensions
		// -------------------------------------------

		$this->EE->db->insert('extensions', array(
			'class'    => get_class($this),
			'method'   => 'assets_file_meta_add_row',
			'hook'     => 'assets_file_meta_add_row',
			'settings' => '',
			'priority' => 10,
			'version'  => $this->version,
			'enabled'  => 'y'
		));

		// -------------------------------------------
		//  Add the column to exp_assets if it's not already there
		// -------------------------------------------

		if ($this->EE->db->table_exists('assets') && ! $this->EE->db->field_exists('expiry_date', 'assets'))
		{
			$this->EE->load->dbforge();

			$this->EE->dbforge->add_column('assets', array(
				'expiry_date' => array('type' => 'int', 'constraint' => 10, 'unsigned' => TRUE),
			));
		}
	}

	/**
	 * Update Extension
	 */
	function update_extension($current = '')
	{
		// Nothing to change...
		return FALSE;
	}

	/**
	 * Disable Extension
	 */
	function disable_extension()
	{
		// -------------------------------------------
		//  Remove the row from exp_extensions
		// -------------------------------------------

		$this->EE->db->where('class', get_class($this))
		             ->delete('extensions');
	}

	// --------------------------------------------------------------------

	/**
	 * assets_file_meta_add_row hook
	 */
	function assets_file_meta_add_row($file)
	{
		$r = $this->EE->extensions->last_call !== FALSE ? $this->EE->extensions->last_call : '';

		//if ($file->kind() == 'image')
		//{
			$this->EE->lang->loadfile('assets_expiry_date');

			$r .= '<tr>'
			    .   '<th scope="row">'.lang('expiry_date').'</th>'
			    .   '<td><input name="expiry_date" type="text" data-type="date"';

			if ($file->row('expiry_date'))
			{
				$default_date = $this->EE->localize->set_localized_time($file->row('expiry_date')) * 1000;
				$value = $this->EE->localize->set_human_time($file->row('date'));

				$r .= ' data-default-date="'.$default_date.'" value="'.$value.'"';
			}

			$r .= ' /></td></tr>';
		//}

		return $r;
	}
}

// End of file ext.wygwam_structure_pages.php */
// Location: ./system/expressionengine/third_party/wygwam_structure_pages/ext.wygwam_structure_pages.php
