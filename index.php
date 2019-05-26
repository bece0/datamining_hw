<!-- 151213059 BEGÜM ÇELEBİ -->


<!DOCTYPE html>
<html>
    <head>
    <meta charset="UTF-8">
        <title>Datamining - Odev 0</title>
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
        </style>

    </head>
    <body>
    <?php
    function DosyalariGetir(){
        $dosyalar = array();
        //data klasöründeki dosyaların isimlerini tara...
        $dosyalar = scandir( __DIR__ ."/data/");  //dosyaların listesini array olarak döner.
      //  var_dump($dosyalar);
        $dosyalar = array_splice($dosyalar, 2); //ilk iki eleman hariç elemanları array olarak döner
      //  var_dump($dosyalar); 
        return $dosyalar;
    }

    //Dosya boyutunu KB olarak döner
    function DosyaBoyutuHesapla($dosya_adi){
        $BYTE = filesize(__DIR__ ."/data/".$dosya_adi);
        return  ceil($BYTE / 1024);
    }
    ?>
    <div class="container">
            <!-- <div class="header">
                <h3>Uploaded files</h3>
            </div> -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Datasets</li>
                </ol>
            </nav>
            <div class="card">
                <div class="card-body">
                    <div>
                        <h3>Upload new csv file</h3>
                     </div>
                    <form method="POST" action="upload_action.php" enctype="multipart/form-data">
                        <input type="file" name="file" id="file" accept=".csv">
                        <input type="submit" name="file" id="file">
                    </form>
                </div>
            </div>
            <div class="">
                <table class="table tbl-bordered">
                    <?php
                        $dosyalar = DosyalariGetir();

                        $dosyalar_sayisi = count($dosyalar);
                        for ($i=0; $i < $dosyalar_sayisi ; $i++) { 
                            echo "<tr>";
                                echo "<td>".($i + 1)."</td>";
                                echo "<td>". $dosyalar[$i]."</td>";
                                echo "<td>". DosyaBoyutuHesapla($dosyalar[$i])." KB </td>";
                                echo "<td><a href='dosya.php?name=". $dosyalar[$i]."' title='View dataset'>View</a></td>";
                                echo "<td><a href='analiz_dt.php?name=". $dosyalar[$i]."'>Decision Tree</a></td>";
                                echo "<td><a href='analiz_knn.php?name=". $dosyalar[$i]."'>kNN</a></td>";
                                echo "<td><a href='clustering.php?name=". $dosyalar[$i]."'>Clustering</a></td>";
                                //dosya linki oluşturuldu
                            echo "</tr>";
                        }
                    ?>
                </table>
            </div>
        </div>
    </body>
<html>