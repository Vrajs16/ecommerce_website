<?php require(__DIR__ . "/../../partials/nav.php");

echo var_export($_POST);
if (isset($_POST["checking-out-order"])) {
    echo "<br>";
    echo var_export($_POST["checking-out-order"]);
};
