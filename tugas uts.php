<?php

// =====================================
// CLASS INDUK: KENDARAAN
// =====================================
class Kendaraan {
    protected $nama;
    protected $jarak;

    public function __construct($nama, $jarak) {
        $this->nama = $nama;
        $this->jarak = $jarak;
    }

    public function getNama() {
        return $this->nama;
    }

    public function getJarak() {
        return $this->jarak;
    }
}

// =====================================
// CLASS ANAK 1: MOTOR
// =====================================
class Motor extends Kendaraan {

    public function hitungBiaya($tarifPerKm) {
        return $this->jarak * $tarifPerKm;
    }
}

// =====================================
// CLASS ANAK 2: MOBIL
// =====================================
class Mobil extends Kendaraan {

    public function hitungBiaya($tarifPerKm, $biayaTol = 0) {
        if ($tarifPerKm < 0 || $biayaTol < 0) {
            return false;
        }

        return ($this->jarak * $tarifPerKm) + $biayaTol;
    }
}

// =====================================
// DATA DEFAULT
// =====================================
$nama = "Sayudi";
$jarakTempuh = 50;
$tarifMotor = 1500;
$tarifMobil = 4000;
$biayaTol = 25000;
$error = "";

// =====================================
// MENGAMBIL DATA DARI FORM
// =====================================
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nama = trim($_POST["nama"]);
    $jarakTempuh = (float) $_POST["jarak"];
    $tarifMotor = (float) $_POST["tarif_motor"];
    $tarifMobil = (float) $_POST["tarif_mobil"];
    $biayaTol = (float) $_POST["biaya_tol"];

    if (
        $nama === "" ||
        $jarakTempuh <= 0 ||
        $tarifMotor < 0 ||
        $tarifMobil < 0 ||
        $biayaTol < 0
    ) {
        $error = "Nama tidak boleh kosong, jarak harus lebih dari 0 km, dan biaya tidak boleh negatif!";
    }
}

// =====================================
// PROSES PERHITUNGAN
// =====================================
$motor = new Motor($nama, $jarakTempuh);
$totalBiayaMotor = $motor->hitungBiaya($tarifMotor);

$mobil = new Mobil($nama, $jarakTempuh);
$totalBiayaMobil = $mobil->hitungBiaya($tarifMobil, $biayaTol);

$selisihBiaya = abs($totalBiayaMobil - $totalBiayaMotor);

// Data tambahan untuk visualisasi grafik batang CSS
$biayaTertinggi = max($totalBiayaMotor, $totalBiayaMobil, 1);
$persenMotor = round(($totalBiayaMotor / $biayaTertinggi) * 100);
$persenMobil = round(($totalBiayaMobil / $biayaTertinggi) * 100);
$opsiHemat = ($totalBiayaMotor <= $totalBiayaMobil) ? "Motor" : "Mobil";

?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Vehicle Cost Analytics</title>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
}

:root {
    --bg-main: #0b0f19;
    --bg-panel: #131a2a;
    --bg-card: #192236;
    --border-color: rgba(255, 255, 255, 0.08);
    --text-primary: #f8fafc;
    --text-secondary: #94a3b8;
    --accent-cyan: #06b6d4;
    --accent-emerald: #10b981;
    --accent-amber: #f59e0b;
    --accent-rose: #f43f5e;
}

body {
    background-color: var(--bg-main);
    background-image: 
        radial-gradient(at 0% 0%, rgba(6, 182, 212, 0.12) 0px, transparent 50%),
        radial-gradient(at 100% 100%, rgba(16, 185, 129, 0.08) 0px, transparent 50%);
    color: var(--text-primary);
    min-height: 100vh;
    padding: 28px;
}

.wrapper {
    max-width: 1220px;
    margin: 0 auto;
}

/* TOP BAR */
.topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 24px;
    margin-bottom: 28px;
    border-bottom: 1px solid var(--border-color);
    flex-wrap: wrap;
    gap: 15px;
}

.brand {
    display: flex;
    align-items: center;
    gap: 14px;
}

.brand-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--accent-cyan), #3b82f6);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 18px;
    color: #0b0f19;
    box-shadow: 0 0 20px rgba(6, 182, 212, 0.35);
}

