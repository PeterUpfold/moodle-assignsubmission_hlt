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
 * Library class for HLT submission plugin
 *
 * @package assignsubmission_hlt
 * @copyright 2017 Test Valley School {@link https://www.testvalley.hants.sch.uk/}
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class assign_submission_hlt extends assign_submission_plugin {

	/**
	 * Get the name of this submission plugin.
	 * @return string
	 */
	public function get_name() {
		return get_string('hlt', 'assignsubmission_hlt');
	}


	/**
	 * Saves the settings for this submission plugin -- this is when events should
	 * be hooked up.
	 * @param stdClass data
	 * @return bool
	 */
	public function save_settings(stdClass $data) {

		global $USER, $DB;

		/* $data contains the form submission contents on the assignment editing form. We will
		be interested in $data->duedate and $data->allowsubmissionsfromdate (set date) */

		$set_date_event = new stdClass();

		// see if a set date already exists in the DB, and populate the id property if so
		$params = array(
			'modulename' => 'assign',
			'instance'   => $data->instance,
			'eventtype'  => 'hlt_setdate'
		);

		$set_date_event->id = $DB->get_field('event', 'id', $params);
		
		//var_dump($data);die();

		$set_date_event->name         = get_string('setdatename', 'assignsubmission_hlt');
		$set_date_event->description  = get_string('setdatedescription', 'assignsubmission_hlt');
		$set_date_event->courseid     = $data->course;
		$set_date_event->groupid      = 0;
		$set_date_event->userid       = $USER->id;
		$set_date_event->modulename   = 'assign';
		$set_date_event->instance     = $data->instance;
		$set_date_event->eventtype    = 'hlt_setdate';
		$set_date_event->timestart    = $data->allowsubmissionsfromdate;
		$set_date_event->visible      = true;
		$set_date_event->timeduration = 0;

		// if id is not equatable to false, it is an existing event to update.
		if ( $set_date_event->id ) {
			$cal_set_date_event = calendar_event::load($set_date_event->id);
			$cal_set_date_event->update($set_date_event);
		}
		else {
			unset($set_date_event->id);
			calendar_event::create($set_date_event);
		}

		return true;
	}
	

};
