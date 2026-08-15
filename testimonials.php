<?php
$page_title = "Testimonials";
include ("includes/header.php");
?>
<div id="main" class="container">
<form action="" method="post" enctype="multipart/form-data">
<p>Your name:<br><input type="text" name="name" size="30" maxlength="30"></p>
<p>Your comments:<br><textarea name="comment" rows="7" cols="35"></textarea></p>
<label for="myPhoto">Upload image (optional):</label>
<input type="file" id="myPhoto" name="myPhoto" accept="image/*"><br>
<input type="submit" name="submit" value="Submit">
<input type="submit" name="view" value="View">
</form>

<?php
$myFile = "testimonials.txt";
$uploadsDir = __DIR__ . '/uploads';
if (!is_dir($uploadsDir)) mkdir($uploadsDir, 0755, true);

if (isset($_POST['submit'])) {
    $name = trim($_POST['name'] ?? '');
    $comment = trim($_POST['comment'] ?? '');
    $imageName = '';

    if (!empty($_FILES['myPhoto']['tmp_name'])) {
        $f = $_FILES['myPhoto'];
        // basic validation: image mime
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $f['tmp_name']);
        finfo_close($finfo);
        $allowed = ['image/jpeg','image/png','image/gif','image/webp'];
        if (in_array($mime, $allowed)) {
            $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
            $imageName = 'img_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            move_uploaded_file($f['tmp_name'], $uploadsDir . '/' . $imageName);
        } else {
            echo "<p class='text-danger'>Uploaded file is not an allowed image type.</p>";
        }
    }
    if (is_writable($myFile) || (!file_exists($myFile) && is_writable(__DIR__))) {
        file_put_contents($myFile, $name.PHP_EOL, FILE_APPEND);
        file_put_contents($myFile, ($imageName ? 'uploads/'.$imageName : '').PHP_EOL, FILE_APPEND);
        file_put_contents($myFile, $comment.PHP_EOL, FILE_APPEND);
        file_put_contents($myFile, date('m/d/Y').PHP_EOL, FILE_APPEND);
        echo "<p>Thank you!</p>";
    } else {
        echo "<p>Something is wrong: cannot write testimonials.</p>";
    }
}

if (isset($_POST['view'])) {
    if (file_exists($myFile)) {
        $allComments = file($myFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        for ($i = 0; $i < count($allComments); $i += 4) {
            $n = $allComments[$i] ?? '';
            $img = $allComments[$i+1] ?? '';
            $c = $allComments[$i+2] ?? '';
            $d = $allComments[$i+3] ?? '';
            echo '<div class="testimonial">';
            echo '<strong>'.htmlspecialchars($n).'</strong><br>';
            if ($img) {
                echo '<img src="'.htmlspecialchars($img).'" width="100" height="100" alt="testimonial image"><br>';
            }
            echo nl2br(htmlspecialchars($c)).'<br>';
            echo '<small>' . htmlspecialchars($d) . '</small>';
            echo '</div><hr>';
        }
    } else {
        echo '<p>No testimonials yet.</p>';
    }
}
?>
</div>
<?php include ("includes/footer.php"); ?>