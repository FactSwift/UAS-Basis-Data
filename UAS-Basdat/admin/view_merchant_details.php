<?php
function showAllMerchants() {
    global $db;

    $sql = "SELECT * FROM Merchant";
    $result = mysqli_query($db, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        echo "<div class='table-responsive'>
                <div class='table-wrapper'>
                    <div class='table-title'>
                        <div class='row'>
                            <div class='col-sm-6'>
                                <h2>Data <b>Merchant</b></h2>
                            </div>
                        </div>
                    </div>
                    <table class='table table-striped table-hover'>
                        <thead class='table-dark'>
                            <tr>
                                <th>ID Merchant</th>
                                <th>Username</th>
                                <th>Nama Toko</th>
                                <th>Alamat Toko</th>
                                <th>Nomor HP</th>
                                <th>Alamat Email</th>
                                <th>ID Toko Gi-Pay</th>
                                <th>Saldo Akhir</th>
                                <th>Status Aktif</th>
                            </tr>
                        </thead>
                        <tbody>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$row['id_merchant']}</td>
                    <td>{$row['username']}</td>
                    <td>{$row['nama_toko']}</td>
                    <td>{$row['alamat_toko']}</td>
                    <td>{$row['nomor_hp']}</td>
                    <td>{$row['alamat_email']}</td>
                    <td>{$row['id_toko_gipay']}</td>
                    <td>{$row['saldo_akhir']}</td>
                    <td>" . ($row['status_aktif'] ? 'Aktif' : 'Tidak Aktif') . "</td>
                  </tr>";
        }

        echo "          </tbody>
                    </table>
                </div>
              </div>";
    } else {
        echo "<p>No merchants found.</p>";
    }
}

showAllMerchants();
?>
