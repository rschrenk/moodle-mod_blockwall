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
if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');
}

require_once($CFG->dirroot . '/course/moodleform_mod.php');
require_once($CFG->libdir . '/formslib.php');
require_once($CFG->libdir . '/blocklib.php');

class mod_blockwall_mod_form extends moodleform_mod {
    public function definition() {
        global $COURSE;

        $mform =& $this->_form;

        $mform->addElement('header', 'general', get_string('general', 'form'));

        // Get list of available blocks on the course page.
        $page = new moodle_page();
        $page->set_course($COURSE);
        $blockmanager = new block_manager($page);
        $blockmanager->load_blocks(true);
        $blocklist = $blockmanager->get_addable_blocks();
        $options = array(
            'multiple' => true
        );
        $blockselection = [];
        foreach ($blocklist as $blockname => $block) {
            $blockselection[$block->id] =  get_string('pluginname', 'block_' . $block->name);
        }

        // Provide selection form element for available blocks.
        $mform->addElement('autocomplete', 'blockselection',
            get_string('blockselection', 'blockwall'), $blockselection, $options);
        $mform->setType('blockselection', PARAM_INT);

        $this->standard_coursemodule_elements();
        $this->standard_intro_elements();

        /*
        $mform->addElement('hidden', 'showdescription', 1);
        $mform->setType('showdescription', PARAM_INT);
        */
        $mform->setDefault('showdescription', 1);

        $this->add_action_buttons();
    }
}
