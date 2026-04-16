<?php
$dir = __DIR__;

// First modify includes/db.php to define BASE_URL
$db_file = $dir . '/includes/db.php';
$db_content = file_get_contents($db_file);
if (strpos($db_content, 'BASE_URL') === false) {
    $base_def = "<?php\n\$project_root = str_replace('\\\\', '/', __DIR__);\n\$project_root = dirname(\$project_root);\n\$doc_root = str_replace('\\\\', '/', \$_SERVER['DOCUMENT_ROOT']);\n\$base_url = str_replace(\$doc_root, '', \$project_root);\ndefine('BASE_URL', \$base_url);\n";
    $db_content = preg_replace('/<\?php/', $base_def, $db_content, 1);
    file_put_contents($db_file, $db_content);
}

// Now replace in all .php
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
foreach ($files as $file) {
    if ($file->isFile()) {
        $ext = $file->getExtension();
        if ($ext === 'php') {
            if ($file->getFilename() === 'fix_paths.php') continue;
            
            $content = file_get_contents($file->getRealPath());
            
            // Replaces HTML attributes
            $content = str_replace('href="/', 'href="<?= BASE_URL ?>/', $content);
            $content = str_replace('src="/', 'src="<?= BASE_URL ?>/', $content);
            $content = str_replace("action='/", "action='<?= BASE_URL ?>/", $content);
            $content = str_replace('action="/', 'action="<?= BASE_URL ?>/', $content);
            
            // Replaces headers
            $content = str_replace("header('Location: /", "header('Location: ' . BASE_URL . '/", $content);
            $content = str_replace('header("Location: /', 'header("Location: " . BASE_URL . "/', $content);
            $content = str_replace("Location: /", "Location: ' . BASE_URL . '/", $content);

            file_put_contents($file->getRealPath(), $content);
        }
    }
}
echo "Done";
