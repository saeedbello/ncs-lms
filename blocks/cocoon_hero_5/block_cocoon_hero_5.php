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

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/theme/edumy/ccn/block_handler/ccn_block_handler.php');
class block_cocoon_hero_5 extends block_base
{

  /**
   * Initialises the block.
   *
   * @return void
   */
  public function init()
  {
    $this->title = get_string('pluginname', 'block_cocoon_hero_5');
  }

  /**
   * The block is usable in all pages
   */
  function applicable_formats()
  {
    $ccnBlockHandler = new ccnBlockHandler();
    return $ccnBlockHandler->ccnGetBlockApplicability(array('all'));
  }


  /**
   * Customize the block title dynamically.
   */
  function specialization()
  {
    // if (isset($this->config->title)) {
    //     $this->title = $this->title = format_string($this->config->title, true, ['context' => $this->context]);
    // } else {
    //     $this->title = get_string('newcustomsliderblock', 'block_cocoon_hero_5');
    // }
    global $CFG, $DB;
    include($CFG->dirroot . '/theme/edumy/ccn/block_handler/specialization.php');
    if (empty($this->config)) {
      $this->config = new \stdClass();
      $this->config->items = '3';
      $this->config->title = 'More than 2500 Online Courses';
      $this->config->subtitle = 'Own your future learning new skills online';
      $this->config->image1 = $CFG->wwwroot . '/theme/edumy/images/ccnBg.png';
      $this->config->image2 = $CFG->wwwroot . '/theme/edumy/images/ccnBg.png';
      $this->config->image3 = $CFG->wwwroot . '/theme/edumy/images/ccnBg.png';
    }
  }

  /**
   * The block can be used repeatedly in a page.
   */
  function instance_allow_multiple()
  {
    return false;
  }

  /**
   * Gets the block contents.
   *
   * If we can avoid it better not check the server status here as connecting
   * to the server will slow down the whole page load.
   *
   * @return string The block HTML.
   */
  public function get_content()
  {
    global $OUTPUT, $CFG, $PAGE;

    require_once($CFG->libdir . '/filelib.php');

    if ($this->content !== null) {
      return $this->content;
    }

    if (!empty($this->config) && is_object($this->config)) {
      $ccnStorage = $this->config;
      $ccnStorage->items = is_numeric($ccnStorage->items) ? (int)$ccnStorage->items : 3;
    } else {
      $ccnStorage = new stdClass();
      $ccnStorage->items = '3';
    }

    $this->content = new stdClass();
    $this->content->footer = '';
    $this->content->text = '';
    if (!empty($this->config->title)) {
      $this->content->title = $this->config->title;
    }
    if (!empty($this->config->subtitle)) {
      $this->content->subtitle = $this->config->subtitle;
    }
    if ($ccnStorage->items > 0) {
      $this->content->text .= '
          <div class="home-three home10-style" style="background-color:white">
  <div class="container">
    <div class="row posr">
      <div class="col-lg-8">
        <div class="home-content home10">
          <div class="home-text">
            <h2 data-ccn="title" class="color-black-mod" >' . format_text($this->content->title, FORMAT_HTML, array('filter' => true)) . '</h2>
            <p data-ccn="subtitle" class="color-black-mod">' . format_text($this->content->subtitle, FORMAT_HTML, array('filter' => true)) . '</p>
            
            <button style="display: flex;
            flex-direction: column;
            align-items: flex-start;
            padding: 15.5788px 12.9823px;
           
            width: 235.78px;
            height: 62.16px;
            left: 120px;
            top: 411.43px;
            font-size: 17.3098px;
            background: #0B4F2C;
            border-radius: 8.6549px;
            color: #FFFFFF;
            "><span>Access your courses <svg class="ml10" width="22" height="19" viewBox="0 0 22 19" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12.678 0.855469L11.4404 2.0611L18.0008 8.64488H0.561157V10.3759H18.0008L11.4404 16.9302L12.678 18.1653L21.3329 9.51037L12.678 0.855469Z" fill="white"/>
            </svg>
            </span> </button>
              </div>
          
        </div>
      </div>
      <div class="col-lg-4">
        <div class="home-content style2 home10">
          <div class="home-text">
            <div class="home10_slider">';
      $fs = get_file_storage();
      for ($i = 1; $i <= $ccnStorage->items; $i++) {
        $ccnItmImage = 'image' . $i;

        $files = $fs->get_area_files($this->context->id, 'block_cocoon_hero_5', 'slides', $i, 'sortorder DESC, id ASC', false, 0, 0, 1);
        if (!empty($ccnStorage->$ccnItmImage) && count($files) >= 1) {
          $mainfile = reset($files);
          $mainfile = $mainfile->get_filename();
          $ccnStorage->$ccnItmImage = moodle_url::make_file_url("$CFG->wwwroot/pluginfile.php", "/{$this->context->id}/block_cocoon_hero_5/slides/" . $i . '/' . $mainfile);
        } else {
          $ccnStorage->$ccnItmImage = $CFG->wwwroot . '/theme/edumy/images/ccnBg.png';
        }

        $this->content->text .= '          <div class="item">
                                    <img data-ccn="' . $ccnItmImage . '" src="' . $ccnStorage->$ccnItmImage . '" alt="">
                                  </div>';
      }
      $this->content->text .= '            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>';
    }


    return $this->content;
  }

