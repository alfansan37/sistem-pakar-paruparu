<?php
include '../koneksi.php';

// Cek apakah user sudah login dan role user
if (!is_logged_in() || !is_user()) {
    redirect('../login.php');
}

require_once('../assets/tcpdf/tcpdf.php');

// Buat class PDF custom
class MYPDF extends TCPDF {
    // Page header
    public function Header() {
        // Logo
        $this->Image('../assets/images/logo.png', 10, 10, 15, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 12);
        // Title
        $this->Cell(0, 15, 'LAPORAN SISTEM PAKAR DIAGNOSA PENYAKIT PARU-PARU', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(10);
        $this->SetFont('helvetica', '', 10);
        $this->Cell(0, 0, 'Generated on: ' . date('d/m/Y H:i'), 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(5);
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

// Buat PDF baru
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator('Sistem Pakar Paru-Paru');
$pdf->SetAuthor('Pasien');
$pdf->SetTitle('Laporan Diagnosa');
$pdf->SetSubject('Laporan Sistem Pakar');

// Set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// Set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Set font
$pdf->SetFont('helvetica', '', 10);

// Add a page
$pdf->AddPage();

// Cek apakah cetak per ID atau semua
if (isset($_GET['id'])) {
    // Cetak per ID hasil diagnosa
    $id_hasil = bersihkan_input($_GET['id']);
    
    $query = "SELECT h.*, u.nama as nama_user, p.nama_penyakit, p.deskripsi, p.solusi 
              FROM hasil_diagnosa h 
              JOIN users u ON h.id_user = u.id_user 
              JOIN penyakit p ON h.id_penyakit = p.id_penyakit 
              WHERE h.id_hasil = '$id_hasil'";
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);
    
    if ($row) {
        $html = '
        <h2 style="text-align:center;color:#007bff;">HASIL DIAGNOSA</h2>
        <table border="0" cellpadding="5">
            <tr>
                <td width="30%"><strong>Nama Pasien</strong></td>
                <td width="70%">: ' . $row['nama_user'] . '</td>
            </tr>
            <tr>
                <td><strong>Tanggal Diagnosa</strong></td>
                <td>: ' . date('d/m/Y H:i', strtotime($row['tanggal'])) . '</td>
            </tr>
            <tr>
                <td><strong>Hasil Diagnosa</strong></td>
                <td>: <span style="color:#dc3545;font-weight:bold;">' . $row['nama_penyakit'] . '</span></td>
            </tr>
        </table>
        
        <br>
        <h4 style="color:#28a745;">Gejala yang Dipilih:</h4>
        <ul>';
        
        // Tampilkan gejala yang dipilih
        $gejala_ids = explode(',', $row['gejala_terpilih']);
        foreach ($gejala_ids as $id_gejala) {
            $gejala_query = mysqli_query($koneksi, "SELECT nama_gejala FROM gejala WHERE id_gejala = '$id_gejala'");
            if ($gejala_row = mysqli_fetch_assoc($gejala_query)) {
                $html .= '<li>' . $gejala_row['nama_gejala'] . '</li>';
            }
        }
        
        $html .= '</ul>
        
        <br>
        <h4 style="color:#ffc107;">Deskripsi Penyakit:</h4>
        <p>' . $row['deskripsi'] . '</p>
        
        <br>
        <h4 style="color:#17a2b8;">Saran dan Solusi:</h4>
        <p>' . $row['solusi'] . '</p>
        
        <br><br>
        <div style="text-align:center;">
            <p><em>** Hasil diagnosa ini merupakan perkiraan awal. Disarankan untuk berkonsultasi dengan dokter spesialis paru untuk diagnosa yang lebih akurat.</em></p>
        </div>';
        
        $pdf->writeHTML($html, true, false, true, false, '');
    }
} else {
    // Cetak laporan semua atau berdasarkan filter
    $filter_tanggal = isset($_GET['tanggal']) ? bersihkan_input($_GET['tanggal']) : '';
    
    if (!empty($filter_tanggal)) {
        $where = "WHERE DATE(h.tanggal) = '$filter_tanggal'";
        $title = "Laporan Diagnosa Tanggal " . date('d/m/Y', strtotime($filter_tanggal));
    } else {
        $where = "";
        $title = "Laporan Semua Diagnosa";
    }
    
    $query = "SELECT h.*, u.nama as nama_user, p.nama_penyakit 
              FROM hasil_diagnosa h 
              JOIN users u ON h.id_user = u.id_user 
              JOIN penyakit p ON h.id_penyakit = p.id_penyakit 
              $where
              ORDER BY h.tanggal DESC";
    $result = mysqli_query($koneksi, $query);
    
    $html = '<h2 style="text-align:center;color:#007bff;">' . $title . '</h2>';
    
    if (mysqli_num_rows($result) > 0) {
        $html .= '
        <table border="1" cellpadding="5" style="border-collapse:collapse;">
            <thead>
                <tr style="background-color:#f8f9fa;">
                    <th width="5%"><strong>No</strong></th>
                    <th width="25%"><strong>Nama User</strong></th>
                    <th width="25%"><strong>Penyakit</strong></th>
                    <th width="35%"><strong>Gejala Terpilih</strong></th>
                    <th width="10%"><strong>Tanggal</strong></th>
                </tr>
            </thead>
            <tbody>';
        
        $no = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            // Ambil nama gejala
            $gejala_ids = explode(',', $row['gejala_terpilih']);
            $gejala_names = array();
            foreach ($gejala_ids as $id_gejala) {
                $gejala_query = mysqli_query($koneksi, "SELECT nama_gejala FROM gejala WHERE id_gejala = '$id_gejala'");
                if ($gejala_row = mysqli_fetch_assoc($gejala_query)) {
                    $gejala_names[] = $gejala_row['nama_gejala'];
                }
            }
            
            $html .= '
            <tr>
                <td>' . $no++ . '</td>
                <td>' . $row['nama_user'] . '</td>
                <td>' . $row['nama_penyakit'] . '</td>
                <td>' . implode(', ', $gejala_names) . '</td>
                <td>' . date('d/m/Y', strtotime($row['tanggal'])) . '</td>
            </tr>';
        }
        
        $html .= '</tbody></table>';
        
        // Statistik
        $html .= '<br><br><h4>Statistik Diagnosa:</h4>';
        $html .= '<p>Total Diagnosa: <strong>' . mysqli_num_rows($result) . '</strong></p>';
    } else {
        $html .= '<p style="text-align:center;color:#dc3545;">Tidak ada data diagnosa.</p>';
    }
    
    $pdf->writeHTML($html, true, false, true, false, '');
}

// Close and output PDF document
$pdf->Output('laporan_diagnosa_' . date('Ymd_His') . '.pdf', 'I');