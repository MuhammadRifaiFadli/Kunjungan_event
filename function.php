<?php
define("DB_APP","db_event.sqlite");
$db = new SQLite3("DB_APP");

// Buat tabel jika belum ada
$db->exec('CREATE TABLE IF NOT EXISTS event_kunjungan (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nama_event TEXT NOT NULL,
    lokasi TEXT NOT NULL,
    tanggal DATE NOT NULL,
    status TEXT NOT NULL,
    gambar TEXT
)');

// Fungsi tambah event baru
function tambahEvent($nama_event, $lokasi, $tanggal, $status, $gambar) {
    global $db;
    $stmt = $db->prepare('INSERT INTO event_kunjungan (nama_event, lokasi, tanggal, status, gambar) VALUES (:nama_event, :lokasi, :tanggal, :status, :gambar)');
    $stmt->bindValue(':nama_event', $nama_event, SQLITE3_TEXT);
    $stmt->bindValue(':lokasi', $lokasi, SQLITE3_TEXT);
    $stmt->bindValue(':tanggal', $tanggal, SQLITE3_TEXT);
    $stmt->bindValue(':status', $status, SQLITE3_TEXT);
    $stmt->bindValue(':gambar', $gambar, SQLITE3_TEXT);
    $stmt->execute();
}

// Fungsi ambil semua event
function ambilSemuaEvent() {
    global $db;
    return $db->query('SELECT * FROM event_kunjungan ORDER BY tanggal DESC');
}

// Fungsi ambil detail event untuk edit
function ambilEvent($id) {
    global $db;
    $stmt = $db->prepare('SELECT * FROM event_kunjungan WHERE id = :id');
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    return $stmt->execute()->fetchArray(SQLITE3_ASSOC);
}

// Fungsi update nama event
function updateEvent($id, $nama_event) {
    global $db;
    $stmt = $db->prepare('UPDATE event_kunjungan SET nama_event = :nama_event WHERE id = :id');
    $stmt->bindValue(':nama_event', $nama_event, SQLITE3_TEXT);
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $stmt->execute();
}

// Fungsi hapus event
function hapusEvent($id) {
    global $db;
    $stmt = $db->prepare('DELETE FROM event_kunjungan WHERE id = :id');
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $stmt->execute();
}
?>
