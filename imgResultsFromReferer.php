<?php
/**
 * Plugin Name: imgResultsFromReferer
 * Plugin URI: http://www.westrad.de/wp-plugin-imgResultsFromReferer
 * Description: Extract image file name out of the referrer string - shows images ranked in the backend
 * Version: 0.2
 * Author: Matthias Greiling
 * Author URI: http://www.westrad.de/impressum
 * Text Domain: imgResultsFromReferer
 * Domain Path: /locale/
 * Network: true
 * License: http://creativecommons.org/licenses/by-nc-sa/4.0/
 */

// considered security note 
defined('ABSPATH') or die("No script kiddies please!");

// multilanguage
load_plugin_textdomain(
	'imgResultsFromReferer', 
	plugins_url() . '/' . dirname(plugin_basename(__FILE__)) . '/locale', 
	'/' . dirname(plugin_basename(__FILE__)) . '/locale'
);

// add to admin menu with different roles
function imgResultsFromRefererAddMenu() 
{
	add_menu_page(
		__('aboutTitle','imgResultsFromReferer'), 
		__('imgResultsFromReferer','imgResultsFromReferer'), 
		'edit_posts',
		__FILE__, 
		'lastClicks'
	);
	add_submenu_page(
		__FILE__, 
		__('aboutTitle','imgResultsFromReferer'), 
		__('rankingTotal','imgResultsFromReferer'), 
		'edit_posts', 
		'rankingTotal', 
		'rankingTotal'
	);
	add_submenu_page(
		__FILE__, 
		__('aboutTitle','imgResultsFromReferer'), 
		__('rankingMonthly','imgResultsFromReferer'), 
		'edit_posts', 
		'rankingMonthly', 
		'rankingMonthly'
	);
}

// use hook to admin menu
add_action('admin_menu', 'imgResultsFromRefererAddMenu');

// register plugin setting options
function register_setup_imgResultsFromReferer()
{
	register_setting( 'imgResultsFromReferer-settings-group', 'imgResultsFromReferer_thumbnailWidth' );
	register_setting( 'imgResultsFromReferer-settings-group', 'imgResultsFromReferer_thumbnailHeight' );
	register_setting( 'imgResultsFromReferer-settings-group', 'imgResultsFromReferer_amountLastClicks' );
	register_setting( 'imgResultsFromReferer-settings-group', 'imgResultsFromReferer_tableName' );
	register_setting( 'imgResultsFromReferer-settings-group', 'imgResultsFromReferer_tableCol' );
	register_setting( 'imgResultsFromReferer-settings-group', 'imgResultsFromReferer_tableColTime' );
	register_setting( 'imgResultsFromReferer-settings-group', 'imgResultsFromReferer_tableGroupBy' );
	register_setting( 'imgResultsFromReferer-settings-group', 'imgResultsFromReferer_string2kill' );
	register_setting( 'imgResultsFromReferer-settings-group', 'imgResultsFromReferer_static' );
	register_setting( 'imgResultsFromReferer-settings-group', 'imgResultsFromReferer_amountMonthlyClicks' );
}

// use hook to admin initialisation
add_action( 'admin_init', 'register_setup_imgResultsFromReferer' );

// add plugin CSS style to backend
function imgResultsFromReferer_admin_theme_style() 
{
    wp_enqueue_style(
		'imgResultsFromReferer-admin-theme', 
		plugins_url( 'styles/imgResultsFromReferer.css', __FILE__)
	);
}

// use hook 
add_action('admin_enqueue_scripts', 'imgResultsFromReferer_admin_theme_style');



