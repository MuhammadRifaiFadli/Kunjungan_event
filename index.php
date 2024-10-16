<?php
require_once 'function.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'tambah':
                // Upload gambar
                if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == UPLOAD_ERR_OK) {
                    $target_dir = "poster_konser/";
                    $target_file = $target_dir . basename($_FILES["gambar"]["name"]);
                    move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file);
                    $gambar = $target_file;
                } else {
                    $gambar = null; // Atau bisa Anda tangani sesuai kebutuhan
                }
                tambahEvent($_POST['nama_event'], $_POST['lokasi'], $_POST['tanggal'], $_POST['status'], $gambar);
                break;
            case 'update':
                updateEvent($_POST['id'], $_POST['nama_event']);
                break;
            case 'hapus':
                hapusEvent($_POST['id']);
                break;
        }
    }
    header('Location: index.php');
    exit;
}

// Request for editing an event
$event_edit = null;
if (isset($_GET['edit'])) {
    $event_edit = ambilEvent($_GET['edit']);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kunjungan Event</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Kunjungan Event</h1>
        
        <!-- Form Tambah/Edit -->
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="<?php echo $event_edit ? 'update' : 'tambah'; ?>">
            <?php if ($event_edit): ?>
                <input type="hidden" name="id" value="<?php echo $event_edit['id']; ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="nama_event">Nama Event</label>
                <input type="text" id="nama_event" name="nama_event" autofocus value="<?php echo $event_edit ? $event_edit['nama_event'] : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="lokasi">Lokasi</label>
                <input type="text" id="lokasi" name="lokasi" value="<?php echo $event_edit ? $event_edit['lokasi'] : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="tanggal">Tanggal</label>
                <input type="date" id="tanggal" name="tanggal" value="<?php echo $event_edit ? $event_edit['tanggal'] : date('Y-m-d'); ?>" required readonly>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" <?php echo $event_edit ? 'disabled' : ''; ?> required>
                    <option value="berhasil mengunjungi" <?php echo $event_edit && $event_edit['status'] == 'berhasil mengunjungi' ? 'selected' : ''; ?>>Berhasil Mengunjungi</option>
                    <option value="gagal mengunjungi" <?php echo $event_edit && $event_edit['status'] == 'gagal mengunjungi' ? 'selected' : ''; ?>>Gagal Mengunjungi</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="gambar">Gambar</label>
                <input type="file" id="gambar" name="gambar" accept="image/*" <?php echo $event_edit ? '' : 'required'; ?>>
            </div>

            <button type="submit"><?php echo $event_edit ? 'Update' : 'Tambah'; ?> Event</button>
        </form>

        <!-- Tabel Daftar Event -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nama Event</th>
                        <th>Lokasi</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = ambilSemuaEvent();
                    while ($row = $result->fetchArray(SQLITE3_ASSOC)):
                    ?>
                    <tr>
                        <td data-label="Nama Event"><?php echo htmlspecialchars($row['nama_event']); ?></td>
                        <td data-label="Lokasi"><?php echo htmlspecialchars($row['lokasi']); ?></td>
                        <td data-label="Tanggal"><?php echo $row['tanggal']; ?></td>
                        <td data-label="Status"><?php echo htmlspecialchars($row['status']); ?></td>
                        <td data-label="Gambar"><img src="<?php echo htmlspecialchars($row['gambar']); ?>" alt="Gambar Event" style="width: 100px;"></td>
                        <td data-label="Aksi">
                            <div class="action-buttons">
                                <a href="?edit=<?php echo $row['id']; ?>"><button>Edit</button></a>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="hapus">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="hapus" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="footer">
        <p>&copy; <?php echo date("Y"); ?> Kunjungan Event By mas pai. All rights reserved.</p>
    </div>
</body>
</html>
