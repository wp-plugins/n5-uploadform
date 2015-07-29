<?php
/*
Plugin Name:N5 UploadForm
Plugin URI:http://www.n5-creation.com
Description: N5-UploadForm is a file uploading tool. This tool enables its user to add a form to static pages as well as articles in WordPress.
Version:1.0
Author:Kazuki Niibori
Author URI:http://www.n5-creation.com
License:GPL2

Copyright 2015 Kazuki Niibori (email : niibori@n5-creation.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

new N5_UploadForm();

class N5_UploadForm
{

	var $action = '';
	var $section = '';
	
	var $form = '';
	
	public function __construct()
	{
		load_plugin_textdomain('N5_UPLOADFORM', false, basename( dirname( __FILE__ ) ) . '/languages');
		$this->action = isset($_REQUEST['action'])?$_REQUEST['action']:'';
		$this->section = isset($_REQUEST['section'])?$_REQUEST['section']:'';

		if($this->action == -1)
			{
				if(isset($_REQUEST['action2']))
					{
						$this->action = isset($_REQUEST['action2'])?$_REQUEST['action2']:'';
					}
			}
		
		$this->form = isset($_REQUEST['form'])?$_REQUEST['form']:'';
	
		add_action('init',array(&$this, 'n5uf_save_form'));
		add_action('admin_init', array(&$this, 'n5uf_action'));
		add_action('admin_menu', array(&$this, 'n5uf_menu'));
		add_shortcode('n5uploadform',array(&$this,'n5uf_shortcode_n5uploadform_handler'));

	}

	function n5uf_shortcode_n5uploadform_handler($atts, $content=null)
	{

		if( isset($atts['id']) )
			{

				$post = get_post($atts['id']);
				$post_meta = array();
				
				$post_meta['directory']			 = get_post_meta($post->ID,'directory',true);
				$post_meta['ext']				 = get_post_meta($post->ID,'ext',true);
				$post_meta['mime']				 = get_post_meta($post->ID,'mime',true);
				$post_meta['adminnotice']		 = get_post_meta($post->ID,'adminnotice',true);
				$post_meta['adminnotice_subject'] = get_post_meta($post->ID,'adminnotice_subject',true);
				$post_meta['adminnotice_body']	 = get_post_meta($post->ID,'adminnotice_body',true);
				$post_meta['usernotice']		 = get_post_meta($post->ID,'usernotice',true);
				$post_meta['usernotice_subject'] = get_post_meta($post->ID,'usernotice_subject',true);
				$post_meta['usernotice_body']	 = get_post_meta($post->ID,'usernotice_body',true);
				$post_meta['finish']			 = get_post_meta($post->ID,'finish',true);
								
				if( isset($_POST['action']))
					{
						$upload_errors = array();
						$match_cnt = 0;
						
						switch($_POST['action'])
							{
							case 'input-n5uploadform':
								
								$file = array();
								$file['obj'] = $_FILES['n5uf-file'];
								$file['ext'] = pathinfo($_FILES['n5uf-file']['name'], PATHINFO_EXTENSION);
								$file['mime'] = $_FILES['n5uf-file']['type'];
								$file['date'] = date("Y-m-d H:i:s");
								
								// 拡張子フィルタ
								if(!empty($post_meta['ext']))
									{
										foreach( preg_split( "/,/", $post_meta['ext']) as $ext)
											{
												$ext = preg_replace("/\s/","",$ext);
												if( strcmp( $ext, $file['ext'] ) == 0) $match_cnt++;
											}
										
									}
								
								if(!empty($post_meta['mime']))  // MIMEタイプフィルタ
									{
										foreach( preg_split( "/,/", $post_meta['mime']) as $mime)
											{
												$mime = preg_replace("/\s/","",$mime);
												if( strcmp( $mime, $file['mime'] ) == 0) $match_cnt++;
											}
									}
								
								if( empty($post_meta['mime']) && empty($post_meta['ext']) ) $match_cnt++;

							}
						
						//validation check
						if( $file['obj']['error'] > 0 || $match_cnt == 0)
							{
								$upload_errors[] = __('The uploading failed.','N5_UPLOADFORM');
							}


						//validation check - mail
						if( $post_meta['usernotice'] > 0 && isset($_REQUEST['n5uf-usermail']))
							{
								if( empty($_REQUEST['n5uf-usermail']) )
									{
										$upload_errors[] = __('Please enter your E-mail address.','N5_UPLOADFORM');
									}
								elseif ( !is_email($_REQUEST['n5uf-usermail']))
									{
										$upload_errors[] = __('Please enter your E-mail address.','N5_UPLOADFORM');
									}
								
							}

						if( $post_meta['usernotice'] == 0 && !file_exists($post_meta['directory']))
							{
								$upload_errors[] = __('The uploading failed.','N5_UPLOADFORM');
							}
						
						//アップロード処理
						if( count($upload_errors) == 0)
							{
								
								$file['uploadedname'] = sprintf(
									"%s.%s",
									md5( uniqid( rand())),
									$file['ext']
								);
								
								move_uploaded_file(
									$file['obj']['tmp_name'],
									sprintf(
										"%s/%s",
										$post_meta['directory'],
										$file['uploadedname']
									)
								);
								
								add_post_meta( $post->ID, 'file', $file);
										
								if( !empty($post_meta['adminnotice']) && is_email($post_meta['adminnotice']) )
									{
										wp_mail( $post_meta['adminnotice'], $post_meta['adminnotice_subject'], $post_meta['adminnotice_body']);
									}
																		
								if( $post_meta['usernotice'] > 0 && isset($_REQUEST['n5uf-usermail']))
									{
										if( !empty($_REQUEST['n5uf-usermail']) && is_email($_REQUEST['n5uf-usermail']))
											{
												wp_mail( $_REQUEST['n5uf-usermail'], $post_meta['usernotice_subject'], $post_meta['usernotice_body']);
											}
									}
								
								
								ob_start();
								require sprintf( "%s/tmpl/form_finish.tpl.php", __DIR__); $res = ob_get_contents();
								ob_end_clean();
								
						
							}
						else
							{
								ob_start();
								require sprintf( "%s/tmpl/form_input.tpl.php", __DIR__); $res = ob_get_contents();
								ob_end_clean();
							}
						
					}
				else
					{
						ob_start();
						require sprintf( "%s/tmpl/form_input.tpl.php", __DIR__); $res = ob_get_contents();
						ob_end_clean();
					}
				
			}
		
		return $res;
	}

	
	function n5uf_save_form()
	{
		
		if( isset($_POST['action']))
			{
				switch( $_POST['action'])
					{
					case 'add-n5uploadform':
						
						$post = array(
							'ID' => '',
							'post_type' => 'n5-uploadform',
							'post_status' => 'publish',
							'post_title' => $_POST['n5uf-name'],
						);
			
						$post_id = wp_insert_post( $post );

						if ($post_id > 0)
							{
								if( !preg_match( '/^[^\/].+/' , $_POST['n5uf-directory']))
									{
										if(isset($_POST['n5uf-directory']))
											{
												$n5uf_directory = sanitize_text_field( $_POST['n5uf-directory']);
												update_post_meta(
													$post_id,
													'directory',
													preg_replace('/(.+)\/$/',"$1", $n5uf_directory)
												);
											}
									}
								else
									{
										if(isset($_POST['n5uf-directory']))
											{
												$n5uf_directory = sanitize_text_field( $_POST['n5uf-directory']);
												update_post_meta(
													$post_id,
													'directory',
													sprintf(
														"%s%s",
														ABSPATH,
														preg_replace('/(.+)\/$/',"$1",$_POST['n5uf-directory'])
													));
											}
									}


								if(isset($_POST['n5uf-ext']))
									{
										$n5uf_ext = sanitize_text_field( $_POST['n5uf-ext']);
										update_post_meta( $post_id, 'ext', $n5uf_ext);
									}
								
								if(isset($_POST['n5uf-mime']))
									{
										$n5uf_mime = sanitize_text_field( $_POST['n5uf-mime']);
										update_post_meta( $post_id, 'mime', $n5uf_mime);
									}
								
								if(isset($_POST['n5uf-adminnotice']))
									{
										$n5uf_adminnotice = sanitize_text_field( $_POST['n5uf-adminnotice']);
										$n5uf_adminnotice = sanitize_email( $n5uf_adminnotice);
										update_post_meta( $post_id, 'adminnotice',	$n5uf_adminnotice );
									}
								
								if(isset($_POST['n5uf-adminnotice_subject']))
									{
										$n5uf_adminnotice_subject = sanitize_text_field( $_POST['n5uf-adminnotice_subject']);
										update_post_meta( $post_id, 'adminnotice_subject', $n5uf_adminnotice_subject );
									}
								
								if(isset($_POST['n5uf-adminnotice_body']))
									{
										$n5uf_adminnotice_body = sanitize_text_field( $_POST['n5uf-adminnotice_body']);
										update_post_meta( $post_id, 'adminnotice_body',	$n5uf_adminnotice_body);
									}
								
								if(isset($_POST['n5uf-usernotice']))
									{
										$n5uf_usernotice = sanitize_text_field( $_POST['n5uf-usernotice']);
										update_post_meta( $post_id, 'usernotice', $n5uf_usernotice );
									}
								
								if(isset($_POST['n5uf-usernotice_subject']))
									{
										$n5uf_usernotice_subject = sanitize_text_field( $_POST['n5uf-usernotice_subject']);
										update_post_meta( $post_id, 'usernotice_subject', $n5uf_usernotice_subject );
									}
								
								if(isset($_POST['n5uf-usernotice_body']))
									{
										$n5uf_usernotice_body = sanitize_text_field( $_POST['n5uf-usernotice_body']);
										update_post_meta( $post_id, 'usernotice_body', $n5uf_usernotice_body );
									}
								
								if(isset($_POST['n5uf-finish']))
									{
										$n5uf_finish = $_POST['n5uf-finish'];
										update_post_meta( $post_id, 'finish', $n5uf_finish );
									}

							}
						break;
					case 'save-n5uploadform':

						$post = array(
							'ID' => $_POST['n5uf-id'],
							'post_type' => 'n5-uploadform',
							'post_status' => 'publish',
							'post_title' => $_POST['n5uf-name'],
						);
						
						$post_id = wp_update_post( $post);
						
						if($post_id > 0)
							{
								
								if( !preg_match( '/^[^\/].+/' , $_POST['n5uf-directory']))
									{
										if(isset($_POST['n5uf-directory']))
											update_post_meta(
												$post_id,
												'directory',
												preg_replace('/(.+)\/$/','$1',$_POST['n5uf-directory'])
											);
									}
								else
									{
										if(isset($_POST['n5uf-directory']))
											update_post_meta(
												$post_id,
												'directory',
												sprintf(
													"%s%s",
													ABSPATH,
													preg_replace('/(.+)\/$/','$1',$_POST['n5uf-directory'])
												));
									}

								if(isset($_POST['n5uf-ext']))
									{
										$n5uf_ext = sanitize_text_field( $_POST['n5uf-ext']);
										update_post_meta( $post_id, 'ext', $n5uf_ext);
									}
								
								if(isset($_POST['n5uf-mime']))
									{
										$n5uf_mime = sanitize_text_field( $_POST['n5uf-mime']);
										update_post_meta( $post_id, 'mime', $n5uf_mime);
									}
								
								if(isset($_POST['n5uf-adminnotice']))
									{
										$n5uf_adminnotice = sanitize_text_field( $_POST['n5uf-adminnotice']);
										$n5uf_adminnotice = sanitize_email( $n5uf_adminnotice);
										update_post_meta( $post_id, 'adminnotice',	$n5uf_adminnotice );
									}
								
								if(isset($_POST['n5uf-adminnotice_subject']))
									{
										$n5uf_adminnotice_subject = sanitize_text_field( $_POST['n5uf-adminnotice_subject']);
										update_post_meta( $post_id, 'adminnotice_subject', $n5uf_adminnotice_subject );
									}
								
								if(isset($_POST['n5uf-adminnotice_body']))
									{
										$n5uf_adminnotice_body = sanitize_text_field( $_POST['n5uf-adminnotice_body']);
										update_post_meta( $post_id, 'adminnotice_body',	$n5uf_adminnotice_body);
									}
								
								if(isset($_POST['n5uf-usernotice']))
									{
										$n5uf_usernotice = sanitize_text_field( $_POST['n5uf-usernotice']);
										update_post_meta( $post_id, 'usernotice', $n5uf_usernotice );
									}
								
								if(isset($_POST['n5uf-usernotice_subject']))
									{
										$n5uf_usernotice_subject = sanitize_text_field( $_POST['n5uf-usernotice_subject']);
										update_post_meta( $post_id, 'usernotice_subject', $n5uf_usernotice_subject );
									}
								
								if(isset($_POST['n5uf-usernotice_body']))
									{
										$n5uf_usernotice_body = sanitize_text_field( $_POST['n5uf-usernotice_body']);
										update_post_meta( $post_id, 'usernotice_body', $n5uf_usernotice_body );
									}
								
								if(isset($_POST['n5uf-finish']))
									{
										$n5uf_finish = $_POST['n5uf-finish'];
										update_post_meta( $post_id, 'finish', $n5uf_finish );
									}


							}

						break;

					}
			}
	}

	function n5uf_menu()
	{
		add_menu_page( 'N5 upload form menu page', 'N5 Upload Form', 8, 'n5-menu', array(&$this,'n5uf_page'));
	}

	
	function n5uf_page()
	{

		if(!empty($this->action))
			{
				switch($this->action)
					{
					case 'edit':
						$this->n5uf_edit_page($this->form);
						break;
					default:
						$this->n5uf_menu_page();	   
					}
			}
		else
			{
				switch($this->section)
					{
					case 'files':
						$this->n5uf_list_page($this->form);
						break;
					default:
						$this->n5uf_menu_page();	   
					}

			}






	}

	function n5uf_action()
	{

		switch($this->action)
			{
			case 'download':
				$this->n5uf_file_download($this->form,$_REQUEST['file']);
				break;
			case 'delete':
				if(isset($_REQUEST['file']))
					{
						$this->n5uf_file_delete( $_REQUEST['file'], $_REQUEST['form']);
					}
				else
					{
						$this->n5uf_form_delete($_REQUEST['form']);
					}
				break;
			case 'trash':
				$this->n5uf_form_trash($_REQUEST['form']);
				break;
			case 'untrash':
				$this->n5uf_form_restore($_REQUEST['form']);
				break;
			case 'restore':
				$this->n5uf_form_restore($_REQUEST['form']);
				break;
			case 'delete_all':
				if(isset($_REQUEST['file']))
					{
						$this->n5uf_file_delete_all($_REQUEST['file'], $_REQUEST['form']);
					}
				else
					{
						$this->n5uf_form_delete_all($_REQUEST['form']);
					}
				break;
			case 'trash_all':
				$this->n5uf_form_trash_all($_REQUEST['form']);
				break;
			case 'restore_all':
				$this->n5uf_form_restore_all($_REQUEST['form']);
				break;
			default:
			}
		return;
	}


	function n5uf_form_delete( $id)
	{
		wp_delete_post($id);
	}
	
	function n5uf_form_trash( $id)
	{
		wp_trash_post($id);
	}
		
	function n5uf_form_restore( $id)
	{
		
		$post = array(
			'ID'		  => $id,
			'post_status' => 'publish'
		);
		
		wp_update_post($post);
		
	}


	function n5uf_form_restore_all( $ids)
	{
		
		foreach( $ids as $id)
			{
				$post = array(
					'ID'		  => $id,
					'post_status' => 'publish'
				);
				
				wp_update_post($post);
			}

	}

	function n5uf_form_trash_all( $ids)
	{
		foreach( $ids as $form_id)
			{
				wp_trash_post($form_id);
			}
	}
		
	function n5uf_form_delete_all( $ids)
	{
		foreach( $ids as $form_id)
			{
				wp_delete_post($form_id);
			}
	}

	function n5uf_file_delete( $file_id, $form)
	{
		$files = get_post_meta( $form, 'file');

		if( count($files) > $file_id)
			{
				$file = $files[$file_id];
				
				$file['delete'] = date("Y-m-d H:i:s");
				
				update_post_meta($form, 'file', $file, $files[$file_id]);
			}
	}
		
	function n5uf_file_delete_all( $ids, $form)
	{
		$files = get_post_meta( $form, 'file');

		foreach( $ids as $file_id)
			{

				if( count($files) > $file_id)
					{
						$file = $files[$file_id];
						$file['delete'] = date("Y-m-d H:i:s");
						update_post_meta($form, 'file', $file, $files[$file_id]);
					}
			}
	}


	function n5uf_file_download( $form, $file_id)
	{
		
		$files = get_post_meta( $form, 'file');
		$uploadedDir = get_post_meta( $form, 'directory')[0];


		if( count($files) > $file_id)
			{
				
				$file = $files[$file_id];

				header(sprintf("Content-Type: %s", $file['mime']));
				header(sprintf("Content-Disposition: attachment; filename=%s", $file['obj']['name']));
				readfile(sprintf( "%s/%s", $uploadedDir, $file['uploadedname']));

			}

		exit;
			
	}

	function n5uf_menu_page()
	{
		$n5_upload_form_list_table = new N5_Upload_Form_Table();
		$n5_upload_form_list_table->prepare_items();
		require sprintf( "%s/tmpl/menu_page.tpl.php", __DIR__);
	}


	function n5uf_edit_page($form_id)
	{
		$post = get_post($form_id);
		$post_meta = array();
		$post_meta['directory'] = get_post_meta($post->ID,'directory',true);
		$post_meta['ext'] = get_post_meta($post->ID,'ext',true);
		$post_meta['mime'] = get_post_meta($post->ID,'mime',true);
		$post_meta['adminnotice'] = get_post_meta($post->ID,'adminnotice',true);
		$post_meta['adminnotice_subject']  =  get_post_meta($post->ID,'adminnotice_subject',true);
		$post_meta['adminnotice_body']	 =  get_post_meta($post->ID,'adminnotice_body',true);
		$post_meta['usernotice'] = get_post_meta($post->ID,'usernotice',true);
		$post_meta['usernotice_subject']  =  get_post_meta($post->ID,'usernotice_subject',true);
		$post_meta['usernotice_body']	 =  get_post_meta($post->ID,'usernotice_body',true);
		$post_meta['finish']	  = get_post_meta($post->ID,'finish',true);

		require sprintf( "%s/tmpl/edit_form_page.tpl.php", __DIR__);
	}

	function n5uf_list_page($form_id)
	{
		$n5_upload_file_list_table = new N5_Upload_File_Table();
		$n5_upload_file_list_table->prepare_items();
		require sprintf( "%s/tmpl/files_page.tpl.php", __DIR__);
	}
	
	function n5uf_smenu_createform_page()
	{
		require sprintf( "%s/tmpl/create_form_page.tpl.php", __DIR__);
	}
	
}

if ( !class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . '/wp-admin/includes/class-wp-list-table.php' );
}

class N5_Upload_Form_Table extends WP_List_Table
{

	var $status = '';

	function __construct()
	{
		parent::__construct( array(
			'singular' => 'form',
			'plural'   => 'forms',
			'ajax'     => false	
		) );

	}

	function get_items()
	{

		$this->status	= isset( $_GET['status'] ) ? $_GET['status'] : '';
		$args			= array( 'post_type' => 'n5-uploadform', 'numberposts' => -1, 'post_status' => $this->status, 'post_parent' => null );


		$forms = get_posts($args);
		$items = array();

		foreach ( $forms as $form ) {

			$items[] = array(
				'id'	=> $form->ID,
				'title'	=> esc_html($form->post_title),
				'date'	=> $form->post_modified
			);
			
		}

		return $items;
	}


	function get_views()
	{
		$views		= array();
		$current	= ( !empty($_REQUEST['status']) ? $_REQUEST['status'] : 'all');
		
		$class		= ($current == 'all' ? ' class="current"' :'');
		$all_url	= remove_query_arg(array('status','action','form'));

		$views['all']	= "<a href='{$all_url }' {$class} >All</a>";

		$trash_url		= add_query_arg('status','trash',$all_url);
		$class			= ($current == 'trash' ? ' class="current"' :'');
		$views['trash']	= "<a href='{$trash_url}' {$class} >". __('Trash','N5_UPLOADFORM') ."</a>";
		
		return $views;

	}
	
	function column_cb($item)
	{
			return sprintf('<input type="checkbox" name="form[]" value="%s">',$item['id']);
	}
	
	function column_default( $item, $column_name )
	{
		switch( $column_name ) {
		case 'id':
			$actions = array(
				'edit' => sprintf(
					'<a href="?page=%s&action=%s&section=editform=%s">'. __('Edit','N5_UPLOADFORM') .'</a>'
					, $_REQUEST['page']
					, 'edit'
					, $item['id']
				),
				'files' => sprintf(
					'<a href="?page=%s&section=%s&form=%s">'. __('Files','N5_UPLOADFORM') .'</a>'
					, $_REQUEST['page']
					, 'files'
					, $item['id']
				),
				'delete' => sprintf(
					'<a href="?page=%s&action=%s&form=%s">'. __('Delete','N5_UPLOADFORM') .'</a>'
					, $_REQUEST['page']
					, 'delete'
					, $item['id']
				)
			);
			
			return sprintf('%1$s %2$s', $item[$column_name],$this->row_actions($actions));
			break;
		case 'title':
			switch($this->status)
				{
				case 'trash':
					$actions = array(
						'restore' => sprintf(
							'<a href="?page=%s&action=%s&form=%s">'. __('Restore','N5_UPLOADFORM') .'</a>'
							, $_REQUEST['page']
							, 'restore'
							, $item['id']
						),
						'delete' => sprintf(
							'<a href="?page=%s&action=%s&form=%s">'. __('Delete','N5_UPLOADFORM') .'</a>'
							, $_REQUEST['page']
							, 'delete'
							, $item['id']
						)
					);

					break;
				default:
					$actions = array(
						'edit' => sprintf(
							'<a href="?page=%s&action=%s&section=form&form=%s">'. __('Edit','N5_UPLOADFORM') .'</a>'
							, $_REQUEST['page']
							, 'edit'
							, $item['id']
						),
						'files' => sprintf(
							'<a href="?page=%s&section=%s&form=%s">'. __('Files','N5_UPLOADFORM') .'</a>'
							, $_REQUEST['page']
							, 'files'
							, $item['id']
						),
						'trash' => sprintf(
							'<a href="?page=%s&action=%s&form=%s">'. __('Trash','N5_UPLOADFORM') .'</a>'
							, $_REQUEST['page']
							, 'trash'
							, $item['id']
						)
					);
					
				}
			
			return sprintf('%1$s %2$s', $item[$column_name],$this->row_actions($actions));
			break;
		case 'shortcode':
			return sprintf('[n5uploadform id="%s"]', $item['id']);
		case 'date':
			return $item[ $column_name ];
		 default:
			return print_r( $item, true ) ;
		}
	}

	function get_bulk_actions()
	{
		switch($this->status)
			{
			case 'trash':
				$actions = array(
					'restore_all'	=> __('Restore','N5_UPLOADFORM'),
					'delete_all'	=> __('Delete','N5_UPLOADFORM')
				);
				break;
			default:
				$actions = array(
					'trash_all'		=> __('Trash','N5_UPLOADFORM')
				);
			}
		return $actions;
	}

	function get_columns(){
		
		$columns = array(
			'cb'		=> '<input type="checkbox" >',
			'title'		=> 'Title',
			'shortcode'	=> 'Code',
			'date'		=> 'Date',
		);
		
		return $columns;
	}

	public function get_sortable_columns()
	{
		return array(
			'title'	=> array('title', true),
			'date'	=> array('date', true)
			
		);
	}

	public function get_hidden_columns()
	{
		return array();
	}

	function sort_data($a,$b)
	{
		$orderby	= (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'title';
		$order		= (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'desc';
		$result		= strcmp($a[$orderby], $b[$orderby]);
		return ($order==='asc') ? $result : -$result;
	}

	function prepare_items()
	{
		
		$customvar = ( isset($_REQUEST['status']) ? $_REQUEST['status'] : 'all');

		$columns	= $this->get_columns();
		$hidden		= $this->get_hidden_columns();
		$sortable	= $this->get_sortable_columns();
		$data		= $this->get_items();


		$current_page	= $this->get_pagenum();
		$data_count		= count($data);
		$per_page		= 10;
		$total_pages	= ceil($data_count/$per_page);

		usort( $data, array( &$this, 'sort_data' ) );
		$data = array_slice($data, (($current_page - 1)*$per_page), $per_page);

		$this->set_pagination_args(
			array(
				'total_items'	=> $data_count,
				'per_page'		=> $per_page,
				'total_pages'	=> $total_pages
			)
		);

		$this->_column_headers = array($columns, $hidden, $sortable);
		$this->items = $data;
	}

}


class N5_Upload_File_Table extends WP_List_Table
{

	var $status	= '';
	var $form	= '';

	function get_items()
	{

		$this->status = isset( $_GET['status'] ) ? $_GET['status'] : '';
		
		$this->form = isset( $_GET['form'] ) ? $_GET['form'] : '';
		$files = get_post_meta( $this->form, 'file');
		

		$items = array();

		foreach ( $files as $index => $file ) {

			if(!isset($file['delete']))
				{
					$items[] = array(
						'id'	=> $index,
						'name'	=> $file['obj']['name'],
						'date'	=> $file['date']
					);
				}
			
		}

		return $items;
	}

	function column_cb($item)
	{
		return sprintf('<input type="checkbox" name="file[]" value="%s">',$item['id']);
	}
	
	function column_default( $item, $column_name )
	{
		switch( $column_name ) {
		case 'name':
			$actions = array(
				'download' => sprintf(
					'<a href="?page=%s&action=%s&form=%s&section=files&file=%s">'. __('Download','N5_UPLOADFORM') .'</a>'
					, $_REQUEST['page']
					, 'download'
					, $this->form
					, $item['id']
				),
				'delete' => sprintf(
					'<a href="?page=%s&action=%s&form=%s&section=files&file=%s">'. __('Delete','N5_UPLOADFORM') .'</a>'
					, $_REQUEST['page']
					, 'delete'
					, $this->form
					, $item['id']
				)
			);
			
			return sprintf('%1$s %2$s', $item[$column_name],$this->row_actions($actions));
			break;

		case 'date':
			return $item[ $column_name ];
		 default:
			return print_r( $item, true ) ;
		}
	}

	function get_bulk_actions()
	{
		$actions = array(
			'delete_all' =>  __('Delete','N5_UPLOADFORM')
		);
		return $actions;
	}



	function get_columns(){
		
		$columns = array(
			'cb'	=> '<input type="checkbox" >',
			'name'	=> 'Name',
			'date'	=> 'Date',
		);
		
		return $columns;
	}

	public function get_sortable_columns()
	{
		return array('date' => array('date', false));
	}

	public function get_hidden_columns()
	{
		return array();
	}

	function prepare_items()
	{

		$columns	= $this->get_columns();
		$hidden		= $this->get_hidden_columns();
		$sortable	= $this->get_sortable_columns();
		$data		= $this->get_items();

		$current_page	= $this->get_pagenum();
		$data_count		=  count($data);
		$per_page		=  10;
		$total_pages	=  ceil($data_count/$per_page);

		$data = array_slice($data, (($current_page - 1)*$per_page), $per_page);
		$this->set_pagination_args(
			array(
				'total_items'	=> $data_count,
				'per_page'		=> $per_page,
				'total_pages'	=> $total_pages
			)
		);



		$this->_column_headers = array($columns, $hidden, $sortable);
		$this->items = $data;
	}

}