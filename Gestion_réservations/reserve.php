<?php
session_start();
require 'config.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}
if(!isset($_POST['event_id']) || !is_numeric($_POST['event_id'])){
    header("Location: index.php");
    exit();
}

$u = (int)$_SESSION['user_id'];
$e = (int)$_POST['event_id'];

$s = $pdo->prepare("SELECT id FROM reservations WHERE user_id=? AND event_id=?");
$s->execute([$u,$e]);
if($s->rowCount()){
    header("Location: index.php?error=already");
    exit();
}

try {
    $pdo->beginTransaction();

    $s = $pdo->prepare("SELECT nbPlaces FROM events WHERE id=? FOR UPDATE");
    $s->execute([$e]);
    $d = $s->fetch();

    if(!$d){
        $pdo->rollBack();
        header("Location: index.php?error=not_found");
        exit();
    }
    if($d['nbPlaces']<=0){
        $pdo->rollBack();
        header("Location: index.php?error=complet");
        exit();
    }

    $pdo->prepare("INSERT INTO reservations(user_id,event_id) VALUES(?,?)")->execute([$u,$e]);
    $pdo->prepare("UPDATE events SET nbPlaces=nbPlaces-1 WHERE id=?")->execute([$e]);

    $pdo->commit();
    header("Location: index.php?success=1");
    exit();

} catch(Exception $e){
    $pdo->rollBack();
    header("Location: index.php?error=server");
    exit();
}