class options_page {
	function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}
	function admin_menu () {
		add_options_page( __('imgResultsFromReferer','imgResultsFromReferer'), __('imgResultsFromReferer','imgResultsFromReferer'),'manage_options','imgResultsFromReferer', array( $this, 'setupImgResultsFromReferer' ) );
	}
	function setupImgResultsFromReferer()
	{
	?>
	<div class="wrap">
	<h2><?php echo __('imgResultsFromReferer','imgResultsFromReferer') . ': ' . __('setup','imgResultsFromReferer'); ?></h2>
	
	<form method="post" action="options.php">
		<?php settings_fields( 'imgResultsFromReferer-settings-group' ); ?>
		<?php do_settings_sections( 'imgResultsFromReferer-settings-group' ); ?>
		<table class="form-table">
			<tr valign="top">
			<th scope="row"><?php echo __('thumbnailSize','imgResultsFromReferer'); ?></th>
			<td><input type="number" name="imgResultsFromReferer_thumbnailWidth" value="<?php echo esc_attr( get_option('imgResultsFromReferer_thumbnailWidth') ); ?>" placeholder="150" /> x <input type="number" name="imgResultsFromReferer_thumbnailHeight" value="<?php echo esc_attr( get_option('imgResultsFromReferer_thumbnailHeight') ); ?>" placeholder="150" /></td>
			</tr>
			<tr valign="top">
			<th scope="row"><?php echo __('amountLastClicks','imgResultsFromReferer'); ?></th>
			<td><input type="number" name="imgResultsFromReferer_amountLastClicks" value="<?php echo esc_attr( get_option('imgResultsFromReferer_amountLastClicks') ); ?>" placeholder="20" /></td>
			</tr>
			<tr valign="top">
			<th scope="row"><?php echo __('amountMonthlyClicks','imgResultsFromReferer'); ?></th>
			<td><input type="number" name="imgResultsFromReferer_amountMonthlyClicks" value="<?php echo esc_attr( get_option('imgResultsFromReferer_amountMonthlyClicks') ); ?>" placeholder="5" /></td>
			</tr>
			<tr valign="top">
			<th scope="row"><?php echo __('tableName','imgResultsFromReferer'); ?></th>
			<td><input type="text" name="imgResultsFromReferer_tableName" value="<?php echo esc_attr( get_option('imgResultsFromReferer_tableName') ); ?>" placeholder="statpress" /></td>
			</tr>
			<tr valign="top">
			<th scope="row"><?php echo __('tableCol','imgResultsFromReferer'); ?></th>
			<td><input type="text" name="imgResultsFromReferer_tableCol" value="<?php echo esc_attr( get_option('imgResultsFromReferer_tableCol') ); ?>"  placeholder="referrer" /></td>
			</tr>
			<tr valign="top">
			<th scope="row"><?php echo __('tableColTime','imgResultsFromReferer'); ?></th>
			<td><input type="text" name="imgResultsFromReferer_tableColTime" value="<?php echo esc_attr( get_option('imgResultsFromReferer_tableColTime') ); ?>"  placeholder="timestamp" /></td>
			</tr>
			<tr valign="top">
			<th scope="row"><?php echo __('tableGroupBy','imgResultsFromReferer'); ?></th>
			<td><input type="text" name="imgResultsFromReferer_tableGroupBy" value="<?php echo esc_attr( get_option('imgResultsFromReferer_tableGroupBy') ); ?>"  placeholder="ip" /></td>
			</tr>
			<tr valign="top">
			<th scope="row"><?php echo __('string2kill','imgResultsFromReferer'); ?></th>
			<td><input type="text" name="imgResultsFromReferer_string2kill" value="<?php echo esc_attr( get_option('imgResultsFromReferer_string2kill') ); ?>"  placeholder="string2kill" /></td>
			</tr>
			<tr valign="top">
			<th scope="row"><?php echo __('static','imgResultsFromReferer'); ?></th>
			<?php 
				( get_option('imgResultsFromReferer_static'))? $staticChecked = 'checked="checked"': $staticChecked = '';
			?>
			
			<td><input type="checkbox" name="imgResultsFromReferer_static" <?php echo $staticChecked; ?> /> <?php echo WP_CONTENT_URL; ?> </td>
			</tr>
		</table>
		
		<?php submit_button(); ?>
	
	</form>
	</div>
	<?php
	}
}
new options_page;


function imgResultsFromReferer_plugin_action_links( $links) 
{
	$links[] = '<a href="'. get_admin_url(null, 'options-general.php?page=imgResultsFromReferer') .'">'  . __('setup','imgResultsFromReferer') . '</a>';
   
	return $links;
}

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'imgResultsFromReferer_plugin_action_links' );



function imgResultsFromReferer_plugin_row_meta($links, $file) 
{

	if ($file == plugin_basename(__FILE__)) {
		$links[] = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=N44CVCHAWSL7E">' . __('donate','imgResultsFromReferer') . '</a>';
	}
	return $links;
}

add_filter( 'plugin_row_meta','imgResultsFromReferer_plugin_row_meta', 10, 2);



 
/**
 * Shows last entries 
 */
