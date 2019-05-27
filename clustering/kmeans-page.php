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
                    <li class="breadcrumb-item">K-Means</li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?php echo $OKUNACAK_DOSYA_ADI;?>
                    </li>
                </ol>
            </nav>
            
            <h6>Parametreler</h6>
            <div class="alert alert-info" role="alert">
                <?php  
                    $clusterCount = $_GET["cluster_count"];
                    $xKolonAdi = $_GET["x_column_name"];
                    $yKolonAdi = $_GET["y_column_name"];

                    echo "X Column Name : <b>".$xKolonAdi."</b><br>";
                    echo "Y Column Name : <b>".$yKolonAdi."</b><br>";
                    echo "Cluster Count : <b>".$clusterCount."</b><br>"; 

                    //$dogrulukOrani = $knnAlgoritmaNesnesi->DogrulukHesapla();
                ?>
            </div>
            <?php 
             

                require_once('../algorithms/K-MEANS.php');

                //KMEANS sınıfının nesnesi oluşturulur
                $kMeanalgoritmaNesnesi = new KMEANSAlg($OKUNACAK_DOSYA_ADI);
                $kMeanalgoritmaNesnesi->ParametreleriAyarla($clusterCount, $xKolonAdi, $yKolonAdi);

                $result = $kMeanalgoritmaNesnesi->result;

                // $samples = [[1, 1], [8, 7], [1, 2], [7, 8], [2, 1], [8, 9]];
                //$samples = dosyaSampleOku($OKUNACAK_DOSYA_ADI, $xKolonAdi, $yKolonAdi);
                
                // $kmeans = new KMeans($clusterCount);
                // $result = $kmeans->cluster($samples);
            ?>
            <div class="row">
                <div class="col-md-5" style="border-right: solid 1px grey;">
                    <?php 
                        $series = [];
                        for ($i=0; $i < count($result); $i++) { 
                            $cluster = $result[$i];

                            $cluesterElemanSayisi = count($cluster);
                            $clusterNo = $i + 1;
                            echo "<h5>Cluster $clusterNo - ($cluesterElemanSayisi)</h4>";
                            echo clusterTabloYazdir($cluster);
                            
                            $clusterArray = [];
                            foreach ($cluster as $key => $value) {
                                array_push($clusterArray,  [ floatval($value[0]), floatval($value[1]) ]);
                            }

                            $series[$i] = new stdClass();;
                            $series[$i]->name = "Cluster ".$i;
                            $series[$i]->data =  $clusterArray;                        
                        }
                    
                        $seriesJSON = json_encode($series);
                        echo "<script> var series = $seriesJSON;  </script>";
                    ?>
                </div>
                <div class="col-md-7">
                    <label>Graph</label>
                    <div id="chart">
                    </div>
                </div>
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
            // series: [{
            //     name: "SAMPLE A",
            //     data: [ [16.4, 5.4], [21.7, 2], [25.4, 3], [19, 2], [10.9, 1], [13.6, 3.2], [10.9, 7.4], [10.9, 0], [10.9, 8.2], [16.4, 0], [16.4, 1.8], [13.6, 0.3], [13.6, 0], [29.9, 0], [27.1, 2.3], [16.4, 0], [13.6, 3.7], [10.9, 5.2], [16.4, 6.5], [10.9, 0], [24.5, 7.1], [10.9, 0], [8.1, 4.7], [19, 0], [21.7, 1.8], [27.1, 0], [24.5, 0], [27.1, 0], [29.9, 1.5], [27.1, 0.8], [22.1, 2]]
            // },{
            //     name: "SAMPLE B",
            //     data: [ [36.4, 13.4], [1.7, 11], [5.4, 8], [9, 17], [1.9, 4], [3.6, 12.2], [1.9, 14.4], [1.9, 9], [1.9, 13.2], [1.4, 7], [6.4, 8.8], [3.6, 4.3], [1.6, 10], [9.9, 2], [7.1, 15], [1.4, 0], [3.6, 13.7], [1.9, 15.2], [6.4, 16.5], [0.9, 10], [4.5, 17.1], [10.9, 10], [0.1, 14.7], [9, 10], [12.7, 11.8], [2.1, 10], [2.5, 10], [27.1, 10], [2.9, 11.5], [7.1, 10.8], [2.1, 12]]
            // },{
            //     name: "SAMPLE C",
            //     data: [ [21.7, 3], [23.6, 3.5], [24.6, 3], [29.9, 3], [21.7, 20], [23, 2], [10.9, 3], [28, 4], [27.1, 0.3], [16.4, 4], [13.6, 0], [19, 5], [22.4, 3], [24.5, 3], [32.6, 3], [27.1, 4], [29.6, 6], [31.6, 8], [21.6, 5], [20.9, 4], [22.4, 0], [32.6, 10.3], [29.7, 20.8], [24.5, 0.8], [21.4, 0], [21.7, 6.9], [28.6, 7.7], [15.4, 0], [18.1, 0], [33.4, 0], [16.4, 0]]
            // }]
        }
        var chart = new ApexCharts(
            document.querySelector("#chart"),
            options
        );
        chart.render();
    </script>
<html>