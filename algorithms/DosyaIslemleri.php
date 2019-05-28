<?php 

class DosyaIslemleri{
    
    public $dosyaAdi;
    public $xColumn = "";
    public $yColumn = "";

    /**
     * koordinat ikililerinini tutar
     */
    public $samples = [];
    public $uzaklikMatrix = [];

    public $result = [];

    public $DosyaData = [];

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

    public function ParametreleriAyarla(string $xKolon, string $yKolon) : string
    {
        $this->xColumn = $xKolon;
        $this->yColumn = $yKolon;

        if($xKolon == $yKolon){
            return "X kolonu ve Y kolonu aynı olmaz!";
        }
        return "";
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
     *  $samples = [[1, 3], [1, 4], [2, 4], [3, 1], [4, 1], [4, 2]];
     *  $labels = ['a', 'a', 'a', 'b', 'b', 'b'];
     *
     */
    public function SamplesAyarla() : void{

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

    public function UzaklıkMatrixHesapla(){
        if($this->samples ==NULL || count($this->samples) <= 1)
            return;

        
        $uzunluk = count($this->samples);
        
        $uzaklikMatrix = [];
        for ($i=0; $i < $uzunluk; $i++) { 
            $uzaklikMatrix[$i] = [];
            for ($j=0; $j < $uzunluk; $j++) { 
                $uzaklikMatrix[$i][$j] = 0;
            }
        }

        for ($i=0; $i < $uzunluk; $i++) { 
            for ($j=0; $j < $uzunluk; $j++) { 
                 if($i == $j)
                    continue;
                
                $uzaklikMatrix[$i][$j] = $this->distance($this->samples[$i], $this->samples[$j]);
            }
        }

        $this->uzaklikMatrix = $uzaklikMatrix;

        return $this->uzaklikMatrix;
    }

    // private function sqDistance(array $a, array $b): float
    // {
    //     return $this->distance($a, $b);
    // }

    public function distance(array $a, array $b): float
    {
        $distance = 0;

        $x1 = $a[0];
        $y1 = $a[1];

        $x2 = $b[0];
        $y2 = $b[1];
 
        $karelerToplami = (abs($x1 - $x2) ** 2 ) + (abs($y1 - $y2) ** 2);

        return $karelerToplami ** (1 / 2);
    }

    // protected function deltas(array $a, array $b): array
    // {
    //     $count = count($a);

    //     if ($count !== count($b)) {
    //         throw new InvalidArgumentException('Size of given arrays does not match');
    //     }

    //     $deltas = [];

    //     for ($i = 0; $i < $count; $i++) {
    //         $deltas[] = abs($a[$i] - $b[$i]);
    //     }

    //     return $deltas;
    // }
}

?>
