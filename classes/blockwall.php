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
namespace mod_blockwall;

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/mod/blockwall/lib.php');

use block_instance;
use blockinstance;
use contextid;
use HTML;

class blockwall {
    public static $region = 'side-post'; // 'blockwall-main';

    public function __construct($id) {
        global $CFG, $DB;

    }

    /**
     * Loads all blocks for a context id and returns the block instances in an array.
     *
     * @param contextid
     * @return array of block instances.
     */
    public static function get_block_instances($contextid) {
        $showblocks = $DB->get_records('block_instances', array('parentcontextid' => $contextid));
        $blocks = array();
        foreach ($showblocks AS $showblock) {
            $blocks[] = new block_instance($showblock->blockname, $showblock);
        }
        return $blocks;
    }

    /**
     * Renders a block instance
     *
     * @param blockinstance
     * @return HTML output.
     */
    public static function render_block($blockinstance) {
        // @todo we have no clue how this will work.
        return $blockinstance->rendersomehow();
    }
}