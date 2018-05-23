<?php session_start(); if(!isset($_SESSION['nom'])){ echo "erreur de connexion"; } else {
	include("../../connector.php");
	$message = 0 ;
	if(isset($_GET['action'])){
		switch($_GET['action']){
			case 'add':
				if(isset($_POST['poste'])){
					$stmt = $bdd->prepare('SELECT COUNT(id) FROM choix WHERE user = :user;');
					$stmt->bindValue(':user', $_SESSION['id'], PDO::PARAM_INT);
					$stmt->execute();
					$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
					$position = $rows[0]['COUNT(id)'] + 1 ;
					$stmt = $bdd->prepare("INSERT INTO `choix` (`id`, `user`, `poste`, `numerotation`) VALUES (NULL, :user, :poste, :posi);");
					$stmt->bindValue(':user', $_SESSION['id']);
					$stmt->bindValue(':poste', $_POST['poste']);
					$stmt->bindValue(':posi', $position);
					$stmt->execute();
					$message = 1;
				}
			break;
			case 'up':
				if(isset($_GET['upid'])){
					$stmt = $bdd->prepare('SELECT user, numerotation FROM choix WHERE id = :upid;');
					$stmt->bindValue(':upid', $_GET['upid'], PDO::PARAM_INT);
					$stmt->execute();
					$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
					if($rows[0]["user"] == $_SESSION['id'] && $rows[0]["numerotation"] >= 1){
						$oldnum = $rows[0]["numerotation"];
						//getting value of choice that will be pushed down
						$stmt = $bdd->prepare('SELECT id FROM choix WHERE numerotation = :nume AND user = :user;');
						$stmt->bindValue(':nume', $rows[0]["numerotation"]-1, PDO::PARAM_INT);
						$stmt->bindValue(':user', $_SESSION['id'], PDO::PARAM_INT);
						$stmt->execute();
						$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
						$todownid = $rows[0]["id"];			
						//pushing up !						
						$stmt = $bdd->prepare("UPDATE `choix` SET `numerotation` = :newn WHERE `choix`.`id` = :upid;");
						$stmt->bindValue(':newn', $oldnum-1, PDO::PARAM_INT);
						$stmt->bindValue(':upid', $_GET['upid'], PDO::PARAM_INT);
						$stmt->execute(); //Done pushin up the choice, now pushin down the other choice
						$stmt = $bdd->prepare("UPDATE `choix` SET `numerotation` = :newn WHERE `choix`.`id` = :dnid;");
						$stmt->bindValue(':newn', $oldnum, PDO::PARAM_INT);
						$stmt->bindValue(':dnid', $todownid, PDO::PARAM_INT);
						$stmt->execute(); //done
						$message = 2;
					} else {
						$message = 3;
					}
				}
			break;
			case 'down':
				if(isset($_GET['did'])){
					$stmt = $bdd->prepare('SELECT user, numerotation FROM choix WHERE id = :did;');
					$stmt->bindValue(':did', $_GET['did'], PDO::PARAM_INT);
					$stmt->execute();
					$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
					if($rows[0]["user"] == $_SESSION['id'] && $rows[0]["numerotation"] >= 1){
						$oldnum = $rows[0]["numerotation"];
						//getting value of choice that will be pushed up
						$stmt = $bdd->prepare('SELECT id FROM choix WHERE numerotation = :nume AND user = :user;');
						$stmt->bindValue(':nume', $rows[0]["numerotation"]+1, PDO::PARAM_INT);
						$stmt->bindValue(':user', $_SESSION['id'], PDO::PARAM_INT);
						$stmt->execute();
						$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
						$toupid = $rows[0]["id"];	
						//pushing down !						
						$stmt = $bdd->prepare("UPDATE `choix` SET `numerotation` = :newn WHERE `choix`.`id` = :did;");
						$stmt->bindValue(':newn', $oldnum+1, PDO::PARAM_INT);
						$stmt->bindValue(':did', $_GET['did'], PDO::PARAM_INT);
						$stmt->execute(); //Done pushin down the choice, now pushin up the other choice
						$stmt = $bdd->prepare("UPDATE `choix` SET `numerotation` = :newn WHERE `choix`.`id` = :dnid;");
						$stmt->bindValue(':newn', $oldnum, PDO::PARAM_INT);
						$stmt->bindValue(':dnid', $toupid, PDO::PARAM_INT);
						$stmt->execute(); //done
						$message = 2;
					} else {
						$message = 3;
					}
				}
			break;
			case 'del':
			if(isset($_GET['delid'])){
					$stmt = $bdd->prepare('SELECT user FROM choix WHERE id = :delid;');
					$stmt->bindValue(':delid', $_GET['delid'], PDO::PARAM_INT);
					$stmt->execute();
					$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
					if($rows[0]["user"] == $_SESSION['id']){
						$stmt = $bdd->prepare('DELETE FROM `choix` WHERE `choix`.`id` = :delid');
						$stmt->bindValue(':delid', $_GET['delid'], PDO::PARAM_INT);
						$stmt->execute();
						$message = 4;
					} else {
						$message = 3;
					}
				}
			break;
			case 'sur':
					$stmt = $bdd->prepare('SELECT sur FROM users WHERE id = :usid;');
					$stmt->bindValue(':usid', $_SESSION['id'], PDO::PARAM_INT);
					$stmt->execute();
					$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
					$new = 1;
					if($rows[0]['sur'] == 1){$new = 0;}					
					$stmt = $bdd->prepare("UPDATE `users` SET `sur` = :new WHERE `id` = :usid;");
					$stmt->bindValue(':new', $new, PDO::PARAM_INT);
					$stmt->bindValue(':usid', $_SESSION['id'], PDO::PARAM_INT);
					$stmt->execute();
			break;
			default:
			break;
		}
	}
	
	
	
	?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Choix des postes - Ordre <?php echo $_SESSION['nom']; ?></title>

    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="../vendor/morrisjs/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Choix des postes</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i><?php echo $_SESSION['nom'] .' '.$_SESSION['prenom']; ?></a>
                        <li class="divider"></li>
                        <li><a href="../../disco.php"><i class="fa fa-sign-out fa-fw"></i> Déconnexion</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="sidebar-search">
                            <div class="input-group custom-search-form">
                                <input type="text" class="form-control" placeholder="Search...">
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                            </div>
                            <!-- /input-group -->
                        </li>
                        <li>
                            <a href="index.php"><i class="fa fa-dashboard fa-fw"></i> Accueil</a>
                        </li>
                        <li>
                            <a href="./ordre.php"><i class="fa fa-bar-chart-o fa-fw"></i> Ordonner mes postes</a>
                            <!-- /.nav-second-level -->
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Liste des postes</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-comments fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div>Classement</div>
                                    <div class="huge"><?php echo $_SESSION['classement']; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                
				<?php
								$stmt = $bdd->prepare('SELECT sur FROM `users` WHERE id = :usid ;');
								$stmt->bindValue(':usid', $_SESSION['id'], PDO::PARAM_STR);
								$stmt->execute();
								$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
								$suretat = $rows[0]['sur'];
								if($suretat == 0){ ?>
								<div class="col-lg-6">
                        <div class="panel-heading">
                            Postes
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
						<p>Voici la liste de vos choix. Il est recommandé de mettre au moins <b><?php echo $_SESSION['classement']; ?></b> choix afin d'être sûr d'avoir un poste.<br />Utilisez les flèches pour ordonner vos choix.</p>
                            <div class="table-responsive">
							<?php
								
								$stmt = $bdd->prepare('SELECT choix.numerotation, postes.poste, choix.id, postes.id AS poste_id FROM `choix` INNER JOIN postes ON choix.poste = postes.id WHERE choix.user = :user ORDER BY choix.numerotation ASC;');
								$stmt->bindValue(':user', $_SESSION['id'], PDO::PARAM_STR);
								$stmt->execute();
								$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
								 ?>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Ordre</th>
                                            <th>Poste</th>
											<th>Haut</th>
											<th>Bas</th>
											<th>Supprimer</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php 
									$numchoix = sizeof($rows);
									$used = [];
									foreach($rows as $row){
										echo '<tr><td>' . $row["numerotation"] . '</td><td>' . $row["poste"] . '</td>';
											if($row["numerotation"] > 1){ echo '<td><a href="ordre.php?action=up&upid=' . $row["id"] . '">&uarr;</a></td>'; }	else { echo '<td>X</td>'; }
											if($row["numerotation"] != $numchoix) { echo '<td><a href="ordre.php?action=down&did=' . $row["id"] . '">&darr;</a></td>'; }	else  { echo '<td>X</td>'; }
											echo '<td><a href="ordre.php?action=del&delid=' . $row['id'] .'">X</a></td>';
										echo "</tr>";
										array_push($used, $row['poste_id']);
										 }									
									?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
					</div> 
					<!-- /.panel-body -->
					<div class="col-lg-6">
					    <div class="huge">
                            Ajouter un poste
                        </div>
						<div class="panel-body">
						<p>Le poste sera automatiquement ajouté à la dernière place, soit la position <?php echo $numchoix+1; ?>.</p>
				<form action="ordre.php?action=add" method="post" id="add">
					<select name="poste">
					<?php					
								$stmt = $bdd->prepare('SELECT * FROM postes');
								$stmt->execute();
								$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
								foreach($rows as $row){
									if(!in_array($row['id'], $used)) echo "<option value='" . $row['id'] . "'>" . $row['poste'] . "</option>";
								}
								 ?>
					</select>
							<button class="btn btn-secondary" form="add" type="submit">Ajouter</button>
					</form>
				<form action="ordre.php?action=sur" method="post" id="sur"><hr />
								<h2>Je suis sur de mon choix</h2>
								<p>Je suis sûr(e) de ne pas vouloir changer mes voeux(il est possible que mon veux change tout de même si quelqu'un de mieux classer que moi le prend <br />
								(l'opération est réversible, il est possible d'être sûr même si toutes les personnes devant ne sont pas sûres d'elles)<br />
								<br /><br />Alors je clique sur le bouton : 
								<button class="btn btn-success" form="sur" type="submit">Je suis sûr</button></p>
								</form>
								</div>
								</div>
								</div>
								<?php
								}
								else { ?>
								
				<form action="ordre.php?action=sur" method="post" id="sur">
								<h2>Je ne suis plus vraiment sûr</h2>
								<p>J'ai changé d'avis ...
								<br /><br />Alors je clique sur le bouton : 
								<button class="btn btn-danger" form="sur" type="submit">Je ne suis plus sûr</button></p>
								</form>
								</div>
								</div>
								</div>
								<?php
								}
								
				?>
				

                    <!-- /.panel -->
				</div>	
                </div>
                </div>
                <!-- /.col-lg-8 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Morris Charts JavaScript -->
    <script src="../vendor/raphael/raphael.min.js"></script>
    <script src="../vendor/morrisjs/morris.min.js"></script>
    <script src="../data/morris-data.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

</body>

</html>
	<?php $stmt = NULL; ?>
<?php } ?>