<?php
require '../components/db.php';

if (isset($_POST['regionCode'])) {
    $regionCode = mysqli_real_escape_string($conn, $_POST['regionCode']);
    
    $provinceQuery = "SELECT * FROM refprovince WHERE regCode = '$regionCode'";
    $provinceResult = mysqli_query($conn, $provinceQuery);
    
    echo "<option value=''>Select Province</option>";
    while ($row = mysqli_fetch_assoc($provinceResult)) {
        echo "<option value='".$row['provCode']."'>".$row['provDesc']."</option>";
    }
}

if (isset($_POST['provCode'])) {
    $provCode = mysqli_real_escape_string($conn, $_POST['provCode']);
    
    $cityQuery = "SELECT * FROM refcitymun WHERE provCode = '$provCode'";
    $cityResult = mysqli_query($conn, $cityQuery);
    
    echo "<option value=''>Select City/Municipality</option>";
    while ($row = mysqli_fetch_assoc($cityResult)) {
        echo "<option value='".$row['citymunCode']."'>".$row['citymunDesc']."</option>";
    }
}

if (isset($_POST['cityCode'])) {
    $cityCode = mysqli_real_escape_string($conn, $_POST['cityCode']);
    
    $barangayQuery = "SELECT * FROM refbrgy WHERE citymunCode = '$cityCode'";
    $barangayResult = mysqli_query($conn, $barangayQuery);
    
    echo "<option value=''>Select Barangay</option>";
    while ($row = mysqli_fetch_assoc($barangayResult)) {
        echo "<option value='".$row['psgcCode']."'>".$row['brgyDesc']."</option>";
    }
}
?>