<?php
function showAllUsers() {
    global $db;

    $sql = "SELECT * FROM Pengguna";
    $result = mysqli_query($db, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        echo "<div class='table-responsive'>
                <div class='table-wrapper'>
                    <div class='table-title'>
                        <div class='row'>
                            <div class='col-sm-6'>
                                <h2>Data <b>Pengguna</b></h2>
                            </div>
                        </div>
                    </div>
                    <table class='table table-striped table-hover'>
                        <thead class='table-dark'>
                            <tr>
                                <th>ID Pengguna</th>
                                <th>Username</th>
                                <th>Nama Lengkap</th>
                                <th>Nomor HP</th>
                                <th>Alamat Email</th>
                                <th>Saldo</th>
                                <th>Status Aktif</th>
                            </tr>
                        </thead>
                        <tbody>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$row['id_pengguna']}</td>
                    <td>{$row['username']}</td>
                    <td>{$row['nama_lengkap']}</td>
                    <td>{$row['nomor_hp']}</td>
                    <td>{$row['alamat_email']}</td>
                    <td>{$row['saldo']}</td>
                    <td>" . ($row['status_aktif'] ? 'Aktif' : 'Tidak Aktif') . "</td>
                  </tr>";
        }

        echo "          </tbody>
                    </table>
                </div>
              </div>";
    } else {
        echo "<p>No users found.</p>";
    }
}

showAllUsers();
?>
