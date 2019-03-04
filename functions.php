<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

 
class WPdomaze {
      public function __construct()
      {
      /* ===============================  LE CONSTRUCTEUR =================================================== */ 

        /*------------------------------------------------------------------- *\
        # Suppression de nombreuses functions liées aux hooks de storefront 
        \*------------------------------------------------------------------- */
          add_action( 'init', array($this, 'domaze_remove_default_hooks'), 10);
        /*-------------------------------------------------------------------- *\
        \*-------------------------------------------------------------------- */
              
        /*------------------------------------------------------------------- *\
        # Suppression des 3 Menus de storefront
        \*------------------------------------------------------------------- */
          add_action( 'after_setup_theme', array($this, 'remove_default_menus'), 11);
        /*-------------------------------------------------------------------- *\
        \*-------------------------------------------------------------------- */

          add_theme_support( 'custom-header');
          add_action( 'wp_enqueue_scripts', array($this, 'domaze_enqueue_style'),18);
          add_action( 'wp_enqueue_scripts', array($this, 'domaze_adding_scripts'),19);


        //  add_action( 'after_setup_theme', 'register_primary_menu',10 );
          
        /*------------------------------------------------------------------- *\
        # Création des menus et widgets
        \*------------------------------------------------------------------- */
          /* Création rempli avec les catégories de produits de WooCommerce */     
          add_action( 'storefront_header', array($this, 'domaze_top_menu_categories_woo'),50);
          /* Création des menus paramétrables depuis le back-office/admin de WP */
          add_action( 'init', array($this, 'register_domaze_menus'), 11);
          /* Mise en place des widgets */ 
          add_action('widgets_init', array($this, 'domaze_sidebar_widgets_init'),10);     
        /*-------------------------------------------------------------------- *\
        \*-------------------------------------------------------------------- */  
          
        /*------------------------------------------------------------------- *\
        # Mise en place des éléments du header 
        \*------------------------------------------------------------------- */
          add_action( 'storefront_header', array($this, 'domaze_custom_logo'),10);
          add_action( 'storefront_footer', array($this, 'domaze_personalise_footer'),50);
          add_action( 'storefront_header', array($this, 'domaze_append_wc_cart'),10);
          add_action( 'storefront_header', array($this, 'my_account_menu'), 52);
          add_action( 'storefront_before_content', array($this, 'domaze_woocommerce_breadcrumbs'), 10 );;
        /*-------------------------------------------------------------------- *\
        \*-------------------------------------------------------------------- */ 
          add_action( 'woocommerce_before_cart', array($this, 'add_coupon_notice' ), 60);
          add_action( 'woocommerce_before_checkout_form', array($this, 'add_coupon_notice'),65 );
          add_action( 'woocommerce_before_main_content', array($this, 'domaze_woo_output_content_wrapper'), 10);
          add_action( 'woocommerce_after_main_content', array($this, 'domaze_woo_output_content_wrapper_end'), 10);

        /* ===== FIN DU CONSTRUCTEUR ===================================================================================== */  
      }
  

      /* =================== Suppression des actions liées à plusieurs hooks du header de storefront  ==================== */
      public function domaze_remove_default_hooks() {
          remove_action( 'storefront_header', 'storefront_skip_links', 0);
          remove_action( 'storefront_header', 'storefront_site_branding', 20 );
          remove_action( 'storefront_header', 'storefront_secondary_navigation', 30 );
          remove_action( 'storefront_header', 'storefront_primary_navigation_wrapper', 42 );
          remove_action( 'storefront_header', 'storefront_primary_navigation', 50 );
          remove_action( 'storefront_header', 'storefront_primary_navigation_wrapper_close', 68 );
          remove_action( 'storefront_header', 'storefront_header_cart', 60 );
          //  remove_action( 'storefront_header', 'storefront_product_search', 40 );
          remove_action( 'storefront_before_content', 'woocommerce_breadcrumb', 10 );
          remove_action( 'storefront_before_content', 'storefront_primary_navigation_wrapper_close', 68 );
          remove_action( 'storefront_footer', 'storefront_handheld_footer_bar', 57 );
          remove_action( 'storefront_before_content', 'storefront_header_widget_region', 10 );
          remove_action( 'storefront_footer', 'storefront_footer_widgets', 10 );
          remove_action( 'storefront_footer', 'storefront_credit', 20 );
          remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
          remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
      }
    /* ------------------------------------------------------------------------------------------------------------------------- */    
      public function remove_default_menus() {
        unregister_nav_menu('primary');
        unregister_nav_menu('secondary');
        unregister_nav_menu('handheld');
      }

     /* ------------------------------Ajout de mon script jQuery --------------------------------------------------------------- */
      public function domaze_adding_scripts() {
        wp_register_script('domaze_amazing_script', get_stylesheet_directory_uri().'/js/amazing_jquery.js');
                                          /* get_stylesheet_directory_uri() car ça marche dans les thèmes enfants */
        wp_enqueue_script('domaze_amazing_script');
        load_theme_textdomain('storefront-child', get_stylesheet_directory() . '/languages');
      }
      /* ----------------------------------------------------------------------------------------------------------------------- */

