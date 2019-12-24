<!doctype html>
<html lang="es">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE-edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="author" content="Alberto Rodríguez - Mannwe Developments">

        <?php
            // Primero incluimos los documentos necesarios
            if(!isset($title) || empty($title)){
                $title = 'Mis Notas';
            }
            echo '<title>' . $title . '</title>';
        ?>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <!-- Fontawesome CSS -->
        <link rel="stylesheet" href="<?php echo PATH_CSS ?>all.css">
        <!-- Custom CSS -->
        <link rel="stylesheet" href="<?php echo PATH_CSS ?>styles.css">
    </head>
    <body>
