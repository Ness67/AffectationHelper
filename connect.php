<?php
session_start();
if(isset($_POST['nom']) && isset($_POST['pwd'])){
	$nom = ucfirst(strtolower($_POST['nom']));
	include('connector.php');
	$stmt = $bdd->prepare('SELECT nom, pwd, prenom, classement, id FROM users WHERE nom=:nom');
	$stmt->bindValue(':nom', $nom, PDO::PARAM_STR);
	$stmt->execute();
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$stmt = NULL;
	if($rows != NULL){ //perform check of pwd
		$pwd = $_POST['pwd'];
	if($pwd == $rows[0]['pwd']){
		$_SESSION['nom'] = $nom;
		$_SESSION['prenom'] =  $rows[0]['prenom'];
		$_SESSION['classement'] =  $rows[0]['classement'];
		$_SESSION['id'] =  $rows[0]['id'];
		include('./app/index.php');
	}
	else {
		header("Location: index.php?err=0");
		die();
	}
	} else {
		header("Location: index.php?err=0");
		die();
	
	}
} else {
	echo "Erreur d'accès";
}
?>