      /* ------------------------------------Les feuilles de style ------------------------------------------------------------- */  
      public function domaze_enqueue_style() {
          wp_enqueue_style( 'style-child', get_stylesheet_directory_uri().'/style.css' );
          add_filter('style-child', 'domaze_remove_type_attr', 10, 2);
          function domaze_remove_type_attr($tag, $handle) {
              return preg_replace( "/type=['\"]text\/(javascript|css)['\"]/", 'Blabla', $tag );
          }

        //wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css' );
          wp_enqueue_style( 'font-awesome-cdn', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
        }
      /* ----------------------------------------------------------------------------------------------------------------------- */
 
        /*------------------------------------------------------------- *\
        # Ajout d'un message flash par exemple "en construction..." 
        \*------------------------------------------------------------- */
          public function domaze_ticker($under_conctrution=0) {
            if($under_conctrution):
              add_action( 'storefront_header', array($this, 'domaze_ft_ticker'),22);
            endif;
          }
          public function domaze_ft_ticker($under_conctrution=0) {  
            ?>
            <div class="ticker-container">
              <span><em>    <br> CE SITE EST EN CONSTRUCTION (il est réalisé dans le cadre d'une formation). 
              Les informations ne sont pas exactes pour l'instant (pas de vente pour l'instant)<br>
              Vous avez des questions? Appelez Dominique :</em> <strong>06 26 66 10 37</strong></span>
            </div>
          <?php }
        /*------------------------------------------------------------- *\
        \*------------------------------------------------------------- */ 
        
    
        /*------------------------------------------------------------- *\
        #  Paramétrage du fil d'Ariane de WooCommerce 
        \*------------------------------------------------------------- */
        public function domaze_woocommerce_breadcrumbs() {
              $args = array(
                'delimiter'   => '&#47;',
                'wrap_before' => '  <nav id="nav-domaze-breacrumb" class="domaze-breadcrumb">  <i class="fas fa-home"></i>  ',
                'wrap_after'  => '</nav>',
                'before'      => '  ',
                'after'       => '',
                'home'        => _x( 'Home', 'breadcrumb', 'woocommerce' ),
              );
          woocommerce_breadcrumb( $args );
        }
        /*------------------------------------------------------------- *\
        \*------------------------------------------------------------- */ 

        /*------------------------------------------------------------- *\
        # Enveloppement partie produits
        \*------------------------------------------------------------- */
        public function domaze_woo_output_content_wrapper () {}
        public function domaze_woo_output_content_wrapper_end () {}
        /*------------------------------------------------------------- *\
        \*------------------------------------------------------------- */ 

        /*------------------------------------------------------------- *\
        # Mise en place du logo personnalisable depuis le back-offoce
        \*------------------------------------------------------------- */
        public function domaze_custom_logo() {
          the_custom_logo();
        }
        /*------------------------------------------------------------- *\
        \*------------------------------------------------------------- */ 


        /*------------------------------------------------------------- *\
        # Menu faisant apparaître les catégories woocommerce En dur !!
        \*------------------------------------------------------------- */
        public function domaze_top_menu_categories_woo() {
        $orderby = 'name';
        $order = 'asc';
        $hide_empty = false;
        $cat_args = array(
            'orderby'    => $orderby,
            'order'      => $order,
            'hide_empty' => $hide_empty,
        );
        $product_categories = get_terms( 'product_cat', $cat_args );
      ?><div id="boutton_menu1" class='hamburger'>
          <i class="fas fa-bars"></i>
        </div>
            <nav id="menu1" class="menu1">
                <ul class="domaze-top-categ-menu"><?php
        if( !empty($product_categories) ) {
                  foreach ($product_categories as $key => $category) {
                      echo '<li class="cat-item"><a href="'.get_term_link($category).'">'.$category->name.'</a></li>';
                      } } ?>
              </ul>
            <nav><?php
        }
        /*------------------------------------------------------------- *\
        \*------------------------------------------------------------- */


        /*------------------------------------------------------------- *\
        # Menus paramétrables depuis l'administration de WP
        \*------------------------------------------------------------- */
        public function register_domaze_menus() {
          register_nav_menus(array(
            'header-menu' => __( 'Header Menu' ),
            'footer-menu1' => __( 'Footer Menu colonne1' ),
            'footer-menu2' => __( 'Footer Menu colonne2' ),
            ));
          }
        /*------------------------------------------------------------- *\
        \*------------------------------------------------------------- */


        /*------------------------------------------------------------- *\
        # Menu dans le header Mon compte
        \*------------------------------------------------------------- */ 
        public function my_account_menu() {
          $topMenu = array(
            //   'menu'              => "", // (int|string|WP_Term) Desired menu. Accepts a menu ID, slug, name, or object.
            'menu_class'        => "my-account", // (string) CSS class to use for the ul element which forms the menu. Default 'menu'.
            'menu_id'           => "my-account", // (string) The ID that is applied to the ul element which forms the menu. Default is the menu slug, incremented.
            'container'         => FALSE, // (string) Whether to wrap the ul, and what to wrap it with. Default 'div'.
            //   'container_class'   => "", // (string) Class that is applied to the container. Default 'menu-{menu slug}-container'.
            'container_id'      => FALSE, // (string) The ID that is applied to the container.
            //   'fallback_cb'       => "", // (callable|bool) If the menu doesn't exists, a callback function will fire. Default is 'wp_page_menu'. Set to false for no fallback.
            'before'            => "", // (string) Text before the link markup.
            'after'             => "", // (string) Text after the link markup.
            'link_before'       => "", // (string) Text before the link text.
            'link_after'        => "", // (string) Text after the link text.
            'echo'              => true, // (bool) Whether to echo the menu or return it. Default true.
            'depth'             => 0, // (int) How many levels of the hierarchy are to be included. 0 means all. Default 0.
            'walker'            => new Walker_Nav_Menu, // (object) Instance of a custom walker class.
            'theme_location'    => "header-menu", // (string) Theme location to be used. Must be registered with register_nav_menu() in order to be selectable by the user.
            //'items_wrap'        => '<ul id ="top_menu" class="%2$s">3$s</ul>', // (string) How the list items should be wrapped. Default is a ul with an id and class. Uses printf() format with numbered placeholders.
            'item_spacing'      => 'discard', // (string) Whether to preserve whitespace within the menu's HTML. Accepts 'preserve' or 'discard'. Default 'preserve'.
            );

            wp_nav_menu($topMenu);
        }
        /*------------------------------------------------------------- *\
        \*------------------------------------------------------------- */  
        
        /*------------------------------------------------------------- *\
        # Footer Menus et credits
        \*------------------------------------------------------------- */  
        public function domaze_personalise_footer() { ?>
          <div class="row1-footer-container">
            <nav class="footer-menu1"><?php wp_nav_menu( array( 'theme_location' => 'footer-menu1' ) ); ?></nav>
            <nav class="footer-menu2"><?php wp_nav_menu( array( 'theme_location' => 'footer-menu2' ) ); ?></nav>
          </div>
          <div class="row2-footer-container">  
            <p id="licence" class="footer-credit">©2019 Dominique AZELART étudiant  <a href="https://www.ifocop.fr/centre-formations/villeneuve-dascq/" target="_blank">IFOCOP</a></p>
          </div>
                                                <?php }
        /*------------------------------------------------------------- *\
        \*------------------------------------------------------------- */ 


        /*------------------------------------------------------------- *\
        # Widgets du thème
        \*------------------------------------------------------------- */
        public function domaze_sidebar_widgets_init() {
          register_sidebar( array(
          'name' => 'area-domaze-1',
          'id' => 'area1',
          'before_widget' => '<div class="sidewidget-div">',
          'after_widget' => '</div>',
          'before_title' => '<h2 class="sidewidget-title">',
          'after_title' => '</h2>',
          ) );
        }
        /*------------------------------------------------------------- *\
        \*------------------------------------------------------------- */

        /*------------------------------------------------------------- *\
        # Top caddy
        \*------------------------------------------------------------- */
        public function domaze_append_wc_cart( $items =10, $args =2 ) {
          $cart_item_count = WC()->cart->get_cart_contents_count();
          $cart_count_span = '';
          $url_cart = esc_url(wc_get_cart_url());
            
          global $woocommerce;
          ob_start();
          ?><a  href="<?=$url_cart ?>" >  
          <div id="cart-zone" style="background:white" class="cart-contents">
          <?php 
          
          /* gestion traduction */
          $singular = _x( 'item', 'shopping-cart', 'woocommerce' );
          $plural =   _x( 'items', 'shopping-cart', 'woocommerce' );
          $cptItemsInBasket =  $woocommerce->cart->cart_contents_count;
          echo $cptItemsInBasket.' ';
          
          /* gestion féminin pluriel */
          echo _n($singular, $plural,  $cptItemsInBasket, 'woocommerce'); ?> - <?php echo $woocommerce->cart->get_cart_total(); ?>
          </div></a>
          <?php   
        }
        /*------------------------------------------------------------- *\
        \*------------------------------------------------------------- */


        /*------------------------------------------------------------- *\
        # Coupons EN RESERVE
        \*------------------------------------------------------------- */  
        public function add_coupon_notice() {
            $cart_total = WC()->cart->get_subtotal();
            $minimum_amount = 50;
            $currency_code = get_woocommerce_currency();
            wc_clear_notices();
    
           if ( $cart_total < $minimum_amount ) {
                  WC()->cart->remove_coupon( 'COUPON' );
                  wc_print_notice( "Get 50% off if you spend more than $minimum_amount $currency_code!", 'notice' );
            } else {
                  WC()->cart->apply_coupon( 'COUPON' );
                  wc_print_notice( 'You just got 50% off your order!', 'notice' );
            }
              wc_clear_notices();
        }
        /*------------------------------------------------------------- *\
        \*------------------------------------------------------------- */  
        


      }  /* Find de la class WPdomaze */


/* ========================================== INSTANCIATION  ===========================================================*/
$wpDomaze = new WPdomaze();
$wpDomaze->domaze_ticker(1); /* Affiche le ticker pour site en conctruction  */