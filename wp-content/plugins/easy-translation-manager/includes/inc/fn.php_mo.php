<?php

function phpmo_convert($input, $output = false) {
	if ( !$output )
		$output = str_replace( '.po', '.mo', $input );

	$hash = phpmo_parse_po_file( $input );
	if ( $hash === false ) {
		return false;
	} else {
		phpmo_write_mo_file2( $hash, $output );
		return true;
	}
}

function phpmo_clean_helper($x) {
	if (is_array($x)) {
		foreach ($x as $k => $v) {
			$x[$k] = phpmo_clean_helper($v);
		}
	} else {
		if ($x[0] == '"')
			$x = substr($x, 1, -1);
		$x = str_replace("\"\n\"", '', $x);
		$x = str_replace('$', '\\$', $x);
		$x = @ eval ("return \"$x\";");
	}
	return $x;
}

/* Parse gettext .po files. */
/* @link http://www.gnu.org/software/gettext/manual/gettext.html#PO-Files */
function phpmo_parse_po_file($in) {
	// read .po file
	$fc = file_get_contents($in);
	// normalize newlines
	$fc = str_replace(array (
		"\r\n",
		"\r"
	), array (
		"\n",
		"\n"
	), $fc);
	
	
	// results array
	$hash = array ();
	// temporary array
	$temp = array ();
	// state
	$state = null;
	$fuzzy = false;
	$generate_array = array();
	
	$stage_str = 0;
	$fc .= "\nmsgid \"end\"";
	
	$msgid_plural = '';
	$msgid = '';
	$msgstr = array();
	$msgctxt = '';
	$current_active = '';
	$loop_chekc = array('msgctxt','msgid','msgid_plural','msgstr');
	
	
	
	// iterate over lines
	foreach (explode("\n", $fc) as $line) {
		$line = trim($line);
		if ($line === '')
			continue;


		$line = str_replace('"', '', $line);
		list ($key, $data) = explode(' ', $line, 2);

		switch ($key) {
			case 'msgctxt' :
				if(!empty($msgid)){
					if(count($msgstr) == 1){
						$msgstr[] = '';
					}
					$temp[] = array('msgid_plural'=>$msgid_plural ,'msgid'=>$msgid,'msgstr'=>$msgstr,'msgctxt'=>$msgctxt);
					$msgid_plural = '';
					$msgstr = array();
					$msgctxt = '';
					$msgid = '';
				}
				$msgctxt = $data;
				$current_active = 'msgctxt';
				break;
			case 'msgid' :
				if(!empty($msgid)){
					if(count($msgstr) == 1){
						$msgstr[] = '';
					}
					$temp[] = array('msgid_plural'=>$msgid_plural ,'msgid'=>$msgid,'msgstr'=>$msgstr,'msgctxt'=>$msgctxt);
					$msgid_plural = '';
					$msgstr = array();
					$msgctxt = '';
				}
				$msgid = $data;
				$current_active = 'msgid';
				break;
			case 'msgid_plural' :
				$msgid_plural = $data;
				$current_active = 'msgid_plural';
				break;
			case 'msgstr' :
				$msgstr[0] = $data;
				$current_active = 'msgstr';
				break;
			default :
				if (strpos($key, 'msgstr[') !== FALSE) {
						$msgstr[] = $data;
				} else {
					if((!empty($key) && !in_array($key, $loop_chekc)) or empty($key)){
						if($current_active == 'msgctxt') {
							$msgctxt .= $data;
						} else if($current_active == 'msgid') {
							$msgid .= $data;
						} else if($current_active == 'msgid_plural') {
							$msgid_plural .= $data;
						} else if($current_active == 'msgstr') {
							if(count($msgstr) > 0){
								$msgstr[count($msgstr)-1] .= $data;	
							} else {
								$msgstr[0] = $data;	
							}
						}	
					}
				}
				break;
		}
	}
	
	return $temp;
}

