<!-- 151213059 BEGÜM ÇELEBİ -->


<!DOCTYPE html>
<html>
    <head>
    <meta charset="UTF-8">
        <title>Dataminin - Odev 0</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <style>
        .container2{
            margin-left: 50px;
            margin-right: 50px; 
                
        }

        .table-container{
            overflow-x: auto;
            white-space: nowrap;
            height: 45vh;
            width: 100%;
            border-bottom: 1px solid #6c757d;
        }

        .kolon-satiri{
            background-color: #3cb371;
            font-weight: bold;
        }

        .index-kolonu{
            background-color: #d0dcdc;
            font-weight: bold;
        }

        </style>

    </head>
    <body>
<?php

//Verilen dosya ismine göre okuma yapar ve iki boyutlu dizi döner.
//Dizinin ilk satırı kolon isimleridir.
function CsvDosyaOku($dosyaAdi){
    $tablo = array();
    $dosya_yolu = __DIR__ . "/data/" . $dosyaAdi;
    if (($handle = fopen($dosya_yolu , "r")) !== FALSE) {
        $satir_sayisi = 0;
        while (($satir = fgetcsv($handle, 10000, ",")) !== FALSE) {
            if($satir != NULL && $satir[0] != NULL){
                if($satir_sayisi == 0)
                    array_unshift($satir , 'Index'); //kolon isimlerinin başına index kolonu eklendi
                else
                    array_unshift($satir , $satir_sayisi); //satırların başına index değeri ekler

                $tablo[$satir_sayisi] = $satir;
                $satir_sayisi++;
            }
        }
        fclose($handle);
    }else{
        echo $dosya_yolu. " isimli dosya bulunamadı!";
        die();
    }

    return $tablo;
}

//Verilen 2 boyutlu diziyi tablo olarak yazdirir.
function TabloYazdir($tabloVerisi){
    if($tabloVerisi ==null || count($tabloVerisi) == 0){
        echo "<p>tablo boş!!</p>";
    }
    echo "<table class='table tbl-bordered table-sm'>";
   
    for ($i=0; $i < count($tabloVerisi) ; $i++) {
        if($i == 0)
            echo "<tr class='kolon-satiri'>";
        else
            echo "<tr>";

        for($j=0; $j <count($tabloVerisi[$i]) ; $j++) {
            if($j == 0)
                echo "<td class='index-kolonu'>".$tabloVerisi[$i][$j]."</td>";
            else
                echo "<td>".$tabloVerisi[$i][$j]."</td>";
        }
        echo "</tr>";
    }

   echo "</table>";
}

//iki boyutlu dizi alır, her bir kolonu(sayısal olanlar) için hesaplamaları yapar
//ekrana basar.
function HesaplamaYazdir($tabloVerisi){
    if($tabloVerisi ==null || count($tabloVerisi) <= 1){
        echo "<p>tablo boş!!</p>";
    }

    $kolon_isimler_arr = $tabloVerisi[0];
    $kolon_sayisi = count($kolon_isimler_arr);
    // echo "kolon_sayisi ".$kolon_sayisi;

    //1 den başlıyoruz, çünkü index kolonunu hesaplamıyoruz.
    for ($i=1; $i < $kolon_sayisi ; $i++) { 
         //echo $i.". kolon için hesaplamalar :".$kolon_isimler_arr[$i]." <br>";

         $kolon_ilk_deger = $tabloVerisi[1][$i];

         echo "<div>";
         if(is_numeric($kolon_ilk_deger)){
            
            $kolon_veri = KolonGetir($tabloVerisi, $i);
            $kolon_veri_sirali = KolonSıralıGetir($tabloVerisi, $i);

            echo "<b>".$kolon_isimler_arr[$i]."</b><br>";

            $min = minHesapla($kolon_veri);
            echo "Min : ".$min."<br>";
            
            $max = maxHesapla($kolon_veri, $i);
            echo "Max : ".$max."<br>";

            $medyan = medyanHesapla($kolon_veri_sirali);
            echo "Median : ".$medyan."<br>";

            $Q1 = Q1Hesapla($kolon_veri_sirali);
            echo "Q1 : ".$Q1."<br>";

            $Q3 = Q3Hesapla($kolon_veri_sirali);
            echo "Q3 : ".$Q3."<br>";

            $outlier_indexs = OutlierHesapla($kolon_veri, $Q1, $Q3);
            echo "<br><i>OUTLIER INDEXES</i><br>";
            if($outlier_indexs !=null && count($outlier_indexs)>0){
                for ($j=0; $j < count($outlier_indexs) ; $j++) { 
                    echo $outlier_indexs[$j]."<br>";
                }
            }else{
                echo "Outlier sample not found<br>";
            }

         }else{
            echo "<b>CLASS AND LABELS FOR : </b> ".$kolon_isimler_arr[$i]."<br>";

            $kolon_verisi = KolonGetir($tabloVerisi, $i);
            $labels = ClassLabelHesapla($kolon_verisi);

            foreach ($labels as $key => $deger) {
                echo $key." : ".$deger."<br>";
            }
         }
         echo "<hr>";
         echo "</div>";
    }
 
}

