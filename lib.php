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

defined('MOODLE_INTERNAL') || die;

function blockwall_add_instance($mod) {
    global $DB, $COURSE;
    $mod->course = $COURSE->id;
    $mod->created = time();
    $mod->cmid = $mod->coursemodule;

    $id = $DB->insert_record('blockwall', $mod, true);
    return $id;
}
function blockwall_update_instance($mod) {
    global $DB, $COURSE;
    $mod->id = $mod->instance;
    $mod->course = $COURSE->id;
    $mod->cmid = $mod->coursemodule;

    // Now receive files.
    $modcontext = context_module::instance($mod->cmid);
    $draftid = file_get_submitted_draft_itemid('introeditor');

    $DB->update_record('blockwall', $mod);

    return true;
}
function blockwall_delete_instance($id) {
    global $DB;
    $DB->delete_records('blockwall', array('id' => $id));

    return true;
}
