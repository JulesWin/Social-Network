<?php
  session_start();

$bdd = new PDO('mysql:host=localhost;dbname=network', 'upjv', 'upjv2021');

if(isset($_SESSION['id']) AND !empty($_SESSION['id'])) {

        if (isset($_GET['ActuPoste'])) {
          $comm = $bdd->prepare('SELECT * FROM commentaire INNER JOIN publication ON commentaire.poste = publication.id INNER JOIN user ON user.id = commentaire.pers WHERE poste = ? ORDER BY commentaire.date DESC');
          $comm->execute(array($_GET['ActuPoste']));
          $redirection = 1;
        
          if(isset($_POST['envoi'])) {
            $envoyer = $bdd->prepare('INSERT INTO commentaire(poste, pers, commentaire) VALUES (?,?,?)');
            $envoyer->execute(array($_GET['ActuPoste'], $_SESSION['id'], $_POST['commentaire']));
            header("Refresh:0");
          }
        }
        elseif(isset($_GET['ProfilPoste'])){
            $comm = $bdd->prepare('SELECT * FROM commentaire INNER JOIN publication ON commentaire.poste = publication.id INNER JOIN user ON user.id = commentaire.pers WHERE poste = ? ORDER BY commentaire.date DESC');
            $comm->execute(array($_GET['ProfilPoste']));
            $redirection = 2;

            if(isset($_POST['envoi'])) {
            $envoyer = $bdd->prepare('INSERT INTO commentaire(poste, pers, commentaire) VALUES (?,?,?)');
            $envoyer->execute(array($_GET['ProfilPoste'], $_SESSION['id'], $_POST['commentaire']));
            header("Refresh:0");
          }
          $pers = $bdd->prepare('SELECT pers FROM commentaire WHERE poste = ?');
          $pers->execute(array($_GET['ProfilPoste']));
        }

        
?>


<html>
<head>
   <title>Social Network</title>
   <meta charset="utf-8">
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
   <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
      <div class="container-fluid">
      <div class="row">
         <header class="col-sm-12">
            <div class="row" id="entete">
               <nav class="col-lg-2 col-md-12"><img src="SN.png" height="130px" width="130px" /> </br> </nav>
               <nav class="col-md-10" id="menu">
                  <a href="MonProfilSN.php" >Mon profil</a>
                  <a href= "AmisSN.php" > Mes amis</a>
                  <a href="FilActuSN.php" >Mon fil d'actualité</a>
                  <a href="messageSN.php" >Chat</a>
                  <a href="DeconnexionSN.php">déconnexion</a>
                  
               </nav>
            </div>
         </header>
      </div></div>

      <div class ="principale" align="center">
        <?php
        if($redirection == 1){
          echo "<a href='FilActuSN.php'>retour</a>";
        }
        elseif ($redirection == 2) {
          $pers = $pers->fetch();
          //echo "<a href='VoirProfilSN.php?a=".$pers['pers']." >retour</a>";
        }
        ?>

        <br/><hr> <h3>Poster commentaires :</h3><hr><br/>
         
         <form method="POST">
         <textarea placeholder="Votre commentaire" name="commentaire"></textarea><br>
         <input type="submit" value="Envoyer" name="envoi" />
         <br/>
      </form>
      <br/>

         <br/><hr> <h3>Commentaires :</h3><hr><br/>

         <?php
         if($comm->rowCount() > 0) { ?>
            <ul>
            <?php 
            while($a = $comm->fetch()) { ?>
                  <li>
                 <p class="comm"> <br> <?php echo  $a['username'] ."  : <br> ".$a['commentaire'] ?> <br>  </p>
                   <?php echo  $a['date'] ?> <br/>
                  </li>
                  <br> 

            <?php }}}// fin while ?> 
            </ul>
       
      </div>
   </body>
</html>