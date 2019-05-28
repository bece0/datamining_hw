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

            .desc-label{
                margin-bottom: 3px;
                font-family: serif;
            }

            form{
                border: solid 1px #c09b9b;
                padding: 10px;
                border-radius: 10px;
            }

            label {
                margin-bottom: 0.2rem;
                line-height: 0.5;
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
                        <option value="hiyerarsik">Hiyerarşik yöntem - AGNES </option>
                        <option value="yogunluk">Yoğunluk Tabanlı yöntem - DBSCAN</option>
                    </select>
                </div>
                
                <form method="GET" id="bolunmeliForm" action="clustering/kmeans-page.php" >
                    <input type="hidden" name="name" value="<?php echo $OKUNACAK_DOSYA_ADI;?>">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="control-label">X Column</label>
                            <select class="form-control" name="x_column_name">
                                <?php echo $sayisalKolonlarSelect;?>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">Y Column</label>
                            <select class="form-control" name="y_column_name">
                                <?php echo $sayisalKolonlarSelect;?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Cluster Count</label>
                        <p class="control-label desc-label">Number of clusters to find</p>
                        <input class="form-control" name="cluster_count" value="3"/>
                    </div>
                    <input type="submit" class="btn btn-success" value="Calculate">
                </form>
                
                <form method="GET" id="hiyerarsikForm"  action="clustering/hiyerarsik-page.php" style="display:none">
                    <!-- <p class="alert alert-danger">//TODO</p> -->
                    <input type="hidden" name="name" value="<?php echo $OKUNACAK_DOSYA_ADI;?>">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="control-label">X Column</label>
                            <select class="form-control" name="x_column_name">
                                <?php echo $sayisalKolonlarSelect;?>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">Y Column</label>
                            <select class="form-control" name="y_column_name">
                                <?php echo $sayisalKolonlarSelect;?>
                            </select>
                        </div>
                    </div>
                    <input type="submit" class="btn btn-success" value="Calculate" >
                </form>
                
                <form method="GET" id="yogunlukForm"  action="clustering/dbscan-page.php" style="display:none">
                    <input type="hidden" name="name" value="<?php echo $OKUNACAK_DOSYA_ADI;?>">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="control-label">X Column</label>
                            <select class="form-control" name="x_column_name">
                                <?php echo $sayisalKolonlarSelect;?>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">Y Column</label>
                            <select class="form-control" name="y_column_name">
                                <?php echo $sayisalKolonlarSelect;?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Minimum Samples</label>
                        <p class="control-label desc-label">Number of samples in a neighborhood for a point to be considered as a core point (this includes the point itself)</p>
                        <input class="form-control" name="minsamples" value="2"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Epsilon</label>
                        <p class="control-label desc-label">Maximum distance between two samples for them to be considered as in the same neighborhood</p>
                        <input class="form-control" name="epsilon" value="2" />
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

        function getHashValue(key) {
            var matches = location.hash.match(new RegExp(key+'=([^&]*)'));
            return matches ? matches[1] : null;
        }

        var form = location.hash;
        if(form){
            formName = form.substring(1);
            var selectElement = document.getElementById("method");
            selectElement.value = formName;
            metodDegisim();
        }


    </script>
<html>