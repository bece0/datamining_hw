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
    $OKUNACAK_DOSYA_ADI =  "";
    if(isset($_GET['name']) && $_GET['name'] != ""){
        $OKUNACAK_DOSYA_ADI = $_GET['name'];
    }else {
        echo "name parametresi değeri bulunamadı!!";
        die();
    }
 ?>

<?php

require_once __DIR__ . '/vendor/autoload.php';
use Phpml\Classification\DecisionTree;

/**
 * $input = içerisinde ham kayıt ve kayıtlara karşılık gelen sonuç koLonlarının bulunduğu 2 boyutlu dizi
 * $ks = sonuç verisinin bulunduğu index.
 * 
 * her kayıttan sonuç kolonu değerini siler ve bunlardan yeni bir 2 boyutlu dizi oluşturur.
 * Sonuç kolonunu tek boyutlu diziye çevirir.
 * 
 * Bu iki diziyi geri döner.
 */
function getData2(array $input, $ks) : array
{
    $targets = array_column($input, $ks);
    array_walk($input, function (&$v): void {
        array_splice($v, count($v) - 1, 1);
    });
    return [$input, $targets];
}

/*
function getData3(array $input): array
{
    $targets = array_column($input, 4);
    array_walk($input, function (&$v): void {
        array_splice($v, 4, 1);
    });
    return [$input, $targets];
}

// verilen 2 boyutlu verinin son kolon değerlerini ayırır.
function getData(array $input): array
{   
    $kolon_sayisi = count($input[0]);
    $targets = array_column($input, $kolon_sayisi - 1 );
    for ($i=0; $i < count($input); $i++) { 
        $input[$i] =  array_slice($input[$i], 0, $kolon_sayisi - 1 );
    }
    // var_dump($input);echo "<br>";
    // var_dump($targets);;echo "<br>";
    return [$input, $targets];
}
/*

/* 
$data = [
    ['sunny',       85,    85,    'false',    'Dont_play'],
    ['sunny',       80,    90,    'true',     'Dont_play'],
    ['overcast',    83,    78,    'false',    'Play'],
    ['rain',        70,    96,    'false',    'Play'],
    ['rain',        68,    80,    'false',    'Play'],
    ['rain',        65,    70,    'true',     'Dont_play'],
    ['overcast',    64,    65,    'true',     'Play'],
    ['sunny',       72,    95,    'false',    'Dont_play'],
    ['sunny',       69,    70,    'false',    'Play'],
    ['rain',        75,    80,    'false',    'Play'],
    ['sunny',       75,    70,    'true',     'Play'],
    ['overcast',    72,    90,    'true',     'Play'],
    ['overcast',    81,    75,    'false',    'Play'],
    ['rain',        71,    80,    'true',     'Dont_play'],
];

$input = [
    ['sunny',       85,    85,    'false'],
    ['sunny',       80,    90,    'true'],
    ['overcast',    83,    78,    'false']
    .....    
]

$targets = ['Dont_play', 'Dont_play', 'Play', 'Play' ...]

$extraData = [
    ['scorching',   90,     95,     'false',   'Dont_play'],
    ['scorching',  100,     93,     'true',    'Dont_play'],
];

[$data, $targets] = getData2($data, count($data[0])-1);
$classifier = new DecisionTree(count($data[0]));
$classifier->train($data, $targets);
echo "1. = ".$classifier->predict(['sunny', 78, 72, 'false'])."<br>";
echo "2. = ".$classifier->predict(['overcast', 60, 60, 'false'])."<br>";
 
*/
//die();

/*
Verilen diziyi belirtilen yuzdelik dilime göre iki parça diziye böler ve bunları döner.
*/
function DataParcala($data, $yuzde){

    $uzunluk = count($data);
    //bu değişken dizinin kaçıncı elemanına kadar parçalanacağını gösterir
    $ilk_yuzde_son_index = $uzunluk;

    if($yuzde == NULL || $yuzde >= 100 || $yuzde <= 0){
        $ilk_yuzde_son_index = $uzunluk ;
    }else {
        $ilk_yuzde_son_index = floor (($uzunluk * $yuzde) / 100);
    }

    //echo $uzunluk." ----  ".$ilk_yuzde_son_index."<br>";

    $ilk_yuzde = array_slice($data, 0, $ilk_yuzde_son_index);
    $ikinci_yuzde = array_slice($data, $ilk_yuzde_son_index);

    // echo "<br> ilk_yuzde : <br>";
    // var_dump($ilk_yuzde); 

    // echo "<br> ikinci_yuzde : <br>";
    // var_dump($ikinci_yuzde);

    return [$ilk_yuzde, $ikinci_yuzde];
}

