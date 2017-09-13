<?php

if( !defined( 'DATALIFEENGINE' ) OR !defined( 'LOGGED_IN' ) ) {
    die( "Hacking attempt!" );
}

$id = intval( $_REQUEST['id'] );

if( !$action ) $action = "list";
// ********************************************************************************
// Список кафедр
// ********************************************************************************
if( $action == "list" ){

}
// ********************************************************************************
// Добавление кафедры
// ********************************************************************************
if( $action == "addkaf" ){

    if( !$user_group[$member_id['user_group']]['admin_editusers'] ) {
        msg( "error", $lang['index_denied'], $lang['index_denied'] );
    }
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

    /*
    Если все проходит:
        1 Создать кафедру: name, altname
        2 создать категорию dle для хранения новостей кафедры: name, altname

*/
    $kafname = $db->safesql($_POST['kafedraname']);
    $altname = $db->safesql($_POST['altname']);
    $newsname = $db->safesql($_POST['newsname']);

    /*
     * if(  $db->query( "SELECT true From " . USERPREFIX . "_kafedra WHERE alt_name = '" . $altname . "' LIMIT 1" )
            OR
            $db->query( "SELECT true From " . USERPREFIX . "_category WHERE alt_name = '" . $altname . "' LIMIT 1"))
            msg("error", "Сокращенное название", "Сокращенное название уже <b>используется</b>", "javascript:history.go(-1)");
    */

    $rowkaf = $db->super_query( "SELECT COUNT(id) FROM " . USERPREFIX . "_kafedra WHERE alt_name = '$altname' LIMIT 1" );
    $rowcat = $db->super_query( "SELECT COUNT(id) FROM " . USERPREFIX . "_category WHERE alt_name = '$altname' LIMIT 1" );

    if($rowkaf['id'] or $rowcat['id']) {
        msg("error", "Сокращенное название", "Сокращенное название уже <b>используется</b>", "javascript:history.go(-1)");
    }

    $newsKafName = preg_replace('~Кафедр.~u','Новости кафедры', $kafname);


    $db->query( "INSERT INTO " . USERPREFIX . "_kafedra (name, alt_name) values ('$kafname', '$altname')" );
    $db->query( "INSERT INTO " . USERPREFIX . "_category (name, alt_name, metatitle) values ('$newsname', '$altname','$newsKafName')" );
    clear_cache();
    msg( "success", "Кафедра создана", "<b>$kafname</b> создана успешно.", "?mod=kafedra&action=list" );



}



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
// в $modal написать скрипт проверки ввода в altname только английских букв без пробелов
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
					<label>Сокращенное название кафедры
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

$body = "Список всех кафедр";