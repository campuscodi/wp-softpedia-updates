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
			'Linux' => 'http://linux.softpedia.com/backend.xml',
			'Mac' => 'http://mac.softpedia.com/backend.xml',
			'Windows' => 'http://www.softpedia.com/backend.xml',
			'Drivers' => 'http://drivers.softpedia.com/backend.xml',
			'Games' => 'http://games.softpedia.com/backend.xml',
			'Mobile' => 'http://mobile.softpedia.com/backend.xml',
			'Handheld' => 'http://handheld.softpedia.com/backend.xml',
			'All News' => 'http://news.softpedia.com/newsRSS/Global-0.xml',
			'Security News' => 'http://news.softpedia.com/newsRSS/Security-5.xml',
			'Webmaster News' => 'http://news.softpedia.com/newsRSS/Webmaster-4.xml',
			'Life & Style News' => 'http://news.softpedia.com/newsRSS/Life-and-Style-175.xml',
			'Oddiverse News' => 'http://news.softpedia.com/newsRSS/Oddiverse-236.xml',
			'Microsoft News' => 'http://news.softpedia.com/newsRSS/Microsoft-6.xml',
			'Apple News' => 'http://news.softpedia.com/newsRSS/Apple-8.xml',
			'Linux News' => 'http://news.softpedia.com/newsRSS/Linux-7.xml',			
			'Games News' => 'http://news.softpedia.com/newsRSS/Games-9.xml',
			'Technology and Gadgets News' => 'http://news.softpedia.com/newsRSS/Technology-and-Gadgets-3.xml',
			'Laptops and Tablets News' => 'http://news.softpedia.com/newsRSS/Laptops-Tablets-241.xml',
			'Green News' => 'http://news.softpedia.com/newsRSS/Green-215.xml',
			'Science News' => 'http://news.softpedia.com/newsRSS/Science-2.xml',
			'Telecoms News' => 'http://news.softpedia.com/newsRSS/Telecoms-10.xml',
			'Photo News' => 'http://news.softpedia.com/newsRSS/Photo-244.xml',
			'3D Printing News' => 'http://news.softpedia.com/newsRSS/3D-Printing-243.xml',
			'Wearable Tech News' => 'http://news.softpedia.com/newsRSS/Wearable-Tech-247.xml',			
			'Editorials' => 'http://news.softpedia.com/newsRSS/Editorials-14.xml',
			'Interviews' => 'http://news.softpedia.com/newsRSS/Interviews-15.xml',
			'Reviews' => 'http://news.softpedia.com/newsRSS/Reviews-12.xml'
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
