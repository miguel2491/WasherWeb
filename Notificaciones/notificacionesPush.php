<?php    
        $uploaddir = 'http://www.washdryapp.com/app/storage/app/uploads/autos/';
        $file_name = underscore($_FILES['file']['name']);
        $uploadfile = $uploaddir.$file_name;
    
        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
            $dataDB['status'] = 'success';       
            $dataDB['filename'] = $file_name;
         } else {
            $dataDB['status'] =  'failure';       
         }
         $this->response($dataDB, 200);
    