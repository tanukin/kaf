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

define( 'KN_DIR', ROOT_DIR.'/engine/kafedra/nucleus' );
require_once( KN_DIR . '/classes/person.class.php' );
require_once( KN_DIR . '/classes/uchStep.class.php' );
require_once( KN_DIR . '/classes/uchZvan.class.php' );
require_once( KN_DIR . '/classes/capacity.class.php' );


// ********************************************************************************
// Список сотрудников кафедр
// ********************************************************************************
if( $action == "list" ){
    $person = new Person();
    $header = <<<HTML
Список сотрудников кафедр
    <div class="heading-elements">
		<ul class="icons-list">
		    <li>
			    <a href="?mod=kafedra&amp;a=allpeople&amp;action=edit">
			        <i class="fa fa-user-plus position-left"></i>Добавить нового сотрудника
			    </a>
            </li>  
		</ul>
	</div>
HTML;
    $body = '<div class="panel-body">';
    if(!$person->getCountPersonIntoDB()){
        $body .= <<<HTML
           <div style="display: table;min-height:100px;width:100%;">
    	        <div class="text-center" style="display: table-cell;vertical-align:middle;">- не добавлено ни одного сотрудника каферд -</div>
	       </div>     
HTML;
    }
    else{
        $body .= <<<HTML
            <table class="table table-xs table-hover">
              <thead>
                  <tr>
                    <th style="width: 100px">ID</th>
                    <th>ФИО</th>        
                    <th class="hidden-xs">Кафедра</th>
                    <th style="width: 100px"></th>
                  </tr>
                  </thead>
                  <tbody>
HTML;

       $r = $person->getAllPeopleFromDB();
        while ($row = $db->get_row($r)) {
            $person = new Person();
           // $person->getAllPeopleFromArray($row);
            $person->getPersonFromRow($row);
            $body .= <<<HTML
                <tr>
                    <td>{$person->getId()}</td>
                    <td>{$person->getFullFIO()}</td>
                    <td>{$person->getNameKafedra()}</td>
                    <td>        
                        <div class="btn-group">
                          <a href="#" class="dropdown-toggle nocolor" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-bars"></i><span class="caret"></span></a>
                          <ul class="dropdown-menu text-left dropdown-menu-right">
                            <li><a href="?mod=kafedra&amp;a=allpeople&amp;action=edit&amp;id={$person->getId()}"><i class="fa fa-pencil-square-o position-left"></i>Редактировать</a></li>
                            <li class="divider"></li>
                            <li><a href="?mod=kafedra&amp;a=allpeople&amp;action=delete&amp;user_hash=' . $dle_login_hash . '&amp;id={$person->getId()}"><i class="fa fa-trash-o position-left text-danger"></i>Удалить</a></li>
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
        
    </div>
HTML;



}
// ********************************************************************************
// Добавление и изменение сотрудника кафедры
// ********************************************************************************
if ($action == "edit"){
    $person = new Person($id);
    require_once( KN_DIR . '/classes/kafedra.class.php' );

    $kaferda = new Kafedra();
    $r = $kaferda->getAllKafedraFromDB();
        $select_list_kafedra .= "<option value='0'> </option>";
    while ($row = $db->get_row($r)) {
        $select_list_kafedra .= "<option value='{$row['id']}' ";
        if($row['id'] == $person->getIdKafedra())
            $select_list_kafedra .= " selected";
        $select_list_kafedra .= ">{$row['name']}</option>";
    }

    $arr_uch_step = [0 => ' ', 1 => 'Кандидат', 2 => 'Доктор'];
    foreach ($arr_uch_step as $key => $value) {
        $uch_step .= '\n\t<option value="' . $key . '"';
        $uch_step .= ( $person->getUchStep() == $key) ? ' selected="selected">' : '>';
        $uch_step .= $value . '</option>';
    }

    $uchStep = new UchStep();
    $r = $uchStep->getAllUchStep();
    $select_uchStep .= "<option value='0'> </option>";
    while ($row = $db->get_row($r)) {

        $select_uchStep .= "<option value='{$row['id']}' ";
        if($row['id'] == $person->getIdUchStep())
            $select_uchStep .= " selected";
        $select_uchStep .= ">{$row['stepen']}</option>";
    }

    $uchZvan = new UchZvan();
    $r = $uchZvan->getAllUchZvanie();
     $select_uchZvanie .= "<option value='0'> </option>";
     while ($row = $db->get_row($r)) {
         $select_uchZvanie .= "<option value='{$row['id']}' ";
         if($row['id'] == $person->getIdUchZvan())
             $select_uchZvanie .= " selected";
         $select_uchZvanie .= ">{$row['zvanie']}</option>";
     }

    $capacity = new Capacity();
    $r = $capacity->getAllCapacity();
    $select_capacity .= "<option value='0'> </option>";
    while ($row = $db->get_row($r)) {
        $select_capacity .= "<option value='{$row['id']}' ";
        if($row['id'] == $person->getIdCapacity())
            $select_capacity .= " selected";
        $select_capacity .= ">{$row['capacity']}</option>";
    }

    /// подумать может сделать функцию
    if($id){
        $course = "";
        $array = $person->getCourse();
        $total = count($array);
        foreach ($array as $key => $value) {
            $counter++;
            $course .= '<div class="row entry"><div class="col-md-12 col-sm-12"> <input class="form-control width-400" maxlength="200"   name="course[]" type="text" value="'.$value .'"/>';
            if($counter != $total){
                $course .= '<i class="fa fa-trash-o position-left"></i></div></div>';
            }else{
                $course .= '<i class="fa fa-plus position-left"></i></div></div>';
            }

        }
    }else{
        $course = '<input class="form-control width-400" maxlength="200"   name="course[]" type="text" /><i class="fa fa-plus position-left"></i> ';
    }




    if(!$id)
        $header = "Добавление нового сотрудника";
    else
        $header = "Редактирование <span class='text-semibold'>".$person->getFullFIO()."</span>";
    $body = '<form name="saveuserform" id="saveuserform" action="" method="post" enctype="multipart/form-data" class="form-horizontal"> <div class="panel-body  edit_profile">';

    $body .=<<<HTML

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3">Фамилия:</label>
        <div class="col-md-9 col-sm-9">
            <input class="form-control width-400" maxlength="50" type="text" name="family" value="{$person->getFamily()}" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3">Имя:</label>
        <div class="col-md-9 col-sm-9">
            <input class="form-control width-400" maxlength="50" type="text" name="name" value="{$person->getName()}" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3">Отчество:</label>
        <div class="col-md-9 col-sm-9">
            <input class="form-control width-400" maxlength="50" type="text" name="patronymic" value="{$person->getPatronymic()}" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3">Кафедра:</label>
        <div class="col-md-9 col-sm-9">
            <select name="id_kafedra" class="uniform" required>{$select_list_kafedra}</select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3">Ученая степень, звание:</label>
        <div class="col-md-3 col-sm-3">
            <select name="uch_step" class="uniform" >{$uch_step}</select>
        </div>
        <div class="col-md-3 col-sm-3">
            <select name="id_uch_step" class="uniform" >{$select_uchStep}</select>
        </div>
        <div class="col-md-3 col-sm-3">
            <select name="id_uch_zvan" class="uniform" >{$select_uchZvanie}</select>
        </div>
    </div>    
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3">Должность:</label>
        <div class="col-md-9 col-sm-9">
            <select name="id_capacity" class="uniform" required>{$select_capacity}</select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3">Научная специальность по диплому доктора (кандидата) наук:</label>
        <div class="col-md-9 col-sm-9">
            <input class="form-control width-400" maxlength="200" type="text" name="speciality" value="{$person->getSpeciality()}">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3">Почетные звания и награды:</label>
        <div class="col-md-9 col-sm-9">
            <textarea class="form-control width-400" maxlength="50000"  name="award" rows="5">{$person->getAward()}</textarea>
        </div>
    </div> 
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3">Основные биографические данные: год рождения; год окончания учебного заведения (название); год защиты кандидатской диссертации; год защиты докторской диссертации; год присвоения ученого звания доцента или профессора; дополнительные сведения по желанию:</label>
        <div class="col-md-9 col-sm-9">
            <textarea class="form-control width-400" maxlength="50000"  name="biography" rows="5" required>{$person->getBiography()}</textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3">Курсы повышения квалификации:</label>
        <div id="pov-kvalif" class="controls col-md-9 col-sm-9"> 
            <div class="row entry">
                <div class="col-md-12 col-sm-12">                    
                    <input class="form-control width-400" maxlength="200"   name="pov_kvalif[]" type="text" /><i class="fa fa-plus position-left"></i>                                                                                  
                </div>
            </div>
        </div>       
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3">Стаж работы:</label>
        <div class="col-md-1 col-sm-1">общий: </div>
        <div class="col-md-3 col-sm-3">
            <input class="form-control width-200" maxlength="2" type="number" name="staz_obs" value="{$person->getStaz()[0]}">
        </div>
        <div class="col-md-2 col-sm-2">педагогический: </div>
        <div class="col-md-3 col-sm-3">
            <input class="form-control width-200" maxlength="2" type="number" name="staz_ped" value="{$person->getStaz()[1]}">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3">Краткая аннотация научной деятельности (разработал ..., создал ..., научные исследования были поддержаны грантами...)</label>
        <div class="col-md-9 col-sm-9">
            <textarea class="form-control width-400" maxlength="50000"  name="science" rows="5">{$person->getScience()}</textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3">Количество публикаций, авторских свидетельств и патентов:</label>
        <div class="col-md-9 col-sm-9">
            <input class="form-control width-400" maxlength="5" type="number" name="count_pub" value="{$person->getCountPub()}">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3">Наиболее значимые публикации:</label>        
        <div id="read-course" class="controls col-md-9 col-sm-9"> 
            <div id="2" class="row entry">
                <div id="1" class="col-md-12 col-sm-12">                    
                    <input class="form-control width-400" maxlength="200"   name="top_pub[]" type="text" /><i class="fa fa-plus position-left"></i>                                                                                  
                </div>
            </div>
        </div>       
   </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3">Количество подготовленных кандидатов и докторов наук:</label>
        <div class="col-md-9 col-sm-9">
            <input class="form-control width-400" maxlength="5" type="number" name="count_trained" value="{$person->getCountTrained()}">
        </div>
    </div>
    
    
   
   <div class="form-group">
        <label class="control-label col-md-3 col-sm-3">Читаемые курсы (основные):</label>        
        <div id="read-course" class="controls col-md-9 col-sm-9"> 
            <div class="row entry">
                <div class="col-md-12 col-sm-12">                    
                    {$course}                                                                                  
                </div>
            </div>
        </div>       
   </div>
    
    <script>
        $(function() {
            $(document).on('click', '.fa-plus', function(e) {
                e.preventDefault();        
                var controlForm = $($(this).parent().parent().parent()),                
                    currentEntry = $(this).parents('.entry:first'),
                    newEntry = $(currentEntry.clone()).appendTo(controlForm);
        
                newEntry.find('input').val('');
                controlForm.find('.entry:not(:last) .fa-plus')
                    .removeClass('fa-plus').addClass('fa-trash-o');
            }).on('click', '.fa-trash-o', function(e){
                $(this).parents('.entry:first').remove();
        
                e.preventDefault();
                return false;
            });
        });
    </script> 
   
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3">Адрес (рабочий):</label>
        <div class="col-md-9 col-sm-9">
            <input class="form-control width-400" maxlength="200" type="text" name="address" value="{$person->getAddress()}">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3">Телефон (рабочий):</label>
        <div class="col-md-9 col-sm-9">
            <input class="form-control width-400" maxlength="100" type="text" name="phone" value="{$person->getPhone()}">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3">E-mail:</label>
        <div class="col-md-9 col-sm-9">
            <input class="form-control width-400" maxlength="100" type="email" name="email" value="{$person->getEmail()}">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3">Загрузка фотографию:</label>
        <div class="col-md-9 col-sm-9">
            <input type="file" name="photo" style="width:304px;" accept="image/jpeg,image/png"  class="icheck">
        </div>
    </div>
    
       
    
    <div class="form-group">
        <div class="col-md-12">
            <div class="checkbox">
                <label>
                    <input class="icheck" type="checkbox" name="skill" value="on"  />Ведущий специалист
                </label>
            </div> 
        </div>
    </div>
    <input type="hidden" name="user_hash" value="$dle_login_hash">
    <input type="hidden" name="action" value="doedit">
	<input type="hidden" name="a" value="allperson">
	<input type="hidden" name="mod" value="kafedra">

    
HTML;
    if($id)
        $body .= '<input type="hidden" name="id" value="{$id}">';

    $body .= <<<HTML
    </div>
    <div class="panel-footer">
        <button type="submit" class="btn bg-teal btn-sm btn-raised position-left legitRipple"><i class="fa fa-floppy-o position-left"></i>Сохранить</button>  
    </div>
</form>       
HTML;
}
// ********************************************************************************
// Сохранение отредактированного или добавляемого сотрудника кафедры
// ********************************************************************************
if($action == "doedit"){
    if( $_REQUEST['user_hash'] == "" or $_REQUEST['user_hash'] != $dle_login_hash ) {
        die( "Hacking attempt! User not found" );
    }

    $person = new Person($id);
    $person ->getPersonFromRequest();
    if($person->setPersonIntoDB())
        msg( "success", "Изменения сохранены", "Сотрудник успешно добавлен", "?mod=kafedra&action=list&a=allpeople" );
    else{
        msg("error", "Ошибка", "Не удалось добавить сотрудника в базу данных <b>(обратитесь к администратору)</b>", "javascript:history.go(-1)");
    }



    $header = "Добавление нового сотрудника";
    $body .= $insert_sql;




}