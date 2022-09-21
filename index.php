<?php
interface Pozajmica {
    public function pozajmitiProizvod();
    public function vratitiProizvod();
}

class Prodavnica {
    private $proizvodi = [
        RAM::class => [],
        CPU::class => [],
        HDD::class => [],
        GPU::class => []
    ];
    private $zarada = 0;

    public function dodajProizvod(Proizvod $proizvod) {
        $proizvodClass = get_class($proizvod);
        $this->proizvodi[$proizvodClass][] = $proizvod;
    }

    public function prodaja(Proizvod $proizvod) {
        $proizvodClass = get_class($proizvod);
        if ($proizvod->getBrojNaLageru() === 0) {
            echo 'Proizvoda nema na lageru';
            return;
        }
        $this->zarada += $proizvod->getCena();
        $brojProizvoda = $proizvod->getBrojNaLageru();
        $proizvod->setBrojaNaLageru($brojProizvoda - 1);
        array_pop($this->proizvodi[$proizvodClass]);
    }

    public function pozajmiti(Proizvod $proizvod) {
        if (!($proizvod instanceof Pozajmica)) {
            echo 'Ovaj proizvod se ne moze vratiti <br>';
            return;
        }
        $proizvod->pozajmitiProizvod();
        $this->zarada += ($proizvod->getCena() * 0.25);
        $proizvodClass = get_class($proizvod);
        array_pop($this->proizvodi[$proizvodClass]);

    }

    public function vratiti(Proizvod $proizvod) {
        if (!($proizvod instanceof Pozajmica)) {
            return;
        }
        $proizvod->vratitiProizvod();
        $proizvodClass = get_class($proizvod);
        $this->proizvodi[$proizvodClass][] = $proizvod;
    }

}

abstract class Proizvod {
    protected $seriskiBroj;
    protected $proizvodjac;
    protected $model;
    protected $cena;


    public function __construct(
        $seriskiBroj,
        $proizvodjac,
        $model,
        $cena
    ) {
        $this->seriskiBroj = $seriskiBroj;
        $this->proizvodjac = $proizvodjac;
        $this->model = $model;
        $this->cena = $cena;
    }

    public abstract function getBrojNaLageru();
    public abstract function setBrojaNaLageru($brojProizvoda);

    public function getCena() {
        return $this->cena;
    }
}

class RAM extends Proizvod implements Pozajmica{
    public $kapacitet;
    public $frekvencija;
    public static $brojNaLageru = 0;


    public function __construct(
        $seriskiBroj,
        $proizvodjac,
        $model,
        $cena,
        $kapacitet,
        $frekvencija
    ) {
        parent::__construct($seriskiBroj, $proizvodjac, $model, $cena);
        self::$brojNaLageru ++;
        $this->kapacitet = $kapacitet;
        $this->frekvencija = $frekvencija;
    }

    public function getBrojNaLageru() {
        return self::$brojNaLageru;
    }

    public function setBrojaNaLageru($brojProizvoda) {
        self::$brojNaLageru = $brojProizvoda;
    }

    public function pozajmitiProizvod() {
        self::$brojNaLageru -= 1;
    }

    public function vratitiProizvod(){
        self::$brojNaLageru += 1;
    }
}

class CPU extends Proizvod implements Pozajmica {
    private $brojJezgra;
    private $frekvencija;
    public static $brojNaLageru = 0;

    public function __construct(
        $seriskiBroj,
        $proizvodjac,
        $model,
        $cena,
        $brojJezgra,
        $frekvencija
    ) {
        parent::__construct($seriskiBroj, $proizvodjac, $model, $cena);
        self::$brojNaLageru ++;
        $this->brojJezgra = $brojJezgra;
        $this->frekvencija = $frekvencija;
    }

    public function getBrojNaLageru() {
        return self::$brojNaLageru;
    }

    public function setBrojaNaLageru($brojProizvoda) {
        self::$brojNaLageru = $brojProizvoda;
    }

    public function pozajmitiProizvod() {
        self::$brojNaLageru -= 1;
    }

    public function vratitiProizvod(){
        self::$brojNaLageru += 1;
    }
}

class HDD extends Proizvod implements Pozajmica {
    private $kapacitet;
    public static $brojNaLageru = 0;

    public function __construct(
        $seriskiBroj,
        $proizvodjac,
        $model,
        $cena,
        $kapacitet
    ) {
        parent::__construct($seriskiBroj, $proizvodjac, $model, $cena);
        self::$brojNaLageru ++;
        $this->kapacitet = $kapacitet;
    }

    public function getBrojNaLageru() {
        return self::$brojNaLageru;
    }

    public function setBrojaNaLageru($brojProizvoda) {
        self::$brojNaLageru = $brojProizvoda;
    }

    public function pozajmitiProizvod() {
        self::$brojNaLageru -= 1;
    }

    public function vratitiProizvod(){
        self::$brojNaLageru += 1;
    }
}

class GPU extends Proizvod implements Pozajmica {
    private $frekvencija;
    public static $brojNaLageru = 0;

    public function __construct(
        $seriskiBroj,
        $proizvodjac,
        $model,
        $cena,
        $frekvencija
    ) {
        parent::__construct($seriskiBroj, $proizvodjac, $model, $cena);
        self::$brojNaLageru ++;
        $this->frekvencija = $frekvencija;
    }

    public function getBrojNaLageru() {
        return self::$brojNaLageru;
    }

    public function setBrojaNaLageru($brojProizvoda) {
        self::$brojNaLageru = $brojProizvoda;
    }

    public function pozajmitiProizvod() {
        self::$brojNaLageru -= 1;
    }

    public function vratitiProizvod(){
        self::$brojNaLageru += 1;
    }
}

$prodavnica = new Prodavnica();

$ram = new Ram('123', 'Kingston', 'AAAA', 250, '4GB', '512');
$cpu = new Cpu('258', 'Intel', 'BBB', 300, 25, '58');
$gpu = new GPU('125', 'Nvidia', 'CCC', 500, '512');

$prodavnica->dodajProizvod($ram);
$prodavnica->dodajProizvod($cpu);
$prodavnica->dodajProizvod($gpu);
var_dump($prodavnica);


$prodavnica->prodaja($ram);
$prodavnica->prodaja($cpu);
var_dump($prodavnica);

$prodavnica->pozajmiti($gpu);
$prodavnica->vratiti($gpu);
var_dump($prodavnica);


echo $ram::$brojNaLageru ;
echo $ram::$brojNaLageru ;
echo $gpu::$brojNaLageru;
?>