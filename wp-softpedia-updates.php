<?php
/*
Plugin Name: WP Softpedia Updates
Plugin URI: http://github.com/campuscodi/wp-softpedia-updates
Description: A widget that lets you call in various Softpedia.com RSS feeds
Version: 1.0.0
Author: Softpedia
Author URI: http://softpedia.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Copyright 2013  Catalin Cimpanu (email : #@#.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

include_once( 'softpedia-updates-widget.php' );

/* inits */
function softpedia_widget_init()
{
	register_widget("SoftpediaWidget");
}
add_action('widgets_init', 'softpedia_widget_init');


/* Adding style sheet for front end */
function softpedia_add_css()
{
	if (!is_admin())
	{
		//$stylesheet_url  = plugins_url( 'style.css', __FILE__ );
		wp_enqueue_style( 's8_softpedia_stylesheets', plugins_url( '/style.css', __FILE__ ) );
	}
}
add_action('wp_print_styles', 'softpedia_add_css');


// time formatting - twitter style
function softpedia_time_formatter( $date )
{
	$date = strtotime($date);
	$date = human_time_diff($date, date('U'));

	return $date;
}

// scrub the data
function softpedia_scrubber (
		$sftpd_feed,
		$sftpd_number
		)
{
	$feed_array = array();

	include_once(ABSPATH . WPINC . '/rss.php');

	$feed = fetch_rss( $sftpd_feed );

	if ( $feed )
	{

		$items = array_slice($feed->items, 0, $sftpd_number);

		foreach ( $items as $item ) :

			$title = $item['title'];
			$description = $item['description'];
			$link = $item['link'];
			$date = softpedia_time_formatter($item['pubdate']);

			$rm = array(" align='right'", "<br/>"); //setting array of things to remove

			$description = str_replace($rm, "", $description); //remove these items from the feed
			 
			$array_item = array(
				$title,
				$description,
				$link,
				$date
			);

			$feed_array[] = $array_item;

		endforeach;

        $feed_array = jpost_multi_array_sort($feed_array, 3);
        $feed_array = array_reverse($feed_array);

		return $feed_array;
	}
}

function jpost_multi_array_sort($array, $key) {
    $sorter = array();
    $ret = array();
    reset($array);
    foreach($array as $ii => $va) {
        $sorter[$ii] = $va[$key];
    }
    asort($sorter);
    foreach ($sorter as $ii => $va) {
        $ret[$ii] = $array[$ii];
    }
    $array = $ret;
    return $array;
}

// adding the HTML image alt="" property so HTML validators are happier
function softpedia_add_alt( $desc )
{
	preg_match_all('/<img (.*?)\/>/', $desc, $images);
	if(!is_null($images))
	{
		foreach($images[1] as $index => $value)
		{
			if(!preg_match('/alt=/', $value))
			{
				$new_img = str_replace('<img', '<img alt=""', $images[0][$index]);
				$desc = str_replace($images[0][$index], $new_img, $desc);
			}
		}
	}
	return $desc;
}

// actual function that spits out the code
function softpedia_echo (
		$feed_array,
		$sftpd_number, 
		$widget_number
		)
{
	// if there is an array
	if ( $feed_array )
	{ 
		$items = $feed_array;

		echo '<div id="widgetnumber-'.$widget_number.'" class="softpedia-feed softpedia-feed-'.$widget_number.'">';
		echo '<ul>';
		$count = 0;
		foreach ( $items as $item ) :
			
			if ($count < $sftpd_number)
			{

				$title = $item[0];
				$desc = $item[1];
				$link = $item[2];
				$date = $item[3];

				echo '<li class="softpedia-item softpedia-item-'.$widget_number.'">';
					echo '<a href="'.$link.'">';
					echo $title;
					echo '</a>';
					echo '<br>';
					echo softpedia_add_alt($desc);
					echo ' <span>'.$date.' ago</span>';
				echo '</li>'."\r\n";

			}

			$count++;

		endforeach;
		echo '</ul>';
		echo '</div>'."\r\n";
	}
	else
	{
		echo '<div id="softpedia-feed"><li>No Feed to Display</li><li>Check Your Settings</li></div>';
	}
}
