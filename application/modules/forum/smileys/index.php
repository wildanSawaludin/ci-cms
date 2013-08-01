<?php
// Note that !== did not exist until 4.0.0-RC2

if ($handle = opendir('.')) {
    echo "Directory handle: $handle\n";
    echo "Files:\n";

    /* This is the correct way to loop over the directory. */
    while (false !== ($file = readdir($handle))) {
        echo "<img src='$file'> ";
    }
    closedir($handle);
}
?>
