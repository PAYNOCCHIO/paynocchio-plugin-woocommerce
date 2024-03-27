<?php
/**
 * Creates the submenu item for the plugin.
 *
 * @package Woocommerce_Paynocchio
 */
/**
 * Creates the submenu item for the plugin.
 *
 * Registers a new menu item under 'Tools' and uses the dependency passed into
 * the constructor in order to display the page corresponding to this menu item.
 *
 * @package Woocommerce_Paynocchio
 */
class Paynocchio_Users_Export_Menu {
    /**
     * A reference the class responsible for rendering the submenu page.
     *
     * @var Paynocchio_Users_Export_Page
     * @access private
     */
    private $users_export_page;
    /**
     * Initializes all of the partial classes.
     *
     * @param Paynocchio_Users_Export_Page $users_export_page A reference to the class that renders the
     * page for the plugin.
     */
    public function __construct( $users_export_page ) {
        $this->users_export_page = $users_export_page;
    }
    /**
     * Adds a submenu for this plugin to the 'Tools' menu.
     */
    public function init() {
        add_action( 'admin_menu', array( $this, 'users_export_page' ) );
    }
    /**
     * Creates the submenu item and calls on the Submenu Page object to render
     * the actual contents of the page.
     */
    public function users_export_page() {
        add_menu_page(
            'Paynocchio Export Users',
            'Export Users',
            'manage_options',
            'paynocchio-export-users',
            array( $this->users_export_page, 'render'),
            'dashicons-migrate',
            999
        );
    }
}