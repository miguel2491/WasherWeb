<?php
		//header('Content-Type: image/jpg; charset=utf-8');
        header('Content-Type: bitmap; charset=utf-8');
        $uploaddir = '../oficial/Autos/';
        $file_name = time()."-".$_FILES['file']['name'];
        $size = $_FILES['file']['size'];
        $uploadfile = $uploaddir.$file_name;
        
        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
            $dataDB['status'] = 'success';       
            $dataDB['filename'] = $file_name;
         } else {
            $dataDB['status'] =  'failure';       
         }
                
         echo $file_name;
    