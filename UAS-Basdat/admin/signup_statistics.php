<?php
function showSignupStatistics() {
    global $db;

    $userQuery = "SELECT DATE(tanggal_daftar) AS tanggal, COUNT(*) AS jumlah_pengguna 
                  FROM Pengguna GROUP BY DATE(tanggal_daftar)";
    $merchantQuery = "SELECT DATE(tanggal_daftar) AS tanggal, COUNT(*) AS jumlah_merchant 
                      FROM Merchant GROUP BY DATE(tanggal_daftar)";

    $userResult = mysqli_query($db, $userQuery);
    $merchantResult = mysqli_query($db, $merchantQuery);

    echo "<div class='table-responsive'>
            <div class='table-wrapper'>
                <div class='table-title'>
                    <div class='row'>
                        <div class='col-sm-6'>
                            <h2>Statistik <b>Sign-Up</b></h2>
                        </div>
                    </div>
                </div>
                <table class='table table-striped table-hover'>
                    <thead class='table-dark'>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jumlah Pengguna</th>
                            <th>Jumlah Merchant</th>
                        </tr>
                    </thead>
                    <tbody>";

    $userStats = [];
    while ($row = mysqli_fetch_assoc($userResult)) {
        $userStats[$row['tanggal']] = $row['jumlah_pengguna'];
    }

    $merchantStats = [];
    while ($row = mysqli_fetch_assoc($merchantResult)) {
        $merchantStats[$row['tanggal']] = $row['jumlah_merchant'];
    }

    $allDates = array_unique(array_merge(array_keys($userStats), array_keys($merchantStats)));
    sort($allDates);

    foreach ($allDates as $date) {
        $jumlahPengguna = isset($userStats[$date]) ? $userStats[$date] : 0;
        $jumlahMerchant = isset($merchantStats[$date]) ? $merchantStats[$date] : 0;
        echo "<tr>
                <td>$date</td>
                <td>$jumlahPengguna</td>
                <td>$jumlahMerchant</td>
              </tr>";
    }

    echo "          </tbody>
                </table>
              </div>
            </div>";
}

showSignupStatistics();
?>
