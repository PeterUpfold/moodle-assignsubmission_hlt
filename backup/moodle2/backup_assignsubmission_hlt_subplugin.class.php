<?php

// This module for Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This module for Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This file contains the version information for the HLT submission plugin.
 *
 * @package assignsubmission_hlt
 * @copyright 2017 Test Valley School {@link https://www.testvalley.hants.sch.uk/}
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Provides the information to backup the HLT state of this assignment
 *
 * @package assignsubmission_hlt
 * @copyright 2017 Test Valley School {@link https://www.testvalley.hants.sch.uk/}
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class backup_assignsubmission_hlt_subplugin extends backup_subplugin {

	/**
	 * Returns the subplugin information to attach to the submission element
	 * @return backup_subplugin_element
	 */
	protected function define_submission_subplugin_structure() {
		$subplugin = $this->get_subplugin_element();

		$subpluginwrapper = new backup_nested_element($this->get_recommended_name());

		$subplugin->add_child($subpluginwrapper);
		$subpluginwrapper->add_child($subpluginelement);

		return $subplugin;
	}

};
