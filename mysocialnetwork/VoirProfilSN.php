<?php
session_start();
 
$bdd = new PDO('mysql:host=localhost;dbname=network', 'upjv', 'upjv2021');

// afficher données profil
if(isset($_SESSION['id'])) {
   $a = $_GET["a"];
   $requete = $bdd->prepare('SELECT * FROM user WHERE id = ?');
   $requete->execute(array( $a ));
   $user = $requete->fetch();
      
   if(isset($_POST['suivre'])) { 
      $suivi = $user['id'];
      $suiveur =  $_SESSION['id'];
      $s = 1;

      $ajoute = $bdd->prepare("INSERT INTO suivre(suiveur, suivi) VALUES(?, ?)");
        $ajoute->execute(array($suiveur, $suivi));
   }

   if(isset($_POST['PlusSuivre'])) { 
      $suivi = $user['id'];
      $suiveur =  $_SESSION['id'];
      $s = 0;

      $sup = $bdd->prepare("DELETE FROM suivre WHERE suiveur = ? AND suivi = ?");
        $sup->execute(array($suiveur, $suivi));
   }

   if (isset($_POST['conv'])) {
      header("Location: conversationSN.php?r=".$user['id']);

   }
}

$requete2 = $bdd->prepare('SELECT suiveur FROM suivre WHERE suiveur = ? AND suivi = ?');
   $suivi = $user['id'];
   $suiveur =  $_SESSION['id'];
   $requete2->execute(array($suiveur, $suivi));
   $res = $requete2->fetch();

   
   if (!$res) {
     $s = 0 ;
   }
   else { $s = 1;}

/* 
if(isset($_POST['suivre'])) {
  $requete = $bdd->prepare('SELECT * FROM user WHERE suiveur = ? AND suivi = ?');
  $requete->execute(array( $_SESSION['id'], $user['id']));
  $suivi = $requete->fetch();
}
*/
  // récupère publications
  $pub = $bdd->prepare('SELECT * FROM publication WHERE pers1 = ? ORDER BY date');
  $pub->execute(array($user['id']));

  // permission accéder publications (si il nous suit alors ...)
  $permi = $bdd->prepare('SELECT * FROM suivre WHERE suivi = ? AND suiveur = ? ');
  $permi->execute(array($_SESSION['id'],$user['id']));
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

      <div class ="principale" align="center">
        <br>
        <hr>
        <h3>Profil de <?php echo $user['username']; ?></h3>
        <hr>
         
         <br/><br/>


<div class="container px-4">
  <div class="row gx-5">
    <div class="col">
     <div class="p-3 ">
        
        <form method="POST" action="" enctype="multipart/form-data">
            <label>Pseudo :</label>
              <?php echo $user['username']; ?> <br><br>

              <label>Photo :</label>
            <img src="photo/<?php echo $user['photo']; ?>" width="100px" height = "100px">
     </div>
    </div>
    <div class="col">
      <div class="p-3 ">
         <label>Mail :</label>
               <?php echo $user['email']; ?><br/><br/>
               <label>Nom  :</label>
              <?php echo $user['nom']; ?> <br/><br/>
               <label>Prenom :</label>
               <?php echo $user['prenom']; ?> <br/><br/>
      </div>
    </div>
  </div>
</div>

<?php


if ($s == 0) {
  echo  "<form method='POST' action=''>
            <input type='submit' name ='suivre' value='Suivre' />
            <input type='submit' name ='conv' value='Conversation' />
        </form>";
        $s = 1;
        //echo $s;
}
elseif ($s == 1) {
  echo  "<form method='POST' action=''>
            <input type='submit' name ='PlusSuivre' value='Ne plus suivre' />
            <input type='submit' name ='conv' value='Conversation' />
        </form>";
        $s = 0;
        //echo $s;
}


//echo $suivi;

 
?>

      <br />
    

    <div align="center">

      <?php
      if($user['prive'] == 1 AND $permi->rowCount() == 0){
        echo "les publications sont privées";
      }
      else{
      ?>
        <br>
        <hr>
        <h4>Ses publications</h4>
        <hr>
        <br/><br/>
        <?php
         if($pub->rowCount() > 0) { ?>
            <ul>
            <?php 
            while($a = $pub->fetch()) { 
              $res = $a['id'];  // identifie un bouton j'aime-(pas)
              $res2 = -$a['id'];
              ?>
               <li>

                 <p > <?php echo  $a['date'] ?> </p>
                <?php if (!empty($a['image'])) {  // si il y a une image on l'affiche
                  ?> 
                  <img src="image/<?php echo $a['image'] ?>" style ="max-width: 250px; max-height: 250px;"><?php
                } ?>
               
                 <p style ="font-weight: bold" id ="<?php echo $a['id'] ?>"> <?php echo $a['titre']  ?> </p>
                
                <?php echo "<p style='margin-left:20%;margin-right:20%'>".$a['texte']."<br><br></p>";


                  // verifie si la publication est aimé ou non
                $jaime = $bdd->prepare('SELECT * FROM aime WHERE pers = ? AND poste = ?');
                $jaime->execute(array($_SESSION['id'], $a['id']));
                $req = $jaime->fetch();



                  //compte le nombre de j'aime
                $nb = $bdd->prepare('SELECT count(id) FROM aime WHERE poste = ?');
                $nb->execute(array($a['id']));
                $nbJaime = $nb->fetch();




                  //echo $nbJaime[0];
                  
                if (!$req) {
                 $s = 0 ;
                }
                else { $s = 1;}

                  

                  //  echo $jaime->rowCount();
                  
                 if($s == 0){
                     echo "<form method='POST' action=''>
                          <input class='aime' type='submit' name ='".$res."' value='aimer' ></input><br>
                          soyez le 1er à aimer <br><br>
                          <a href='CommentaireSN.php?ProfilPoste=".$res."'>Commentaire</a>
                        </form><br><hr>";
                        $s = 1;
                 }
                 elseif($s == 1){
                    echo "<form method='POST' action=''>
                          <input class='aime' type='submit' name ='". $res2 ."' value='ne plus aimer'></input><br>
                          ".$nbJaime[0]." personne(s) aime(s) <br><br>
                          <a href='CommentaireSN.php?ProfilPoste=".$res."'>Commentaire</a>
                        </form><br><hr>";
                        $s = 0;
                 }
                
                 //var_dump($j);// $j['poste'];
                 if(isset($_POST[$res])) { 
                    $ajoute = $bdd->prepare("INSERT INTO aime(poste, pers) VALUES(?, ?)");
                    $ajoute->execute(array($a['id'], $_SESSION['id']));
                    header("Location: VoirProfilSN.php?a=".$user['id']."#$res");
                    
                  }

                  if(isset($_POST[$res2])) { 
                    $sup = $bdd->prepare("DELETE FROM aime WHERE pers = ? AND poste = ?");
                    $sup->execute(array($_SESSION['id'], $a['id']));
                    header("Location: VoirProfilSN.php?a=".$user['id']."#$res");
                  }

                 ?>
                 <br/><br/>
               </li>
            <?php }}} // fin while ?> 
            </ul>
      </div>
      </div>
   </body>
</html>