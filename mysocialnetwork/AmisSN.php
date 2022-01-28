	<meta charset="utf-8" />
<?php
   session_start();
$bdd = new PDO('mysql:host=localhost;dbname=network', 'upjv', 'upjv2021');

$pers = $bdd->query('SELECT * FROM user ORDER BY id DESC');
if(isset($_GET['recherche']) AND !empty($_GET['recherche'])) {
   $recherche = htmlspecialchars($_GET['recherche']);
   $pers = $bdd->query('SELECT * FROM user WHERE username LIKE "%'.$recherche.'%" ORDER BY id DESC');
}

$requete = $bdd->prepare('SELECT count(id) FROM suivre WHERE suiveur = ? GROUP BY suiveur');
$requete->execute(array($_SESSION['id']));
$nbsuiv = $requete->fetch();

$requete2 = $bdd->prepare('SELECT count(id) FROM suivre WHERE suivi = ? GROUP BY suivi');
$requete2->execute(array($_SESSION['id']));
$nbsuivi = $requete2->fetch();

?>

<head>
   <title>Mes amis</title>
   <meta charset="utf-8">
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
   <link rel="stylesheet" type="text/css" href="style.css">
</head>

      <div class="container-fluid">
      <div class="row">
         <header class="col-sm-12">
            <div class="row" id="entete">
               <nav class="col-lg-2 col-md-12"><img src="SN.png" height="130px" width="130px" /> </br> </nav>
               <nav class="col-md-10" id="menu">
                  <a href="MonProfilSN.php" >Mon profil</a>
                  <a href= "AmisSN.php" class="current"> Mes amis</a>
                  <a href="FilActuSN.php">Mon fil d'actualité</a>
                  <a href="messageSN.php">Chat</a>
                  <a href="DeconnexionSN.php">déconnexion</a>
                  
               </nav>
            </div>
         </header>
      </div></div>

<div class ="principale" style="text-align: center;">

   <div class="row g-0" style="text-align: center;">
     <div class="col-sm-6 col-md-6">
         <br>
        <hr>
        <h3> Rechercher une personne : </h3>
        <hr>
        
        <form method="GET">
            <input type="search" name="recherche" placeholder="Recherche" />
            <input class='aime' type="submit" value="Valider" />
         </form>
         <?php if($pers->rowCount() > 0) { ?>
            <ul>
            <?php while($a = $pers->fetch()) { ?>
               <li> 
               <div class="row g-0">
                 <div class="col-sm-4 col-md-3"> <img src="photo/<?php echo $a['photo']; ?>" width="50px" height = "50px"> </div>
                 <div class="col-4 col-md-3"> <?=  $a['username']  ?>  </div>
                 <div class="col-4 col-md-3"> <a href= <?php echo "VoirProfilSN.php?a=".$a['id']  ?>> voir </a> </div>
               </li>
            <?php } ?>
            </ul>
            <?php } else { ?>
            Aucun résultat pour: <?= $recherche ?>...
            <?php } ?>
     </div>
     <div class="col-6 col-md-6">
         <?php if (empty($nbsuiv['count(id)'])) {
                  echo "<br><hr><h3> Nombre de personnes suivies :  0 </h3><hr>";
               }
               else{ echo "<br><hr><h3> Nombre de personnes suivies : " . $nbsuiv['count(id)'] ."</h3><hr>" ;}
          ?>
          <div> <a href= "AfficherListeSN.php"> Afficher liste </a></div>
           <?php
               if (empty($nbsuivi['count(id)'])) {
                  echo "<br><hr><h3> Nombre de personnes qui vous suivent : 0 </h3><hr>";
               }
               else{ echo "<br><hr><h3> Nombre de personnes qui vous suivent : ". $nbsuivi['count(id)']. "</h3><hr>" ;}
            ?>

              <div > <a href= "AfficherListeSN.php"> Afficher liste </a></div>
        

     </div>
   </div>
</div>