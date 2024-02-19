<?php
require_once("../../../bitrix/modules/main/bx_root.php");
require_once("../../../bitrix/modules/main/lib/composite/responder.php");
require_once("../../../bitrix/modules/main/include/prolog_before.php");
require_once "../../../vendor/autoload.php";

function connection() {
      $host = 'localhost';
      $user = 'root';
      $pass = '';
      $db = 'Practices';
      try{
        #$connect = new mysqli($host, $user,$pass,$db);
		$connect = Bitrix\Main\Application::getConnection();
		return $connect;
      }
      catch(Exception $e){
         die("[1] - connection_error");
      }
		return $connect;
}

function get_user_fields($user){
		$rsUser = CUser::GetByID($user->GetID());
		$arUser = $rsUser->Fetch();
	//print_r($arUser);
		return $arUser;
	}

function checkIsStudent($connect,$user_id){
		$groups_query = $connect->query("SELECT GROUP_ID FROM b_user_group WHERE USER_ID = '$user_id';");
		$groups=array();

		foreach($groups_query as $group){
			array_push($groups,$group["GROUP_ID"]);
		}

	if (in_array(17, $groups)){
		return true;
	}
	return false;
}

function add_student_otchet($connect,$student_id) {
      try{
			      $connect->query("INSERT INTO Practices.student_otchet (student_id, link_ya, status) 
						VALUES ('$student_id ','".$_POST['YaUrl']."', 0)");

               succesfull_insert();
      }
      catch(Exception $e){
         die("[2] - insert_error");
      }
   }

function delete_student_otchet($connect,$id) {
      try{
			      $connect->query("DELETE FROM Practices.student_otchet WHERE id = ".$id);

               succesfull_delete();
      }
      catch(Exception $e){
         die("[2] - delete_error");
      }
   }

function enrolled_check($connect,$student_id) {
      try{
         $resultset=$connect->query("SELECT student_id FROM Practices.student_practic Where student_id = $student_id");
         $result = $resultset->Fetch()["student_id"];
         if($result){
            return FALSE;
         }
         else{
            return TRUE;
         }
      }
      catch(Exception $e){
         die("[5] - select_error");
      }
   }





function succesfull_insert() {
	echo '<script type="text/javascript"> alert("Отчет отправлен!"); </script>';
	header("Refresh: 0");
   }
function succesfull_delete() {
	echo '<script type="text/javascript"> alert("Отправка отчета отменена!"); </script>';
	header("Refresh: 0");
   }
?>