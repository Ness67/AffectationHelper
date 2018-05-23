<?php session_start(); if(!isset($_SESSION['nom'])){ echo "erreur de connexion"; } else {?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Choix des postes</title>

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
                            <a href="ordre.php"><i class="fa fa-bar-chart-o fa-fw"></i> Ordonner mes postes</a>
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
                    <h1 class="page-header">Choix d'un utilisateur</h1>
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
                <div class="col-lg-6">
                        <div class="panel-heading">
                            Postes
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
							<?php
								include("../../connector.php");
								$stmt = $bdd->prepare('SELECT postes.id AS poste_id, postes.poste, choix.numerotation, users.classement, users.nom, users.prenom FROM `choix` INNER JOIN postes ON choix.poste = postes.id  INNER JOIN users ON choix.user = users.id ORDER BY users.classement, choix.numerotation ASC;');
								$stmt->execute();
								$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
								 ?>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Poste probable</th>
                                            <th>Nom</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php 
									$alreadyused = []; $doneuser = [];
									$postes = [];
									foreach($rows as $row){
										 if(!in_array($row['poste_id'], $alreadyused) && !in_array($row['nom'], $doneuser)){
										 array_push($postes, [$row['classement'],$row['poste'],$row['nom'],$row['prenom']]);
										 array_push($alreadyused, $row['poste_id']);
										 array_push($doneuser, $row['nom']);
										 }
									}
									$stmt = $bdd->prepare('SELECT nom, prenom, classement, id, sur FROM users ORDER BY classement ASC;');
									$stmt->execute();
									$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
									$taillemax = sizeof($postes);
									array_push($postes, 99);
									$pos = 0;
									foreach($rows as $row){
											if($postes[$pos][0] == $row['classement'] && $pos <= $taillemax ){
												if($row['sur'] == 1) {
													echo '<tr bgcolor="#AAFFAA"><td>' . $row['classement'] . '</td><td>' . $postes[$pos][1] . '</td><td><a href="show.php?usid=' . $row['id'] . '">' . $row['nom'] . ' ' . $row['prenom'] . '</a> (Sûr(e))</td></tr>';
												} else {
												echo '<tr><td>' . $row['classement'] . '</td><td>' . $postes[$pos][1] . '</td><td><a href="show.php?usid=' . $row['id'] . '">' . $row['nom'] . ' ' . $row['prenom'] . '</a></td></tr>';
												}
												$pos++;
											} else {
												echo '<tr bgcolor="#FFAAAA"><td>' . $row['classement'] . '</td><td>Pas de choix</td><td><a href="show.php?usid=' . $row['id'] . '">' . $row['nom'] . ' ' . $row['prenom'] . '</a></td></tr>';
											}
									}
									
									?>
                                    </tbody>
									<?php $stmt = NULL; ?>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
					</div>   
					<!-- /.panel-body -->
                    </div>
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
<?php } ?>