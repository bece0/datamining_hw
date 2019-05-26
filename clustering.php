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
        ?>

        <div class="container2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Datasets</a></li>
                    <li class="breadcrumb-item">Clustering</li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?php echo $OKUNACAK_DOSYA_ADI ; ?>
                    </li>
                </ol>
            </nav>

                    <h4>Sınıflandırma için çalışma parametrelerini belirtiniz.</h4>
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <form method="POST">
                            
                            <div class="form-group">
                                <label class="control-label">Method</label>
                                <select class="form-control" name="method">
                                    <option value="1">Bölünmeli yöntem</option>
                                    <option value="2">Hiyerarşik yöntem</option>
                                    <option value="2">Yoğunluk Tabanlı yöntem</option>
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

                                <input type="submit" class="btn btn-success" value="Calculate">
                       
                        </form>
                    </div>

    </body>
<html>