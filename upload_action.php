<?php

   $MAX_DOSYA_BOYUTU_BYTE = 10485760; //10 mb

   if(isset($_FILES['file'])){

      $errors= array();
      $file_name = $_FILES['file']['name'];
      $file_size =$_FILES['file']['size'];
      $file_tmp =$_FILES['file']['tmp_name'];
      $file_type=$_FILES['file']['type'];
      $file_error=$_FILES['file']['error'];

      //https://secure.php.net/manual/tr/features.file-upload.errors.php
      echo 'file_error : '.$file_error;

      $temp = explode('.', $file_name);
      $file_ext = strtolower(end($temp));
      
      //csv
      
      if($file_ext != "csv"){
         echo "sadece csv uzantılı dosyalar yüklenebilir!";
         die();
      }
      
      if($file_size > $MAX_DOSYA_BOYUTU_BYTE){
         echo 'dosya boyutu en fazla 10 MB olabilir!';
         die();
      }

      move_uploaded_file($file_tmp, __DIR__ ."/data/".$file_name); //dosya oluşturuldu
   }

  header('Location: index.php');

?>