/* Write a GNU gettext style machine object. */
/* @link http://www.gnu.org/software/gettext/manual/gettext.html#MO-Files */
function phpmo_write_mo_file($hash, $out) {
	// sort by msgid
	ksort($hash, SORT_STRING);
	// our mo file data
	$mo = '';
	// header data
	$offsets = array ();
	$ids = '';
	$strings = '';
	$str = '';
	$str1 = '';
	
	foreach ($hash as $entry) {
		$id = $entry['msgid'];
		if (isset ($entry['msgid_plural']))
			$id .= "\x00" . $entry['msgid_plural'];
		// context is merged into id, separated by EOT (\x04)
		if (array_key_exists('msgctxt', $entry))
			$id = $entry['msgctxt'] . "\x04" . $id;
		// plural msgstrs are NUL-separated
		
		$str = $entry['msgstr'];
		if(!empty($entry['msgstr1'])){
			$str1 = $entry['msgstr1'];
		} else {
			$str1 = '';
		}
		
		// keep track of offsets
		$offsets[] = array(strlen($ids), strlen($id), strlen($strings), strlen($str));
		// plural msgids are not stored (?)
		$ids .= $id . "\x00";
		
		if(!empty($str1)){
			$strings .= $str .$str1. "\x00";
		} else {
			$strings .= $str . "\x00";
		}
		
		
	}

	// keys start after the header (7 words) + index tables ($#hash * 4 words)
	$key_start = 7 * 4 + sizeof($hash) * 4 * 4;
	// values start right after the keys
	$value_start = $key_start +strlen($ids);
	// first all key offsets, then all value offsets
	$key_offsets = array ();
	$value_offsets = array ();
	// calculate
	foreach ($offsets as $v) {
		list ($o1, $l1, $o2, $l2) = $v;
		$key_offsets[] = $l1;
		$key_offsets[] = $o1 + $key_start;
		$value_offsets[] = $l2;
		$value_offsets[] = $o2 + $value_start;
	}
	$offsets = array_merge($key_offsets, $value_offsets);

	// write header
	$mo .= pack('Iiiiiii', 0x950412de, // magic number
	0, // version
	sizeof($hash), // number of entries in the catalog
	7 * 4, // key index offset
	7 * 4 + sizeof($hash) * 8, // value index offset,
	0, // hashtable size (unused, thus 0)
	$key_start // hashtable offset
	);
	
	// offsets
	foreach ($offsets as $offset)
		$mo .= pack('i', $offset);
	// ids
	$mo .= $ids;
	// strings
	$mo .= $strings;

	file_put_contents($out, $mo);
}

/* Write a GNU gettext style machine object. */
/* @link http://www.gnu.org/software/gettext/manual/gettext.html#MO-Files */
function phpmo_write_mo_file2($hash, $out) {
	// sort by msgid
	ksort($hash, SORT_STRING);
	// our mo file data
	$mo = '';
	// header data
	$offsets = array ();
	$ids = '';
	$strings = '';

	foreach ($hash as $entry) {
		$id = $entry['msgid'];
		if (isset ($entry['msgid_plural']))
			$id .= "\x00" . $entry['msgid_plural'];
		// context is merged into id, separated by EOT (\x04)
		if (array_key_exists('msgctxt', $entry))
			$id = $entry['msgctxt'] . "\x04" . $id;
		// plural msgstrs are NUL-separated
		$str = implode("\x00", $entry['msgstr']);
		// keep track of offsets
		$offsets[] = array (
			strlen($ids
		), strlen($id), strlen($strings), strlen($str));
		// plural msgids are not stored (?)
		$ids .= $id . "\x00";
		$strings .= $str . "\x00";
	}

	// keys start after the header (7 words) + index tables ($#hash * 4 words)
	$key_start = 7 * 4 + sizeof($hash) * 4 * 4;
	// values start right after the keys
	$value_start = $key_start +strlen($ids);
	// first all key offsets, then all value offsets
	$key_offsets = array ();
	$value_offsets = array ();
	// calculate
	foreach ($offsets as $v) {
		list ($o1, $l1, $o2, $l2) = $v;
		$key_offsets[] = $l1;
		$key_offsets[] = $o1 + $key_start;
		$value_offsets[] = $l2;
		$value_offsets[] = $o2 + $value_start;
	}
	$offsets = array_merge($key_offsets, $value_offsets);

	// write header
	$mo .= pack('Iiiiiii', 0x950412de, // magic number
	0, // version
	sizeof($hash), // number of entries in the catalog
	7 * 4, // key index offset
	7 * 4 + sizeof($hash) * 8, // value index offset,
	0, // hashtable size (unused, thus 0)
	$key_start // hashtable offset
	);
	// offsets
	foreach ($offsets as $offset)
		$mo .= pack('i', $offset);
	// ids
	$mo .= $ids;
	// strings
	$mo .= $strings;

	file_put_contents($out, $mo);
}


?>