//Verilen dosya ismine göre okuma yapar ve iki boyutlu dizi döner.
//Dizinin ilk satırı kolon isimleridir.
function CsvDosyaOku($dosyaAdi){
    $tablo = array();
    $dosya_yolu = __DIR__ . "/data/" . $dosyaAdi;
    if (($handle = fopen($dosya_yolu , "r")) !== FALSE) {
        $satir_sayisi = 0;
        while (($satir = fgetcsv($handle, 10000, ",")) !== FALSE) {
            if($satir != NULL && $satir[0] != NULL){
                if($satir_sayisi == 0){
                    $satir_sayisi++;
                    continue;
                }
                // if($satir_sayisi == 0)
                //     array_unshift($satir , 'Index'); //kolon isimlerinin başına index kolonu eklendi
                // else
                //     array_unshift($satir , $satir_sayisi); //satırların başına index değeri ekler

                $uzunluk = count($satir);
                
                for ($k=0; $k < $uzunluk; $k++) { 
                    //var_dump($satir[$k]); echo "<br>";

                    $deger = $satir[$k];
                    if(is_numeric($deger)){
                        //echo " is_numeric";
                        $satir[$k] = intval($deger);
                        // if(is_int($satir[$k]))
                        //     $satir[$k] = intval($satir[$k]);
                        // if(is_float($satir[$k]))
                        //     $satir[$k] = floatval($satir[$k]);
                    }
                    //var_dump($satir[$k]); echo "<br>";
                }

                $tablo[$satir_sayisi-1] = $satir;
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

/**
 * Train verisi ile eğittiğimiz karar ağacı nesnesine test datasını verip sonuçları hesaplar
 */
function TahminTest($test_data, $classifier, $train_data_sayisi){
    echo "<div>";
    echo "</p> Running Test  : </p>";
    echo "<table border='1'><tr><td>";

    $dogruTahminSayisi = 0;
    $toplamTest = count($test_data);
    for ($j=0; $j < $toplamTest; $j++) {
        $beklenen = end($test_data[$j]);
        
        //var_dump($test_data[$j]);
        //son kolon hariç
        $test_kayit = array_slice($test_data[$j], 0, -1);
        //var_dump($test_kayit);

        //karar ağacına test verisi verip tahmin sonucunu aldık.
        $sonuc = $classifier->predict($test_kayit);

        echo "Test $j - Beklenen : <b>$beklenen</b>  Sonuç: <b>$sonuc</b></br>";
        if($beklenen == $sonuc)
            $dogruTahminSayisi++;
        //echo "3. = ".$classifier2->predict($test_data[$j])."<br>";
    }
    echo "</td>";

    echo "<td valign='top'>";
    echo "Toplam Eğitim Verisi Sayısı : <b>$train_data_sayisi</b></br>";
    echo "Toplam Test Verisi sayısı : <b>$toplamTest</b></br>";
    echo "Doğru Tahmin Sayısı : <b>$dogruTahminSayisi</b></br>";

    $basariOrani = floor(($dogruTahminSayisi/$toplamTest) * 100);
    echo "Başarı Oranı : <b>%$basariOrani</b></br>";
    echo "</td></tr></table>";

    echo "</div>";
}

?>
        <div class="container2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Datasets</a></li>
                    <li class="breadcrumb-item">Decision Tree</li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?php echo $OKUNACAK_DOSYA_ADI ; ?>
                    </li>
                </ol>
            </nav>
            <?php            
            
            $data_full =  CsvDosyaOku($OKUNACAK_DOSYA_ADI);
            $kolon_sayisi = count($data_full[0]);

            //veri yuzde 70 ve 30 oranında parçalanıyor
            [$train_datasi, $test_data] = DataParcala($data_full, 70);
            

            //eğitim verisinin içindeki girdi ve çıktı(sonuç) verilerini parçala
            [$train_kolon_datasi, $train_hedef_datasi] = getData2($train_datasi, $kolon_sayisi - 1);

            //karar ağacı nesnesi oluşturduk. parametre olarak verimizin içindeki kolon sayısını verdik.
            // kolon sayısı ağacın max. derinliğidir.
            $classifier = new DecisionTree($kolon_sayisi);
            //train metoduna girdi ve çıktı verilerini vererek, karar ağacımızı eğittik/oluşturduk
            $classifier->train($train_kolon_datasi, $train_hedef_datasi);
            echo "</p> Train completed </p>";

            //test verimizi oluşturulan karar ağacımız üzerinde test ettik.
            TahminTest($test_data, $classifier, count($train_datasi) );
            ?>
            <div class="table-container">

            </div>
            <div>
 
            </div>
        </div>
    </body>
</html>