function lastClicks() 
{
 	global $wpdb;
	
 	echo '<h1>' . __('lastClicks','imgResultsFromReferer') .'</h1>';
	// get options from setup
	$tableName = $wpdb->prefix . get_option('imgResultsFromReferer_tableName');
	$tableCol = get_option('imgResultsFromReferer_tableCol');
	$limit = get_option('imgResultsFromReferer_amountLastClicks');
	$tableColTime = get_option('imgResultsFromReferer_tableColTime');
	$tableGroupBy = get_option('imgResultsFromReferer_tableGroupBy');
	
	$query = $wpdb->get_results(
		"
		SELECT 
			`$tableCol` AS `referrer`
		FROM	 
			`$tableName` 
		WHERE 
			`$tableCol` LIKE '%imgres%'
		AND	
			`$tableCol` LIKE '%.google.%'
		GROUP BY
			`$tableGroupBy`
		ORDER BY 
			`$tableColTime`
		DESC	
		LIMIT 
			0,$limit
		");
		
	if ($query) {
	
		foreach ($query as $picRef){
			$temp = extractInfo($picRef->referrer);
			
			if ($temp) {
				echo  '<div class="wrapOverlay">' . buildPic($temp) . '</div>';
			}	else {
				echo '<div class="wrapOverlay"><div class="foreignPic">' . __('something fishy','imgResultsFromReferer') . '<br /><em>' . $picRef->referrer . '</em></div></div>';
	
			}
		}	
	} else {
		echo '<a href="' 
		. get_admin_url(null, "/options-general.php?page=imgResultsFromReferer") . '">' 
		. __('setupFirst','imgResultsFromReferer') 
		. '</a>';
	} 
}

/**
 * Shows all entries 
 */
function rankingTotal() 
{ 
	$pic2rank = picQuery();
	
	foreach($pic2rank as $key => $val) {
		$temp['imgurl'] = $key;
		$searchTerms = '';
		
		if(array_key_exists('q',$val)){
			$searchTerms = sortSearchTerms($val['q']);
		}
		
		$count += $val['count'];
		
		$buffer .=  '<div class="wrapOverlay">' 
		. $searchTerms 
		. buildPic($temp) 
		. '<div class="overlay"><h2>' 
		. $val['count'] 
		.'</h2></div></div>' 
		. PHP_EOL;
	}
	
 	echo '<h1>' . __('rankingTotal','imgResultsFromReferer') . '</h1><h2>' . __('totalCount','imgResultsFromReferer') . $count . ' / ' .  sizeOf($pic2rank) . __('totalPics','imgResultsFromReferer') . '</h2>';
	echo $buffer; 

}

/**
 * Shows entries monthly ranked
 */
function rankingMonthly() 
{
 	global $wpdb;
	
	echo '<h1>' . __('rankingMonthly','imgResultsFromReferer') .'</h1>';
	
	$monthCount = 0;
	
	// since how many months this statistic counts
	$pic2rank = array();
	$tableName = $wpdb->prefix .get_option('imgResultsFromReferer_tableName');
	$tableCol = get_option('imgResultsFromReferer_tableCol');
	$tableColTime = get_option('imgResultsFromReferer_tableColTime');
	$tableGroupBy = get_option('imgResultsFromReferer_tableGroupBy');
	
	$sql = "
		SELECT 
			MIN(`$tableColTime`) AS `start` 
		FROM	 
			`$tableName` 
		WHERE 
			`$tableCol` LIKE '%imgres%'
		AND	
			`$tableCol` LIKE '%.google.%'
		";
	$query = $wpdb->get_results( $sql );

	// pretty cool interval generator
	$datetime1 = new DateTime( $query[0]->start);
	$datetime2 = new DateTime( "now");
	$interval = $datetime1->diff( $datetime2);

	$months = $interval->format('%m');
	$years = $interval->format('%y');
	
	$months += $years * 12; 
	
	$localLanguage = WPLANG;
	
	if (WPLANG == 'de_DE') {
		$localLanguage = 'de_DE.UTF8';
	} 
	setlocale(LC_ALL, $localLanguage);
	
	// first == initial month 
	if ($months == 0) $months = 1;
	
	for ($i = 0; $i < $months; $i++) { 	
		
		$pic2rank = picQuery( $i);
		
		
		if ($pic2rank) {
			echo '<hr style="clear:both;" />
			<h3>' 
			. strftime ('%B %Y', mktime(0, 0, 0, date("m") - $i, date("d"),   date("Y")) )
			.'</h3><h4>' . __('monthTotal','imgResultsFromReferer') . $pic2rank['monthTotal'] . __('sepNumbers','imgResultsFromReferer') . $pic2rank['monthDifferent'] . __('monthDifferent','imgResultsFromReferer') . '</h4>'
			. PHP_EOL;
			
			foreach($pic2rank as $key => $val) {
				if (strpos($key, 'monthTotal') !== 0 && strpos($key, 'monthDifferent') !== 0) {
			
					$temp['imgurl'] = $key;
					$searchTerms = '';
				
					if(array_key_exists('q', $val)){
						$searchTerms = sortSearchTerms($val['q']);
					}
					echo '<div class="wrapOverlay">' . $searchTerms . buildPic($temp) . '<div class="overlay"><h2><div style="float:left">' . $val['count'] .'</div><div style="float:right">'  . round(100/$pic2rank['monthTotal']*$val['count']) . '%</div></h2></div></div>';
				}
			}	
		}
	}
}

  
// HELPER
 
/**
 * Return all GET-params && removes domain origin
 *
 * @param String HTTP_REFERER 
 * @return Array
 */
function extractInfo($_picRef) 
{
	$buffer = array();
	$data2convert = urldecode($_picRef);
	
	// own domain or prior used domain
	// thumbnail generation will otherwise fail
		
	$removeArray = array( '&amp;', get_bloginfo('wpurl'));
	$replaceWith = array( '&', '');
	
	$removeOptionArray = explode(',', get_option('imgResultsFromReferer_string2kill'));
	$removeArray = array_merge($removeArray, $removeOptionArray);

	foreach($removeOptionArray as $val) {
		array_push($replaceWith, '');
	}
	
	$data2convert = str_replace( $removeArray, $replaceWith, $data2convert);
	
	if (strpos($data2convert,'?') != 0) {
		$explode1 = explode('?', $data2convert);

		if (strpos($explode1[1],'&') != 0) {
			$explode2 = explode('&',$explode1[1]);
	
			foreach($explode2 as $attributes) {
	
				if (strpos($attributes,'=') != 0) {
					$attr = explode('=', $attributes);
					
					if ($attr[1]) {
						$buffer[$attr[0]] = $attr[1];
					}	
				}
			}
		}
		
		return $buffer;
	}
	
	return false;
}

/**
 * Return HTML IMG or placeholder DIV
 *
 * @param Array 
 * @return String HTML
 */
function buildPic($_picInfo) 
{
	// remove pictures from other domains
	// thumbnail generation will otherwise fail
	$pos = strpos($_picInfo['imgurl'], 'http');

	if ($pos !== 0) {
		$countReplacement = 0;
		$thumbnail = preg_replace(
			'(-[0-9]{3,4}x[0-9]{3,4}(\.jpg|\.png|\.gif))', 
			'-' . get_option('imgResultsFromReferer_thumbnailWidth') . 'x' . get_option('imgResultsFromReferer_thumbnailHeight') . '$1',  
			$_picInfo['imgurl'], 
			-1, 
			$countReplacement
		);
		
		if ($countReplacement == 0) {
			$newSize = get_option('imgResultsFromReferer_thumbnailWidth') . 'x' . get_option('imgResultsFromReferer_thumbnailHeight');
			 $thumbnail = str_replace(
				array(	
					'.jpg', 
					'.gif', 
					'.png'
				), 
				array (
					'-' . $newSize . '.jpg', 
					'-' . $newSize . '.gif', 
					'-' . $newSize . '.png', 
				),	
				$_picInfo['imgurl']
			);
		} 

		// From URL to file://
		$getMyAdmin = str_replace(get_bloginfo('wpurl'), '', get_admin_url( )); 
		
		$path = str_replace($getMyAdmin . 'admin.php', '', $_SERVER['SCRIPT_FILENAME']);
		$urlPath =  get_bloginfo('wpurl');

		if (get_option('imgResultsFromReferer_static') == "on") {
			
			if (strpos($_picInfo['imgurl'], '/wp-content/uploads') !== 0) {
				$path .= '/wp-content/uploads';
				$urlPath = get_bloginfo('wpurl') . '/wp-content/uploads';
			}	
		}
		
		if (!is_file($path . urldecode($thumbnail))) {
	
			return '<img title="' .  $thumbnail . '" src="' . $urlPath . $_picInfo['imgurl'] . '" />'  ; 
		} else {
		
			return '<img title="' .  $thumbnail . '" src="' . $urlPath . urldecode($thumbnail) . '" />'  ; 
		}
	} else {
	
		return '<div class="foreignPic">' . __('foreignPic','imgResultsFromReferer') . '<br /><em>' . $_picInfo['imgurl'] . '</em></div>';
	}
	
	return false;
}

/**
 * Return numeric sorted picture array
 *
 * @param INT _monthCount
 * @param BOOLEAN _searchTerm
 * @return Array
 */
function picQuery($_monthCount = false, $_searchTerm = false) 
{
	global $wpdb;

	$monthTotal = $monthDifferent = 0;
	$pic2rank = array();
	$tableName = $wpdb->prefix .get_option('imgResultsFromReferer_tableName');
	$tableCol = get_option('imgResultsFromReferer_tableCol');
	$monthLimit = get_option('imgResultsFromReferer_amountMonthlyClicks');
	$tableColTime = get_option('imgResultsFromReferer_tableColTime');
	$tableGroupBy = get_option('imgResultsFromReferer_tableGroupBy');
	
	$sql = "
		SELECT 
			`$tableCol` AS `referrer`
		FROM	 
			`$tableName` 
		WHERE 
			`$tableCol` LIKE '%imgres%'
		AND	
			`$tableCol` LIKE '%.google.%'
		";
	
	if ($_monthCount !== false) {
	
		$monthNumber = date('n') - $_monthCount;
		$calcYear = date('Y');
		
		while ($monthNumber < 1) {
			$monthNumber += 12;
			$calcYear--; 
		}
		
		$sql .= " 
			AND EXTRACT(MONTH FROM `$tableColTime`) = $monthNumber
			AND EXTRACT(YEAR FROM `$tableColTime`) = $calcYear
		";
	}
	
	$sql .= " GROUP BY `$tableGroupBy`";
	
	$query = $wpdb->get_results( $sql );
	
	foreach ($query as $picRef){
		$buffer = extractInfo($picRef->referrer);
	
		if (array_key_exists('imgurl',$buffer)) {
		
			if( 	strpos($buffer['imgurl'],'.jpg') != 0
				|| 	strpos($buffer['imgurl'],'.gif') != 0
				||	strpos($buffer['imgurl'],'.png') != 0
				) {
				@$pic2rank[$buffer['imgurl']]['count'] += 1;
				
				if (array_key_exists('q',$buffer)) {
					@$pic2rank[$buffer['imgurl']]['q'][] = $buffer['q'];
				}
			}
		}
		empty($buffer);
	}	

	$sortArray = array();
	
	foreach($pic2rank as $key => $array) {
		$sortArray[$key] = $array['count'];
		$monthTotal += $array['count'];
	}

	array_multisort($sortArray, SORT_DESC, SORT_NUMERIC, $pic2rank); 
	
	if ($_monthCount !== false) {
		$monthDifferent = sizeOf($pic2rank);
		$pic2rank = array_slice($pic2rank, 0, $monthLimit);
		$pic2rank['monthTotal'] = $monthTotal;
		$pic2rank['monthDifferent'] = $monthDifferent;
	}
	
	return $pic2rank;
}

/**
 * Return HTML onMouseOver presentation of search terms sorted numeric
 *
 * @param array _searchTerms
 * @return string
 */
function sortSearchTerms($_searchTerms)
{
	$buffer = '';
	$returnValue = array();
	
	foreach ($_searchTerms as $val) {
		@$returnValue[$val] += 1;
	}
	
	$sortArray = array();
	
	foreach($returnValue as $key => $array) {
		$sortArray[$key] =  $array[0];
	}

	array_multisort($sortArray, SORT_ASC, SORT_NUMERIC, $returnValue); 
	
	arsort($returnValue);
	
	$buffer = '<ul>';
	
	foreach($returnValue as $key => $val) {
		$buffer .= '<li>' . $key. ' (' . $val .')</li>';
	}
	$buffer .= '</ul>';
	
	return $buffer;
}
?>