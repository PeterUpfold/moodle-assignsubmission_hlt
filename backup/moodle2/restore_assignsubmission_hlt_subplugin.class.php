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
 * Provides the information to restore the HLT state of this assignment
 *
 * @package assignsubmission_hlt
 * @copyright 2017 Test Valley School {@link https://www.testvalley.hants.sch.uk/}
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class restore_assignsubmission_hlt_subplugin extends backup_subplugin {

	/**
	 * Returns the paths to be handled by the subplugin at workshop level
	 * @return array
	 */
	protected function define_submission_subplugin_structure() {

		$paths = array();

		$elename = $this->get_namefor('submission');
		$elepath = $this->get_pathfor('/submission_hlt');
		// We used get_recommended_name() so this works

		$paths[] = new restore_path_element($elename, $elepath);

		return $paths;
	}

};
