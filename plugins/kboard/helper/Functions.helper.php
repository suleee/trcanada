<?php
if(!defined('ABSPATH')) exit;
/**
 * KBoard 워드프레스 게시판 사용자 함수
 * @link www.cosmosfarm.com
 * @copyright Copyright 2013 Cosmosfarm. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl.html
 */

/**
 * 배열에서 허가된 데이터만 남긴다.
 * @param array $array
 * @param array $whitelist
 * @return array
 */
function kboard_array_filter($array, $whitelist){
	if(function_exists('array_intersect_key')){
		return array_intersect_key($array, array_flip($whitelist));
	}
	foreach($array as $key=>$value){
		if(!in_array($key, $whitelist)) unset($array[$key]);
	}
	return $array;
}

/**
 * JSON 인코더
 * @param array $val
 * @return string
 */
function kboard_json_encode($val){
	/*
	 * http://kr1.php.net/json_encode#113219
	 */
	if(function_exists('json_encode')){
		return json_encode($val);
	}

	if(is_string($val)) return '"'.addslashes($val).'"';
	if(is_numeric($val)) return $val;
	if($val === null) return 'null';
	if($val === true) return 'true';
	if($val === false) return 'false';

	$assoc = false;
	$i = 0;
	foreach($val as $k=>$v){
		if($k !== $i++){
			$assoc = true;
			break;
		}
	}
	$res = array();
	foreach($val as $k=>$v){
		$v = kboard_json_encode($v);
		if($assoc){
			$k = '"'.addslashes($k).'"';
			$v = $k.':'.$v;
		}
		$res[] = $v;
	}
	$res = implode(',', $res);

	return ($assoc)? '{'.$res.'}' : '['.$res.']';
}

/**
 * 파일의 MIME Content-type을 반환한다.
 * @param string $file
 * @return string
 */
function kboard_mime_type($file){
	/*
	 * http://php.net/manual/en/function.mime-content-type.php#87856
	 */
	$mime_types = array(
			'txt' => 'text/plain',
			'htm' => 'text/html',
			'html' => 'text/html',
			'php' => 'text/html',
			'css' => 'text/css',
			'js' => 'application/javascript',
			'json' => 'application/json',
			'xml' => 'application/xml',
			'swf' => 'application/x-shockwave-flash',
			'flv' => 'video/x-flv',
				
			// images
			'png' => 'image/png',
			'jpe' => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'jpg' => 'image/jpeg',
			'gif' => 'image/gif',
			'bmp' => 'image/bmp',
			'ico' => 'image/vnd.microsoft.icon',
			'tiff' => 'image/tiff',
			'tif' => 'image/tiff',
			'svg' => 'image/svg+xml',
			'svgz' => 'image/svg+xml',
				
			// archives
			'zip' => 'application/zip',
			'rar' => 'application/x-rar-compressed',
			'exe' => 'application/x-msdownload',
			'msi' => 'application/x-msdownload',
			'cab' => 'application/vnd.ms-cab-compressed',
			'7z' => 'application/x-7z-compressed',
				
			// audio/video
			'mp3' => 'audio/mpeg',
			'qt' => 'video/quicktime',
			'mov' => 'video/quicktime',
				
			// adobe
			'pdf' => 'application/pdf',
			'psd' => 'image/vnd.adobe.photoshop',
			'ai' => 'application/postscript',
			'eps' => 'application/postscript',
			'ps' => 'application/postscript',
				
			// ms office
			'doc' => 'application/msword',
			'rtf' => 'application/rtf',
			'xls' => 'application/vnd.ms-excel',
			'ppt' => 'application/vnd.ms-powerpoint',
			'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
			'xltx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
			'potx' => 'application/vnd.openxmlformats-officedocument.presentationml.template',
			'ppsx' => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
			'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
			'sldx' => 'application/vnd.openxmlformats-officedocument.presentationml.slide',
			'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			'dotx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
			'xlam' => 'application/vnd.ms-excel.addin.macroEnabled.12',
			'xlsb' => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
				
			// open office
			'odt' => 'application/vnd.oasis.opendocument.text',
			'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
				
			// etc
			'hwp' => 'application/hangul',
	);

	$mime_type = '';
	$temp = basename($file);
	$temp = explode('.', $temp);
	$temp = array_pop($temp);
	$ext = strtolower($temp);

	if(array_key_exists($ext, $mime_types)){
		$mime_type = $mime_types[$ext];
	}
	else if(function_exists('mime_content_type') && file_exists($file)){
		$mime_type = mime_content_type($file);
	}
	else if(function_exists('finfo_open') && file_exists($file)){
		$finfo = finfo_open(FILEINFO_MIME);
		$mime_type = finfo_file($finfo, $file);
		finfo_close($finfo);
	}

	if($mime_type) return $mime_type;
	else return 'application/octet-stream';
}

