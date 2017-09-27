<?php
if(!defined('DATALIFEENGINE'))
{
    die("Hacking attempt!");
}

class Person {
    private $id;
    private $family;
    private $name;
    private $patronymic;
    private $id_kafedra;
    private $skill;
    private $uch_step;
    private $id_uch_step;
    private $id_uch_zvan;
    private $id_capacity;
    private $speciality;
    private $award;
    private $biography;
    private $pov_kvalif;
    private $staz;
    private $science;
    private $count_pub;
    private $top_pub;
    private $count_trained;
    private $course;
    private $address;
    private $phone;
    private $email;
    private $photo;
    private $lastupdate;

    const NODEGREE = 0;
    const CANDIDATE = 1;
    const DOCTOR  = 2;
    const SUPERSPECIALIST  = 1;
    const SPECIALIST  = 0;

    function __construct($id = 0) {
        if ($id != 0) {
            $this->getPersonFromDB($id);
        }
    }
    function __toString(){
        $fio = $this->normalizeFIO($this->family) . " ";
        $fio .= $this->normalizeFIO($this->name) . " ";
        $fio .= $this->normalizeFIO($this->patronymic);
        return trim($fio);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = intval($id);
    }

    public function getFamily()
    {
        return $this->family;
    }

    public function setFamily($family)
    {
        $this->family = $this->normalizeFIO($family);
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $this->normalizeFIO($name);
    }

    public function getPatronymic()
    {
        return $this->patronymic;
    }

    public function setPatronymic($patronymic)
    {
        $this->patronymic = $this->normalizeFIO($patronymic);
    }

    public function getIdKafedra()
    {
        return $this->id_kafedra;
    }

    public function setIdKafedra($id_kafedra)
    {
        if ($id_kafedra == "")
            $this->id_kafedra = null;
        else
            $this->id_kafedra = intval($id_kafedra);
    }

    public function getSkill()
    {
        return $this->skill;
    }

    public function setSkill($skill)
    {
        if(intval($skill) == Person::SUPERSPECIALIST)
            $this->skill = Person::SUPERSPECIALIST;
        else
            $this->skill = Person::SPECIALIST;
    }

    public function getUchStep()
    {
        return $this->uch_step;
    }

    public function setUchStep($uch_step)
    {
       switch (intval($uch_step)){
            case Person::DOCTOR: $this->uch_step = Person::DOCTOR;
                break;
            case Person::CANDIDATE: $this->uch_step = Person::CANDIDATE;
                break;
            default: $this->uch_step = Person::NODEGREE;
                     $this->id_uch_step = Person::NODEGREE;
        }
    }

    public function getIdUchStep()
    {
        return $this->id_uch_step;
    }

    public function getNameUchStep()
    {
        $uchStep = new UchStep($this->id);
        return $uchStep->getStepen();
    }

    public function setIdUchStep($id_uch_step)
    {
        $this->id_uch_step = intval($id_uch_step);
    }

    public function getIdUchZvan()
    {
        return $this->id_uch_zvan;
    }

    public function getNameUchZvan()
    {
        global $db;
        if ($db instanceof db) {
            $row = $db->super_query("SELECT zvanie FROM ". USERPREFIX . "_people_uzvan WHERE id = {$this->getIdUchZvan()} LIMIT 1");
            return $row['zvanie'];
        }

    }

    public function setIdUchZvan($id_uch_zvan)
    {
        $this->id_uch_zvan = intval($id_uch_zvan);
    }

    public function getIdCapacity()
    {
        return $this->id_capacity;
    }

    public function getNameCapacity()
    {
        global $db;
        if ($db instanceof db) {
            $row = $db->super_query("SELECT capacity FROM ". USERPREFIX . "_people_capacity WHERE id = {$this->getIdCapacity()} LIMIT 1");
            return $row['capacity'];
        }
    }

    public function setIdCapacity($id_capacity)
    {
        $this->id_capacity = intval($id_capacity);
    }

    public function getSpeciality()
    {
        return $this->speciality;
    }

    public function setSpeciality($speciality)
    {
        $this->speciality = trim(htmlspecialchars(strip_tags($speciality)));
    }

    public function getAward()
    {
        return $this->award;
    }

    public function setAward($award)
    {
        $this->award = trim(htmlspecialchars(strip_tags($award)));
    }

    public function getBiography()
    {
        return $this->biography;
    }

    public function setBiography($biography)
    {
        $this->biography = trim(htmlspecialchars(strip_tags($biography)));
    }

    public function getPovKvalif()
    {
        return explode("::", $this->pov_kvalif);
    }

