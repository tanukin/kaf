<?php

if( !defined( 'DATALIFEENGINE' ) OR !defined( 'LOGGED_IN' ) ) {
    die( "Hacking attempt!" );
}
if( !$user_group[$member_id['user_group']]['admin_editusers'] ) {
    msg( "error", $lang['index_denied'], $lang['index_denied'] );
}

$id = intval( $_REQUEST['id'] );
$action = $_REQUEST['action'];

if( !$action ) $action = "list";

$header = <<<HTML
Список кафедр
    <div class="heading-elements">
		<ul class="icons-list">
		    <li>
			    <a href="#" data-toggle="modal" data-target="#advancedadd">
			        <i class="fa fa-plus position-left"></i>Добавить новую кафедру
			    </a>
            </li>  
		</ul>
	</div>
HTML;

$modal = <<<HTML
<div class="modal fade" name="advancedadd" id="advancedadd">
<form method="post" action="" autocomplete="off">
<input type="hidden" name="action" value="addkaf">
<input type="hidden" name="user_hash" value="{$dle_login_hash}" />
<input type="hidden" name="mod" value="kafedra">
<div class="modal-dialog" role="document">
	<div class="modal-content">
	  <div class="modal-header ui-dialog-titlebar">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<span class="ui-dialog-title">Добавление кафедры</span>
	  </div>
	  <div class="modal-body">
	  
		<div class="form-group">
			<div class="row">
				<div class="col-sm-12">
					<label>Название кафедры</label>
					<input name="kafedraname" type="text" class="form-control" maxlength="150" required>
				</div>
				<div class="col-sm-12">
					<label>Сокращенное название кафедры<span class="text-muted text-size-small"><i class="fa fa-exclamation-circle position-left position-right"></i> Латинскими буквами</span>
					    <i class="help-button visible-lg-inline-block text-primary-600 fa fa-question-circle position-right" data-rel="popover" data-trigger="hover" data-placement="right" data-content="Сокращенное название на латинском языке. Используется в url адресе: domain.ru/kaf/сокращенное название/" data-original-title="" title=""></i>
                    </label>
					<input name="altname" type="text" class="form-control" maxlength="50" required>
					
				</div>
				<div class="col-sm-12">
					<label>Название новостной категории
					    <i class="help-button visible-lg-inline-block text-primary-600 fa fa-question-circle position-right" data-rel="popover" data-trigger="hover" data-placement="right" data-content="Пример ввода: Новости кафедры ПМиИ" data-original-title="" title=""></i>
                    </label>
					<input name="newsname" type="text" class="form-control" maxlength="50" value="Новости кафедры ..." required>
					
				</div>
			</div>
		</div>
		
	
	   </div>
      <div class="modal-footer" style="margin-top:-20px;">
	    <button type="submit" class="btn bg-teal btn-sm btn-raised position-left"><i class="fa fa-floppy-o position-left"></i>Сохранить</button>
        <button type="button" class="btn bg-slate-600 btn-sm btn-raised" data-dismiss="modal">Отмена</button>
      </div>
	</div>
</div>
</form>
</div>



HTML;


