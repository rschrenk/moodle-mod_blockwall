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

use mod_blockwall\blockwall;

require_once(__DIR__ . '/classes/blockwall.php');

defined('MOODLE_INTERNAL') || die;

function blockwall_add_instance($mod) {
    global $DB, $COURSE;
    $mod->course = $COURSE->id;
    $mod->created = time();
    $mod->name = get_string('modulename','blockwall');
    $mod->modified = time();
    $mod->cmid = $mod->coursemodule;

    $id = $DB->insert_record('blockwall', $mod, true);
    blockwall_update_instance_region($mod);

    /*
    $inst = $DB->get_record('course_modules', array('id' => $id));
    $inst->showdescription = 1;
    $DB->update_record('course_modules', $inst);
    */
    return $id;
}

function blockwall_update_instance($mod) {
    global $DB, $COURSE;
    $mod->id = $mod->instance;
    $mod->course = $COURSE->id;
    $mod->modified = time();
    $mod->cmid = $mod->coursemodule;

    $DB->update_record('blockwall', $mod);

    blockwall_update_instance_region($mod);

    /*
    $inst = $DB->get_record('course_modules', array('id' => $id));
    $inst->showdescription = 1;
    $DB->update_record('course_modules', $inst);
    */

    return true;
}
/**
 * Updates the blocks for our region.
 *
 * @param mod the course module.
 */
function blockwall_update_instance_region($mod) {
    // We need to add the blocks to the course-context to a region dependent on the cmid!
    $page = new moodle_page();
    //$page->set_context(context_module::instance($mod->cmid));
    $page->set_context(context_course::instance($mod->course));
    $page->blocks->add_region(mod_blockwall\blockwall::region($mod->cmid));
    foreach( $mod->blockselection as $idx => $blockname) {
      $page->blocks->add_block($blockname, mod_blockwall\blockwall::region(), $idx, false, '*');
    }
}

function blockwall_delete_instance($id) {
    global $DB;

    if (! $blockwall = $DB->get_record("blockwall", array("id"=>$id))) {
        return false;
    }

    $result = true;

    if (! $DB->delete_records("label", array("id"=>$blockwall->id))) {
        $result = false;
    }

    return $result;
}

/**
 * Given a course_module object, this function returns any
 * "extra" information that may be needed when printing
 * this activity in a course listing.
 * See get_array_of_activities() in course/lib.php
 *
 * @global object
 * @param object $coursemodule
 * @return object|null
 */
function blockwall_get_coursemodule_info($cm) {
    global $COURSE, $OUTPUT;
    // $PAGE->requires->css('/mod/blockwall/style/main.css');

    $cminfo = new cached_cm_info();
    $cminfo->name = "";
    $cminfo->content = mod_blockwall\blockwall::render_blocks();

    return $cminfo;
}

function blockwall_supports($feature) {
  switch($feature) {
      case FEATURE_MOD_ARCHETYPE:           return MOD_ARCHETYPE_RESOURCE;
      case FEATURE_GROUPS:                  return false;
      case FEATURE_GROUPINGS:               return false;
      case FEATURE_MOD_INTRO:               return true;
      case FEATURE_COMPLETION_TRACKS_VIEWS: return true;
      case FEATURE_GRADE_HAS_GRADE:         return false;
      case FEATURE_GRADE_OUTCOMES:          return false;
      case FEATURE_BACKUP_MOODLE2:          return true;
      case FEATURE_SHOW_DESCRIPTION:        return true;
      default: return null;
  }
}