    public function setPovKvalif($pov_kvalif = array())
    {
        $pov_kvalif = str_replace(":", "&#058;", $pov_kvalif);
        $pov_kvalif = htmlspecialchars(strip_tags(implode("::", $pov_kvalif)));
        $this->pov_kvalif = $pov_kvalif;
    }

    public function getStaz()
    {
        return  explode("::", $this->staz);
    }

    public function setStaz($staz = array())
    {
        $this->staz = implode("::", $staz);
    }

    public function getScience()
    {
        return $this->science;
    }

    public function setScience($science)
    {
        $this->science = trim(htmlspecialchars(strip_tags($science)));
    }

    public function getCountPub()
    {
        return $this->count_pub;
    }

    public function setCountPub($count_pub)
    {
        $this->count_pub = intval($count_pub);
    }

    public function getTopPub()
    {
        return explode("::", $this->top_pub);
    }

    public function setTopPub($top_pub = array())
    {
        $top_pub = str_replace(":", "&#058;", $top_pub);
        $top_pub = htmlspecialchars(strip_tags(implode("::", $top_pub)));
        $this->top_pub = $top_pub;
    }

    public function getCountTrained()
    {
        return $this->count_trained;
    }

    public function setCountTrained($count_trained)
    {
        $this->count_trained = intval($count_trained);
    }

    public function getCourse()
    {
        return explode("::", $this->course);
    }

