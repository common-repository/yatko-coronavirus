<?php

require_once plugin_dir_path( __FILE__ ) . './class.feedSource.php';

class CoronaFeedWidget extends WP_Widget
{
    /** @var \CoronaFeedSource */
    public $source;

    public function __construct()
    {
        parent::__construct(
            'coronafeed',
            __('Coronavirus Spread Feed', 'text_domain'),
            [
                'customize_selective_refresh' => true,
            ]
        );

        // Precache feed file
        $this->source = new CoronaFeedSource();
        $this->source->resolveFeedJSON();
    }

    public static function privacyPolicy() {
        $content = sprintf(
            __( 'We may collect information that your browser sends whenever you visit our Service or when you access the Service by or through a mobile device ("Usage Data").
                This Usage Data may include information such as your computer\'s Internet Protocol address (e.g. IP address), browser type, browser version, the pages of our Service that you visit, the time and date of your visit, the time spent on those pages, unique device identifiers and other diagnostic data.
                When you access the Service by or through a mobile device, this Usage Data may include information such as the type of mobile device you use, your mobile device unique ID, the IP address of your mobile device, your mobile operating system, the type of mobile Internet browser you use, unique device identifiers and other diagnostic data.
 
                Full privacy policy text can be found at <a href="%s" target="_blank">here</a>.',
                'my_plugin_textdomain'
            ),
            'https://quarantine.country/coronavirus/api/privacy.html'
        );

        wp_add_privacy_policy_content(
            'Coronavirus Plugin',
            wp_kses_post( wpautop( $content, false ) )
        );
    }

    public static function register()
    {
        register_widget(static::class);
    }

    public static function ajaxHandler() {
        $widget = new static();
        $selectedRegion = sanitize_text_field($_REQUEST['region']);
        $regionFeed = $widget->source->resolveRegionData($selectedRegion);

        echo json_encode(array(
            'region' => $widget->prettyPrintKeyName($selectedRegion),
            'total_cases' => number_format_i18n($regionFeed['total_cases'], 0),
            'active_cases' => number_format_i18n($regionFeed['total_cases'] - $regionFeed['recovered'] - $regionFeed['deaths'], 0),
            'recovered' => number_format_i18n($regionFeed['recovered'], 0),
            'deaths' => number_format_i18n($regionFeed['deaths'], 0),
            'death_ratio' => number_format_i18n($regionFeed['death_ratio'], 2),
            'recovery_ratio' => number_format_i18n($regionFeed['recovery_ratio'], 2),
        ));

        wp_die();
    }

    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;

        $instance['title'] = isset($new_instance['title']) ? wp_strip_all_tags($new_instance['title']) : '';
        $instance['default_region'] = isset($new_instance['default_region']) ? wp_strip_all_tags($new_instance['default_region']) : '';
        $instance['display_title'] = isset($new_instance['display_title']) ? 1 : false;

        return $instance;
    }

    public function form($instance)
    {
        // Set widget defaults
        $defaults = [
            'title' => '',
            'default_region' => '',
            'display_title' => true,
            'display_region' => true,
        ];

        extract(wp_parse_args(( array )$instance, $defaults), 1);

        $regions = [];
        $feed = $this->source->resolveFeedJSON();

        foreach ($feed['regions'] as $key => $value) {
            $regions[$key] = $this->prettyPrintKeyName($key);
        }

        include(plugin_dir_path(__FILE__) . '/views/feed-admin.php');
    }

    /**
     * @param string $key
     * @return string
     */
    public function prettyPrintKeyName($key) {
        switch ($key) {
            case 'us':
            case 'usa':
                return 'USA';

            case 's. korea':
                return 'South Korea';

            default:
                return implode(
                    ' ',
                    array_map(
                        static function ($word) {
                            return ucfirst($word);
                        },
                        explode(' ', $key)
                    )
                );
        }
    }

    public function widget($args, $instance)
    {
        wp_enqueue_style( 'quarantine', plugins_url('/assets/styles/feed.css', __FILE__), [], false);
        wp_enqueue_script( 'quarantine', plugins_url('/assets/js/feed.js', __FILE__), ['jquery'], false);
        wp_localize_script( 'quarantine', 'xhr', array( 'url' => admin_url( 'admin-ajax.php' ) ) );

        extract($args, 1);

        $title = isset($instance['title']) ? apply_filters('widget_title', $instance['title']) : '';
        $defaultRegion = isset($instance['default_region']) ? $instance['default_region'] : 'us';
        $selectedRegion = isset($_GET['quarantine_region']) ? wp_strip_all_tags($_GET['quarantine_region']) : $defaultRegion;
        $displayTitle = isset($instance['display_title']) ? $instance['display_title'] === 1 : true;

        $feed = $this->source->resolveFeedJSON();
        $regionFeed = $this->source->resolveRegionData($selectedRegion);

        $regionName = $this->prettyPrintKeyName($selectedRegion);

        $templatePath = locate_template('inc/quarantine-feed.php', false);

        if ($templatePath === '') {
            include(plugin_dir_path(__FILE__) . '/views/feed.php');
        } else {
            get_template_part('inc/quarantine', 'feed');
        }
    }
}