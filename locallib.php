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

require_once($CFG->dirroot . '/calendar/lib.php');

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
	public function save_settings(stdClass &$data) {
				/**
				 * OK, so I'm being very naughty here by violating E_STRICT and passing $data by reference to this method.
				 * Why? Well, I'm modifying the 'intro' of the assign to include the set and due dates. I want to do it this way
				 * so that they appear inline in the course view if "display description on course page" is ticked.
				 *
				 * However, if I cannot modify the 'intro' parameter in the form data here such that it affects Core,
				 * the original 'intro' will overwrite what I do here. This is caused by modlib.php:add_moduleinfo() and 
				 * only affects the creation of a new assign. Editing an existing assign doesn't require this hack.
				 */

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
		

		$set_date_event->name         = get_string('setdatename', 'assignsubmission_hlt') . ' ' . $this->assignment->get_instance()->name;
		$set_date_event->description  = get_string('setdatedescription', 'assignsubmission_hlt');
		$set_date_event->courseid     = $data->course;
		$set_date_event->groupid      = 0;
		$set_date_event->userid       = $USER->id;
		$set_date_event->modulename   = 'assign';
		$set_date_event->instance     = $this->assignment->get_instance()->id;
		$set_date_event->eventtype    = 'hlt_setdate';
		$set_date_event->icon         = 'hlt_setdate';
		$set_date_event->timestart    = $data->allowsubmissionsfromdate;
		$set_date_event->timesort     = $data->allowsubmissionsfromdate;
		$set_date_event->visible      = true;
		$set_date_event->timeduration = 0;

		// if id is not bool false, it is an existing event to update.
		if ( $set_date_event->id !== false ) {
			$cal_set_date_event = calendar_event::load($set_date_event->id);
			$cal_set_date_event->update($set_date_event);
		}
		else {
			unset($set_date_event->id);
			calendar_event::create($set_date_event);
		}

		// update the standard event to include our prefix "HLT Due:"
		$due_date_event = new stdClass();

		// see if a due date already exists in the DB, and populate the id property if so
		$params = array(
			'modulename' => 'assign',
			'instance'   => $data->instance,
			'eventtype'  => 'due'
		);		
		
		// update the due event to include the HLT Due: prefix
		$due_date_event->id = $DB->get_field('event', 'id', $params);
		$due_date_event->name = get_string('duedatename', 'assignsubmission_hlt') . ' ' . $this->assignment->get_instance()->name;

		if ($due_date_event->id !== false) {
			$cal_due_date_event = calendar_event::load($due_date_event->id);
			$cal_due_date_event->update($due_date_event);
		}
		else {
			unset($due_date_event->id);
			$due_date_event->eventtype = 'due';
			$due_date_event->courseid     = $data->course;
			$due_date_event->groupid      = 0;
			$due_date_event->userid       = $USER->id;
			$due_date_event->modulename   = 'assign';
			$due_date_event->instance     = $this->assignment->get_instance()->id;
			$due_date_event->icon         = 'hlt_setdate';
			$due_date_event->timestart    = $data->duedate;
			$due_date_event->visible      = true;
			$due_date_event->timeduration = 0;

			$cal_due_date_event = calendar_event::create($due_date_event);
		}

		// update intro to include set date
		$update_assign = new \stdClass();
		$update_assign->id = $this->assignment->get_instance()->id;


		// replace existing set date/due date div in intro
		if (strpos($this->assignment->get_instance()->intro, '<div class="assignsubmission_hlt_metadata">') !== false) {
			$update_assign->intro = preg_replace('/<div class="assignsubmission_hlt_metadata">.*<\/div>/U', '', $this->assignment->get_instance()->intro);
		}
		else {
			$update_assign->intro = $this->assignment->get_instance()->intro;
		}

		$update_assign->intro .=
			'<br/><div class="assignsubmission_hlt_metadata"><p><strong>' . get_string('setdatename', 'assignsubmission_hlt') . '</strong>' .
			\userdate($data->allowsubmissionsfromdate, get_string('strftimedate', 'langconfig')) .
			'</p>' .
			'<p><strong>' . get_string('duedatename', 'assignsubmission_hlt') . '</strong>' .
			\userdate($data->duedate, get_string('strftimedate', 'langconfig')) .
			'</p></div>';

		$data->intro = $update_assign->intro; // allow the new intro to bubble back up (see note at the beginning of this method)

		$DB->update_record('assign', $update_assign);


		return true;
	}


	/** 
	 * This plugin has no submission component (like 'comments' plugin), so should not be counted
	 * when determining whether to show the edit submission link.
	 */
	public function allow_submissions() {
		return false;
	}
	
	/**
	 * Render an introductory section which is displayed right below the activity's "intro" section on the main
	 * assignment page.
	 */
	public function view_header() {
		return '';
	}


};
