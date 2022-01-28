<?php
  session_start();

$bdd = new PDO('mysql:host=localhost;dbname=network', 'upjv', 'upjv2021');

if(isset($_SESSION['id'])) {
  
   if (isset($_POST['titre']) AND isset($_POST['texte']) AND !isset($_POST['image']) AND empty($_POST['image']['name'])) {
      $titre = htmlspecialchars($_POST['titre']);
      $texte = htmlspecialchars($_POST['texte']);

      $insere = $bdd->prepare('INSERT INTO publication SET titre = ?, texte = ?, pers1 = ?');
      $insere->execute(array($titre, $texte, $_SESSION['id']));
                   
                     // Requete qui permet de nommer l'image avec l'id
      $requete = $bdd->prepare('SELECT id FROM publication WHERE pers1 = ? AND date = (SELECT max(date) FROM publication WHERE pers1 = ? GROUP BY pers1)');
      $requete->execute(array( $_SESSION['id'], $_SESSION['id']));
      $id = $requete->fetch();
     
         $tailleMax = 64000;
         $extensionsValides = array('jpg', 'jpeg', 'png');
         if($_FILES['image']['size'] <= $tailleMax) {
            $extensionUpload = strtolower(substr(strrchr($_FILES['image']['name'], '.'), 1));
            if(in_array($extensionUpload, $extensionsValides)) {
                  $chemin = "image/".$id[0].".".$extensionUpload;
                  $resultat = move_uploaded_file($_FILES['image']['tmp_name'], $chemin);  
                  if($resultat) {
                     $update = $bdd->prepare('UPDATE publication SET image = ? WHERE id = ?');
                     $update->execute(array($id[0].".".$extensionUpload, $id[0]));      
            } else {
               $msg = "Votre image de profil doit être au format jpg, jpeg, gif ou png";
            }
         } else {
               $msg = "Erreur durant l'importation de votre image";
                  }
      } else {
            $msg = "Votre image de profil ne doit pas dépasser 64ko";
      }     
   }
}

$pub = $bdd->prepare('SELECT * FROM user
                        INNER JOIN publication ON user.id = publication.pers1
                        WHERE user.id != ?
                        AND pers1 NOT IN (SELECT id FROM user WHERE prive = 1 )
                        AND user.id IN (SELECT suivi FROM suivre WHERE suiveur = ?)
                        ORDER BY publication.date desc ');

$pub->execute(array($_SESSION['id'],$_SESSION['id']));

if(isset($_GET['recherche']) AND !empty($_GET['recherche'])) {
   $recherche = htmlspecialchars($_GET['recherche']);
   $pub = $bdd->prepare('SELECT * FROM user
                        INNER JOIN publication ON user.id = publication.pers1
                        WHERE user.id != ?
                        AND pers1 NOT IN (SELECT id FROM user WHERE prive = 1 AND id NOT IN (SELECT suiveur FROM suivre WHERE suivi = ?))
                        AND user.id IN (SELECT suivi FROM suivre WHERE suiveur = ?)
                        AND (titre LIKE "%'.$recherche.'%"
                        OR texte LIKE "%'.$recherche.'%"
                        OR username LIKE "%'.$recherche.'%")
                        ORDER BY date desc');
   
   $pub->execute(array($_SESSION['id'],$_SESSION['id'],$_SESSION['id']));
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
                  <a href="FilActuSN.php" class="current">Mon fil d'actualité</a>
                  <a href="messageSN.php">Chat</a>
                  <a href="DeconnexionSN.php">déconnexion</a>
                  
               </nav>
            </div>
         </header>
      </div></div>

      <div class ="principale" align="center">
         <br>
         <br>
        <hr>
        <h3> Publier : </h3>
        <hr>


        <form method="POST" action="" enctype="multipart/form-data">   
               <label>Titre   :</label>
               <input type="text" name="titre" placeholder="titre"/><br/><br/>

               <label>Texte:</label>
               <input type="text" name="texte" placeholder="texte"  /><br/><br/>

               <label>Joindre :</label>
               <input type="file" name="image"/> <br /><br />

                 <input type="submit" value="Publier !!" />
            </form>
       

       <br>
        <hr>
        <h3> Mon fil d'acualité : </h3>
        <hr>

        <br>
        <hr>
        <h3> Rechercher une publication : </h3>
        <hr>
        
         
        <form method="GET">
            <input class = "aime" type="search" name="recherche" placeholder="Recherche" /> 
            <input class = "aime" type="submit" value="Valider" />
         </form>
         <?php if($pub->rowCount() > 0) { ?>
            <ul>
            <?php while($a = $pub->fetch()) { 
               $res = $a['id'];  // identifie un bouton j'aime-(pas)
              $res2 = -$a['id']; ?>
               <li> 
               <p > <?php echo $a['username']."  -  ".$a['date'] ?> </p>
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
                          <input class = 'aime' type='submit' name ='".$res."' value='aimer' ></input><br>
                          soyez le 1er à aimer <br><br>
                          <a href='CommentaireSN.php?ActuPoste=".$res."'>Commentaire</a>
                        </form><br><hr>";
                        $s = 1;
                 }
                 elseif($s == 1){
                    echo "<form method='POST' action=''>
                          <input class = 'aime' type='submit' name ='". $res2 ."' value='ne plus aimer'></input><br>
                          ".$nbJaime[0]." personne(s) aime(s) <br><br>
                           <a href='CommentaireSN.php?ActuPoste=".$res."'>Commentaire</a>
                           </form><br><hr>";
                        $s = 0;
                 }
                
                 //var_dump($j);// $j['poste'];
                 if(isset($_POST[$res])) { 
                    $ajoute = $bdd->prepare("INSERT INTO aime(poste, pers) VALUES(?, ?)");
                    $ajoute->execute(array($a['id'], $_SESSION['id']));
                    header("Location: FilActuSN.php#$res");
                    
                  }

                  if(isset($_POST[$res2])) { 
                    $sup = $bdd->prepare("DELETE FROM aime WHERE pers = ? AND poste = ?");
                    $sup->execute(array($_SESSION['id'], $a['id']));
                    header("Location: FilActuSN.php#$res");
                  }

                 ?>
                 <br/><br/>
               </li>
            <?php } // fin while ?> 
            </ul>
            <?php } else { ?>
           pas de resultat pour la recherche : <?= $recherche ?>
           <br>
            <?php }?>
      </div>
           
         </div>
         <br />
      </div>
   </body>
</html>