//
function ClassLabelHesapla($kolon_veri){
    
    $sonuc = array();

    $satirsayisi = count($kolon_veri);
    for ($i= 0; $i < $satirsayisi  ; $i++) { 
        $key = $kolon_veri[$i];
        if(array_key_exists($key,$sonuc)){
            $sonuc[$key] = $sonuc[$key] + 1;
        }else{
            $sonuc[$key] = 1;
        }
    }
    return $sonuc;
}

//
function ortalamaHesapla($ikiboyutludizi, $kolonsirasi){
    $toplam = 0.0;
    $satirsayisi = count($ikiboyutludizi);
    for ($i=1; $i < $satirsayisi  ; $i++) { 
        $toplam =  $toplam + $ikiboyutludizi[$i][$kolonsirasi];
    }
    return $toplam / $satirsayisi ;
}


function minHesapla($kolon_veri){
    $min=$kolon_veri[0];
    for($i=0; $i < count($kolon_veri)  ; $i++){
        if($kolon_veri[$i]<$min) 
            $min=$kolon_veri[$i];
    }
    return $min;
}


function maxHesapla($kolon_veri){
    $min=$kolon_veri[0];
    for($i=0; $i < count($kolon_veri)  ; $i++){
        if($kolon_veri[$i]>$min) 
            $min=$kolon_veri[$i];
    }
    return $min;
}


function medyanHesapla($kolon_veri_sirali){
    
    $eleman_sayisi = count($kolon_veri_sirali);

    if($eleman_sayisi %2 === 0){
        $ortancalar_toplam = $kolon_veri_sirali[$eleman_sayisi/2]  + $kolon_veri_sirali[$eleman_sayisi/2 - 1];
        return (double)$ortancalar_toplam/2;
    }else{
        return (double)$kolon_veri_sirali[($eleman_sayisi + 1)/2];
    }
}


function KolonSıralıGetir($ikiboyutludizi, $kolonsirasi){
     $kolon_degerleri = KolonGetir($ikiboyutludizi, $kolonsirasi);

     sort($kolon_degerleri);
     return $kolon_degerleri;
}


function KolonGetir($ikiboyutludizi, $kolonsirasi){
     $kolon_degerleri = array();

     for ($i=1; $i < count($ikiboyutludizi); $i++) { 
        $kolon_degerleri[$i-1] = $ikiboyutludizi[$i][$kolonsirasi];
     }
     return $kolon_degerleri;
}


function Q3Hesapla($sirali_degerler){
    $eleman_sayisi = count($sirali_degerler);

    if($eleman_sayisi % 2 == 0 ){
        $ilk_yari = array_slice($sirali_degerler, ($eleman_sayisi/2 -1));
        return medyanHesapla($ilk_yari);
    }else{
        $ilk_yari = array_slice($sirali_degerler, ($eleman_sayisi/2));
        return medyanHesapla($ilk_yari);
    }
}


function Q1Hesapla($sirali_degerler){
    $eleman_sayisi = count($sirali_degerler);

    if($eleman_sayisi % 2 == 0 ){
        $ilk_yari = array_slice($sirali_degerler,0, ($eleman_sayisi/2 -1));
        return medyanHesapla($ilk_yari);
    }else{
        $ilk_yari = array_slice($sirali_degerler,0, ($eleman_sayisi/2));
        return medyanHesapla($ilk_yari);
    }
}


function OutlierHesapla($kolon_veri, $Q1, $Q3){
    $eleman_sayisi = count($kolon_veri);
    $sonuc_array = array();

    $IQR = $Q3 - $Q1;
    $q1_sinir = $Q1 - ($IQR * 1.5);
    $q3_sinir = $Q3 + ($IQR * 1.5);

    for ($i=0; $i < $eleman_sayisi; $i++) { 
        if($kolon_veri[$i] < $q1_sinir || $kolon_veri[$i] > $q3_sinir)
            array_push($sonuc_array, $i);
    }

    return  $sonuc_array;
}

?>
 
 <?php
    //TODO - okunacak dosyanın adı url'den parametre olarak alınabilir. ör : http://localhost/datamining/index.php?name=Iris.csv
    $OKUNACAK_DOSYA_ADI =  "";//Varsayılan dosya adı Iris.csv şimdilik...
    if(isset($_GET['name']) && $_GET['name'] != ""){
        $OKUNACAK_DOSYA_ADI = $_GET['name'];
    }else {
        echo "name parametresi değeri bulunamadı!!";
        die();
    }
 ?>
        <div class="container2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Datasets</a></li>
                    <li class="breadcrumb-item">View</li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?php echo $OKUNACAK_DOSYA_ADI ; ?>
                    </li>
                </ol>
            </nav>
            <?php $tablo =  CsvDosyaOku($OKUNACAK_DOSYA_ADI); ?>
            <div class="table-container">
                <?php 
                    if($tablo != null)
                        TabloYazdir($tablo);
                    echo "<br>";
                ?>
            </div>
            <div>
                <?php 
                    if($tablo != null)
                        echo "<b>TITLE</b> : ".$OKUNACAK_DOSYA_ADI."<br>";

                    echo "<b>COLUMN NAMES</b> : [";
                    for($i=1 ; $i < count($tablo[0]) ; $i++){                      
                        echo  $tablo[0][$i].", ";
                    }
                    echo "]<br>";  
                    echo "<hr>"; 
                    
                    HesaplamaYazdir($tablo);
                ?>
            </div>
        </div>

    </body>
</html>
