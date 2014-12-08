<?php
class SoftpediaWidget extends WP_Widget
{
	
	function SoftpediaWidget()
	{
		$widget_options = array(
			'classname'		=>	'softpedia-widget',
			'description'	=>	'A widget to show a Softpedia.com\'s RSS feed'
		);

		parent::WP_Widget(false, 'Softpedia RSS Widget', $widget_options);
	}
	
	function widget($args, $instance)
	{
		extract($args, EXTR_SKIP );
		$sftpd_feed = ( $instance['sftpd_feed'] ) ? $instance['sftpd_feed'] : 'No Softpedia RSS Feed';
		$sftpd_number = ( $instance['sftpd_number'] ) ? $instance['sftpd_number'] : '3';

        echo $before_widget;
		echo $before_title . $instance['title'] . $after_title;

		if ($sftpd_feed != 'No Softpedia RSS Feed')
		{
			$feed_array = softpedia_scrubber(
				$sftpd_feed,
				40
				);

			softpedia_echo(
				$feed_array,
				$sftpd_number, 
				$this->number //$this->number is the Widgets number that WP generate
				);
		}
        echo $after_widget;
	}

    function update($new_instance, $old_instance) {
        $instance = array();

        $instance['title'] = strip_tags(trim($new_instance['title']));
        $instance['sftpd_feed'] = strip_tags($new_instance['sftpd_feed']);
        $instance['sftpd_number'] = absint(strip_tags($new_instance['sftpd_number']));

        if($instance['sftpd_number'] > 38)
            $instance['sftpd_number'] = 38;

        return $instance;
    }

	function form($instance)
	{
        $instance = wp_parse_args(
            (array) $instance,
            array(
                'title' => 'Latest on Softpedia',
                'sftpd_number' => 3,
                'sftpd_feed' => 'http://news.softpedia.com/newsRSS/Global-0.xml',
            )
        );
		$feeds = array (
			'Webscripts' => 'http://webscripts.softpedia.com/backend.xml',
			'Drivers' => 'http://drivers.softpedia.com/backend.xml',			
			'Linux' => 'http://linux.softpedia.com/backend.xml',
			'Mac' => 'http://mac.softpedia.com/backend.xml',
			'Mac (only free software)' => 'http://mac.softpedia.com/backend.xml?_free',
			'Windows' => 'http://www.softpedia.com/backend.xml',
			'Windows (only free software)' => 'http://www.softpedia.com/backend.xml?_free',			
			'Games' => 'http://games.softpedia.com/backend.xml',
			'Games (free only)' => 'http://games.softpedia.com/backend.xml?_free',
			'Mobile - Phones' => 'http://mobile.softpedia.com/rss.php',
			'Mobile - Tablets' => 'http://mobile.softpedia.com/rss.php?t=1',
			'Mobile - Windows Phone Apps' => 'http://mobile.softpedia.com/rssapps.php?p=3',
			'Mobile - iOS Apps' => 'http://mobile.softpedia.com/rssapps.php?p=1',
			'Mobile - Android Apps' => 'http://mobile.softpedia.com/rssapps.php?p=2',
			'Internet Browsers' => 'http://www.softpedia.com/rss-browsers.php',			
			'All News' => 'http://news.softpedia.com/newsRSS/Global-0.xml',
			'Security News' => 'http://news.softpedia.com/newsRSS/Security-5.xml',
			'Webmaster News' => 'http://news.softpedia.com/newsRSS/Webmaster-4.xml',
			'Life and Style News' => 'http://news.softpedia.com/newsRSS/Life-and-Style-175.xml',
			'Oddiverse News' => 'http://news.softpedia.com/newsRSS/Oddiverse-236.xml',
			'World News' => 'http://news.softpedia.com/newsRSS/World-249.xml',			
			'Microsoft News' => 'http://news.softpedia.com/newsRSS/Microsoft-6.xml',
			'Apple News' => 'http://news.softpedia.com/newsRSS/Apple-8.xml',
			'Linux News' => 'http://news.softpedia.com/newsRSS/Linux-7.xml',			
			'Games News' => 'http://news.softpedia.com/newsRSS/Games-9.xml',
			'Technology & Gadgets News' => 'http://news.softpedia.com/newsRSS/Technology-Gadgets-3.xml',
			'Laptops & Tablets News' => 'http://news.softpedia.com/newsRSS/Laptops-Tablets-241.xml',
			'Mobile World News' => 'http://news.softpedia.com/newsRSS/Mobile-World-10.xml',
			'Photo News' => 'http://news.softpedia.com/newsRSS/Photo-244.xml',
			'3D Printing News' => 'http://news.softpedia.com/newsRSS/3D-Printing-243.xml',
			'Wearable Tech News' => 'http://news.softpedia.com/newsRSS/Wearable-Tech-247.xml',
			'Science News' => 'http://news.softpedia.com/newsRSS/Science-2.xml',
			'Green News' => 'http://news.softpedia.com/newsRSS/Green-215.xml',			
			'Editorials' => 'http://news.softpedia.com/newsRSS/Editorials-14.xml',
			'Interviews' => 'http://news.softpedia.com/newsRSS/Interviews-15.xml',
			'Reviews - All' => 'http://news.softpedia.com/newsRSS/Reviews-12.xml',
			'Reviews - Compared' => 'http://news.softpedia.com/newsRSS/Reviews-12.xml',
			'Reviews - Gadget' => 'http://news.softpedia.com/newsRSS/Gadget-Reviews-32.xml',
			'Reviews - Windows' => 'http://news.softpedia.com/newsRSS/Windows-software-reviews-130.xml',			
			'Reviews - Linux' => 'http://news.softpedia.com/newsRSS/Linux-software-reviews-132.xml',
			'Reviews - Mac' => 'http://news.softpedia.com/newsRSS/Mac-software-reviews-131.xml',
			'Reviews - Games' => 'http://news.softpedia.com/newsRSS/Game-reviews-133.xml',
			'Reviews - Mobile Phones' => 'http://news.softpedia.com/newsRSS/Mobile-phones-reviews-134.xml',
			'Reviews - Mobile Apps' => 'http://news.softpedia.com/newsRSS/Mobile-Software-Reviews-209.xml'
			);
        ?>
	    <label for="<?php echo $this->get_field_id('title');?>">
			Title:<br />
			<input
				id="<?php echo $this->get_field_id('title');?>"
				name="<?php echo $this->get_field_name('title');?>"
				value="<?php echo esc_attr($instance['title']) ?>"
			/><br />
		</label>

		<label for="<?php echo $this->get_field_id('sftpd_feed');?>">RSS URL:</label><br />
        <select id="<?php echo $this->get_field_id('sftpd_feed');?>"
                name="<?php echo $this->get_field_name('sftpd_feed');?>">
            <?php
                foreach($feeds as $name=>$feed) {
                    echo '<option value="'.$feed.'"';
                    echo ($instance['sftpd_feed'] == $feed)?' selected="selected"':'';
                    echo '>'.$name.'</option>';
                }
            ?>
        </select>

		<label for="<?php echo $this->get_field_id('sftpd_number');?>">
			Number to display:<br />
			<input
				id="<?php echo $this->get_field_id('sftpd_number');?>"
				name="<?php echo $this->get_field_name('sftpd_number');?>"
				value="<?php echo esc_attr($instance['sftpd_number']) ?>"
				size="2" maxlength="2"
			/><br />
		</label>
    <?php
	}
}
