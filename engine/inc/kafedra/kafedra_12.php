<?php
if(!defined('DATALIFEENGINE')){
    echo '[Datalife Engine PhotoAlbums] You tried to gain unauthorized access to the module, or system DataLife Engine is not licensed.';
    exit();
}

$a = $_GET['a'] ? trim( $_GET['a'] ) : '';

if(in_array($a, array('allkaf', 'allpeople')) && file_exists(ROOT_DIR . '/engine/inc/kafedra/'.$a.'.php')){
    @require_once( ROOT_DIR . '/engine/inc/kafedra/'.$a.'.php' );
}elseif(isset($_GET['a'])){
    $body = <<<HTML
    <div class="panel-body">
           <div style="display: table;min-height:100px;width:100%;">
    	        <div class="text-center" style="display: table-cell;vertical-align:middle;">- Раздел не найден -</div>
	       </div>     
    </div>
HTML;
}elseif(empty($_GET['a'])){
    $header = "";
    $body = "";
    $modal = "";
    $information = "Возможно разместить в данном блоке информацию по данному модулю, возможно какую то обучающую информацию.";
}



function echo_block($header, $body = null, $modal = null, $information = null){

$text = <<<HTML

<!-- оформление навигации-->
<div class="navbar navbar-default navbar-component navbar-xs" style="z-index: inherit;">
	<ul class="nav navbar-nav visible-xs-block">
		<li class="full-width text-center">
		    <a data-toggle="collapse" data-target="#navbar-filter" class="legitRipple">
		        <i class="fa fa-bars"></i>
		    </a>
		</li>
	</ul>
	<div class="navbar-collapse collapse" id="navbar-filter">
		<ul class="nav navbar-nav">			
            <li>
			    <a href="?mod=kafedra&a=allkaf" class="tip legitRipple" title="" data-original-title="Вывести список всех кафедр.">
                    <i class="fa fa-building position-left"></i>Список каферд
                </a>
            </li>
            <li>
			    <a href="?mod=kafedra&a=allpeople" class="tip legitRipple" title="" data-original-title="Вывести список сотрудников, состоящих в кадровом составе кафедр.">
                    <i class="fa fa-users position-left"></i>Список сотрудников
                </a>
            </li>
		</ul>
	</div>
</div>
HTML;

if($body) {
    $text .= <<<HTML
    {$modal}
<!-- оформление блок контента-->
<div class="panel panel-default">
  <div class="panel-heading">
        {$header}
  </div>
        {$body}
</div>


HTML;
    }

if($information){
    $text .= <<<HTML
    <!-- оформление информационного блока-->
    <div class="alert alert-info alert-styled-left alert-arrow-left alert-component">{$information}</div>
HTML;

}
  echo $text;

}



echoheader( "<i class=\"icon-file-alt\"></i>Кафедры", 'Управление кафедральными разделами');
echo_block($header, $body, $modal, $information);
echofooter();