<?php 


class KNN{
    
    private $dosyaAdi;
    private $k = 3;
    private $method = 1;
    private $xColumn = "";
    private $yColumn = "";
    private $labelColumn = "";
    public $norm = 2.0;

    /**
     * koordinat ikililerinini tutar
     */
    private $samples = [];

    /**
     * Koordinat ikililerine karşılık gelen label değerlerini tutar
     */
    private $targets = [];

    /**
     * Koordinat ikililerine karşılık gelen label değerlerini tutar
     */
    private $labels = [];

    private $DosyaData = [];

    public $SayisalKolonlar = [];
    public $SozelKolonlar = [];

    /**
     * KNN sınıfı yapıcı metodu.
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

                    // $uzunluk = count($satir);
                    // for ($k=0; $k < $uzunluk; $k++) { 
                    //     $deger = $satir[$k];
                    //     if(is_numeric($deger)){
                    //         $satir[$k] = intval($deger);
                    //     }
                    // }

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
     * @param int K kaysayısı
     * @param string Kullanılacak olan metodu belirler, 1 ise  "En Çok Tekrarlanan Sınıf", 2 ise "Ağırlıklı Oylama"
     * @param string x değerlerinin bulunduğu sayısal kolon adı
     * @param string y değerlerinin bulunduğu sayısal kolon adı
     * @param string label değerlerinin bulunduğu sözel kolon adı
     * @return string Hata oluşursa hata mesajını döner, aksi halde "" döner
     */
    public function ParametreleriAyarla(int $k, string $method, string $xKolon, string $yKolon, string $labelColumn) : string
    {
        $this->k = $k;
        $this->method = $method;
        $this->xColumn = $xKolon;
        $this->yColumn = $yKolon;
        $this->labelColumn = $labelColumn;

        if($xKolon == $yKolon){
            return "X kolonu ve Y kolonu aynı olmaz!";
        }

        if($method != "1" && $method != "2"){
            return "Method parametresi 1 ya da 2 olabilir!";
        }

        return "";
    }

    /**
     * Datanın içinde x, y ikililerini $samples dizinde toplar ve label değerlerini de $labels dizinde toplar
     * İşlem sonucu bu diziler aşağıdaki gibi bir yapıda olur.
     *  $samples = [[1, 3], [1, 4], [2, 4], [3, 1], [4, 1], [4, 2]];
     *  $labels = ['a', 'a', 'a', 'b', 'b', 'b'];
     *
     */
    private function SamplesVeLabelsAyarlar() : void{

        $satir_sayisi = count($this->DosyaData);

        $xKolonNumarasi = $this->SayisalKolonlar[$this->xColumn];
        $yKolonNumarasi = $this->SayisalKolonlar[$this->yColumn];
        $LabelKolonNumarasi = $this->SozelKolonlar[$this->labelColumn];

        for ($i=0; $i < $satir_sayisi ; $i++) { 
            //echo "i = ".$i."</br>";

            $satir = $this->DosyaData[$i];

            $x = $satir[$xKolonNumarasi];
            $y = $satir[$yKolonNumarasi];
            $label = $satir[$LabelKolonNumarasi];
            $target = $satir[$LabelKolonNumarasi];

            array_push($this->samples, [$x, $y]);
            array_push($this->labels, $label);
            array_push($this->targets, $target);
        }
    }


    /**
     * 
     * @return string Hata oluşursa hata mesajını döner, aksi halde "" döner
     */
    public function TahminEt(int $xKolonDegeri, int $yKolonDegeri) : array
    {
        //echo "tahmin et<br/>";
        // $samples = [[1, 3], [1, 4], [2, 4], [3, 1], [4, 1], [4, 2]];
        // $labels = ['a', 'a', 'a', 'b', 'b', 'b'];
        $predictData = [[$xKolonDegeri, $yKolonDegeri]];

        $this->SamplesVeLabelsAyarlar();
        //echo "SamplesVeLabelsAyarlar bitti<br/>";

        $soruXY = [[$xKolonDegeri, $yKolonDegeri]] ;
        if (!is_array($soruXY[0])) {
            //echo "predictSample tekli<br/>";
            return $this->predictSample($soruXY);
        }

        $predicted = [];
        foreach ($soruXY as $index => $sample) {
            $predicted[$index] = $this->predictSample($sample);
        }
        //echo "<br>";

        return $predicted;

        // $classifier->predict([3, 2]);
        // return 'b'

        // $classifier->predict([[3, 2], [1, 5]]);
        // return ['b', 'a']
    }

    private function predictSample(array $sample)
    {
        //echo "<br/>predictSample icinde<br/>";
        //var_dump($sample);
        //echo "<br/>";

        //öklid uzaklıklarının sırlanmış halinin ilk k tanesi
        $distances = $this->kNeighborsDistances($sample);
        //[ 1 = 25, 5 = 27, 9 = 35 ]

        //echo "<br/>distances <br/>";
        //var_dump($distances);
        //echo "<br/>";

        $predictions = (array) array_combine(array_values($this->targets), array_fill(0, count($this->targets), 0));
        // ["evet" = 2, hayır = 1]

        if($this->method == "1"){
            foreach (array_keys($distances) as $index) {
                ++$predictions[$this->targets[$index]];
            }
            arsort($predictions);
            reset($predictions);
            return key($predictions);
        }else{
            foreach (array_keys($distances) as $index) {
                $uzaklik_karesi = $distances[$index] *  $distances[$index];
                $predictions[$this->targets[$index]] = $predictions[$this->targets[$index]] + 1/$uzaklik_karesi;
            }
            arsort($predictions);
            reset($predictions);
            return key($predictions);
        }

    }

    private function kNeighborsDistances(array $sample): array
    {
        $distances = [];

        foreach ($this->samples as $index => $neighbor) {
            //echo "kNeighborsDistances-foreach ".$index." <br>";
            $distances[$index] = $this->distance($sample, $neighbor);
            //echo "distances[$index] : ".$distances[$index]." <br>";
        }

        asort($distances);

        return array_slice($distances, 0, $this->k, true);
    }

    public function distance(array $a, array $b): float
    {
        $distance = 0;

        foreach ($this->deltas($a, $b) as $delta) {
            $distance += $delta ** $this->norm;
        }

        return $distance ** (1 / $this->norm);
    }

    private function deltas(array $a, array $b): array
    {
        $count = count($a);

        if ($count !== count($b)) {
            throw new InvalidArgumentException('Size of given arrays does not match');
        }

        $deltas = [];

        for ($i = 0; $i < $count; $i++) {
            $deltas[] = abs($a[$i] - $b[$i]);
        }

        return $deltas;
    }

    
    /**
     * @param int x kolon degeri
     * @param int y kolon degeri
     * @return string tahmin sonucu degerini doner
     */
    private function estimate(int $xKolonDegeri, int $yKolonDegeri)
    {

    }

}

?>