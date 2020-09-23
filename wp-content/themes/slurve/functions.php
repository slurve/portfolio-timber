<?php
/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

/**
 * If you are installing Timber as a Composer dependency in your theme, you'll need this block
 * to load your dependencies and initialize Timber. If you are using Timber via the WordPress.org
 * plug-in, you can safely delete this block.
 */
$composer_autoload = __DIR__ . '/vendor/autoload.php';
if (file_exists($composer_autoload)) {
  require_once $composer_autoload;
  $timber = new Timber\Timber();
}

/**
 * Add styles
 */
function add_styles()
{
  wp_enqueue_style(
    'master',
    get_bloginfo('stylesheet_directory') . '/build/app.css',
    '',
    date('dmYis'),
    'screen'
  );
  wp_dequeue_style('wp-block-library');
}
add_action('wp_print_styles', 'add_styles');

/**
 * Add scripts
 */
function add_scripts($post)
{
  if (!is_admin()) {
    $rand = rand(1, 99999999999);
    wp_enqueue_script('jquery');
    wp_enqueue_script(
      'custom',
      get_template_directory_uri() . '/build/app.js?v=' . $rand,
      null,
      null,
      true
    );
  }
}
add_action('init', 'add_scripts');

// Remove unnedded menus
function remove_menus()
{
  remove_menu_page('index.php'); // Dashboard
  remove_menu_page('edit-comments.php'); // Comments
}
add_action('admin_menu', 'remove_menus');

// Add page slug to body class
function add_slug_body_class($classes)
{
  global $post;
  if (isset($post)) {
    $classes[] = $post->post_type . '-' . $post->post_name;
  }
  return $classes;
}
add_filter('body_class', 'add_slug_body_class');

// Custom error message for Gravity Forms
add_filter("gform_validation_message", "gwp_change_message", 10, 2);
function gwp_change_message($message, $form)
{
  return '
<div class="validation_error">Oops. Please adjust the fields below.</div>
';
}

/**
 * This ensures that Timber is loaded and available as a PHP class.
 * If not, it gives an error message to help direct developers on where to activate
 */
if (!class_exists('Timber')) {
  add_action('admin_notices', function () {
    echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' .
      esc_url(admin_url('plugins.php#timber')) .
      '">' .
      esc_url(admin_url('plugins.php')) .
      '</a></p></div>';
  });

  add_filter('template_include', function ($template) {
    return get_stylesheet_directory() . '/static/no-timber.html';
  });
  return;
}

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = ['templates'];

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;

