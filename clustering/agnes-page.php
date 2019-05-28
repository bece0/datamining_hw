<!-- 151213059 BEGÜM ÇELEBİ -->

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Datamining - AGNES</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" ></script>

        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" ></script>
        <style>
        .container2{
            margin-left: 50px;
            margin-right: 50px;
        }
        #chart {
            max-width: 650px;
            margin: 5px auto;
        }
        </style>
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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

            //KNN algoritmasını  kullanabilmek için KNN sınıfına erişim için bu eklenir.

            function clusterTabloYazdir($cluster ){
                echo "<table class='table table-sm'>";
                foreach ($cluster as $key => $value) {
                    echo "<tr>";
                    echo "<td>$key</td>";
                    echo "<td>[ $value[0] , $value[1] ]</td>";
                    // echo "<p> $key - [ $value[0], $value[1] ] </p>";
                    // array_push($clusterArray,  $value);
                    echo "</tr>";
                }
                echo "</table>";
            }
        ?>

        <div class="container2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../index.php">Datasets</a></li>
                    <li class="breadcrumb-item"><a href="../analiz_clustering.php?name=<?php echo $OKUNACAK_DOSYA_ADI;?>">Clustering</a></li>
                    <li class="breadcrumb-item">AGNES</li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?php echo $OKUNACAK_DOSYA_ADI;?>
                    </li>
                </ol>
            </nav>
            
            <h6>Parametreler</h6>
            <div class="alert alert-info" role="alert">
                <?php  
                    $cluster_count = $_GET["cluster_count"];
                    $xKolonAdi = $_GET["x_column_name"];
                    $yKolonAdi = $_GET["y_column_name"];

                    echo "X Column Name : <b>".$xKolonAdi."</b><br>";
                    echo "Y Column Name : <b>".$yKolonAdi."</b><br>";
                    echo "Cluster Count : <b>".$cluster_count."</b><br>"; 
                ?>
            </div>
            <div>
                <?php 
                    require_once('../algorithms/AGNES.php');

                    //DBSCAN sınıfının nesnesi oluşturulur
                    $agnesAlgoritmaNesnesi = new AGNES($OKUNACAK_DOSYA_ADI);
                    $hata = $agnesAlgoritmaNesnesi->ParametreleriAyarla($xKolonAdi, $yKolonAdi);

                    $result = $agnesAlgoritmaNesnesi->Hesapla($cluster_count);

                    print("<pre>".print_r($result,true)."</pre>");
                ?>
            </div>

            <div class="row">
                <?php 
                    if($result == NULL || count($result) == 0) {
                ?>
                    <div class="col-md-12">
                        <p class="alert alert-warning">Verilen parametreler göre sınıflandırma yapılamadı! 
                        Lütfen <a  href="../analiz_clustering.php?name=<?php echo $OKUNACAK_DOSYA_ADI;?>#yogunluk">tekrar deneyiniz</a></p>
                    </div>
                <?php } else { ?>
                    <div class="col-md-5" style="border-right: solid 1px grey;">
                        <?php 
                            $series = [];

                            // for ($i=0; $i < count($result); $i++) { 
                            //     $cluster = $result[$i];

                            //     $cluesterElemanSayisi = count($cluster);
                            //     $clusterNo = $i + 1;
                            //     echo "<h5>Cluster $clusterNo - ($cluesterElemanSayisi)</h4>";
                            //     echo clusterTabloYazdir($cluster);
                                
                            //     $clusterArray = [];
                            //     foreach ($cluster as $key => $value) {
                            //         array_push($clusterArray,  [ floatval($value[0]), floatval($value[1]) ]);
                            //     }

                            //     $series[$i] = new stdClass();;
                            //     $series[$i]->name = "Cluster ".$i;
                            //     $series[$i]->data =  $clusterArray;                        
                            // }
                        
                            $seriesJSON = json_encode($series);
                            echo "<script> var series = $seriesJSON;  </script>";
                        ?>
                    </div>
                    <div class="col-md-7">
                        <div id="chart" style="display:none">
                        </div>
                    </div>
                <?php } ?>
            </div>
    </body>
    <script>
        //https://apexcharts.com/docs/
        var options = {
            chart: {
                height: 350,
                type: 'scatter',
                toolbar : {
                    show : true,
                    tools: {
                        download: true,
                        selection: true,
                        zoom: true,
                        zoomin: true,
                        zoomout: true,
                        pan: true
                    }
                }
            },
            series: series
        }
        var chart = new ApexCharts(
            document.querySelector("#chart"),
            options
        );
        chart.render();
    </script>
<html>
