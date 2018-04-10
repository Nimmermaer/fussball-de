<?php

/**
 * Created by PhpStorm.
 * User: Cox
 * Date: 28.08.2016
 * Time: 14:09
 */
class Fussball_De
{
    /**
     * @var bool
     */
    private static $initiated = false;

    /**
     *  init
     */
    public static function init()
    {
        if ( ! self::$initiated) {
            self::init_hooks();
        }
    }

    /**
     * Initializes WordPress hooks
     */
    private static function init_hooks()
    {
        self::$initiated = true;

        add_action('wp_head', function () {
            echo "<script type=\"text/javascript\" src='//www.fussball.de/static/egm//js/widget2.js' ></script>\n";
        });

        add_shortcode('fussball', function ($atts) {

            $content = 'Fehler im Shortcode';
            if (array_key_exists('widgetid', get_post_custom($atts['id']))) {
                $id = get_post_custom($atts['id']) ["widgetid"] [0];
                $random  = mt_rand();
                $content = '';
                $content .= '<div id="widget' . $random . '"></div>';
                $content .= '<script type="text/javascript">';
                $content .= 'new fussballdeWidgetAPI().showWidget("' . 'widget' . $random . '", "' . $id . '"); ';
                $content .= '</script>';

            }
            echo $content;


        });
    }

}