// ********************************************************************************
// Список кафедр
// ********************************************************************************
if( $action == "list" ){
    $coutnkaf = $db->super_query( "SELECT COUNT(id) as count FROM " . USERPREFIX . "_kafedra" );
    $body = '<div class=\"panel-body\">';
    if(!$coutnkaf['count']){
        $body .= <<<HTML
           <div style="display: table;min-height:100px;width:100%;">
    	        <div class="text-center" style="display: table-cell;vertical-align:middle;">- Не найдено созданных кафедр -</div>
	       </div>     
HTML;
    }else{
        $body .= <<<HTML
            <table class="table table-xs table-hover">
              <thead>
                  <tr>
                    <th style="width: 100px">ID</th>
                    <th>Название группы</th>        
                    <th style="width: 100px"></th>
                  </tr>
                  </thead>
                  <tbody>
HTML;
        $r = $db->query( "SELECT id, name FROM " . USERPREFIX . "_kafedra ORDER BY name");
        @require_once 'engine/classes/kafedra.class.php';
        while ($row = $db->get_row($r)) {
            $d = new Kafedra();
            $d->getKafedraFromArray($row);
        $body .= <<<HTML
                <tr>
                    <td>{$d->getId()}</td>
                    <td>{$d->getName()}</td>
                    <td>        
                        <div class="btn-group">
                          <a href="#" class="dropdown-toggle nocolor" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-bars"></i><span class="caret"></span></a>
                          <ul class="dropdown-menu text-left dropdown-menu-right">
                            <li><a href="?mod=kafedra&amp;a=allkaf&amp;action=edit&amp;id={$d->getId()}"><i class="fa fa-pencil-square-o position-left"></i>Редактировать</a></li>
                            <li class="divider"></li>
                            <li><a href="?mod=kafedra&amp;a=allkaf&amp;action=delete&amp;user_hash=' . $dle_login_hash . '&amp;id={$d->getId()}"><i class="fa fa-trash-o position-left text-danger"></i>Удалить</a></li>
                          </ul>
                        </div>
                    </td>
                </tr>   
HTML;
        }
   }
    $body .= <<<HTML
    </tbody>
</table>
    </div>
        <div class="panel-footer">
        <button class="btn bg-teal btn-sm btn-raised legitRipple" type="button" data-toggle="modal" data-target="#advancedadd"><i class="fa fa-plus position-left"></i>Добавить новую кафедру</button>
    </div>
HTML;



}
// ********************************************************************************
// Добавление кафедры
// ********************************************************************************
if( $action == "addkaf" ){


    if( $_REQUEST['user_hash'] == "" or $_REQUEST['user_hash'] != $dle_login_hash ) {
        die( "Hacking attempt! User not found" );
    }

    if( ! $_POST['kafedraname'] ) {
        msg( "error", "Недоступное имя кафедры", "Необходимо заполнить все поля", "javascript:history.go(-1)" );
    }
    if( ! $_POST['altname'] ) {
        msg( "error", "Недоступное сокращенное название", "Необходимо заполнить все поля", "javascript:history.go(-1)" );
    }
    if( ! $_POST['newsname'] ) {
        msg( "error", "Недоступное название новостной категории", "Необходимо заполнить все поля", "javascript:history.go(-1)" );
    }

    if( preg_match( "/[\||\'|\<|\>|\[|\]|\%|\"|\!|\?|\$|\@|\#|\/|\\\|\&\~\*\{\+]/", $_POST['kafedraname'] ) ) msg( "error", "Недоступное имя кафедры", "Запрещено в названии использовать <b>символы</b>", "javascript:history.go(-1)" );
    if( preg_match( "/(^[^a-z0-9]+$|[\||\'|\<|\>|\[|\]|\%|\"|\!|\?|$|\@|\#|\/|\\\|\&\~\*\{\+\s\_])/i", $_POST['altname'],$m ) ) msg( "error", "Недоступное сокращенное название", "Сокращенное назнание должно набрано на латинком языке. <br ><b>Запрещено использовать символы и пробелы</b>", "javascript:history.go(-1)" );


//    Если все проходит:
//        1 Создать кафедру: name, altname
//        2 создать категорию dle для хранения новостей кафедры: name, altname


    //kafedra = new kafedra();

    $kafname = $db->safesql($_POST['kafedraname']);
    $altname = $db->safesql($_POST['altname']);
    $newsname = $db->safesql($_POST['newsname']);

    $rowkaf = $db->super_query( "SELECT COUNT(id) FROM " . USERPREFIX . "_kafedra WHERE alt_name = '$altname' LIMIT 1" );
    $rowcat = $db->super_query( "SELECT COUNT(id) FROM " . USERPREFIX . "_category WHERE alt_name = '$altname' LIMIT 1" );

    if($rowkaf['id'] or $rowcat['id']) {
        msg("error", "Сокращенное название", "Сокращенное название уже <b>используется</b>", "javascript:history.go(-1)");
    }

    $newsKafName = preg_replace('~Кафедр.~u','Новости кафедры', $kafname);

    $db->query( "INSERT INTO " . USERPREFIX . "_kafedra (name, alt_name) values ('$kafname', '$altname')" );
    $db->query( "INSERT INTO " . USERPREFIX . "_category (name, alt_name, metatitle) values ('$newsname', '$altname','$newsKafName')" );
    clear_cache();
    msg( "success", "Кафедра создана", "<b>$kafname</b> создана успешно.", "?mod=kafedra&action=list&a=allkaf" );
}

