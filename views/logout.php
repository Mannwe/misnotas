<?php

    // Accesos a la base de datos
    require_once 'app/config.inc.php';
    require_once 'app/SessionManager.inc.php';
    require_once 'app/Redirection.inc.php';

    SessionManager::closeSession();
    Redirection::redirect(MAIN_PATH);
