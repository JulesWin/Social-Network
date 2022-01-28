<?php
session_start();
 
$bdd = new PDO('mysql:host=localhost;dbname=network', 'upjv', 'upjv2021');

if(isset($_SESSION['id'])) {

   $requete = $bdd->prepare('SELECT * FROM user WHERE id = ?');
   $requete->execute(array($_SESSION['id']));
   $user = $requete->fetch();

   if(isset($_POST['newusername']) AND isset($_POST['newemail']) AND isset($_POST['newnom']) AND isset($_POST['newprenom'])) {

      $newusername = htmlspecialchars($_POST['newusername']);
      $newemail = htmlspecialchars($_POST['newemail']);
      $newnom = htmlspecialchars($_POST['newnom']);
      $newprenom = htmlspecialchars($_POST['newprenom']);
      $prive = 0;

      if (isset($_POST['prive'])) {
        $prive = 1;
      }
     
      $modif = $bdd->prepare("UPDATE user SET username = ?, nom = ?, prenom = ?, email = ?, prive = ? WHERE id = ?");
      $modif->execute(array($newusername, $newnom, $newprenom, $newemail, $prive, $_SESSION['id']));
      header("Refresh:0");
   }

   // photo de profil
   if(isset($_FILES['photo']) AND !empty($_FILES['photo']['name'])) {
      $tailleMax = 64000;
      $extPossible= array('jpg', 'jpeg', 'png');
      
      if($_FILES['photo']['size'] <= $tailleMax) {
         $extensionPhoto = strtolower(substr(strrchr($_FILES['photo']['name'], '.'), 1)); // recupère l'extension
         
         if(in_array($extensionPhoto, $extPossible)) {
            $chemin = "photo/".$_SESSION['id'].".".$extensionPhoto;
            $res = move_uploaded_file($_FILES['photo']['tmp_name'], $chemin);
            
            if($res) {
               $modif = $bdd->prepare('UPDATE user SET photo = :photo WHERE id = :id');
               $modif->execute(array(
                  'photo' => $_SESSION['id'].".".$extensionPhoto,
                  'id' => $_SESSION['id']
                  ));
               header('Location: MonProfilSN.php?id='.$_SESSION['id']);
            }
            else {
               $msg = "Erreur durant l'importation de votre photo de profil";
            }
         } 
        else {
            $msg = "Votre photo de profil doit être au format jpg, jpeg, gif ou png";
         }
      } 
    else {
         $msg = "Votre photo de profil ne doit pas dépasser 2Mo";
      }
}

 

  $pub = $bdd->prepare('SELECT * FROM publication WHERE pers1 = ? ORDER BY date desc');
  $pub->execute(array($_SESSION['id']));

   /*
   if($pers->rowCount() == 0) {
      $pers = $bdd->query('SELECT * FROM user WHERE CONCAT(nom, prenom) LIKE "%'.$q.'%" ORDER BY id DESC');
   }
   */
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
                  <a href="MonProfilSN.php" class="current">Mon profil</a>
                  <a href= "AmisSN.php"> Mes amis</a>
                  <a href="FilActuSN.php">Mon fil d'actualité</a>
                  <a href="messageSN.php">Chat</a>
                  <a href="DeconnexionSN.php">déconnexion</a>
                  
               </nav>
            </div>
         </header>
      </div>

      <div class ="principale" align="center">
        <br>
        <hr>
        <h3> Mon profil :</h3>
        <hr>
         
         <br/><br/>

<div class="container px-4">
  <div class="row gx-5">
    <div class="col">
     <div class="p-3 ">
        
        <form method="POST" action="" enctype="multipart/form-data">
               <label>Pseudo :</label>
               <input type="text" name="newusername" placeholder="username" value="<?php echo $user['username']; ?>" /><br /><br />

               <label>Photo :</label>
            
               <img src="photo/<?php echo $user['photo'] ?>" width="100px" height = "100px">
               <p> Nouvelle image : </p>
               <input type="file" name="photo"/> <br /><br />
           </div>

          </div>
          <div class="col">
            <div class="p-3">
               
               <label>Mail   :</label>
                     <input type="text" name="newemail" placeholder="email" value="<?php echo $user['email']; ?>" /><br /><br />

                     <label>Nom    :</label>
                     <input type="text" name="newnom" placeholder="nom" value="<?php echo $user['nom']; ?>" /><br /><br />

                     <label>Prenom :</label>
                     <input type="text" name="newprenom" placeholder="prenom" value="<?php echo $user['prenom']; ?>" /><br /><br />

                     <label>Publications privées :</label>
                      <input type="checkbox" name="prive" <?php if($user['prive']){echo "checked";} ?> >
                      
            </div>
    </div>
  </div>
</div>
      <?php
      if (isset($msg)) {
    echo $msg;
      }

      ?>
                <input type="submit" value="Mettre à jour mon profil !" />
            </form>

        <?php
         if(isset($_SESSION['id']) AND $user['id'] == $_SESSION['id']) {
        ?>
         <br />

        <?php
         }
        ?>
      </div>
      <div align="center">
        <br>
        <hr>
        <h3>Mes publications</h3>
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
                
                <?php echo $a['texte']."<br><br>";

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
                          soyez le 1er à aimer<br><br>
                        </form><br><hr>";
                        $s = 1;
                 }
                 elseif($s == 1){
                    echo "<form method='POST' action=''>
                          <input class='aime' type='submit' name ='". $res2 ."' value='ne plus aimer'></input><br>
                          ".$nbJaime[0]." personne(s) aime(s)<br><br>
                        </form><br><hr>";
                        $s = 0;
                 }
                
                 //var_dump($j);// $j['poste'];
                 if(isset($_POST[$res])) { 
                    $ajoute = $bdd->prepare("INSERT INTO aime(poste, pers) VALUES(?, ?)");
                    $ajoute->execute(array($a['id'], $_SESSION['id']));
                    header("Location: MonProfilSN.php#$res");
                    
                  }

                  if(isset($_POST[$res2])) { 
                    $sup = $bdd->prepare("DELETE FROM aime WHERE pers = ? AND poste = ?");
                    $sup->execute(array($_SESSION['id'], $a['id']));
                    header("Location: MonProfilSN.php#$res");
                  }

                 ?>
                 <br/><br/>
               </li>
            <?php } // fin while ?> 
            </ul>
      </div>
   </body>
</html>  
<?php  
}
}

?>