.brand h1 {
    font-size: 22px;
    font-weight: 700;
    letter-spacing: -0.5px;
}

.brand span {
    font-size: 13px;
    color: var(--text-secondary);
}

.user-pill {
    background: var(--bg-panel);
    border: 1px solid var(--border-color);
    padding: 10px 18px;
    border-radius: 999px;
    font-size: 14px;
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    gap: 8px;
}

.user-pill strong {
    color: var(--text-primary);
}

.status-dot {
    width: 8px;
    height: 8px;
    background: var(--accent-emerald);
    border-radius: 50%;
    box-shadow: 0 0 8px var(--accent-emerald);
}

/* SPLIT GRID LAYOUT */
.dashboard-grid {
    display: grid;
    grid-template-columns: 380px 1fr;
    gap: 28px;
    align-items: start;
}

/* LEFT SIDEBAR: FORM */
.sidebar-panel {
    background: var(--bg-panel);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 26px;
    position: sticky;
    top: 28px;
}

.panel-title {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.panel-subtitle {
    font-size: 13px;
    color: var(--text-secondary);
    margin-bottom: 22px;
}

.form-group {
    margin-bottom: 16px;
}

.form-group label {
    display: block;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    color: var(--text-secondary);
    margin-bottom: 8px;
    font-weight: 600;
}

.input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.input-prefix, .input-suffix {
    position: absolute;
    font-size: 13px;
    color: var(--text-secondary);
    font-weight: 500;
    pointer-events: none;
}

.input-prefix { left: 14px; }
.input-suffix { right: 14px; }

.form-group input {
    width: 100%;
    background: var(--bg-main);
    border: 1px solid var(--border-color);
    color: var(--text-primary);
    padding: 12px 14px;
    border-radius: 12px;
    font-size: 14px;
    transition: all 0.2s ease;
    outline: none;
}

.form-group input.has-prefix { padding-left: 40px; }
.form-group input.has-suffix { padding-right: 45px; }

.form-group input:focus {
    border-color: var(--accent-cyan);
    box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.15);
}

.btn-submit {
    width: 100%;
    margin-top: 10px;
    padding: 14px;
    border: none;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--accent-cyan), #2563eb);
    color: white;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}

.btn-submit:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 20px rgba(37, 99, 235, 0.35);
}

.error-box {
    background: rgba(244, 63, 94, 0.12);
    border: 1px solid rgba(244, 63, 94, 0.35);
    color: #fda4af;
    padding: 14px;
    border-radius: 12px;
    font-size: 13px;
    margin-bottom: 18px;
    line-height: 1.5;
}

/* RIGHT MAIN CONTENT */
.main-content {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

/* HERO SAVINGS BANNER */
.highlight-banner {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(6, 182, 212, 0.12));
    border: 1px solid rgba(16, 185, 129, 0.3);
    border-radius: 20px;
    padding: 24px 28px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}

.highlight-banner .label {
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: var(--accent-emerald);
    font-weight: 700;
    margin-bottom: 6px;
}

.highlight-banner h2 {
    font-size: 28px;
    font-weight: 800;
    color: var(--text-primary);
}

.badge-hemat {
    background: rgba(16, 185, 129, 0.2);
    color: #6ee7b7;
    padding: 8px 16px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
}

/* VEHICLE COMPARISON CARDS */
.vehicle-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.v-card {
    background: var(--bg-panel);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 24px;
    position: relative;
    overflow: hidden;
}

.v-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
}

.v-card.motor::before { background: var(--accent-emerald); }
.v-card.mobil::before { background: var(--accent-amber); }

.v-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
}

.v-tag {
    font-size: 11px;
    padding: 4px 10px;
    border-radius: 6px;
    font-weight: 600;
    background: var(--bg-card);
    color: var(--text-secondary);
}

.v-title {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-secondary);
    margin-bottom: 8px;
}

.v-price {
    font-size: 26px;
    font-weight: 800;
    margin-bottom: 12px;
}

.motor .v-price { color: var(--accent-emerald); }
.mobil .v-price { color: var(--accent-amber); }

