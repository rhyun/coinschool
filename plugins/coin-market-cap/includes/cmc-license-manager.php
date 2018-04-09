<?php


if ( ! class_exists( 'CMC_License_Manager' ) ) {

    class CMC_License_Manager {
      /**
       * The API endpoint. Configured through the class's constructor.
       *
       * @var String  The API endpoint.
       */
      private $api_endpoint;

      /**
       * The product id (slug) used for this product on the License Manager site.
       * Configured through the class's constructor.
       *
       * @var int     The product id of the related product in the license manager.
       */
      private $product_id;

      /**
       * The name of the product using this class. Configured in the class's constructor.
       *
       * @var int     The name of the product (plugin / theme) using this class.
       */
      private $product_name;

      /**
       * The type of the installation in which this class is being used.
       *
       * @var string  'theme' or 'plugin'.
       */
      private $type;

      /**
       * The text domain of the plugin or theme using this class.
       * Populated in the class's constructor.
       *
       * @var String  The text domain of the plugin / theme.
       */
      private $text_domain;

      /**
       * @var string  The absolute path to the plugin's main file. Only applicable when using the
       *              class with a plugin.
       */
      private $plugin_file;


    public function __construct( $product_id, $product_name, $text_domain, $api_url='',
                                 $type = 'theme', $plugin_file = '' ) {
            // Store setup data
            $this->product_id = $product_id;
            $this->product_name = $product_name;
            $this->text_domain = $text_domain;
            $this->api_endpoint = $api_url;
            $this->type = $type;
            $this->plugin_file = $plugin_file;

            // Add actions required for the class's functionality.
            // NOTE: Everything should be done through actions and filters.
            if ( is_admin() ) {
                // Add the menu screen for inserting license information
                add_action( 'admin_menu', array( $this, 'add_license_settings_page' ) );
                add_action( 'admin_init', array( $this, 'add_license_settings_fields' ) );
                // Add a nag text for reminding the user to save the license information
                add_action( 'admin_notices', array( $this, 'show_admin_notices' ) );
            }
        }

     /**
     * Creates the settings items for entering license information (email + license key).
     */
    public function add_license_settings_page() {
        $title = sprintf( __( '%s', $this->text_domain ),'Auto Update Settings');

        add_submenu_page(
            'edit.php?post_type=cmc',
            $title,
            $title,
            'read',
            $this->get_settings_page_slug(),
            array( $this, 'render_licenses_menu' )
        );
    }


    /**
   * Creates the settings fields needed for the license settings menu.
   */
  public function add_license_settings_fields() {
      $settings_group_id = $this->product_id . '-license-settings-group';
      $settings_section_id = $this->product_id . '-license-settings-section';

      register_setting( $settings_group_id, $this->get_settings_field_name() );

      add_settings_section(
          $settings_section_id,
          __( 'Add Your Details', $this->text_domain ),
          array( $this, 'render_settings_section' ),
          $settings_group_id
      );

      add_settings_field(
          $this->product_id . '-uname',
          __( 'Enter Your Envato UserName', $this->text_domain ),
          array( $this, 'render_username_settings_field' ),
          $settings_group_id,
          $settings_section_id
      );

      add_settings_field(
          $this->product_id . '-pkey',
          __( 'Enter Your Envato Purchased key', $this->text_domain ),
          array( $this, 'render_license_key_settings_field' ),
          $settings_group_id,
          $settings_section_id
      );
  }

  /**
   * Renders the description for the settings section.
   */
  public function render_settings_section() {
      _e( 'Insert below mentioned information to enable automatic updates.', $this->text_domain);
  }

  /**
   * Renders the settings page for entering license information.
   */
  public function render_licenses_menu() {
      $title = sprintf( __( '%s Plugin Automatic Updates Settings', $this->text_domain ), $this->product_name );
      $settings_group_id = $this->product_id . '-license-settings-group';

      ?>
          <div class="wrap">
              <form action='options.php' method='post'>

                  <h2><?php echo $title; ?></h2>

                  <?php
                      settings_fields( $settings_group_id );
                      do_settings_sections( $settings_group_id );
                      submit_button();
                  ?>

              </form>
          </div>
      <?php
  }

        /**
       * Renders the email settings field on the license settings page.
       */
      public function render_username_settings_field() {
          $settings_field_name = $this->get_settings_field_name();
          $options = get_option( $settings_field_name );
          ?>
              <input type='text' name='<?php echo $settings_field_name; ?>[uname]'
                 value='<?php echo isset($options['uname'])?$options['uname']:''; ?>' class='regular-text'>
          <?php
      }

      /**
       * Renders the license key settings field on the license settings page.
       */
      public function render_license_key_settings_field() {
          $settings_field_name = $this->get_settings_field_name();
          $options = get_option( $settings_field_name );
          ?>
              <input type='text' name='<?php echo $settings_field_name; ?>[pkey]'
                 value='<?php echo isset($options['pkey'])?$options['pkey']:''; ?>' class='regular-text'>
          <?php
      }

      /**
       * @return string   The name of the settings field storing all license manager settings.
       */
      protected function get_settings_field_name() {
          return $this->product_id;
      }

      /**
       * @return string   The slug id of the licenses settings page.
       */
      protected function get_settings_page_slug() {
          return $this->product_id;
      }

      /**
   * If the license has not been configured properly, display an admin notice.
   */
  public function show_admin_notices() {
      $options = get_option( $this->get_settings_field_name() );

      if ( !$options || ! isset( $options['uname'] ) || ! isset( $options['pkey'] ) ||
          $options['uname'] == '' || $options['pkey'] == '' ) {

          $msg = __( 'Please enter your UserName and Purchaed key to enable Automatic updates to %s.', $this->text_domain );
          $msg = sprintf( $msg, $this->product_name );
          ?>  <div class="update-nag">
                  <p><?php echo $msg; ?></p>
                  <p><a href="<?php echo admin_url( 'edit.php?post_type=cmc&page=' . $this->get_settings_page_slug() ); ?>">
                          <?php _e( 'Complete the setup now.', $this->text_domain ); ?>
                      </a></p>
              </div>
          <?php
      }
  }

}




}
