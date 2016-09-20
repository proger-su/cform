<?php

/*
  Plugin Name: Contacts form
  Description: Adding contacts to data base
  Author: Sergey Pererva
  Author URI: http://proger.su
  Version: 1.0.0
 */

defined('ABSPATH') or die();

class cform {

	public $slug;
	public $path;

	function __construct() {
		//Params
		$this->slug = 'cform';
		$this->path = plugin_dir_path(__FILE__);

		//Scripts
		add_action('wp_enqueue_scripts', array($this, 'addScripts'));

		//Form shortcode
		add_shortcode('cform', array($this, 'addFormShortcode'));

		//Post type
		add_action('init', array($this, 'addFormPostType'));

		//Table columns
		$this->addContactTableColumns();

		//Form handler
		add_action('wp_ajax_add_contact', array($this, 'registerFormHandler'));
		add_action('wp_ajax_nopriv_add_contact', array($this, 'registerFormHandler'));
		
	}

	public function addScripts() {
		wp_enqueue_script('jquery.validate', plugin_dir_url( __FILE__ ) . '/js/jquery.validate.min.js', array('jquery'));
		wp_enqueue_script('cform', plugin_dir_url( __FILE__ ) . '/js/cform.js', array('jquery.validate'));
	}

	public function addFormShortcode() {
		ob_start();
		require $this->path . 'inc/form.php';
		$form = ob_get_contents();
		ob_end_clean();
		return $form;
	}

	public function addFormPostType() {
		register_post_type($this->slug, array(
			'supports' => array('title', 'custom-fields'),
			'menu_icon' => 'dashicons-groups',
			'labels' => array(
				'name' => 'Contacts',
				'singular_name' => 'Contact',
				'menu_name' => 'Contacts',
				'add_new' => 'Add Contact'
			),
			'public' => true,
			'publicly_queryable' => false,
			'exclude_from_search' => true,
			'has_archive' => false,
			'delete_with_user' => false,
		));
	}

	public function addContactTableColumns() {
		add_filter('manage_edit-' . $this->slug . '_columns', function ($columns) {
			$columns = array(
				'cb' => '<input type="checkbox" />',
				'title' => __('Name'),
				'email' => __('Email'),
				'phone' => __('Phone'),
				'date' => __('Date')
			);

			return $columns;
		});

		add_action('manage_posts_custom_column', function ($column) {
			global $post;
			switch ($column) {
				case 'email' :
					echo get_post_meta($post->ID, $this->slug . '_email', true);
					break;
				case 'phone' :
					echo get_post_meta($post->ID, $this->slug . '_phone', true);
					break;
			}
		});
	}

	public function registerFormHandler() {
		
			if (!wp_verify_nonce(filter_input(INPUT_POST, $this->slug . '_field_nonce'), $this->path)) {
				die();
			}

			$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_MAGIC_QUOTES);
			$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
			$phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_MAGIC_QUOTES);
			
			if (!$name || !$email || !$phone) {
				die();
			}

			$contact = wp_insert_post(array(
				'post_title' => $name,
				'post_status' => 'publish',
				'post_type' => $this->slug
			));

			if (!$contact) {
				die('Error');
			}

			update_post_meta($contact, $this->slug . '_email', $email);
			update_post_meta($contact, $this->slug . '_phone', $phone);

			die('Ok');

	}

}

new cform;