    public function setCourse($course = array())
    {
        $course = str_replace(":", "&#058;", $course);
        $course = htmlspecialchars(strip_tags(implode("::", $course)));
        $this->course = $course;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = trim(htmlspecialchars(strip_tags($address)));
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($phone)
    {
        $this->phone = trim(htmlspecialchars(strip_tags($phone)));
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = trim(htmlspecialchars(strip_tags($email)));
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto($photo)
    {
        $this->photo = trim(htmlspecialchars(strip_tags($photo)));
    }

    public function getLastupdate()
    {
        return $this->lastupdate;
    }

    public function setLastupdate($lastupdate = false)
    {
        if (!$lastupdate) {
            $lastupdate = mktime();
        }
        $this->lastupdate = intval($lastupdate);
    }

    public function getNameKafedra() {
        global $db;
        if ($db instanceof db) {
            $row = $db->super_query("SELECT name FROM ". USERPREFIX . "_kafedra WHERE id = '{$this->getIdKafedra()}' LIMIT 1");
            return $row['name'];
        }
        return false;
    }


    public function getPersonFromRequest(){
        $this->setID($_REQUEST[id]);
        $this->setFamily($_REQUEST[family]);
        $this->setName($_REQUEST[name]);
        $this->setPatronymic($_REQUEST[patronymic]);
        $this->setIdKafedra($_REQUEST[id_kafedra]);
        if ($_REQUEST[skill] == 'on')
            $this->setSkill(1);
        else
            $this->setSkill(0);
        $this->setUchStep($_REQUEST[uch_step]);
        $this->setIdUchStep($_REQUEST[id_uch_step]);
        $this->setIdUchZvan($_REQUEST[id_uch_zvan]);
        $this->setIdCapacity($_REQUEST[id_capacity]);
        $this->setSpeciality($_REQUEST[speciality]);
        $this->setAward($_REQUEST[award]);
        $this->setBiography($_REQUEST[biography]);
        $this->setScience($_REQUEST[science]);
        $this->setCountPub($_REQUEST[count_pub]);
        foreach ($_REQUEST[pov_kvalif] as $pov_kvalif) {
            if($pov_kvalif != '')
                $arr_pov_kvalif[] = trim($pov_kvalif);
        }
        foreach ($_REQUEST[top_pub] as $top_pub) {
            if($top_pub != '')
                $arr_top_pub[] = trim($top_pub);
        }
        foreach ($_REQUEST[course] as $course) {
            if($course != '')
                $arr_course[] = trim($course);
        }
        $this->setPovKvalif($arr_pov_kvalif);
        $this->setStaz([$_REQUEST[staz_obs], $_REQUEST[staz_ped]]);
        $this->setTopPub($arr_top_pub);
        $this->setCountTrained($_REQUEST[count_trained]);
        $this->setCourse($arr_course);
        $this->setAddress($_REQUEST[address]);
        $this->setPhone($_REQUEST[phone]);
        $this->setEmail($_REQUEST[email]);

        if($_REQUEST[deletePhoto] == 'yes'){
            $this->removePhoto();
        }
        if($_FILES[photo][name]){
            if ($_FILES[photo][error] == 0)
                $this->uploadPhoto();
            switch ($_FILES[photo][error]){
                case 1: $describeError = "Размер принятого файла превысил максимально допустимый размер, разрешенный на сервере";
                    break;
                case 2: $describeError = "Размер принятого файла превысил максимально допустимый размер";
                    break;
                case 3: $describeError = "Загружаемый файл был получен только частично";
                    break;
                case 6: $describeError = "Отсутствует временная папка";
                    break;
                case 7: $describeError = "Не удалось записать файл на диск";
                    break;
                default: $describeError = "Файл не был загружен";
            }
            if($describeError && $_FILES[photo][error] != 0)
                msg("error", "Ошибка", $describeError, "javascript:history.go(-1)");
        }
    }
    public function getPersonFromRow($row = null){
        if ($row) {
            $this->id = stripslashes($row[id]);
            $this->family = stripslashes($row[family]);
            $this->name = stripslashes($row[name]);
            $this->patronymic = stripslashes($row[patronymic]);
            $this->id_kafedra = stripslashes($row[id_kafedra]);
            $this->skill = stripslashes($row[skill]);
            $this->uch_step = stripslashes($row[uch_step]);
            $this->id_uch_step = stripslashes($row[id_uch_step]);
            $this->id_uch_zvan = stripslashes($row[id_uch_zvan]);
            $this->id_capacity = stripslashes($row[id_capacity]);
            $this->speciality = stripslashes($row[speciality]);
            $this->award = stripslashes($row[award]);
            $this->biography = stripslashes($row[biography]);
            $this->pov_kvalif = htmlspecialchars_decode(stripslashes($row[pov_kvalif]),ENT_NOQUOTES);
            $this->staz = stripslashes($row[staz]);
            $this->science = stripslashes($row[science]);
            $this->count_pub = stripslashes($row[count_pub]);
            $this->top_pub = htmlspecialchars_decode(stripslashes($row[top_pub]),ENT_NOQUOTES);
            $this->count_trained = stripslashes($row[count_trained]);
            $this->course = htmlspecialchars_decode(stripslashes($row[course]),ENT_NOQUOTES);
            $this->address = stripslashes($row[address]);
            $this->phone = stripslashes($row[phone]);
            $this->email = stripslashes($row[email]);
            $this->photo = stripslashes($row[photo]);
            $this->lastupdate = stripslashes($row[lastupdate]);
        }else{
            new ErrorException("Массив пустой");
        }
    }
    public function getPersonFromDB($id = 0){
        global $db;
        $id = intval($id);
        $row = $db->super_query("SELECT * FROM " . USERPREFIX . "_people WHERE id = {$id}  LIMIT 1");
        $this->getPersonFromRow($row);
    }
    public function setPersonIntoDB (){
        global $db;
        // Поля: фамилия, имя, отчество, ИД кафедры не могут быть пустыми
        // Если уч.звание не указано, то обязательно д.б. выбрfyye. должность
        // Если кандидат или доктор - должен быть ид уч.степени
        // Если не доктор и не кандидат - не должно быть ид уч.степени и специальности по диплому
            if (    $this->family != ""
                &&  $this->name != ""
                &&  $this->patronymic != ""
                &&  $this->id_kafedra != null
                &&  (($this->id_uch_zvan == 0 && $this->id_capacity != 0) || $this->id_uch_zvan != 0)
                &&  (($this->uch_step != 0 && $this->id_uch_step > 0) || ($this->uch_step == 0 && $this->id_uch_step == 0 && $this->speciality == ""))) {
                // Не кандидат и не доктор не могут иметь ни уч.степени, ни уч.звания, ни специальности по диплому
                    if ($this->uch_step == 0) {
                        $this->id_uch_step = 0;
                        $this->id_uch_zvan = 0;
                        $this->speciality = '';
                    }
                    // Если в базе есть ссылка на фотку, а файл отсутствует
                    if ($this->photo != "") {
                        if (    !file_exists(ROOT_DIR . "/uploads/kafedra/people/" . $this->photo)
                            ||  !is_file(ROOT_DIR . "/uploads/kafedra/people/" . $this->photo)) {
                            $this->photo = "";
                        }
                    }
                    //Inserts the new person into DB
                    if ($this->id == null || $this->id == 0) {
                        $insert_sql = "INSERT INTO " . USERPREFIX . "_people(id, family, name, patronymic, id_kafedra, skill, uch_step, id_uch_step, id_uch_zvan, id_capacity, speciality, award, biography, pov_kvalif, staz, science, count_pub, top_pub, count_trained, course, address, phone, email, photo, lastupdate) "
                            . " VALUES ("
                            . "'', "
                            . "'" . $db->safesql($this->family) . "', "
                            . "'" . $db->safesql($this->name) . "', "
                            . "'" . $db->safesql($this->patronymic) . "', "
                            . "'" . $db->safesql($this->id_kafedra) . "', "
                            . "'" . $db->safesql($this->skill) . "', "
                            . "'" . $db->safesql($this->uch_step) . "', "
                            . "'" . $db->safesql($this->id_uch_step) . "', "
                            . "'" . $db->safesql($this->id_uch_zvan) . "', "
                            . "'" . $db->safesql($this->id_capacity) . "', "
                            . "'" . $db->safesql($this->speciality) . "', "
                            . "'" . $db->safesql($this->award) . "', "
                            . "'" . $db->safesql($this->biography) . "', "
                            . "'" . $db->safesql($this->pov_kvalif) . "', "
                            . "'" . $db->safesql($this->staz) . "', "
                            . "'" . $db->safesql($this->science) . "', "
                            . "'" . $db->safesql($this->count_pub) . "', "
                            . "'" . $db->safesql($this->top_pub) . "', "
                            . "'" . $db->safesql($this->count_trained) . "', "
                            . "'" . $db->safesql($this->course) . "', "
                            . "'" . $db->safesql($this->address) . "', "
                            . "'" . $db->safesql($this->phone) . "', "
                            . "'" . $db->safesql($this->email) . "', "
                            . "'" . $db->safesql($this->photo) . "', "
                            . "'" . mktime() . "'"
                            . ")";
           // print_r($insert_sql); exit;
                        $db->query($insert_sql, false);

                        return true;
                    }
                    else {
                        $update_sql = "UPDATE " . USERPREFIX . "_people SET  "
                            . "family='" . $db->safesql($this->family) . "', "
                            . "name='" . $db->safesql($this->name) . "', "
                            . "patronymic='" . $db->safesql($this->patronymic) . "', "
                            . "id_kafedra='" . $db->safesql($this->id_kafedra) . "', "
                            . "skill='" . $db->safesql($this->skill) . "', "
                            . "uch_step='" . $db->safesql($this->uch_step) . "', "
                            . "id_uch_step='" . $db->safesql($this->id_uch_step) . "', "
                            . "id_uch_zvan='" . $db->safesql($this->id_uch_zvan) . "', "
                            . "id_capacity='" . $db->safesql($this->id_capacity) . "', "
                            . "speciality='" . $db->safesql($this->speciality) . "', "
                            . "award='" . $db->safesql($this->award) . "', "
                            . "biography='" . $db->safesql($this->biography) . "', "
                            . "pov_kvalif='" . $db->safesql($this->pov_kvalif) . "', "
                            . "staz='" . $db->safesql($this->staz) . "', "
                            . "science='" . $db->safesql($this->science) . "', "
                            . "count_pub='" . $db->safesql($this->count_pub) . "', "
                            . "top_pub='" . $db->safesql($this->top_pub) . "', "
                            . "count_trained='" . $db->safesql($this->count_trained) . "', "
                            . "course='" . $db->safesql($this->course) . "', "
                            . "address='" . $db->safesql($this->address) . "', "
                            . "phone='" . $db->safesql($this->phone) . "', "
                            . "email='" . $db->safesql($this->email) . "', "
                            . "photo='" . $db->safesql($this->photo) . "', "
                            . "lastupdate='" . time() . "' "
                            . "WHERE id='" . intval($this->id) . "'";
                        $db->query($update_sql);
                        return true;
                    }
                } else {
                $this->removePhoto();
                return false;
            }
    }
    public function getAllPeopleFromDB(){
        global $db;
        if($db instanceof db){
            return $db->query( "SELECT id, family, name, patronymic, id_kafedra FROM " . USERPREFIX . "_people ORDER BY family");
        }
        return false;
    }
    public function removePerson(){
        global $db;
        if(!$this->removePhoto()){
            msg("error", "Ошибка", "Неудалось удалить фото пользователя", "javascript:history.go(-1)");
            return false;
        }
        return $db->query("DELETE FROM " . USERPREFIX . "_people WHERE id = {$this->id}");
    }
    public function getCountPersonIntoDB(){
        global $db;
        if($db instanceof db){
            $row = $db->super_query( "SELECT COUNT(id) as count FROM " . USERPREFIX . "_people" );
            return $row['count'];
        }
        return false;

    }
    public function getList ($id, $name, $array = array()){
        if($id){
            $list = "";
            $total = count($array)-1;
            foreach ($array as $key => $value) {
                $list .= "<div class=\"row entry\"><div class=\"col-md-12 col-sm-12\"> <input class=\"form-control width-400\" maxlength=\"350\"   name=\"{$name}[]\" type=\"text\" value=\"{$value}\"/>";
                if($key != $total){
                    $list .= '<i class="fa fa-trash-o position-right"></i></div></div>';
                }else{
                    $list .= '<i class="fa fa-plus position-right"></i></div></div>';
                }
            }
        }else{
            $list = "<input class=\"form-control width-400\" maxlength=\"350\" name=\"{$name}[]\" type=\"text\" /><i class=\"fa fa-plus position-right\"></i> ";
        }
        return $list;
    }

    private function removePhoto(){
        if ($this->getPhoto() != "") {
            $dir_photo = ROOT_DIR . "/uploads/kafedra/people/";
            if (file_exists($dir_photo . $this->getPhoto()) && is_file($dir_photo . $this->getPhoto())) {
                return unlink($dir_photo . $this->getPhoto());
            }
            return false;
        }
        return true;
    }
    private function uploadPhoto(){
        require_once "engine/kafedra/nucleus/functions/default.php";
        $fileTempName = $_FILES[photo][tmp_name];
        //Проверка загружаемого файла, расширение gif, jpeg или png
        $arr_info_img = getimagesize($fileTempName);
        $AllTypesImage = array('', 'GIF', 'JPG', 'PNG', 'SWF', 'PSD', 'BMP', 'TIFF(байтовый порядок intel)', 'TIFF(байтовый порядок motorola)', 'JPC', 'JP2', 'JPX');
        $allowTypesImage = array('', 'gif', 'jpeg', 'png');
        if(!$allowTypesImage[$arr_info_img[2]]){
            msg("error", "Ошибка", "Разрешена загрузка изображений следующих форматов: GIF, JPG, PNG.<br/>Было загружено изображение с расширением {$AllTypesImage[$arr_info_img[2]]}", "javascript:history.go(-1)");
            return;
        }
        $name = $this->getIdKafedra() . "_" . translation($this->getFamily());
        $namePhoto = $this->changeSizePhoto($fileTempName, $name);
        $this->removePhoto();
        $this->setPhoto($namePhoto);
    }
    private function normalizeFIO($name){
        $name = trim($name);
        $name = mb_strtoupper(mb_substr($name,0,1,"UTF-8")). mb_strtolower(mb_substr($name,1,1024,"UTF-8"));
        return $name;
    }
    private function changeSizePhoto($file_input, $nameFile) {

        $dir_output  = ROOT_DIR . "/uploads/kafedra/people/";

        if(!is_dir($dir_output)){
            if (!mkdir($dir_output, 0777, true)) {
                msg("error", "Ошибка", "Неудается создать директорию для хранения фоторгафий.", "javascript:history.go(-1)");
                return;
            }
        }

        $file_output = $nameFile . "_" . mktime();
        list($w_i, $h_i, $type) = getimagesize($file_input);
        if (!$w_i || !$h_i) {
            msg("error", "Ошибка", "Невозможно получить длину и ширину изображения", "javascript:history.go(-1)");
            return;
        }
        $types = array('', 'gif', 'jpeg', 'png');
        $ext = $types[$type];
        if (!$ext) {
            msg("error", "Ошибка", "Разрешена загрузка изображений следующих форматов: GIF, JPG, PNG", "javascript:history.go(-1)");
            return;
        }
        $func = 'imagecreatefrom' . $ext;
        $img = $func($file_input);

        // Если картинка чермерно вытянутая
        if((0.75 * $h_i) > $w_i) {
            $srcW = $w_i;                       //Высота копируемой области
            $srcH = round($w_i/0.75);           //Ширина копируемой области
            $srcY = round(($h_i - $srcH)/2);    //Левый верхний угол
            $srcX = 0;
        }
        // Если картинка приплюснутая
        else {
            $srcW = round($h_i*0.75);
            $srcH = $h_i;
            $srcX = round(($w_i - $srcW)/2);
            $srcY = 0;
        }

        $img_o = imagecreatetruecolor(150, 200);
        imagecopyresampled($img_o, $img, 0, 0, $srcX, $srcY, 150, 200, $srcW, $srcH);

        if ($type == 2) {
            if(imagejpeg($img_o,$dir_output.$file_output.".jpg",90)) {
                return $file_output.".jpg";
            }else {
                return '';
            }
        } else {
            $func = 'image'.$ext;
            if($func($img_o,$dir_output.$file_output.".".$ext)) {
                return $file_output.".".$ext;
            }
            else {
                return '';
            }
        }
    }

}