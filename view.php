<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


/**
 * @package    mod_blockwall
 * @copyright  2019, Harald Bamberger, David Bogner, Nicklas Placho, Robert Schrenk
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
$cmid = required_param('id', PARAM_INT);

if ($cmid) {


} else {

}

$cm = get_coursemodule_from_id('blockwall', $cmid, 0, false, MUST_EXIST);
$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
$coursecontext = context_course::instance($course->id);

require_login($course, true, $cm);
$PAGE->set_context($coursecontext);
$PAGE->set_url('/mod/blockwall/view.php', array('id' => $cm->id));
//$PAGE->set_title(get_string('modulename', 'blockwall'));
//$PAGE->set_heading(get_string('modulename', 'blockwall'));
$PAGE->set_pagelayout('standard');

echo $OUTPUT->header();




echo $OUTPUT->footer();