.v-meta {
    font-size: 13px;
    color: var(--text-secondary);
    padding-top: 12px;
    border-top: 1px solid var(--border-color);
}

/* VISUAL BAR CHART BOX */
.analytics-box {
    background: var(--bg-panel);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 24px;
}

.bar-group {
    margin-top: 18px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.bar-item-header {
    display: flex;
    justify-content: space-between;
    font-size: 13px;
    margin-bottom: 6px;
    color: var(--text-secondary);
}

.bar-track {
    width: 100%;
    height: 10px;
    background: var(--bg-main);
    border-radius: 999px;
    overflow: hidden;
}

.bar-fill {
    height: 100%;
    border-radius: 999px;
    transition: width 0.4s ease;
}

.bar-fill.motor { background: var(--accent-emerald); }
.bar-fill.mobil { background: var(--accent-amber); }

/* TABLE */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 14px;
}

th, td {
    padding: 14px 12px;
    text-align: left;
    border-bottom: 1px solid var(--border-color);
    font-size: 14px;
}

th {
    color: var(--text-secondary);
    font-weight: 600;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

td:last-child, th:last-child {
    text-align: right;
}

tr:last-child td {
    border-bottom: none;
}

.footer {
    text-align: center;
    color: var(--text-secondary);
    font-size: 13px;
    margin-top: 36px;
    opacity: 0.7;
}

@media (max-width: 900px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
    .sidebar-panel {
        position: static;
    }
}

@media (max-width: 560px) {
    body { padding: 16px; }
    .vehicle-grid { grid-template-columns: 1fr; }
}
</style>
</head>

<body>

<div class="wrapper">

    <!-- TOP NAVIGATION BAR -->
    <header class="topbar">
        <div class="brand">
            <div class="brand-icon">VC</div>
            <div>
                <h1>Vehicle Cost Analytics</h1>
                <span>Simulasi Perbandingan Biaya Operasional Kendaraan</span>
            </div>
        </div>

        <div class="user-pill">
            <span class="status-dot"></span>
            Pengendara: <strong><?= htmlspecialchars($motor->getNama()); ?></strong>
        </div>
    </header>

    <!-- MAIN SPLIT DASHBOARD -->
    <div class="dashboard-grid">

        <!-- PANEL KIRI: FORM INPUT -->
        <aside class="sidebar-panel">
            <div class="panel-title">Parameter Perjalanan</div>
            <p class="panel-subtitle">Sesuaikan jarak dan tarif untuk menghitung ulang estimasi biaya.</p>

            <?php if ($error): ?>
                <div class="error-box">
                    <?= htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Nama Pengendara</label>
                    <div class="input-wrapper">
                        <input type="text" name="nama" value="<?= htmlspecialchars($nama); ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Jarak Tempuh</label>
                    <div class="input-wrapper">
                        <input type="number" name="jarak" class="has-suffix" value="<?= htmlspecialchars((string)$jarakTempuh); ?>" min="1" step="any" required>
                        <span class="input-suffix">km</span>
                    </div>
                </div>

                <div class="form-group">
                    <label>Tarif Motor / km</label>
                    <div class="input-wrapper">
                        <span class="input-prefix">Rp</span>
                        <input type="number" name="tarif_motor" class="has-prefix" value="<?= htmlspecialchars((string)$tarifMotor); ?>" min="0" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Tarif Mobil / km</label>
                    <div class="input-wrapper">
                        <span class="input-prefix">Rp</span>
                        <input type="number" name="tarif_mobil" class="has-prefix" value="<?= htmlspecialchars((string)$tarifMobil); ?>" min="0" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Biaya Tambahan Tol (Mobil)</label>
                    <div class="input-wrapper">
                        <span class="input-prefix">Rp</span>
                        <input type="number" name="biaya_tol" class="has-prefix" value="<?= htmlspecialchars((string)$biayaTol); ?>" min="0" required>
                    </div>
                </div>

                <button type="submit" class="btn-submit">Kalkulasi Biaya</button>
            </form>
        </aside>

        <!-- PANEL KANAN: HASIL ANALISIS -->
        <main class="main-content">

            <?php if (!$error): ?>

                <!-- BANNER SELISIH BIAYA -->
                <div class="highlight-banner">
                    <div>
                        <div class="label">Selisih Pengeluaran (<?= number_format($motor->getJarak(), 0, ',', '.'); ?> km)</div>
                        <h2>Rp <?= number_format($selisihBiaya, 0, ',', '.'); ?></h2>
                    </div>
                    <div class="badge-hemat">
                        Opsi Lebih Hemat: <?= $opsiHemat; ?>
                    </div>
                </div>

                <!-- KARTU PERBANDINGAN DUA KOLOM -->
                <div class="vehicle-grid">
                    <div class="v-card motor">
                        <div class="v-header">
                            <span class="v-title">Total Biaya Motor</span>
                            <span class="v-tag">Class Motor</span>
                        </div>
                        <div class="v-price">Rp <?= number_format($totalBiayaMotor, 0, ',', '.'); ?></div>
                        <div class="v-meta">
                            <?= number_format($motor->getJarak(), 0, ',', '.'); ?> km × Rp <?= number_format($tarifMotor, 0, ',', '.'); ?>/km
                        </div>
                    </div>

                    <div class="v-card mobil">
                        <div class="v-header">
                            <span class="v-title">Total Biaya Mobil</span>
                            <span class="v-tag">Class Mobil</span>
                        </div>
                        <div class="v-price">Rp <?= number_format($totalBiayaMobil, 0, ',', '.'); ?></div>
                        <div class="v-meta">
                            (<?= number_format($mobil->getJarak(), 0, ',', '.'); ?> km × Rp <?= number_format($tarifMobil, 0, ',', '.'); ?>) + Tol Rp <?= number_format($biayaTol, 0, ',', '.'); ?>
                        </div>
                    </div>
                </div>

                <!-- GRAFIK PROPORSI BIAYA -->
                <div class="analytics-box">
                    <div class="panel-title">Rasio Perbandingan Visual</div>
                    <div class="bar-group">
                        <div>
                            <div class="bar-item-header">
                                <span>Motor (<?= $persenMotor; ?>%)</span>
                                <span>Rp <?= number_format($totalBiayaMotor, 0, ',', '.'); ?></span>
                            </div>
                            <div class="bar-track">
                                <div class="bar-fill motor" style="width: <?= $persenMotor; ?>%;"></div>
                            </div>
                        </div>

                        <div>
                            <div class="bar-item-header">
                                <span>Mobil + Tol (<?= $persenMobil; ?>%)</span>
                                <span>Rp <?= number_format($totalBiayaMobil, 0, ',', '.'); ?></span>
                            </div>
                            <div class="bar-track">
                                <div class="bar-fill mobil" style="width: <?= $persenMobil; ?>%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TABEL RINCIAN -->
                <div class="analytics-box">
                    <div class="panel-title">Rincian Output Perhitungan</div>
                    <table>
                        <thead>
                            <tr>
                                <th>Parameter</th>
                                <th>Objek / Sumber</th>
                                <th>Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Jarak Perjalanan</td>
                                <td><code>Kendaraan::getJarak()</code></td>
                                <td><?= number_format($motor->getJarak(), 0, ',', '.'); ?> km</td>
                            </tr>
                            <tr>
                                <td>Biaya Operasional Motor</td>
                                <td><code>Motor::hitungBiaya()</code></td>
                                <td style="color: var(--accent-emerald); font-weight: 600;">Rp <?= number_format($totalBiayaMotor, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td>Biaya Operasional Mobil</td>
                                <td><code>Mobil::hitungBiaya()</code></td>
                                <td style="color: var(--accent-amber); font-weight: 600;">Rp <?= number_format($totalBiayaMobil, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Selisih Biaya</strong></td>
                                <td>Komparasi Akhir</td>
                                <td style="color: var(--accent-cyan); font-weight: 700;">Rp <?= number_format($selisihBiaya, 0, ',', '.'); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            <?php endif; ?>

        </main>
    </div>

    <footer class="footer">
        © 2026 Vehicle Cost Analytics • PHP OOP Inheritance
    </footer>

</div>

</body>
</html>