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

            //KNN algoritmasını  kullanabilmek için KNN sınıfına erişim için bu eklenir.
            require_once('./algorithms/KNN.php');

            //KNN sınıfının nesnesi oluşturulur
            $knnAlgoritmaNesnesi = new KNN($OKUNACAK_DOSYA_ADI);
        ?>

        <div class="container2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Datasets</a></li>
                    <li class="breadcrumb-item">K-Nearest Neighbor (KNN)</li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?php echo $OKUNACAK_DOSYA_ADI ; ?>
                    </li>
                </ol>
            </nav>

            <?php 
                //Sayfa POST ile yüklenmemiş ise arayüzde form gösterilecek.
                if($_SERVER['REQUEST_METHOD'] != "POST"){

                    $sozelKolonlar = $knnAlgoritmaNesnesi->SozelKolonlar;
                    $sayisalKolonlar = $knnAlgoritmaNesnesi->SayisalKolonlar;

                    // var_dump($sozelKolonlar);
                    // echo "<br>";
                    // var_dump($sayisalKolonlar);

                    $sozelKolonlarSelect = "";
                    foreach ($sozelKolonlar as $key => $value) {
                        $sozelKolonlarSelect = $sozelKolonlarSelect."<option value='$key'>$key</option>\n";
                    }

                    $sayisalKolonlarSelect = "";
                    foreach ($sayisalKolonlar as $key => $value) {
                        $sayisalKolonlarSelect = $sayisalKolonlarSelect."<option value='$key'>$key</option>\n";
                    }
                ?>
                    <h4>K-NN algoritması için çalışma parametrelerini belirtiniz.</h4>
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <form method="POST">
                            <div class="form-group">
                                <label class="control-label">K Değeri</label>
                                <input class="form-control" type="number" name="k_value" placeholder="K değeri" value="3" min="2">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Method</label>
                                <select class="form-control" name="method">
                                    <option value="1">En Çok Tekrarlanan Sınıf</option>
                                    <option value="2">Ağırlıklı Oylama</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">X Column</label>
                                <select class="form-control" name="x_column_name">
                                    <?php echo $sayisalKolonlarSelect;?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Y Column</label>
                                <select class="form-control" name="y_column_name">
                                    <?php echo $sayisalKolonlarSelect;?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Label Column</label>
                                <select class="form-control" name="label_column_name">
                                    <?php echo $sozelKolonlarSelect;?>
                                </select>
                            </div>
                            <?php if(count($sayisalKolonlar)==0 || count($sozelKolonlar) == 0 ) { ?>
                                <input type="submit" class="btn btn-success" value="Calculate" disabled>
                            <?php } else { ?>
                                <input type="submit" class="btn btn-success" value="Calculate">
                            <?php }?>
                        </form>
                    </div>

            <?php } else { 
                //Sayfa post ile yüklenmiş ise gelen parametreler alınır.
                $kDegeri = $_POST["k_value"];
                $method = $_POST["method"];
                $xKolonAdi = $_POST["x_column_name"];
                $yKolonAdi = $_POST["y_column_name"];
                $labelKolon = $_POST["label_column_name"];
                

                $ERROR = $knnAlgoritmaNesnesi->ParametreleriAyarla($kDegeri, $method, $xKolonAdi, $yKolonAdi, $labelKolon);
                if($ERROR != NULL){
                    echo "<div class='alert alert-danger' role='alert'>$ERROR</div>";
                    die();
                }

                // $ERROR = $knnAlgoritmaNesnesi->Calistir();
                // if($ERROR != NULL){
                //     echo "<div class='alert alert-danger' role='alert'>$ERROR</div>";
                //     die();
                // }

            ?>
               
                <div class="alert alert-primary" role="alert">
                    <?php  
                        if($method == "1")
                            echo "Method : <b>En Çok Tekrarlanan Sınıf</b><br>";
                        else
                            echo "Method : <b>Ağırlıklı Oylama</b><br>";
                            
                        echo "X Değer Kolon Adı : <b>".$xKolonAdi."</b><br>";
                        echo "Y Değer Kolon Adı : <b>".$yKolonAdi."</b><br>";
                        echo "Label Değer Kolon Adı : <b>".$labelKolon."</b><br>"; 

                        $dogrulukOrani = $knnAlgoritmaNesnesi->DogrulukHesapla();
                    ?>
                </div>
                <div class="alert alert-info" role="alert">
                    <?php echo "Doğruluk Oranı : <b> % $dogrulukOrani </b><br>"; ?> 
                </div>

                <h4>Sınıfı tahmin edilecek değerleri giriniz.</h4>
                <div class="col-md-6 col-sm-12">
                    <form method="POST">
                        <input type="hidden" name="k_value" value="<?php echo $kDegeri;?>" >
                        <input type="hidden" name="method" value="<?php echo $method;?>" >
                        <input type="hidden" name="x_column_name"  value="<?php echo $xKolonAdi;?>" >
                        <input type="hidden" name="y_column_name"  value="<?php echo $yKolonAdi;?>" >
                        <input type="hidden" name="label_column_name"  value="<?php echo $labelKolon;?>" >
                        <div class="form-group">
                            <label class="control-label">X Kolon Değeri - <?php echo $xKolonAdi;?></label>
                            <input type="number" class="form-control" name="y_tahmin_value">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Y Kolon Değeri - <?php echo $yKolonAdi;?></label>
                            <input type="number" class="form-control" name="x_tahmin_value">
                        </div>
                        <input type="submit" class="btn btn-primary" value="Tahmin Et">
                    </form>
                </div>

                <?php if(isset($_POST["y_tahmin_value"]) && isset($_POST["x_tahmin_value"])) { 
                    $xTahmin = $_POST["x_tahmin_value"];
                    $yTahmin = $_POST["y_tahmin_value"];

                    // echo "Tahmin edilecek değerler -> X : ".$xTahmin." Y : ".$yTahmin;
                    $sonuc = $knnAlgoritmaNesnesi->TahminEt($xTahmin, $yTahmin);

                    
                ?>
                    <hr>
                     <div class="alert alert-success" role="alert">
                        <?php echo $xKolonAdi." -> <b>".$xTahmin."</b><br>"; ?>
                        <?php echo $yKolonAdi." -> <b>".$yTahmin."</b><br>"; ?>
                        <?php 
                            if($sonuc != NULL)
                                echo "Sonuç : <b>".$sonuc[0]."</b><br>"; 
                                //var_dump($sonuc);
                        ?>
                       
                    </div>
                <?php }?>

            <?php } ?>

        </div>

    </body>
<html>