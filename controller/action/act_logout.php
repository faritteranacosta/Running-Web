<?php
session_start();
session_destroy();

header("Location: ../../view/iniciar_sesion.html");