/**
 * 권한을 한글로 출력한다.
 * @param string $permission
 * @return string
 */
function kboard_permission($permission){
	if($permission == 'all'){
		return __('제한없음', 'kboard');
	}
	else if($permission == 'author'){
		return __('로그인 사용자', 'kboard');
	}
	else if($permission == 'editor'){
		return __('선택된 관리자', 'kboard');
	}
	else if($permission == 'administrator'){
		return __('최고관리자', 'kboard');
	}
	else if($permission == 'roles'){
		return __('직접선택', 'kboard');
	}
	else{
		return $permission;
	}
}

/**
 * Captcha 이미지를 생성하고 이미지 주소를 반환한다.
 * @return string
 */
function kboard_captcha(){
	include_once KBOARD_DIR_PATH . '/class/KBCaptcha.class.php';
	$captcha = new KBCaptcha();
	return $captcha->createImage();
}

/**
 * 이미지 사이즈를 조절한다.
 * @param string $image_src
 * @param int $width
 * @param int $height
 * @return string
 */
function kboard_resize($image_src, $width, $height){
	$upload_dir = wp_upload_dir();
	$dirname = dirname($image_src);
	$dirname = explode('/wp-content/uploads', $dirname);
	$resize_dir = end($dirname);

	$basename = basename($image_src);
	$fileinfo = pathinfo($basename);
	$resize_name = basename($image_src, '.'.$fileinfo['extension']) . "-{$width}x{$height}.{$fileinfo['extension']}";

	$new_image = $upload_dir['basedir'] . "{$resize_dir}/{$resize_name}";
	$new_image_src = content_url("uploads{$resize_dir}/{$resize_name}");

	if(file_exists($new_image)){
		return $new_image_src;
	}

	$image_editor = wp_get_image_editor($upload_dir['basedir'] . "{$resize_dir}/{$basename}");
	if(!is_wp_error($image_editor)){
		$image_editor->resize($width, $height, true);
		$image_editor->save($new_image);
		return $new_image_src;
	}
	else{
		return site_url($image_src);
	}
}

/**
 * 리사이즈 이미지를 지운다.
 * @param string $image_src
 */
function kbaord_delete_resize($image_src){
	if(file_exists($image_src)){
		$size = getimagesize($image_src);
		if($size){
			$fileinfo = pathinfo($image_src);
			$original_name = basename($image_src, '.'.$fileinfo['extension']).'-';
			$dir = dirname($image_src);
			if($dh = @opendir($dir)){
				while(($file = readdir($dh)) !== false){
					if($file == "." || $file == "..") continue;
					if(strpos($file, $original_name) !== false){
						@unlink($dir . '/' . $file);
					}
				}
			}
			closedir($dh);
		}
	}
}

/**
 * 새글 알림 시간을 반환한다.
 * @return int
 */
function kboard_new_document_notify_time(){
	return get_option('kboard_new_document_notify_time', '86400');
}

/**
 * 업로드 가능한 파일 크기를 반환한다.
 */
function kboard_limit_file_size(){
	return intval(get_option('kboard_limit_file_size', kboard_upload_max_size()));
}

/**
 * 서버에 설정된 최대 업로드 크기를 반환한다.
 * @link http://stackoverflow.com/questions/13076480/php-get-actual-maximum-upload-size
 * @return int
 */
function kboard_upload_max_size(){
	static $max_size = -1;
	if($max_size < 0){
		$max_size = kboard_parse_size(ini_get('post_max_size'));
		$upload_max = kboard_parse_size(ini_get('upload_max_filesize'));
		if($upload_max > 0 && $upload_max < $max_size){
			$max_size = $upload_max;
		}
	}
	return $max_size;
}

/**
 * 바이트로 크기를 변환한다.
 * @link http://stackoverflow.com/questions/13076480/php-get-actual-maximum-upload-size
 * @return int
 */
function kboard_parse_size($size){
	$unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
	$size = preg_replace('/[^0-9\.]/', '', $size);
	if($unit){
		return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
	}
	else{
		return round($size);
	}
}

/**
 * 허가된 첨부파일 확장자를 반환한다.
 * @param boolean $to_array
 */