  /**
   * Serialize and store config data
   */
  function instance_config_save($ccnStorage, $nolongerused = false)
  {
    global $CFG;

    $filemanageroptions = array(
      'maxbytes'      => $CFG->maxbytes,
      'subdirs'       => 0,
      'maxfiles'      => 1,
      'accepted_types' => array('.jpg', '.png', '.gif')
    );

    for ($i = 1; $i <= $ccnStorage->items; $i++) {
      $field = 'image' . $i;
      if (!isset($ccnStorage->$field)) {
        continue;
      }

      file_save_draft_area_files($ccnStorage->$field, $this->context->id, 'block_cocoon_hero_5', 'slides', $i, $filemanageroptions);
    }

    parent::instance_config_save($ccnStorage, $nolongerused);
  }

  /**
   * When a block instance is deleted.
   */
  function instance_delete()
  {
    global $DB;
    $fs = get_file_storage();
    $fs->delete_area_files($this->context->id, 'block_cocoon_hero_5');
    return true;
  }

  /**
   * Copy any block-specific data when copying to a new block instance.
   * @param int $fromid the id number of the block instance to copy from
   * @return boolean
   */
  public function instance_copy($fromid)
  {
    global $CFG;

    $fromcontext = context_block::instance($fromid);
    $fs = get_file_storage();

    if (!empty($this->config) && is_object($this->config)) {
      $ccnStorage = $this->config;
      $ccnStorage->items = is_numeric($ccnStorage->items) ? (int)$ccnStorage->items : 0;
    } else {
      $ccnStorage = new stdClass();
      $ccnStorage->items = 0;
    }

    $filemanageroptions = array(
      'maxbytes'      => $CFG->maxbytes,
      'subdirs'       => 0,
      'maxfiles'      => 1,
      'accepted_types' => array('.jpg', '.png', '.gif')
    );

    for ($i = 1; $i <= $ccnStorage->items; $i++) {
      $field = 'image' . $i;
      if (!isset($ccnStorage->$field)) {
        continue;
      }

      if (!$fs->is_area_empty($fromcontext->id, 'block_cocoon_hero_5', 'slides', $i, false)) {
        $draftitemid = 0;
        file_prepare_draft_area($draftitemid, $fromcontext->id, 'block_cocoon_hero_5', 'slides', $i, $filemanageroptions);
        file_save_draft_area_files($draftitemid, $this->context->id, 'block_cocoon_hero_5', 'slides', $i, $filemanageroptions);
      }
    }

    return true;
  }

  /**
   * The block should only be dockable when the title of the block is not empty
   * and when parent allows docking.
   *
   * @return bool
   */
  public function instance_can_be_docked()
  {
    return (!empty($this->config->title) && parent::instance_can_be_docked());
  }
  public function html_attributes()
  {
    global $CFG;
    $attributes = parent::html_attributes();
    include($CFG->dirroot . '/theme/edumy/ccn/block_handler/attributes.php');
    return $attributes;
  }
}