/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class StarterSite extends Timber\Site
{
  /** Add timber support. */
  public function __construct()
  {
    add_action('after_setup_theme', [$this, 'theme_supports']);
    add_filter('timber/context', [$this, 'add_to_context']);
    add_filter('timber/twig', [$this, 'add_to_twig']);
    add_action('init', [$this, 'register_post_types']);
    add_action('init', [$this, 'register_taxonomies']);
    parent::__construct();
  }

  /** This is where you can register custom post types. */
  public function register_post_types()
  {
    // Projects
    $labels = [
      "name" => __("Projects", ""),
      "singular_name" => __("Project", "")
    ];
    $args = [
      "label" => __("Project", ""),
      "labels" => $labels,
      "description" => "",
      "public" => true,
      "publicly_queryable" => true,
      "show_ui" => true,
      "delete_with_user" => false,
      "show_in_rest" => true,
      "rest_base" => "",
      "rest_controller_class" => "WP_REST_Posts_Controller",
      "has_archive" => false,
      "show_in_menu" => true,
      "show_in_nav_menus" => true,
      "exclude_from_search" => false,
      "capability_type" => "post",
      "map_meta_cap" => true,
      "hierarchical" => false,
      "rewrite" => ["slug" => "project", "with_front" => false],
      "query_var" => true,
      "supports" => ["title", "thumbnail", "revisions"],
      "menu_icon" => "dashicons-laptop"
    ];
    register_post_type("cpt_projects", $args);

    // Services
    $labels = [
      "name" => __("Services", ""),
      "singular_name" => __("Service", "")
    ];
    $args = [
      "label" => __("Services", ""),
      "labels" => $labels,
      "description" => "",
      "public" => true,
      "publicly_queryable" => true,
      "show_ui" => true,
      "delete_with_user" => false,
      "show_in_rest" => true,
      "rest_base" => "",
      "rest_controller_class" => "WP_REST_Posts_Controller",
      "has_archive" => false,
      "show_in_menu" => true,
      "show_in_nav_menus" => true,
      "exclude_from_search" => false,
      "capability_type" => "post",
      "map_meta_cap" => true,
      "hierarchical" => false,
      "rewrite" => ["slug" => "service", "with_front" => false],
      "query_var" => true,
      "supports" => ["title", "editor", "thumbnail", "excerpt"],
      "menu_icon" => "dashicons-admin-tools"
    ];
    register_post_type("cpt_services", $args);
  }

  /** This is where you can register custom taxonomies. */
  public function register_taxonomies()
  {
    // Project - Type
    $args = [
      "label" => __("Type", ""),
      "labels" => [
        "name" => __("Types", ""),
        "singular_name" => __("Type", "")
      ],
      "public" => true,
      "publicly_queryable" => true,
      "hierarchical" => true,
      "show_ui" => true,
      "show_in_menu" => true,
      "show_in_nav_menus" => true,
      "query_var" => true,
      "rewrite" => ['slug' => 'portfolio/type', 'with_front' => false],
      "show_admin_column" => true,
      "show_in_quick_edit" => true
    ];
    register_taxonomy("project_type", "cpt_projects", $args);
  }

  /** This is where you add some context
   *
   * @param string $context context['this'] Being the Twig's {{ this }}.
   */
  public function add_to_context($context)
  {
    // util
    $theme_path = get_stylesheet_directory();
    $context['site'] = $this;
    $context['is_front'] = is_front_page();

    // footer
    $context['footer__about'] = get_field('footer__about', 'options');
    $context['footer__copyright'] = get_field('footer__copyright', 'options');
    $context['footer__legal_links'] = get_field(
      'footer__legal_links',
      'options'
    );

    // social
    $context['social__linkedin'] = get_field('social__linkedin', 'options');
    $context['social__twitter'] = get_field('social__twitter', 'options');

    // contact
    $context['contact__address'] = get_field('contact__address', 'options');
    $context['contact__email'] = get_field('contact__email', 'options');
    $context['contact__phone'] = get_field('contact__phone', 'options');

    // options page - clients
    $context['clients__logos'] = get_field('clients__logos', 'options');
    $context['clients__text'] = get_field('clients__text', 'options');

    // options page - plans
    $context['plan_a_title'] = get_field('plan_a_title', 'options');
    $context['plan_a_price'] = get_field('plan_a_price', 'options');
    $context['plan_a_notes'] = get_field('plan_a_notes', 'options');
    $context['plan_a_features'] = get_field('plan_a_features', 'options');
    $context['plan_a_link'] = get_field('plan_a_link', 'options');
    $context['plan_b_title'] = get_field('plan_b_title', 'options');
    $context['plan_b_price'] = get_field('plan_b_price', 'options');
    $context['plan_b_notes'] = get_field('plan_b_notes', 'options');
    $context['plan_b_features'] = get_field('plan_b_features', 'options');
    $context['plan_b_link'] = get_field('plan_b_link', 'options');
    $context['plan_c_title'] = get_field('plan_c_title', 'options');
    $context['plan_c_price'] = get_field('plan_c_price', 'options');
    $context['plan_c_notes'] = get_field('plan_c_notes', 'options');
    $context['plan_c_features'] = get_field('plan_c_features', 'options');
    $context['plan_c_link'] = get_field('plan_c_link', 'options');

    // options page - retainers
    $context['retainer_a_title'] = get_field('retainer_a_title', 'options');
    $context['retainer_a_price'] = get_field('retainer_a_price', 'options');
    $context['retainer_a_notes'] = get_field('retainer_a_notes', 'options');
    $context['retainer_a_features'] = get_field(
      'retainer_a_features',
      'options'
    );
    $context['retainer_a_link'] = get_field('retainer_a_link', 'options');
    $context['retainer_b_title'] = get_field('retainer_b_title', 'options');
    $context['retainer_b_price'] = get_field('retainer_b_price', 'options');
    $context['retainer_b_notes'] = get_field('retainer_b_notes', 'options');
    $context['retainer_b_features'] = get_field(
      'retainer_b_features',
      'options'
    );
    $context['retainer_b_link'] = get_field('retainer_b_link', 'options');

    $context['retainer_c_title'] = get_field('retainer_c_title', 'options');
    $context['retainer_c_price'] = get_field('retainer_c_price', 'options');
    $context['retainer_c_notes'] = get_field('retainer_c_notes', 'options');
    $context['retainer_c_features'] = get_field(
      'retainer_c_features',
      'options'
    );
    $context['retainer_c_link'] = get_field('retainer_c_link', 'options');

    // options page - cta
    $context['cta__headline'] = get_field('cta__headline', 'options');
    $context['cta__text'] = get_field('cta__text', 'options');
    $context['cta__link'] = get_field('cta__link', 'options');

    // options page - contact
    $context['contact__form_id'] = get_field('contact__form_id', 'options');
    $context['contact__sidebar_title'] = get_field(
      'contact__sidebar_title',
      'options'
    );
    $context['contact__sidebar_text'] = get_field(
      'contact__sidebar_text',
      'options'
    );

    // search results
    $context['search_term'] = get_search_query();

    // terms
    $context['project_type'] = Timber::get_terms('project_type');
    $context['single_cat_title'] = single_cat_title('', false);

    // menus
    $context['menu_main'] = new Timber\Menu('Main');

    // projects
    $context['projects'] = Timber::get_posts([
      'post_type' => 'cpt_projects',
      'posts_per_page' => -1
    ]);

    // services
    $context['services'] = Timber::get_posts([
      'post_type' => 'cpt_services',
      'posts_per_page' => -1,
      'post__not_in' => array(97, 96, 211)
    ]);

    // misc
    $context['logo'] = new Timber\Image(
      $theme_path . '/assets/images/logo.svg'
    );
    $context['whitesnake'] = new Timber\Image($theme_path . '/assets/video/');

    return $context;
  }

  public function theme_supports()
  {
    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    /*
     * Let WordPress manage the document title.
     * By adding theme support, we declare that this theme does not use a
     * hard-coded <title> tag in the document head, and expect WordPress to
     * provide it for us.
     */
    add_theme_support('title-tag');

    /*
     * Enable support for Post Thumbnails on posts and pages.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /*
     * Add ACF Options page.
     */
    if (function_exists('acf_add_options_page')) {
      acf_add_options_page();
    }

    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
    add_theme_support('html5', [
      'comment-form',
      'comment-list',
      'gallery',
      'caption'
    ]);

    add_theme_support('menus');

    /*
     * Remove extra stuff from wp_head
     */
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rel_canonical');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'index_rel_link');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'parent_post_rel_link');
    remove_action('wp_head', 'start_post_rel_link');
    remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_head', 'rest_output_link_wp_head');
  }

  /** This Would return 'foo bar!'.
   *
   * @param string $text being 'foo', then returned 'foo bar!'.
   */
  public function myfoo($text)
  {
    $text .= ' bar!';
    return $text;
  }

  /** This is where you can add your own functions to twig.
   *
   * @param string $twig get extension.
   */
  public function add_to_twig($twig)
  {
    $twig->addExtension(new Twig\Extension\StringLoaderExtension());
    $twig->addFilter(new Twig\TwigFilter('myfoo', [$this, 'myfoo']));
    return $twig;
  }
}

new StarterSite();
