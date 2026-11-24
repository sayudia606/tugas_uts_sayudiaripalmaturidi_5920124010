<?php

// 1. Kelas Induk (Parent Class)
class Kendaraan {
    public $merk;
    public $warna;

    public function __construct($merk, $warna) {
        $this->merk = $merk;
        $this->warna = $warna;
    }

    public function info() {
        return "Kendaraan: {$this->merk} ({$this->warna})";
    }
}

// 2. Kelas Anak Pertama 
class Motor extends Kendaraan {
    public $kapasitasMesin;

    public function __construct($merk, $warna, $kapasitasMesin) {
        parent::__construct($merk, $warna);
        $this->kapasitasMesin = $kapasitasMesin;
    }

    public function infoMotor() {
        return $this->info() . " - Mesin {$this->kapasitasMesin}cc";
    }
}

// 3. Kelas Anak Kedua 
class Mobil extends Kendaraan {
    public $jumlahPintu;

    public function __construct($merk, $warna, $jumlahPintu) {
        parent::__construct($merk, $warna);
        $this->jumlahPintu = $jumlahPintu;
    }

    public function infoMobil() {
        return $this->info() . " - {$this->jumlahPintu} Pintu";
    }
}

// Membuat objek dari kedua kelas anak
$motor1 = new Motor("Yamaha Jupiter Z", "Hitam", 110);
$mobil1 = new Mobil("Toyota Avanza", "Silver", 4);

echo $motor1->infoMotor() . "<br>";
echo $mobil1->infoMobil();
?>