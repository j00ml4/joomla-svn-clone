<?php
/**
 * Title_here
 *
 * full_description_here
 *
 * @package package_name
 * @version $Id$
 * @author Alan Langford <addr>
 */

class JHtmlSelectTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Skipped based on version message.
	 */
	const VERSION_SKIP_MSG = 'Test disabled for this version of Joomla!';

	/**
	 * Mask for tests that should work in 1.5 and above
	 *
	 * @var array
	 */
	public static $version15up = array('jver_min' => '1.5');

	/**
	 * Version mask. This should track the current version.
	 *
	 * @var array
	 */
	public static $versionMask = array('jver_min' => '1.6');

	/**
	 * Get various common option expectation strings
	 */
	static function expectingOptions($type = 'basic') {
		// The kernel of what we expect from sample data
		$htmlCore = array(
			's0' => '<option value="s0">Selection zero &gt;= 0 &amp; &lt; 6 </option>',
			's<1' => '<option value="s&lt;1">Selection \'1\' &gt; 0 &amp; &lt; 6 </option>',
			's<2' => '<option value="s&lt;2">Selection &quot;2&quot; &gt; 0 &amp; &lt; 6</option>',
			's<3' => '<option value="s&lt;3">Selection 3 &gt; 0 &amp; &lt; 6</option>',
			's<4' => '<option value="s&lt;4">Selection 4 &gt; 0 &amp; &lt; 6</option>',
			's<5' => '<option value="s&lt;5">Selection 5 &gt; 0 &amp; &lt; 6</option>'
		);
		$labels = array(
			's0' => '',
			's<1' => '',
			's<2' => '',
			's<3' => '',
			's<4' => '',
			's<5' => 'Label five',
		);
		$htmlIdCore = array(
			's0' => '<option value="s0" id="list1_s0">Selection zero &gt;= 0 &amp; &lt; 6 </option>',
			's<1' => '<option value="s&lt;1" id="list1_s1">Selection \'1\' &gt; 0 &amp; &lt; 6 </option>',
			's<2' => '<option value="s&lt;2" id="list1_s2">Selection &quot;2&quot; &gt; 0 &amp; &lt; 6</option>',
			's<3' => '<option value="s&lt;3" id="list1_s3">Selection 3 &gt; 0 &amp; &lt; 6</option>',
			's<4' => '<option value="s&lt;4" id="list1_s4">Selection 4 &gt; 0 &amp; &lt; 6</option>',
			's<5' => '<option value="s&lt;5" id="list1_s5">Selection 5 &gt; 0 &amp; &lt; 6</option>'
		);
		$htmlLabelCore = array(
			's0' => '<option value="s0" label="Label zero">Selection zero &gt;= 0 &amp; &lt; 6 </option>',
			's<1' => '<option value="s&lt;1" label="Label &quot;one&quot;">Selection \'1\' &gt; 0 &amp; &lt; 6 </option>',
			's<2' => '<option value="s&lt;2" label="Label &lt;two&gt;">Selection &quot;2&quot; &gt; 0 &amp; &lt; 6</option>',
			's<3' => '<option value="s&lt;3" label="Label &amp;three&amp;">Selection 3 &gt; 0 &amp; &lt; 6</option>',
			's<4' => '<option value="s&lt;4" label="Label four">Selection 4 &gt; 0 &amp; &lt; 6</option>',
			's<5' => '<option value="s&lt;5" label="Label five">Selection 5 &gt; 0 &amp; &lt; 6</option>'
		);
		$post = substr($type . '.', 0, 6) != 'array.';
		switch ($type) {
			case 'array': {
				// Just return raw data
				$html = $htmlCore;
			}
			break;

			case 'array.g1':
			case 'array.g2': {
				//  Options for use in a grouped list
				$group = substr($type, 6) . '.';
				$html = array();
				foreach ($htmlCore as $key => $val) {
					$html[$group . $key] = str_replace('value="', 'value="' . $group, $val);
				}
			}
			break;

			case 'array.s0.s3':
			case 's0.s3': {
				// Select elements 0 and 3
				$htmlCore['s0'] = '<option value="s0" selected="selected">'
					. 'Selection zero &gt;= 0 &amp; &lt; 6 </option>';
				$htmlCore['s<3'] = '<option value="s&lt;3" selected="selected">'
					. 'Selection 3 &gt; 0 &amp; &lt; 6</option>';
			}
			break;

			case 'array.s3':
			case 's3': {
				// Select element 0
				$htmlCore['s<3'] = '<option value="s&lt;3" selected="selected">'
					. 'Selection 3 &gt; 0 &amp; &lt; 6</option>';
			}
			break;

			case 'basic_format': {
				// Use the id version
				foreach ($htmlCore as &$entry) {
					$entry = '    ' . $entry;
				}
				$html = implode(chr(13) . chr(10), $htmlCore) . chr(13) . chr(10);
				$post = false;
			}
			break;

			case 'basic_id': {
				// Use the id version
				$htmlCore = $htmlIdCore;
			}
			break;

			case 'basic_label': {
				// Use the labelled version
				$htmlCore = $htmlLabelCore;
			}
			break;

			case 'basic_label_lang': {
				// Use the labelled version
				$htmlCore = $htmlLabelCore;
				// Put "foo" in front of the label and selection strings
				foreach ($htmlCore as &$opt) {
					$opt = str_replace(
						array('Label', 'Selection'),
						array('fooLabel', 'fooSelection'),
						$opt
					);
				}
			}
			break;

			case 'd3': {
				$htmlCore['s<3'] = '<option value="s&lt;3" disabled="disabled">'
					. 'Selection 3 &gt; 0 &amp; &lt; 6</option>';
			}
			break;

			case 'basic':
			default: {
				// nothing to do
			}
			break;
		}
		if ($post) {
			$html = implode(chr(10), $htmlCore) . chr(10);
		}
		return $html;
	}

	/**
	 * Create select HTML.
	 */
	static function expectingSelect($type = 'basic') {
		$html = '';
		switch ($type) {
			case 'grouped': {
				$items = self::expectingOptions('array.g1');
				$html = '<select id="testlist" name="testlist">' . chr(10)
					. chr(9) . '<optgroup label="Group 1">' . chr(10)
				;
				foreach ($items as $item) {
					$html .= chr(9) . chr(9) . $item . chr(10);
				}
				$html .= chr(9) . '</optgroup>' . chr(10);
				$items = self::expectingOptions('array.g2');
				$html .= chr(9) . '<optgroup label="Group 2">' . chr(10)
				;
				foreach ($items as $item) {
					$html .= chr(9) . chr(9) . $item . chr(10);
				}
				$html .= chr(9) . '</optgroup>' . chr(10);
				$html .= '</select>' . chr(10);
			}
			break;
		}
		return $html;
	}

	/**
	 * Creates a small array of sample data in various forms
	 */
	static function makeSampleOptions($type = 'option') {
		// Scary source data with problematic characters.
		$data = array(
			's0' => 'Selection zero >= 0 & < 6 ',
			's<1' => 'Selection \'1\' > 0 & < 6 ',
			's<2' => 'Selection "2" > 0 & < 6',
			's<3' => 'Selection 3 > 0 & < 6',
			's<4' => 'Selection 4 > 0 & < 6',
			's<5' => 'Selection 5 > 0 & < 6',
		);
		$labels = array(
			's0' => 'Label zero',
			's<1' => 'Label "one"',
			's<2' => 'Label <two>',
			's<3' => 'Label &three&',
			's<4' => 'Label four',
			's<5' => 'Label five',
		);
		$result = array();
		switch ($type) {
			case 'array': {
				foreach ($data as $key => $val) {
					$result[] = array('value' => $key, 'text' => $val);
				}
			}
			break;

			case 'array.g1':
			case 'array.g2': {
				$group = substr($type, 6) . '.';
				foreach ($data as $key => $val) {
					$result[] = array('value' => $group . $key, 'text' => $val);
				}
			}
			break;

			case 'array_custom': {
				foreach ($data as $key => $val) {
					$result[] = array('custKey' => $key, 'custText' => $val);
				}
			}
			break;

			case 'array_id': {
				$ind = 0;
				foreach ($data as $key => $val) {
					$result[] = array(
						'value' => $key,
						'text' => $val,
						'id' => 'list1_s' . $ind++,
					);
				}
			}
			break;

			case 'array_id_custom': {
				$ind = 0;
				foreach ($data as $key => $val) {
					$result[] = array(
						'custKey' => $key,
						'custText' => $val,
						'custId' => 'list1_s' . $ind++,
					);
				}
			}
			break;

			case 'array_label': {
				foreach ($data as $key => $val) {
					$result[] = array('value' => $key, 'text' => $val, 'label' => $labels[$key]);
				}
			}
			break;

			case 'array_label_custom': {
				foreach ($data as $key => $val) {
					$result[] = array('custKey' => $key, 'custText' => $val, 'custLabel' => $labels[$key]);
				}
			}
			break;

			case 'array_label_raw': {
				foreach ($data as $key => $val) {
					$result[] = array(
						'value' => htmlspecialchars($key),
						'text' => htmlentities($val),
						'label' => htmlentities($labels[$key])
					);
				}
			}
			break;

			case 'keyed_array': {
				$result = $data;
			}
			break;

			case 'keyed_array.g1':
			case 'keyed_array.g2': {
				$group = substr($type, 12) . '.';
				foreach ($data as $key => $val) {
					$result[$group . $key] = $val;
				}
			}
			break;

			case 'option_custom': {
				foreach ($data as $key => $val) {
					$result[] = JHtmlSelect::option(
						$key,
						$val,
						array(
							'option.key' => 'custKey',
							'option.text' => 'custText'
						)
					);
				}
			}
			break;

			case 'option_label_custom': {
				foreach ($data as $key => $val) {
					$result[] = JHtmlSelect::option(
						$key,
						$val,
						array(
							'option.key' => 'custKey',
							'option.text' => 'custText',
							'option.label' => 'custLabel',
							'label' => $labels[$key],
						)
					);
				}
			}
			break;

			case 'option.g1':
			case 'option.g2': {
				$group = substr($type, 7) . '.';
				foreach ($data as $key => $val) {
					$result[] = JHtmlSelect::option($group . $key, $val);
				}
			}
			break;

			case 'option':
			default: {
				foreach ($data as $key => $val) {
					$result[] = JHtmlSelect::option($key, $val);
				}
			}
			break;
		}
		return $result;
	}

}