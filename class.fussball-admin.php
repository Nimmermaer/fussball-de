<?php

/**
 * Created by PhpStorm.
 * User: Cox
 * Date: 28.08.2016
 * Time: 14:10
 */
class Fussball_De_Admin
{
    /**
     * @var bool
     */
    private static $initiated = false;

    /**
     *
     */
    public static function init()
    {
        if ( ! self::$initiated) {
            self::init_hooks();
            self::configuration();
        }
    }

    /**
     *
     */
    public static function init_hooks()
    {
        self::$initiated = true;
        add_action('admin_enqueue_scripts', function()
            {
                if (get_post_type() == 'fussball') {
                    $src['bootstrap'] = "/wp-content/plugins/fussball-de/res/backend/css/bootstrap.min.css";
                    $src['styles'] = "/wp-content/plugins/fussball-de/res/backend/css/styles.css";
                    $handle = "fussball_form_admin";
                    foreach ($src as $key => $source) {
                        wp_enqueue_style($handle . '-' . $key, $source, array(), false, 'all');
                    }
                    $js['bootstrap'] = "/wp-content/plugins/fussball-de/res/backend/js/bootstrap.min.js";
                    $js['clipboard'] = "/wp-content/plugins/fussball-de/res/backend/js/clipboard.min.js";
                    $js['styles'] = "/wp-content/plugins/fussball-de/res/backend/js/scripts.js";
                    $handle = "fussball_admin_js";
                    foreach ($js as $key => $jssource) {
                        wp_enqueue_script($handle . '-' . $key, $jssource, array(), false, true);
                    }
                }
            }
        );

        add_action("admin_init", function () {
            add_meta_box("credits_meta", "Tabellen Einstellungen", function () {
                global $post;
                if ($post) {
                    $custom = get_post_custom($post->ID);
                    if (array_key_exists('widgetid', $custom)) {
                        $widgetid = $custom["widgetid"][0];
                    } else {
                        $widgetid = '';
                    }
                    $widgetshortcode = '[fussball id=' . $post->ID . ']';
                }

                ?>
                <p><label for="widgetid">Widget Id:</label><br/>
                 <div class="form-group">
                    <input size="50" name="widgetid" id="widgetid" class="form-control"
                           value="<?php echo $widgetid; ?>"/>
                         <span class="input-group-btn">
                     </span>
                </div>
                <p>
                    <label for="form-<?php echo $widgetid; ?>">Shortcode:</label>
                    <br/>
                <div class="input-group">
                    <input disabled="disabled" size="50" id="form-<?php echo $widgetid; ?>" class="form-control"
                           value="<?php echo $widgetshortcode; ?>"/>
                         <span class="input-group-btn">

                    <button class="btn btn-secondary form-btn" data-clipboard-target="#form-<?php echo $widgetid; ?>" type="button"
                            data-toggle="tooltip" data-placement="top" title="Kopieren! Und in eine beliebige Seite einfügen."><i
                            class="glyphicon glyphicon-paperclip"></i></button>
                    </span>
                </div>
               </p>
                <?php
            }
                , "fussball", "normal", "low");

        });

        add_filter('plugin_action_links', array('Fussball_De_Admin', 'plugin_action_links'), 10, 2);

        add_action('save_post', function () {
            global $post;

            if ($post) {
                update_post_meta($post->ID, "widgetid", $_POST["widgetid"]);
            }

        });

         add_action("manage_posts_custom_column", function ($column) {
             $columns = array(
                "widgetid"  => "Tabelle ID",
                "shortcode" => "Shortcode",
            );

            return $columns;
        }
        );
   }


    public static function configuration()
    {
        $labels = array(
            'name'               => _x('Fussball-Tabellen', 'post type general name'),
            'singular_name'      => _x('Fussball-Tabelle', 'post type singular name'),
            'add_new'            => __('Neue Tabelle anlegen'),
            'add_new_item'       => __('Neue Tabelle anlegen'),
            'edit_item'          => __('Tabelle bearbeiten'),
            'new_item'           => __('Neue Tabelle '),
            'all_items'          => __('Alle Tabellen '),
            'view_item'          => __('Tabelle ansehen'),
            'search_items'       => __('Tabelle durchsuchen'),
            'not_found'          => __('Keine Tabellen gefunden'),
            'not_found_in_trash' => __('Keine Tabellen im Papierkorb gefunden'),
            'parent_item_colon'  => '',
            'menu_name'          => 'Fussball.de Tabelle'
        );
        // Werte des neuen Custom Post Types werden zugewiesen

        $args = array(
            'labels'              => $labels,
            'description'         => 'Hier sind alle Tabellen zu finden',
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => false,
            'show_in_admin_bar'   => false,
            'publicly_queryable'  => false,
            'exclude_from_search' => true,
            'supports'            => array('title'),
            'taxonomies'          => array('post_tag', 'category'),
            'has_archive'         => true,
            'can_export'          => false,
            'menu_position'       => 5,
            'capability_type'     => 'post',
            'menu_icon'           => 'dashicons-editor-table',
            'rewrite'             => array('slug' => 'widgets')
        );
        register_post_type('fussball', $args);

    }

    public static function plugin_action_links($links, $file)
    {
        if ($file == plugin_basename(plugin_dir_url(__FILE__) . '/fussball-de.php')) {
            $links[] = '<a href="' . esc_url(self::get_page_url()) . '">' . esc_html__('Settings', 'fussball') . '</a>';
        }

        return $links;
    }

}