function kboard_allow_file_extensions($to_array=false){
	$file_extensions = get_option('kboard_allow_file_extensions', 'jpg, jpeg, gif, png, bmp, zip, 7z, hwp, ppt, xls, doc, txt, pdf, xlsx, pptx, docx, torrent, smi, mp4, mp3');
	$file_extensions = trim($file_extensions);
	
	if($to_array){
		$file_extensions = explode(',', $file_extensions);
		return array_map('trim', $file_extensions);
	}
	return $file_extensions;
}

/**
 * 작성자 금지단어를 반환한다.
 * @param string $to_array
 */
function kboard_name_filter($to_array=false){
	$name_filter = get_option('kboard_name_filter', '관리자, 운영자, admin, administrator');
	$name_filter = trim($name_filter);
	
	if($to_array){
		$name_filter = explode(',', $name_filter);
		return array_map('trim', $name_filter);
	}
	return $name_filter;
}

/**
 * 본문/제목/댓글 금지단어를 반환한다.
 * @param string $to_array
 */
function kboard_content_filter($to_array=false){
	$content_filter = get_option('kboard_content_filter', '');
	$content_filter = trim($content_filter);
	
	if($to_array){
		$content_filter = explode(',', $content_filter);
		return array_map('trim', $content_filter);
	}
	return $content_filter;
}

/**
 * 구글 reCAPTCHA 사용 여부를 체크한다.
 * @return boolean
 */
function kboard_use_recaptcha(){
	static $use_recaptcha;
	if($use_recaptcha === null){
		$site_key = get_option('kboard_recaptcha_site_key');
		$secret_key = get_option('kboard_recaptcha_secret_key');
		
		if($site_key && $secret_key){
			$use_recaptcha = true;
		}
		else{
			$use_recaptcha = false;
		}
	}
	return $use_recaptcha;
}

/**
 * 구글 reCAPTCHA의 Site key를 반환한다.
 * @return string
 */
function kboard_recaptcha_site_key(){
	static $recaptcha_site_key;
	if($recaptcha_site_key === null){
		$recaptcha_site_key = get_option('kboard_recaptcha_site_key');
		
		wp_enqueue_script('recaptcha');
	}
	return $recaptcha_site_key;
}

/**
 * 구글 reCAPTCHA의 Secret key를 반환한다.
 * @return string
 */
function kboard_recaptcha_secret_key(){
	static $recaptcha_secret_key;
	if($recaptcha_secret_key === null){
		$recaptcha_secret_key = get_option('kboard_recaptcha_secret_key');
	}
	return $recaptcha_secret_key;
}

/**
 * 사용자 IP 주소를 반환한다.
 * @return string
 */
