<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
//Para resolver um erro de sessão duplicada. 
// Agora nos trechos que é necessário estar em uma sessão, ela só será iniciada se não houver nenhuma sessão ativa.