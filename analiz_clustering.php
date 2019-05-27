<!-- 151213059 BEGÜM ÇELEBİ -->

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Dataminin - Odev 0</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" ></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" ></script>
        <style>
        .container2{
            margin-left: 50px;
            margin-right: 50px;
        }
        </style>
    </head>

    <body>
        <?php
            error_reporting(E_ALL);
            ini_set('display_errors', TRUE);
            ini_set('display_startup_errors', TRUE);

            $OKUNACAK_DOSYA_ADI =  "";
            if(isset($_GET['name']) && $_GET['name'] != ""){
                $OKUNACAK_DOSYA_ADI = $_GET['name'];
            }else {
                echo "name parametresi değeri bulunamadı!!";
                die();
            }

            require_once('./algorithms/K-MEANS.php');
            $kMeansalgoritmaNesnesi = new KMEANSAlg($OKUNACAK_DOSYA_ADI);

            $sayisalKolonlar = $kMeansalgoritmaNesnesi->SayisalKolonlar;
            $sayisalKolonlarSelect = "";
            foreach ($sayisalKolonlar as $key => $value) {
                $sayisalKolonlarSelect = $sayisalKolonlarSelect."<option value='$key'>$key</option>\n";
            }
        ?>

        <div class="container2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Datasets</a></li>
                    <li class="breadcrumb-item"><a href="analiz_clustering.php?name=<?php echo $OKUNACAK_DOSYA_ADI;?>">Clustering</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?php echo $OKUNACAK_DOSYA_ADI; ?>
                    </li>
                </ol>
            </nav>

            <h4>Sınıflandırma için çalışma parametrelerini belirtiniz.</h4>
            <div class="col-lg-6 col-md-12 col-sm-12">
                <div class="form-group">
                    <label class="control-label">Method Seçiniz</label>
                    <select class="form-control" name="method" id="method" onchange="metodDegisim()">
                        <option value="bolunmeli">Bölünmeli - K-Means</option>
                        <option value="hiyerarsik">Hiyerarşik yöntem</option>
                        <option value="yogunluk">Yoğunluk Tabanlı yöntem</option>
                    </select>
                </div>
                
                <form method="GET" id="bolunmeliForm" action="clustering/kmeans-page.php" >
                    <input type="hidden" name="name" value="<?php echo $OKUNACAK_DOSYA_ADI;?>">
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
                        <label class="control-label">Cluster Count</label>
                        <input class="form-control" name="cluster_count" value="3"/>
                    </div>
                    <input type="submit" class="btn btn-success" value="Calculate">
                </form>
                
                <form method="POST" id="hiyerarsikForm"  action="clustering/hiyerarsik.php" style="display:none">
                    <input type="hidden" name="method" value="bolunmeli">
                    <div class="form-group">
                        <label class="control-label">X Column</label>
                        <select class="form-control" name="x_column_name">
                            
                        </select>
                    </div>
                    <input type="submit" class="btn btn-success" value="Calculate">
                </form>
                
                <form method="POST" id="yogunlukForm"  action="clustering/yogunluk.php" style="display:none">
                    <input type="hidden" name="method" value="yogunluk">
                    <div class="form-group">
                        <label class="control-label">yogunluk Column</label>
                        <select class="form-control" name="x_column_name">
                            
                        </select>
                    </div>
                    <input type="submit" class="btn btn-success" value="Calculate">
                </form>
            </div>
    </body>
    <script>
        //metod secildiğinde çağrılan fonksiyondur.
        function metodDegisim(){
            var e = document.getElementById("method");
            var method = e.options[e.selectedIndex].value;

            console.log(method)

            document.getElementById("bolunmeliForm").style.display = "none";
            document.getElementById("hiyerarsikForm").style.display = "none";
            document.getElementById("yogunlukForm").style.display = "none";

            if(method === "bolunmeli"){
                document.getElementById("bolunmeliForm").style.display = "block";
            }else  if(method === "hiyerarsik"){
                document.getElementById("hiyerarsikForm").style.display = "block";
            }else if(method === "yogunluk"){
                document.getElementById("yogunlukForm").style.display = "block";
            }
        }
    </script>
<html>