<?php

switch (_opt('404_type')) {
    case 'blank':
        include('assets/template/404-blank.php');
        break;
    default:
        include('assets/template/404-default.php');
        break;
}
