<?php
/*

===

kirk_spectrum Field Type for Perch
implements the Spectrum color picker by @bgrins
http://bgrins.github.io/spectrum/

Usage

<perch:content id="color" type="kirk_spectrum" label="Color" size="m" order="" palette="['orange', '#ff0000', 'purple'],['rgb(255, 128, 0);', 'hsv 100 70 50', 'lightyellow']" options="" />

Attributes:
palette: optional json string using any valid css specifications; each object is a row
options: optional comma-delimited string of options in javascript syntax
NOTE palette can be set separately from options

Config Constants (globals, set in /perch/config/config.php):
KIRK_SPECTRUM_PALETTE
define('KIRK_SPECTRUM_PALETTE', "['orange', '#ff0000', 'purple'],['rgb(255, 128, 0);', 'hsv 100 70 50', 'lightyellow']");

KIRK_SPECTRUM_OPTIONS
define('KIRK_SPECTRUM_OPTIONS', "showInput:true, showPalette:true");

===

Version 0.1.1

- now works with the Content app (previously only worked with Blog and possibly other first-party apps)

Version 0.1

- initial build with Spectrum Colorpicker v1.5.1

===

 
*/
class PerchFieldType_kirk_spectrum extends PerchAPI_FieldType
{
	
  public function render_inputs($details=array()) {
		
		$markup = '';
		$classes = array('kirk-spectrum');
        // if ($this->Tag->mode()) $classes[] = $this->Tag->mode();
		$classes = implode(' ', $classes);

		$id = $this->Tag->id();
		$_id = $this->Tag->input_id();
		$value = $this->Form->get($details, $id, $this->Tag->default(), $this->Tag->post_prefix());
		// text($id, $value='', $class='', $limit=false, $type='text', $attributes='')
		// $value = '#ff0000';
		$markup .= $this->Form->text($_id, $value, $classes, false, 'text', '');

		if($this->Tag->options() || defined('KIRK_SPECTRUM_OPTIONS')) {
			if($this->Tag->options()) {
				$attrStr = $this->Tag->options();
			} else {
				$attrStr = KIRK_SPECTRUM_OPTIONS;
			}
		} else {
			// default
			$attr = array(
				'showInput:true',
				'allowEmpty:true',
				'clickoutFiresChange:true',
				'preferredFormat:"hex"'
				);
			$attrStr = join(',',$attr);
		}

		if($this->Tag->palette() || defined('KIRK_SPECTRUM_PALETTE')) {
			if($this->Tag->palette()) {
				$palette = $this->Tag->palette();
			} else {
				$palette = KIRK_SPECTRUM_PALETTE;
			}
			$attrStr .= ',';
			$attrStr .= 'showPalette:true,';
			$attrStr .= 'hideAfterPaletteSelect:true,';
			$attrStr .= 'palette:[' . $palette . ']';
		}

		$scripts = <<<HTML
				<script>
					$(function(){
						$("#perch_{$id},#{$_id}").spectrum({
						    {$attrStr}
						});
					});
				</script>
HTML;
		$Perch = Perch::fetch();
		$Perch->add_foot_content($scripts);
    
    return $markup;
  }

	public function add_page_resources() {
		$Perch = Perch::fetch();
		$dir = PERCH_LOGINPATH.'/addons/fieldtypes/kirk_spectrum';
    $Perch->add_head_content('<link rel="stylesheet" type="text/css" href="' . $dir . '/spectrum.css">');
		$Perch->add_foot_content('<script type="text/javascript" src="' . $dir . '/spectrum.js"></script>');
	}

}

?>