function kboard_user_ip(){
	static $ip;
	if($ip === null){
		if(!empty($_SERVER['HTTP_CLIENT_IP'])){
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else{
			$ip = $_SERVER['REMOTE_ADDR'];
		}
	}
	return apply_filters('kboard_user_ip', $ip);
}

/**
 * category1 값을 반환한다.
 * @return string
 */
function kboard_category1(){
	static $category1;
	if($category1 === null){
		$_GET['category1'] = isset($_GET['category1'])?sanitize_text_field($_GET['category1']):'';
		$category1 = $_GET['category1'];
	}
	return apply_filters('kboard_category1', $category1);
}

/**
 * category2 값을 반환한다.
 * @return string
 */
function kboard_category2(){
	static $category2;
	if($category2 === null){
		$_GET['category2'] = isset($_GET['category2'])?sanitize_text_field($_GET['category2']):'';
		$category2 = $_GET['category2'];
	}
	return apply_filters('kboard_category2', $category2);
}

/**
 * uid 값을 반환한다.
 * @return string
 */
function kboard_uid(){
	static $uid;
	if($uid === null){
		$_GET['uid'] = isset($_GET['uid'])?intval($_GET['uid']):'';
		$_POST['uid'] = isset($_POST['uid'])?intval($_POST['uid']):'';
		$uid = $_GET['uid']?$_GET['uid']:$_POST['uid'];
	}
	return apply_filters('kboard_uid', $uid);
}

/**
 * execute_uid 값을 반환한다.
 * @return string
 */
function kboard_execute_uid(){
	static $execute_uid;
	if($execute_uid === null){
		$_GET['execute_uid'] = isset($_GET['execute_uid'])?intval($_GET['execute_uid']):'';
		$execute_uid = $_GET['execute_uid'];
	}
	return apply_filters('kboard_execute_uid', $execute_uid);
}

/**
 * mod 값을 반환한다.
 * @param string $default
 * @return string
 */
function kboard_mod($default=''){
	static $mod;
	if($mod === null){
		$_GET['mod'] = isset($_GET['mod'])?sanitize_key($_GET['mod']):'';
		$_POST['mod'] = isset($_POST['mod'])?sanitize_key($_POST['mod']):'';
		$mod = $_GET['mod']?$_GET['mod']:$_POST['mod'];
	}
	if(!in_array($mod, array('list', 'document', 'editor', 'remove', 'order', 'complete', 'history', 'sales'))){
		return $default;
	}
	return apply_filters('kboard_mod', $mod);
}

/**
 * keyword 값을 반환한다.
 * @return string
 */
function kboard_keyword(){
	static $keyword;
	if($keyword === null){
		$_GET['keyword'] = isset($_GET['keyword'])?sanitize_text_field($_GET['keyword']):'';
		$keyword = $_GET['keyword'];
	}
	return apply_filters('kboard_keyword', $keyword);
}

/**
 * target 값을 반환한다.
 * @return string
 */
function kboard_target(){
	static $target;
	if($target === null){
		$_GET['target'] = isset($_GET['target'])?sanitize_key($_GET['target']):'';
		$target = $_GET['target'];
	}
	return apply_filters('kboard_target', $target);
}

/**
 * pageid 값을 반환한다.
 * @return string
 */
function kboard_pageid(){
	static $pageid;
	if($pageid === null){
		$_GET['pageid'] = isset($_GET['pageid'])?intval($_GET['pageid']):1;
		$pageid = $_GET['pageid'];
	}
	return apply_filters('kboard_pageid', $pageid);
}

/**
 * parent_uid 값을 반환한다.
 * @return string
 */
function kboard_parent_uid(){
	static $parent_uid;
	if($parent_uid === null){
		$_GET['parent_uid'] = isset($_GET['parent_uid'])?intval($_GET['parent_uid']):'';
		$parent_uid = $_GET['parent_uid'];
	}
	return apply_filters('kboard_parent_uid', $parent_uid);
}

/**
 * kboard_id 값을 반환한다.
 * @return string
 */
function kboard_id(){
	static $kboard_id;
	if($kboard_id === null){
		$_GET['kboard_id'] = isset($_GET['kboard_id'])?intval($_GET['kboard_id']):'';
		$kboard_id = $_GET['kboard_id'];
	}
	return apply_filters('kboard_id', $kboard_id);
}

/**
 * compare 값을 반환한다.
 * @return string
 */
function kboard_compare(){
	static $compare;
	if($compare === null){
		$compare = isset($_REQUEST['compare'])?sanitize_text_field($_REQUEST['compare']):'';
		if(!in_array($compare, array('=', '!=', '>', '>=', '<', '<=', 'LIKE', 'NOT LIKE'))){
			$compare = 'LIKE';
		}
	}
	return apply_filters('kboard_compare', $compare);
}

/**
 * start_date 값을 반환한다.
 * @return string
 */
function kboard_start_date(){
	static $start_date;
	if($start_date === null){
		$start_date = isset($_REQUEST['start_date'])?sanitize_text_field($_REQUEST['start_date']):'';
	}
	return apply_filters('kboard_start_date', $start_date);
}

/**
 * end_date 값을 반환한다.
 * @return string
 */
function kboard_end_date(){
	static $end_date;
	if($end_date === null){
		$end_date = isset($_REQUEST['end_date'])?sanitize_text_field($_REQUEST['end_date']):'';
	}
	return apply_filters('kboard_end_date', $end_date);
}

/**
 * kboard_option 값을 반환한다.
 * @return array
 */
function kboard_search_option(){
	static $search_option;
	if($search_option === null){
		$search_option = (isset($_REQUEST['kboard_search_option'])&&is_array($_REQUEST['kboard_search_option']))?$_REQUEST['kboard_search_option']:array();
	}
	return apply_filters('kboard_search_option', $search_option);
}

/**
 * order_id 값을 반환한다.
 * @return string
 */
function kboard_order_id(){
	static $order_id;
	if($order_id === null){
		$order_id = isset($_REQUEST['order_id'])?intval($_REQUEST['order_id']):'';
	}
	return apply_filters('kboard_order_id', $order_id);
}

/**
 * order_item_id 값을 반환한다.
 * @return string
 */
function kboard_order_item_id(){
	static $order_item_id;
	if($order_item_id === null){
		$order_item_id = isset($_REQUEST['order_item_id'])?intval($_REQUEST['order_item_id']):'';
	}
	return apply_filters('kboard_order_item_id', $order_item_id);
}
?>