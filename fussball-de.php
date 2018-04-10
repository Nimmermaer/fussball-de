<?php
/*
Plugin Name: Fussball Tabellen
Plugin URI: http://spicherhoehenkicker.de/
Description: Fussball.de mit id wird die Tabelle eingebunden;
Version: 0.45
Author: Michael Blunck
Author URI: http://spicherhoehenkicker.de/
License: GPLv2 or later
Text Domain: fussball
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

define( 'FUSSBALL_VERSION', '0.45' );
define( 'FUSSBALL__MINIMUM_WP_VERSION', '3.2' );
define( 'FUSSBALL__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once( FUSSBALL__PLUGIN_DIR . 'class.fussball-de.php' );

add_action( 'init', array( 'Fussball_De', 'init' ) );

if ( is_admin() ) {
    require_once( FUSSBALL__PLUGIN_DIR . 'class.fussball-admin.php' );
    add_action( 'init', array( 'Fussball_De_Admin', 'init' ) );
}

