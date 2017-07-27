<?php
/* Add admin menu */
if( is_admin() ) {
    add_action('admin_menu', 'display_loobo_menu');
}
function display_loobo_menu() {
    add_menu_page('主题设置', '主题设置', 'administrator','barley', 'loobo_setting_page','dashicons-admin-generic',29);
    add_submenu_page('barley', '主题设置 &gt; 设置', '主题设置', 'administrator','barley', 'loobo_setting_page');
}
function loobo_setting_page(){
	settings_errors();
	?>
	<style>
	.nav-tab-wrapper, h1.nav-tab-wrapper, h3.nav-tab-wrapper { border-bottom: none !important;margin: 0;padding-top: 9px;padding-bottom: 0;line-height: inherit;}
	.nav-tab-active, .nav-tab-active:focus, .nav-tab-active:focus:active, .nav-tab-active:hover {border-bottom: 1px solid #f1f1f1; background: #fff !important;color: #0073aa !important;}
	.nav-tab-active {margin-bottom: -1px;color: #444;}
	.nav-tab {float: left;border: none !important; border-bottom: none;background: #f1f1f1; margin-left: 0 !important;padding: 15px 40px;font-size: 14px;line-height: 24px;color: #555;}
    .m-r-10{margin-right: 10px;}
    .wp-person a:focus .gravatar, a:focus, a:focus .media-icon img {color: #124964;-webkit-box-shadow: none !important; box-shadow: none !important; }
	input.regular-text {width: 25em;padding: 5px 0;}
	.notice, div.error, div.updated {background: #fff;border-left: 4px solid #fff;-webkit-box-shadow: none !important; box-shadow: none !important; margin: 5px 15px 2px;padding: 1px 12px;}
	.media-upload-form .notice, .media-upload-form div.error, .wrap .notice, .wrap div.error, .wrap div.updated {margin: 0 0 0 0 !important;}
	.form-table, .form-table td, .form-table td p, .form-table th, .form-wrap label {font-size: 15px !important;}
	</style>
	<div class="wrap">
		<h2 class="nav-tab-wrapper">
	        <a class="nav-tab nav-tab-active" href="javascript:;" id="tab-title-general"><span class="m-r-10 dashicons dashicons-dashboard"></span>基础功能</a>
	        <a class="nav-tab" href="javascript:;" id="tab-title-skin"><span class="m-r-10 dashicons dashicons-admin-appearance"></span>外观</a>
	        <a class="nav-tab" href="javascript:;" id="tab-title-feature"><span class="m-r-10 dashicons dashicons-editor-code"></span>文章</a>
			<a class="nav-tab" href="javascript:;" id="tab-title-qiniu"><span class="m-r-10 dashicons dashicons-admin-plugins"></span>七牛</a>
			<a class="nav-tab" href="javascript:;" id="tab-title-pay"><span class="m-r-10 dashicons dashicons-thumbs-up"></span>打赏</a>  
			<a class="nav-tab" href="javascript:;" id="tab-title-mail"><span class="m-r-10 dashicons dashicons-email-alt"></span>邮件</a>
			<a class="nav-tab" href="javascript:;" id="tab-title-login"><span class="m-r-10 dashicons dashicons-twitter"></span>登录</a>
	        <a class="nav-tab" href="javascript:;" id="tab-title-social"><span class="m-r-10 dashicons dashicons-share"></span>社交</a>     
	    </h2>
		<form style="background-color:#fff;padding:20px;" action="options.php" method="POST">
			<?php settings_fields( 'barley_group' ); ?>
			<?php
				$labels = loobo_get_option_labels();
				extract($labels);
			?>
			<?php foreach ( $sections as $section_name => $section ) { ?>
	            <div id="tab-<?php echo $section_name; ?>" class="div-tab hidden">
	                <?php loobo_option_do_settings_section($option_page, $section_name); ?>
	            </div>                      
	        <?php } ?>
			<input type="hidden" name="<?php echo $option_name;?>[current_tab]" id="current_tab" value="" />
			<?php submit_button(); ?>
		</form>
		<?php loobo_option_tab_script(); ?>
	</div>
<?php
}
function loobo_setting_active_page(){
	settings_errors();
	$order = loobo_get_setting('order');
	$sn = loobo_get_setting('sn');
	?>
	<div class="wrap">
		<form action="options.php" method="POST">
			<?php settings_fields( 'barley_group' ); ?>
			<?php
				settings_errors();
				$labels = loobo_get_option_labels();
				extract($labels);
			?>
			<?php foreach ( $sections as $section_name => $section ) { ?>
	            <div id="tab-<?php echo $section_name; ?>" class="div-tab <?php if($section_name!='auth') echo 'hidden'; ?>">
	                <?php loobo_option_do_settings_section($option_page, $section_name); ?>
	            </div>                      
	        <?php } ?>
			<input type="hidden" name="<?php echo $option_name;?>[current_tab]" id="current_tab" value="" />
			<?php submit_button(); ?>
		</form>
	</div>

<?php	
}
function loobo_option_tab_script(){
	$current_tab = '';
	$option_name = 'barley';
	$option = get_option( $option_name );
	if(!empty($_GET['settings-updated'])){
		$current_tab = $option['current_tab'];
	}
	?>
	<script type="text/javascript">
		jQuery('div.div-tab').hide();
	<?php if($current_tab){ ?>
		jQuery('#tab-title-<?php echo $current_tab; ?>').addClass('nav-tab-active');
		jQuery('#tab-<?php echo $current_tab; ?>').show();
		jQuery('#current_tab').val('<?php echo $current_tab; ?>');
	<?php } else{ ?>
		jQuery('h2 a.nav-tab').first().addClass('nav-tab-active');
		jQuery('div.div-tab').first().show();
	<?php } ?>
		jQuery(function($){
			$('h2 a.nav-tab').on('click',function(){
		        $('h2 a.nav-tab').removeClass('nav-tab-active');
		        $(this).addClass('nav-tab-active');
		        $('div.div-tab').hide();
		        $('#'+jQuery(this)[0].id.replace('title-','')).show();
		        $('#current_tab').val($(this)[0].id.replace('tab-title-',''));
		    });
		});
	</script>
<?php
}
function loobo_option_field_callback($field) {

	$field_name		= $field['name'];
	$field['key']	= $field_name;
	$field['name']	= $field['option'].'['.$field_name.']';

	$options	= loobo_get_option( $field['option'] );
	$field['value'] = (isset($options[$field_name]))?$options[$field_name]:'';

	echo loobo_admin_get_field_html($field);
}
function loobo_admin_get_field_html($field){

	$key		= $field['key'];
	$name		= $field['name'];
	$type		= $field['type'];
	$value		= $field['value'];

	$class		= isset($field['class'])?$field['class']:'regular-text';
	$description= (!empty($field['description']))?( ($type == 'checkbox')? ' <label for="'.$key.'">'.$field['description'].'</label>':'<p>'.$field['description'].'</p>'):'';

	$title 	= isset($field['title'])?$field['title']:$field['name'];
	$label 	= '<label for="'.$key.'">'.$title.'</label>';

	switch ($type) {
		case 'text':
		case 'password':
		case 'hidden':
		case 'url':
		case 'color':
		case 'url':
		case 'tel':
		case 'email':
		case 'month':
		case 'date':
		case 'datetime':
		case 'datetime-local':
		case 'week':
			$field_html = '<input name="'.$name.'" id="'. $key.'" type="'.$type.'"  value="'.esc_attr($value).'" class="'.$class.'" />';
			break;
		case 'range':
			$max	= isset($field['max'])?' max="'.$field['max'].'"':'';
			$min	= isset($field['min'])?' min="'.$field['min'].'"':'';
			$step	= isset($field['step'])?' step="'.$field['step'].'"':'';

			$field_html ='<input name="'.$name.'" id="'. $key.'" type="'.$type.'"  value="'.esc_attr($value).'"'.$max.$min.$step.' class="'.$class.'" onchange="jQuery(\'#'.$key.'_span\').html(jQuery(\'#'.$key.'\').val());"  /> <span id="'.$key.'_span">'.$value.'</span>';
			break;

		case 'nloobober':
			$max	= isset($field['max'])?' max="'.$field['max'].'"':'';
			$min	= isset($field['min'])?' min="'.$field['min'].'"':'';
			$step	= isset($field['step'])?' step="'.$field['step'].'"':'';

			$field_html = '<input name="'.$name.'" id="'. $key.'" type="'.$type.'"  value="'.esc_attr($value).'" class="'.$class.'"'.$max.$min.$step.' />';
			break;

		case 'checkbox':
			$field_html = '<input name="'.$name.'" id="'. $key.'" type="checkbox"  value="1" '.checked("1",$value,false).' />';
			break;

		case 'textarea':
			$rows = isset($field['rows'])?$field['rows']:6;
			$field_html = '<textarea name="'.$name.'" id="'. $key.'" rows="'.$rows.'" cols="50"  class="'.$class.' code" >'.esc_attr($value).'</textarea>';
			break;

		case 'select':
			$field_html  = '<select name="'.$name.'" id="'. $key.'">';
			foreach ($field['options'] as $option_title => $option_value){ 
				$field_html .= '<option value="'.$option_value.'" '.selected($option_value,$value,false).'>'.$option_title.'</option>';
			}
			$field_html .= '</select>';
			
			break;

		case 'radio':
			$field_html  = '';
			foreach ($field['options'] as $option_value => $option_title) {
				$field_html  .= '<input name="'.$name.'" type="radio" id="'.$key.'" value="'.$option_value .'" '.checked($option_value,$value,false).' />'.$option_title.'<br />';
			}
			break;

		case 'image':
			$field_html = '<input name="'.$name.'" id="'.$key.'" type="url"  value="'.esc_attr($value).'" class="'.$class.'" /><input type="button" class="loobo_upload button" value="上传">';
			$field_html .= '<img src="'.esc_attr($value).'" style="max-width:120px;vertical-align: top;margin-left: 20px;" />';
            break;
        case 'mulit_image':
        case 'multi_image':
        	$field_html  = '';
            if(is_array($value)){
                foreach($value as $image_key=>$image){
                    if(!empty($image)){
                    	$field_html .= '<span><input type="text" name="'.$name.'[]" id="'. $key.'" value="'.esc_attr($image).'"  class="'.$class.'" /><a href="javascript:;" class="button del_image">删除</a></span>';
                    }
                }
            }
            $field_html  = '<span><input type="text" name="'.$name.'[]" id="'.$key.'" value="" class="'.$class.'" /><input type="bu
            tton" class="loobo_mulit_upload button" style="width:110px;" value="选择图片[多选]" title="按住Ctrl点击鼠标左键可以选择多张图片"></span>';
            break;
        case 'mulit_text':
        case 'multi_text':
        	$field_html  = '';
            if(is_array($value)){
                foreach($value as $text_key=>$item){
                    if(!empty($item)){
                    	$field_html .= '<span><input type="text" name="'.$name.'[]" id="'. $key.'" value="'.esc_attr($item).'"  class="'.$class.'" /><a href="javascript:;" class="button del_image">删除</a></span>';
                    }
                }
            }
            $field_html  = '<span><input type="text" name="'.$name.'[]" id="'.$key.'" value="" class="'.$class.'" /><a class="loobo_mulit_text button">添加选项</a></span>';
            break;

        case 'file':
        	$field_html  = '<input type="file" name="'.$name.'" id="'. $key.'" />'.'已上传：'.wp_get_attachment_link($value);
            break;
		
		default:
			$field_html = '<input name="'.$name.'" id="'. $key.'" type="text"  value="'.esc_attr($value).'" class="'.$class.'" />';
			break;
	}

	return $field_html.$description;
}

/* Add setting options */
function loobo_get_option_labels(){
	$option_group               =   'barley_group';
	$option_name = $option_page =   'barley';
	$field_validate				=	'barley_validate';
	$home = home_url();
    //常规设置
	$general_fields = array(
		'description'	=> array(
		'title'=>'网站描述',	
		'type'=>'textarea',	
		'description'=>''
		),
		
		'keywords'	    => array(
		    'title'=>'关键词',	
			'type'=>'textarea',	
			'description'=>''
		),
			
		'page_sign'	    => array(
		'title'=>'标题链接符号',	
		'type'=>'select',	
		'description'=>'选择后切勿更改,对SEO不友好',
			'options'=>array(
				'中横线- '=>'-',
			    '下划线_'=>'_'
				)
			),
	);
    //外观设置
	$skin_fields = array(
		'favicon'	=> array(
		'title'=>'网站Favicon图标',	
	    'type'=>'image',	
		'description'=>''
		),
		'logo'	=> array(
		'title'=>'高清Logo',	
		'type'=>'image',	
		'description'=>'像素大小为76px * 76px'
		),
		'theme-jquery'	=> array(
		'title'=>'jQuery库',	
		'type'=>'text',	
		'description'=>'可以使用第三方CDN或者自己的CDN'
		),
		'login-bg'	=> array(
		'title'=>'登录页背景图片',	
		'type'=>'image',	
		'description'=>''
		),
		'reg-bg'	=> array(
		'title'=>'注册页背景图片',	
		'type'=>'image',	
		'description'=>''
		),
		'pass-bg'	=> array(
		'title'=>'忘记密码页背景图片',	
		'type'=>'image',	
		'description'=>''
		)
	);
    //功能设置
	$feature_fields = array(
	    'share'	=> array(
		'title'=>'分享功能',	
	    'type'=>'checkbox',	
		'description'=>'开启后将显示在文章内容页面'
		),
		'lightbox'	=> array(
		'title'=>'开启图片灯箱效果',	
	    'type'=>'checkbox',	
		'description'=>'我猜你一定会打开这个功能'
		),
	    'nocategory'	=> array(
		'title'=>'开启去除分类文本',	
	    'type'=>'checkbox',	
		'description'=>'开启去除分类文本'
		),
		'themeid'	=> array(
		'title'=>'WordPress主题显示ID',	
	    'type'=>'text',	
		'description'=>'输入你想要显示文章分类的ID，纯数字'
		),
		'typechoid'	=> array(
		'title'=>'typecho主题显示ID',	
	    'type'=>'text',	
		'description'=>'输入你想要显示文章分类的ID，纯数字'
		),
		'tuijian1'	=> array(
		'title'=>'推荐页图像1',	
	    'type'=>'image',	
		'description'=>'推荐页面图片'
		),
		'tuijian2'	=> array(
		'title'=>'推荐页图像2',	
	    'type'=>'image',	
		'description'=>'推荐页面图片接'
		),
		'tuijian3'	=> array(
		'title'=>'推荐页图像3',	
	    'type'=>'image',	
		'description'=>'推荐页面图片'
		)
	);
	//七牛设置
	$qiniu_fields = array(
	    'host'	=> array(
		'title'=>'七牛绑定域名',	
	    'type'=>'text',	
		'description'=>'设置为你在七牛绑定的域名,没绑定则填写七牛域名仅作显示图片的链接使用,注意要域名前面要加上<strong>  http:// </strong>'
		),
		'prefix'	=> array(
		'title'=>'前缀',	
	    'type'=>'text',	
		'description'=>'如果你想像七牛一样在上传的图片前加前缀则填写;例如:<strong>img</strong>,多个前缀:<strong>img/2015</strong>,不想则留空;添加前缀后图片的链接类似于：七牛绑定域名/前缀/图片名称'
		),
		'bucket'	=> array(
		'title'=>'空间名称bucket',	
	    'type'=>'text',	
		'description'=>''
		),
		'imgurl'	=> array(
		'title'=>'图片链接形式',	
	    'type'=>'checkbox',	
		'description'=>'选择后,图片链接原始地址,图片地址类似<strong>&lt;a&gt;&lt;img&gt;&lt;/a&gt;</strong>;不选则无链接,类似<strong>&lt;img&gt;</strong>'
		),
		'accesskey'	=> array(
		'title'=>'AccessKey',	
	    'type'=>'text',	
		'description'=>'在<strong>七牛帐号</strong>-<strong>个人中心</strong>-<strong>密匙管理</strong>中查看'
		),
		'secretkey'	=> array(
		'title'=>'SecretKey',	
	    'type'=>'text',	
		'description'=>'在<strong>七牛帐号</strong>-<strong>个人中心</strong>-<strong>密匙管理</strong>中查看'
		),
		'watermark' => array(
		'title'=>'图片水印字符串',
		'type' =>'textarea',
		'description'=>'设置你的七牛水印字符串'
		)
	);
	//打赏设置
	$pay_fields = array(
	    'paysub'=> array(
		'title'=>'文章打赏样式',	
		'type'=>'select',	
		'description'=>'选择样式后需要在下方进行相关设置',
			'options'=>array(
				'图片模式 '=>'pay_pic',
			    '金额自定义'=>'pay_cum'
				)
			),
		'zanshang'	=> array(
		'title'=>'文章打赏',	
	    'type'=>'checkbox',	
		'description'=>'文章打赏功能'
		),
		//'weixin_pay_5'	=> array(
		///'title'=>'金额设置<span class="dashicons dashicons-arrow-right-alt"></span>',	
		//'type'=>'text',	
		//'description'=>'设置打赏金额要与下方二维码匹配'
		//),
		//'weixin5'	=> array(
		//'title'=>'收款二维码',	
		//'type'=>'image',	
		//'description'=>'微信5元收款码'
		//),
		//'weixin_pay_10'	=> array(
		//'title'=>'金额设置<span class="dashicons dashicons-arrow-right-alt"></span>',	
		//'type'=>'text',	
		//'description'=>'设置打赏金额要与下方二维码匹配'
		//),
		//'weixin10'	=> array(
		//'title'=>'收款二维码',	
		//'type'=>'image',	
		//'description'=>'微信10元收款码'
		//),
		///'weixin_pay_20'	=> array(
		//'title'=>'金额设置<span class="dashicons dashicons-arrow-right-alt"></span>',	
		//'type'=>'text',	
		//'description'=>'设置打赏金额要与下方二维码匹配'
		//),
		//'weixin20'	=> array(
		//'title'=>'收款二维码',	
		//'type'=>'image',	
		//'description'=>'微信20元收款码'
		//),
		'pay_alipay'=> array(
		'title'=>'支付宝打赏二维码',	
		'type'=>'image',	
		'description'=>'用于文章打赏，像素：200*200px'
		)
	);
    //邮箱设置
	$mail_fields = array(
	    'smtp_name'	=> array(
		'title'=>'发信人名称',	
	    'type'=>'text',	
		'description'=>''
		),
		'smtp_account'	=> array(
		'title'=>'邮箱账户',	
	    'type'=>'text',	
		'description'=>''
		),
		'smtp_pass'	=> array(
		'title'=>'密码',	
	    'type'=>'password',	
		'description'=>''
		),
		'smtp_host'	=> array(
		'title'=>'smtp服务器',	
	    'type'=>'text',	
		'description'=>''
		)
	);
	//第三方登录设置
	$login_fields = array(
	    'twitter_oauth_consumer_key'	=> array(
		'title'=>'twitter KEY',	
		'type'=>'text',	
		'description'=>''
		),
		'twitter_oauth_consumer_secret'	=> array(
		'title'=>'twitter secret',	
		'type'=>'text',	
		'description'=>''
		)
		//'open_qq'	=> array(
		//'title'=>'QQ快速登录',	
		//'type'=>'checkbox',	
		//'description'=>'在登录弹窗等区域显示QQ快速登录按钮，需要自行申请APP KEY'
		//),
		//'qqid'	=> array(
		//'title'=>'QQ开放平台ID',	
		//'type'=>'text',	
		//'description'=>''
		//),
		//'qqkey'	=> array(
		//'title'=>'QQ开放平台KEY',	
		//'type'=>'text',	
		//'description'=>''
		//),
		//'open_weibo'	=> array(
		//'title'=>'微博快速登录',	
		//'type'=>'checkbox',	
		//'description'=>'在登录弹窗等区域显示新浪微博快速登录按钮，需要自行申请APP KEY'
		//),
		//'sinaid'	=> array(
		//'title'=>'微博开放平台KEY',	
		//'type'=>'text',	
		//'description'=>''
		//),
		//'sinakey'	=> array(
		//'title'=>'微博开放平台SECRET',	
		//'type'=>'text',	'description'=>''
		//),
		//'wxid'	=> array(
		//'title'=>'微信开放平台ID',	
		//'type'=>'text',	
		//'description'=>''
		//),
		//'wxkey'	=> array(
		//'title'=>'微信开放平台key',	
		//'type'=>'text',	'description'=>''
		//),
		//'login_url'	=> array(
		//'title'=>'返回地址',	
		//'type'=>'text',	
		//'description'=>'绑定登录后返回的地址，一般是首页或者个人中心页', 
		//)
	);
	//
	$social_fields = array(
		'weibo'	=> array(
		    'title'=>'微博地址',	
			'type'=>'text',	
			'description'=>''
		),
		'github'	=> array(
		    'title'=>'Github链接',	
			'type'=>'text',	
			'description'=>''
		),
		'qq'	=> array(
		    'title'=>'qq号码',	
			'type'=>'text',	
			'description'=>''
		),
		'qrcode'	=> array(
		    'title'=>'QQ群二维码',	
			'type'=>'image',	
			'description'=>''
		)
	);
	$sections = array( 
    	'general'	=>array('title'=>'',		'fields'=>$general_fields,	'callback'=>'',	),
    	'skin'		=>array('title'=>'',		'fields'=>$skin_fields,	    'callback'=>'',	),
    	'feature'	=>array('title'=>'',	    'fields'=>$feature_fields,	'callback'=>'',	),
		'qiniu'		=>array('title'=>'',		'fields'=>$qiniu_fields,	'callback'=>'',	),
		'pay'	    =>array('title'=>'',	    'fields'=>$pay_fields,	    'callback'=>'',	),
		'mail'		=>array('title'=>'',		'fields'=>$mail_fields,	    'callback'=>'',	),
		'login'		=>array('title'=>'',		'fields'=>$login_fields,	'callback'=>'',	),
    	'social'	=>array('title'=>'',	    'fields'=>$social_fields,	'callback'=>'',	),
	);

	return compact('option_group','option_name','option_page','sections','field_validate');
}


function loobo_other_field_callback(){
	echo '';
}

function loobo_admin_init() {
	loobo_add_settings(loobo_get_option_labels());
}
add_action( 'admin_init', 'loobo_admin_init' );

function loobo_add_settings($labels){
	extract($labels);
	register_setting( $option_group, $option_name, $field_validate );

	$field_callback = empty($field_callback)?'loobo_option_field_callback' : $field_callback;
	if($sections){
		foreach ($sections as $section_name => $section) {
			add_settings_section( $section_name, $section['title'], $section['callback'], $option_page );

			$fields = isset($section['fields'])?$section['fields']:(isset($section['fields'])?$section['fields']:'');

			if($fields){
				foreach ($fields as $field_name=>$field) {
					$field['option']	= $option_name;
					$field['name']		= $field_name;

					$field_title		= $field['title'];

					$field_title = '<label for="'.$field_name.'">'.$field_title.'</label>';

					add_settings_field( 
						$field_name,
						$field_title,		
						$field_callback,	
						$option_page, 
						$section_name,	
						$field
					);	
				}
			}
		}
	}
}

/* Get checkbox type options */
function loobo_option_get_checkbox_settings($labels){
	$sections = $labels['sections'];
	$checkbox_options = array();
	foreach ($sections as $section) {
		$fields = $section['fields'];
		foreach ($fields as $field_name => $field) {
			if($field['type'] == 'checkbox'){
				$checkbox_options[] = $field_name;
			}
		}
	}
	return $checkbox_options;
}
function barley_validate( $barley ) {
	$current = get_option( 'barley' );

	foreach (array('nocategory','smtp_switch','smtp_ssl','comment_reply_mail','login_mail','login_error_mail','open_qq','open_weibo','font_awesome','bing_login_bg') as $key ) {
		if(empty($barley[$key])){ 
			$barley[$key] = 0;
		}
	}

	flush_rewrite_rules();

	return $barley;
}
function loobo_option_do_settings_section($option_page, $section_name){
	global $wp_settings_sections, $wp_settings_fields;

	if ( ! isset( $wp_settings_sections[$option_page] ) )
		return;

	$section = $wp_settings_sections[$option_page][$section_name];

	if ( $section['title'] )
		echo "<h3>{$section['title']}</h3>\n";

	if ( isset( $wp_settings_fields ) && isset( $wp_settings_fields[$option_page] ) && !empty($wp_settings_fields[$option_page][$section['id']] ) ){
		echo '<table class="form-table">';
		do_settings_fields( $option_page, $section['id'] );
		echo '</table>';
	}
}

/* Defaults value */
function loobo_option_defaults(){
	$name = get_bloginfo('name');
	$defaults = array(
			'description'	    =>	'',
			'keyword'	        =>	'',
			'page_sign'		    =>	'_',
			'favicon'		    =>	'',
			'themelogo'		    =>	'',
			'jquery'		    =>	'',
			'bootstrapcss'	    =>	'',
			'bootstrapjs'		=>	'',
			'categoriesimages'	=>	'',
			'swipebox'	        =>	'',
			'nocategory'		=>	'',
			'nofollow'	        =>	'',
			'maillogin'         =>	'',
			'slider'		    =>	'',
			'weixin_pay_5'      =>  '5',
			'weixin_pay_10'     => '10',
			'weixin_pay_20'     => '20',
			'alipay'            =>  '',
			'paysub'            =>  'pay-cum',
			'sliderbg'			=>	'',
			'smtp_name'			=>	'主题笔记',
			'smtp_account'		=>	'100041385@qq.com',
			'smtp_pass'			=>	'',
			'smtp_host'			=>	'smtp.qq.com',
			'weibo'				=>	'http://weibo.com',
			'github'			=>	'https://github.com/themenote',
			'qq'				=>	'100041385'
		);
	return $defaults;
}

/* Get options filtered by defaults */
function loobo_get_option($option_name){
	$options = get_option( $option_name );
	if($options && !is_admin()){
		return $options;
	}else{
		$defaults = loobo_option_defaults();
		return wp_parse_args($options, $defaults);
	}
}

/* Get setting value */
function loobo_get_setting($setting_name,$default=''){
	$option = get_option('barley');
	if(isset($option[$setting_name])){
		return str_replace("\r\n", "\n", $option[$setting_name]);
	}else{
		return $default;
	}
}

/* Upload image JS */
function loobo_upload_image_enqueue_scripts() {
    wp_enqueue_media();
    wp_enqueue_script('loobo-upload-image', THEME_URL . '/app/setting.js', array('jquery'),'20161111' , true);
    ?>
    <style type="text/css">
    	body #wpadminbar *, body #wpwrap {font-family: Arial, "宋体";}
	</style>
    <?php
}
add_action('admin_enqueue_scripts', 'loobo_upload_image_enqueue_scripts');

?>