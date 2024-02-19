<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Отчёт");
?>

<?php
require_once('function_for_student_otchet.php');
		$connect=connection();

		$student = get_user_fields($USER);
		$student_id = $student["UF_MIRA_ID"];
		if (checkIsStudent($connect,$student["ID"]) == false){
			exit();
		}

		print("[debug] Пользователь: ".$student["LAST_NAME"]." ".$student["NAME"]." ".$student["SECOND_NAME"]);

		$student_request = $connect->query("SELECT * FROM Practices.student_practic WHERE student_id ='".$student_id."' ;")->Fetch();
		$teacher_practic = $connect->query("SELECT * FROM Practices.teachers WHERE id ='".$student_request['teacher_id']."';")->Fetch();
		$company_practic = $connect->query("SELECT * FROM Practices.companies WHERE id ='".$student_request['company_id']."';")->Fetch();


			$name = $student["LAST_NAME"].' '.$student["NAME"].' '.$student["SECOND_NAME"];
			$teacher = $teacher_practic['fio'];
			$company = $company_practic['name'];
			$theme = $student_request['theme'];

		$student_otchet = $connect->query("SELECT * FROM Practices.student_otchet WHERE student_id ='".$student_id."';")->Fetch();
					if($student_otchet){
						$disabled = "disabled";
						switch($student_otchet['status']){
							case 0:
							$status = 'Не проверен';
							break;
							case 1:
							$status = 'Принят';
							break;
							case 2:
							$status = 'Не принят';
							break;
							}
					}
					else{
						$disabled = "";
						$status = 'Не отправлено';
					}


//		}

if (isset($_POST['SubmitYaUrl'])) {

/*
    $url = $_POST['YaUrl'];


	$url_p=parse_url($url);
    $ch = curl_init("https://cloud-api.yandex.net/v1/disk/public/resources?public_key=" . $url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);

    $http_code = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    curl_close($ch);

    if ($http_code >= 400 && $http_code < 600) {
        echo 'Страница не доступна. Код ответа: '.$http_code;
    } else {
        $jsonS = file_get_contents("https://cloud-api.yandex.net/v1/disk/public/resources?public_key=" . $url);
        $json = json_decode($jsonS);
        var_dump($json->_embedded->total);
        for ($i = 0; $i < $json->_embedded->total; $i++) {
            var_dump($json->_embedded->items[$i]->name);
        }
}*/

	add_student_otchet($connect,$student_id);
    
}

if (isset($_POST['fallSubmitYaUrl'])) {

	delete_student_otchet($connect,$student_otchet['id']);
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Веб-форма загрузки ссылки</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</head>
<body>


<div class="all_fragments">
        <div class="fragment one">

    <form method="POST" action="#">
			<div class="form-floating">
  				<input class="form-control" id="lastName" placeholder="name@example.com" value="<?php echo $name;?>" readonly>
  				<label for="lastName">ФИО</label>
			</div>
			<div class="form-floating">
  				<input class="form-control" id="Tema" placeholder="name@example.com" value="<? echo $teacher;?>" readonly>
  				<label for="Tema">Руководитель практики</label>
			</div>
			<div class="form-floating">
  				<input  class="form-control" id="company" placeholder="name@example.com" value='<? echo $company;?>' readonly>
  				<label for="company">Компания</label>
			</div>
			<div class="form-floating">
  				<input  class="form-control" id="Tema" placeholder="name@example.com" value='<? echo $theme;?>' readonly>
  				<label for="Tema">Тема</label>
			</div>
			<div class="form-floating">
  				<button class="form-control" id="dogovor">Скачать</button>
  				<label for="dogovor">Договор</label>
			</div>
            <div >
				<? if($disabled == ""){
            	echo '<label class="form-control">Необходимо отправить ссылку на облачное хранилище <br/>
										<label>с отчётными документами, для корректной загрузки <br/> 
										<label>файлов используйте инструкцию ниже.<br/> 
            	<a  href="https://drive.google.com/drive/folders/1VtF_41jN8bayRbZWEAp7h_r_7CUxyLp1" target="_blank">Ссылка на инструкцию!</a></label>
            </div>';
				}
				?>
			<div class="form-floating">
				<? if($disabled == ""){
					echo '<input type="text" class="form-control" id="yaUrl" placeholder="ссылка на яндекс диск">
							<label for="yaUrl">Ссылка на Яндекс Диск</label>';
				}
				else {
					echo '<a class="form-control" id="YaUrl" href="'.$student_otchet['link_ya'].'" >Отчет</a>
							<label for="yaUrl">Ссылка на Яндекс Диск</label>';	
				}
				?>
			</div>

            <div class="form-floating">
            	<input type="text" id="status" class="form-control" readonly value="<? echo $status;?>">
				<label for="status">Статус проверки</label>
			</div>
			<div class="d-flex justify-content-center">
				<? if($disabled == ""){
					echo '<button type="Submit" name="SubmitYaUrl" class="btn btn-primary" ">Отправить</button>';
				}
				else {
					echo '<button type="submit" name="fallSubmitYaUrl" class="btn btn-primary">Отменить отправку</button>';
				}
				?>
			</div>
<div class="d-flex justify-content-center">
 <?php
    if ($student_otchet) {
        $status_message = '';
        switch ($student_otchet['status']) {
            case 0:
                $status_message = 'Ждите уведомление о проверки вашего отчета руководителем практики!';
				$status_color = 'primary';
                break;
			case 1:
                $status_message = 'Руководитель принял вашу заявку!';
				$status_color = 'success';
                break;
			case 2:
                $status_message = 'Преподаватель отклонил вашу заявку, для получения большей информации свяжитесь с ним.';
				$status_color = 'danger';
                break;

        }

        if ($status_message) {
			echo '<div class="alert alert-'.$status_color.' mt-2">' . $status_message . '</div>';
        }
    }
    ?>
		</div>
    	</form>
	</div>
</div>
	<style>
           input 
            {
                margin: 2px;
            }
.all_fragments {
        display: flex;
        flex-direction: row-reverse;
        align-items: stretch;
        justify-content: space-evenly;
    }
    .fragment {
        padding: 80px 0;
    }
    .block {
    display: flex;
    align-items: flex-start;
    flex-direction: column;
    width: 100%;
    max-width: 200px;
    margin: 20px auto;
    text-align: center; 
	}
        .size1
        {
            Height: 25px; 
			Top: 107px;
   			Left: 235px;
 			border-radius: 10px;
			border: 1px solid black;

        }
		.text{
			Width: 100px;
			Height: 16px;
			Top: 106px;
			Left: 188px;
			Font: Helvetica Neue OTS;
			Weight: 400;
			font-size: 25px;
			Line height: 16px;
        }
        .input1
        {
            display: inline;
            color: #FFF;
            text-align: center;
            font-family: Inter;
            font-size: 20px;
            font-style: normal;
            font-weight: 400;
            line-height: normal;
            width: 250px;
            height: 45px;
            flex-shrink: 0;
            border-radius  : 10px;
            border: none;
            background: #2155AF;
        }
        .silka
        {
            color: #2155AF;
            text-align: center;
            font-family: Inter;
            font-size: 32px;
            font-style: normal;
            font-weight: 400;
            line-height: normal;
            border-radius: 10px;
            background: #D9D9D9;
            width: 397px;
            height: 46px;
            flex-shrink: 0;
        }
        .text2
        {
            color: red;
            font-family: Inter;
            font-size: 28px;
            font-style: normal;
            font-weight: 400;
            line-height: normal;            
        }
  		.in {
            display: inline-block;
        }
    </style>

</body>
</html>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");

?>