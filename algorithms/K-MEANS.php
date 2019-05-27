<!-- 151213059 BEGÜM ÇELEBİ -->

<?php 

require_once __DIR__ . '/../vendor/autoload.php';
use Phpml\Clustering\KMeans;

class KMEANSAlg{
    
    private $dosyaAdi;
    private $k = 3;
    private $xColumn = "";
    private $yColumn = "";

    /**
     * koordinat ikililerinini tutar
     */
    private $samples = [];

    public $result = [];

    private $DosyaData = [];

    public $SayisalKolonlar = [];
    public $SozelKolonlar = [];

    /**
     * KMEANS sınıfı yapıcı metodu.
     * @param string verilerin okunacağı dosyanın adu.
     */
    public function __construct(string $dosyaAdi){
        $this->dosyaAdi = $dosyaAdi;

        $this->DosyaOku();
    }

    private function DosyaOku(){
        $tablo = array();
        $dosya_yolu = __DIR__ . "/../data/" . $this->dosyaAdi;
        $kolon_isimleri = [];

        if (($handle = fopen($dosya_yolu , "r")) !== FALSE) {
            $satir_sayisi = 0;
            while (($satir = fgetcsv($handle, 10000, ",")) !== FALSE) {
                if($satir != NULL && $satir[0] != NULL){
                    if($satir_sayisi == 0){
                        $kolon_isimleri = $satir;
                        $satir_sayisi++;
                        continue;
                    }

                    if($satir_sayisi == 1){
                        $uzunluk = count($satir);
                        for ($k=0; $k < $uzunluk; $k++) { 
    
                            $deger = $satir[$k];
                            if(is_numeric($deger)){
                                $this->SayisalKolonlar[$kolon_isimleri[$k]] = $k;
                            }else{
                                $index = $kolon_isimleri[$k];
                                $this->SozelKolonlar[$index] = $k;
                            }
                        }
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
        
        $this->DosyaData = $tablo;
        // var_dump( $this->DosyaData);
    }

    /**
     *
     */
    public function ParametreleriAyarla(int $clusterCount,  string $xKolon, string $yKolon) : string
    {
        $this->clusterCount = $clusterCount;
        $this->xColumn = $xKolon;
        $this->yColumn = $yKolon;

        if($xKolon == $yKolon){
            return "X kolonu ve Y kolonu aynı olmaz!";
        }

        $this->SamplesAyarla();

        $kmeans = new KMeans($clusterCount);
        
        $this->result = $kmeans->cluster($this->samples);

        //$this->result = [[1, 1], [8, 7], [1, 2], [7, 8], [2, 1], [8, 9]];

        return "";
    }

    /**
     *  $samples = [[1, 3], [1, 4], [2, 4], [3, 1], [4, 1], [4, 2]];
     *  $labels = ['a', 'a', 'a', 'b', 'b', 'b'];
     *
     */
    private function SamplesAyarla() : void{

        $satir_sayisi = count($this->DosyaData);

        $xKolonNumarasi = $this->SayisalKolonlar[$this->xColumn];
        $yKolonNumarasi = $this->SayisalKolonlar[$this->yColumn];

        $this->samples = [];

        for ($i=0; $i < $satir_sayisi ; $i++) { 
            //echo "i = ".$i."</br>";
            $satir = $this->DosyaData[$i];

            $x = $satir[$xKolonNumarasi];
            $y = $satir[$yKolonNumarasi];

            array_push($this->samples, [$x, $y]);
        }
    }

}

?>