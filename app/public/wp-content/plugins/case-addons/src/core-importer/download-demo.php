<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

/**
* Request a download package from the api server and insert it to the created temp folder if the given token are correct
*
*/
class Pxl_Download_Demo
{
	/**
	 * temp folder name
	 *
	 * @access private
	 * @var string
	 */
	private $temp_folder_name = 'pxlart_temp';

	/**
	 * LiquidThemes Api url
	 *
	 * @access private
	 * @var string
	 */
	private $api_url;

	/**
	 * Compressed data format
	 *
	 * @access private
	 * @var string
	 */
	private $data_format = 'zip';


	public function __construct()
	{
		$pxl_server_info = apply_filters( 'pxl_server_info', ['api_url' => 'https://api.casethemes.net/'] ) ;
		$this->api_url = $pxl_server_info['api_url'] ; 
		add_action( 'wp_ajax_pxlart_prepare_demo_package', array($this, 'ajax_prepare_demo'), 10, 1 );
		add_action( 'wp_ajax_pxlart_upload_demo_manual', array($this, 'ajax_upload_demo_manual'), 10, 1 );
	}
 
	public static function init_filesystem() {

		if ( ! defined( 'FS_METHOD' ) ) {
			define( 'FS_METHOD', 'direct' );
		}

		// The Wordpress filesystem.
		global $wp_filesystem;

		if ( empty( $wp_filesystem ) ) {
			require_once( ABSPATH . '/wp-admin/includes/file.php' );
			WP_Filesystem();
		}

		return $wp_filesystem;
	}

	public function temp_folder($url = false) {
		$this->init_filesystem();

		$upload_dir = wp_upload_dir();
		$temp_folder = $this->temp_folder_name;
		$theme_temp_folder = $upload_dir['basedir'].'/'.$temp_folder;
		if(!file_exists($theme_temp_folder)) {
			wp_mkdir_p( $theme_temp_folder );
		}
		if($url) {
			return $upload_dir['baseurl'].'/'.$temp_folder;
		} else {
			return $theme_temp_folder;
		}
	}

	public function download($demo){
		$item = apply_filters( 'pxl_demo_item_download', get_option( 'template' ) );
		$download_to = $this->temp_folder().'/'.$demo.'.'.$this->data_format;
		$demo_file = $this->api_url.'demos'.'/'.$item.'/'.$demo.'.'.$this->data_format;
		$download_file = wp_safe_remote_get($demo_file, array('timeout' => 3000, 'stream' => true, 'filename' => $download_to));
		 
		if ( $download_file['response']['code'] === 200 && file_exists($download_to)) {
			//update_option('pxl_import_demo_id',$demo);
			return true;
		}else{
			unlink($download_to);
			return false;
		}
 
	}

	public function extract($demo) {
		$this->init_filesystem();
		$temp = $this->temp_folder();
		$file = $temp.DIRECTORY_SEPARATOR.$demo.'.'.$this->data_format;

		if(file_exists($file)) {
			unzip_file( $file, $temp );
			unlink($file);
		} else {
			return 'No file to extract or the provided file is not in '.$this->data_format;
		}
	}

	public function ajax_prepare_demo() { //$ret = '{"stat":0}'; echo $ret;  wp_die();
		$demo = esc_attr($_GET['demo']);
		$download_to = $this->temp_folder().'/'.$demo.'.'.$this->data_format;
		$demo_file = get_template_directory() . '/inc/demo-data/' .$demo.'.'.$this->data_format; 
		$ret = '';
  
		if( $demo == '' || !isset($demo) ) {
			$ret = '{"stat":0, "message":"Error: No id provided for the requested demo"}';
		}

		if(file_exists($demo_file)) {
			copy($demo_file, $download_to);
			$download = true;
		}else{
			$download_to = $this->temp_folder().'/'.$demo.'/attachment.zip';
			if(file_exists($download_to) ){
				$ret = '{"stat":1}'; echo $ret;  wp_die();
			}
			$download = $this->download($demo);
		}
  
		// The server wasn't able to download the file
		if( !$download ) {
			$ret = '{"stat":0, "message":"Your server was unable to connect to theme API server. Please check with your hosting company if the connection to '.$this->api_url.' is blocked in the firewall or network setup."}';
			echo $ret;
			echo '<style type="text/css">.pxl-form-upload-demo{display:block !important;}</style>';
			wp_die();
		}else{
			$this->extract($demo);
			$ret = '{"stat":1}';
		}
 
		echo $ret;
		wp_die();
	}

	public function ajax_upload_demo_manual() { 
       	$file = $_FILES['file'];
       	$file_size = $file['size']; 
       	$demo_id = $_POST['demo_id'];
       	$upload_max_filesize = ini_get( 'upload_max_filesize' );
       	 
       	if ( $file_size > wp_convert_hr_to_bytes( $upload_max_filesize ) ) {
       		echo "3"; wp_die();
        }
        if (!empty($file)) {
        	$file_is_exist = $this->temp_folder().'/'.$demo_id.'/attachment.zip';
        	$upload_to = $this->temp_folder().'/'.basename($file["name"]);
        	if(move_uploaded_file($file["tmp_name"], $upload_to)){
        		$this->init_filesystem();
				$temp = $this->temp_folder();
				$file = $temp.DIRECTORY_SEPARATOR.$file["name"];
				if(file_exists($file)) {
					unzip_file( $file, $temp );
					unlink($file);
					if(file_exists($file_is_exist) ){
						echo "1";
					}else{
						echo "2";
					}
					
				} else {
					echo "0";
				}
        	}else{
        		echo "0";
        	}
        }else{
            echo "0";
        }

		wp_die();
	}
}
new Pxl_Download_Demo();
?>