// ********************************************************************************
// Редактирование кафедры
// ********************************************************************************
if( $action == "edit" ){
    $body = "";

    $id= "";

    if( $config['allow_admin_wysiwyg'] == 1 ) {
        $js_array[] = "engine/skins/codemirror/js/code.js";
        $js_array[] = "engine/editor/jscripts/froala/editor.js";
        $js_array[] = "engine/editor/jscripts/froala/languages/{$lang['wysiwyg_language']}.js";
        $css_array[] = "engine/editor/jscripts/froala/css/editor.css";
    }

    if( $config['allow_admin_wysiwyg'] == 2 ) {
        $js_array[] = "engine/editor/jscripts/tiny_mce/tinymce.min.js";
    }

    if( !$config['allow_admin_wysiwyg'] ) {
        $js_array[] = "engine/classes/js/typograf.min.js";
    }

    $js_array[] = "engine/classes/uploads/html5/fileuploader.js";


    $body .= <<<HTML

    <div class="form-group editor-group" style="">
		  <label class="control-label col-md-2">Текст страницы:</label>
		  <div class="col-md-10" style="">
		  
<script type="text/javascript">
jQuery(function($){
    $('.wysiwygeditor').froalaEditor({
        dle_root: '',
        dle_upload_area : "template",
        dle_upload_user : "admin",
        dle_upload_news : "2",
        width: '100%',
        height: '400',
        language: 'ru',
		body_class: dle_theme,
        htmlRemoveTags: [],
		htmlAllowedAttrs: ['.*'],
        imageAllowedTypes: ['jpeg', 'jpg', 'png', 'gif'],
        imageDefaultWidth: 0,
        imageInsertButtons: ['imageBack', '|', 'imageByURL', 'imageUpload'],
		imageUploadURL: 'engine/ajax/upload.php',
		imageUploadParam: 'qqfile',
		imageUploadParams: { "subaction" : "upload", "news_id" : "2", "area" : "template", "author" : "admin", "mode" : "quickload", "user_hash" : "3faa294c9bcd803de822218d619bfef97d15599f"},
        imageMaxSize: 200 * 1024,		
        toolbarButtonsXS: ['bold', 'italic', 'underline', 'strikeThrough', 'align', 'color', 'insertLink', 'emoticons', 'insertImage', 'dleupload','insertVideo', 'paragraphFormat', 'paragraphStyle', 'dlehide', 'dlequote', 'dlespoiler'],
        toolbarButtonsSM: ['bold', 'italic', 'underline', 'strikeThrough', '|', 'align', 'color', 'insertLink', '|', 'emoticons', 'insertImage','dleupload','insertVideo', 'dleaudio', '|', 'paragraphFormat', 'paragraphStyle', '|', 'formatOL', 'formatUL', '|', 'dlehide', 'dlequote', 'dlespoiler'],
        toolbarButtonsMD: ['bold', 'italic', 'underline', 'strikeThrough', '|', 'align', 'indent', 'outdent', '|', 'subscript', 'superscript', '|', 'insertTable', 'formatOL', 'formatUL', 'insertHR', '|', 'undo', 'redo', 'dletypo', 'clearFormatting', 'selectAll', '|', 'fullscreen', '-','fontFamily', 'fontSize', '|', 'color', 'paragraphFormat', 'paragraphStyle', '|', 'insertLink', 'dleleech', '|', 'emoticons', 'insertImage','dleupload','|', 'insertVideo', 'dleaudio', 'dlemedia','|', 'dlehide', 'dlequote', 'dlespoiler','dlecode','page_dropdown', 'html'],
        toolbarButtons: ['bold', 'italic', 'underline', 'strikeThrough', '|', 'align', 'indent', 'outdent', '|', 'subscript', 'superscript', '|', 'insertTable', 'formatOL', 'formatUL', 'insertHR', '|', 'undo', 'redo', 'dletypo', 'clearFormatting', 'selectAll', '|', 'fullscreen', '-', 'fontFamily', 'fontSize', '|', 'color', 'paragraphFormat', 'paragraphStyle', '|', 'insertLink', 'dleleech', '|', 'emoticons', 'insertImage','dleupload','|', 'insertVideo', 'dleaudio', 'dlemedia','|', 'dlehide', 'dlequote', 'dlespoiler','dlecode','page_dropdown', 'html']
        }).on('froalaEditor.image.inserted froalaEditor.image.replaced', function (e, editor, \$img, response) {
        if( response ) {
            response = jQuery.parseJSON(response);
            \$img.removeAttr("data-returnbox").removeAttr("data-success").removeAttr("data-xfvalue").removeAttr("data-flink");
            if(response.flink) {
                if(\$img.parent().hasClass("highslide")) {
                    \$img.parent().attr('href', response.flink);
                } else {
                    \$img.wrap( '<a href="'+response.flink+'" class="highslide"></a>' );
                }
            }
        }
    });
});
</script>
    <div class="editor-panel"><textarea name="template" id="template" class="wysiwygeditor" style="width:98%;height:300px;">Тестовая информация</textarea></div>		  </div>
		 </div>
		 </div>
		 
    
HTML;






   // $header = "Редактирование <b>". preg_replace("~Кафедра~","кафедры",$kaf->getName()) . "</b>";
//    $coutnkaf = $db->super_query( "SELECT COUNT(id) as count FROM " . USERPREFIX . "_kafedra" );
//    $body = '<div class=\"panel-body\">';
//    if(!$coutnkaf['count']){
//        $body .= <<<HTML
//           <div style="display: table;min-height:100px;width:100%;">
//    	        <div class="text-center" style="display: table-cell;vertical-align:middle;">- Не найдено созданных кафедр -</div>
//	       </div>
//HTML;
//    }else{
//        $body .= <<<HTML
//            <table class="table table-xs table-hover">
//              <thead>
//                  <tr>
//                    <th style="width: 100px">ID</th>
//                    <th>Название группы</th>
//                    <th style="width: 100px"></th>
//                  </tr>
//                  </thead>
//                  <tbody>
//HTML;
//        $r = $db->query( "SELECT id, name FROM " . USERPREFIX . "_kafedra ORDER BY name");
//        @require_once 'engine/classes/kafedra.class.php';
//        while ($row = $db->get_row($r)) {
//            $d = new Kafedra();
//            $d->getKafedraFromArray($row);
//            $body .= <<<HTML
//                <tr>
//                    <td>{$d->getId()}</td>
//                    <td>{$d->getName()}</td>
//                    <td>
//                        <div class="btn-group">
//                          <a href="#" class="dropdown-toggle nocolor" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-bars"></i><span class="caret"></span></a>
//                          <ul class="dropdown-menu text-left dropdown-menu-right">
//                            <li><a href="?mod=kafedra&amp;action=edit&amp;id={$d->getId()}"><i class="fa fa-pencil-square-o position-left"></i>Редактировать</a></li>
//                            <li class="divider"></li>
//                            <li><a href="?mod=kafedra&amp;action=delete&amp;user_hash=' . $dle_login_hash . '&amp;id={$d->getId()}"><i class="fa fa-trash-o position-left text-danger"></i>Удалить</a></li>
//                          </ul>
//                        </div>
//                    </td>
//                </tr>
//HTML;
//        }
//    }
//    $body .= <<<HTML
//    </tbody>
//</table>
//    </div>
//        <div class="panel-footer">
//        <button class="btn bg-teal btn-sm btn-raised legitRipple" type="button" data-toggle="modal" data-target="#advancedadd"><i class="fa fa-plus position-left"></i>Добавить новую кафедру</button>
//    </div>
//HTML;
}
// в $modal написать скрипт проверки ввода в altname только английских букв без пробелов

