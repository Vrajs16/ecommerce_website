<?php
require(__DIR__ . "/../../partials/nav.php");

$db = getDB();
$q200 = "SELECT email, username, profile_type FROM Users where profile_type = 1";
$stmt = $db->prepare($q200);
try {
    $stmt->execute();
    $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($r) {
        $profile_info = $r;
    }
} catch (PDOException $e) {
    flash("<pre>" . var_export($e, true) . "</pre>");
}

?>
<h1>Home</h1>
<?php
if (is_logged_in(true)) : ?>
    <div>
        <div class="container bg-light">
            <div class="row justify-content-space-between p-2 h1">
                <div class="col d-flex justify-content-center text-center">Email</div>
                <div class="col d-flex justify-content-center text-center">User</div>
                <div class="col d-flex justify-content-center text-center">Profile Type</div>
            </div>
            <?php foreach ($profile_info as $info) : ?>
                <div class="row justify-content-space-between p-4">
                    <div class="col d-flex justify-content-center text-center"><?php se($info, "email") ?></div>
                    <div class="col d-flex justify-content-center text-center"><?php se($info, "username") ?></div>
                    <div class="col d-flex justify-content-center text-center"><?php echo (se($info, "profile_type", null, false) == 1) ? "Public" : "Private" ?></div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
<?php endif ?>
<?php
require(__DIR__ . "/../../partials/flash.php");
?>