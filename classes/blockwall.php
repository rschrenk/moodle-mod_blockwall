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
    private static $region = 'blockwall'; // 'blockwall-main';
    private static $cmid = 0; // 'To store the current cmid'.

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
     * Gets or sets the region name including the cmid.
     *
     * @param cmid if cmid is given will set this.
     */
    public static function region($cmid = 0) {
        if (!empty($cmid)) self::$cmid = $cmid;
        return self::$region . '-' . self::$cmid;
    }

    /**
     * Renders all blocks in a region.
     *
     * @param region (optional) region to render, if empty use default region.
     * @return HTML output.
     */
    public static function render_blocks($region = '') {
        global $OUTPUT, $PAGE;
        if (empty($region)) {
            $region = self::region();
        }

        $blocks = $PAGE->blocks->get_content_for_region($region, $OUTPUT);
        $blockcontents = array();
        foreach ($blocks AS $block) {
            $blockcontents[] = array('title' => $block->title, 'content' => $block->content);
        }

        return $OUTPUT->render_from_template('blockwall/grid', array('blocks' => $blockcontents));
    }
}
