<?php
  session_start();

$bdd = new PDO('mysql:host=localhost;dbname=network', 'upjv', 'upjv2021');

$id = $_SESSION['id'];
//echo $id;
// personne qui me suive

$suivi = $bdd->query('SELECT user.id, nom, username, prenom, photo 
                     FROM user INNER JOIN suivre
                     ON user.id=suivre.suiveur
                     WHERE suivi = '.$id.'');



// personne que je suis

$suis = $bdd->query('SELECT user.id, nom, username, prenom, photo 
                     FROM user INNER JOIN suivre
                     ON user.id=suivre.suivi
                     WHERE suiveur = '.$id.'');

?>

<html>
<head>
   <title>Mes suivis</title>
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
                  <a href= "AmisSN.php" class="current"> Mes amis</a>
                  <a href="FilActuSN.php" >Mon fil d'actualité</a>
                  <a href="messageSN.php">Chat</a>
                  <a href="DeconnexionSN.php">déconnexion</a>
                  
               </nav>
            </div>
         </header>
      </div></div>

   <button type="button" class="btn btn-info"><a style="color: white"href= "AmisSN.php"> retour </a></button>
   <div style="text-align: center;">


   <div class="row g-0" style="text-align: center;">
     <div class="col-sm-6 col-md-6">
        <h3> Me suivent : </h3>

         <?php if($suivi->rowCount() > 0) { ?>
            <ul>
            <?php while($a = $suivi->fetch()) { ?>
               <li> 
               <div class="row g-0">
                 <div class="col-sm-4 col-md-3"> <img src="photo/<?php echo $a['photo']; ?>" width="50px" height = "50px"> </div>
                 <div class="col-4 col-md-3"> <?=  $a['username']  ?>  </div>
                 <div class="col-4 col-md-3"> <a href= <?php echo "VoirProfilSN.php?a=".$a['id']  ?>> voir </a> </div>
               </li>
            <?php } ?>
            </ul>
            <?php } ?>
     </div>
     <div class="col-6 col-md-6">
         <h3> Je suis : </h3>
        
        <?php if($suis->rowCount() > 0) { ?>
            <ul>
            <?php while($a = $suis->fetch()) { ?>
               <li> 
               <div class="row g-0">
                 <div class="col-sm-4 col-md-3"> <img src="photo/<?php echo $a['photo']; ?>" width="50px" height = "50px"> </div>
                 <div class="col-4 col-md-3"> <?=  $a['username']  ?>  </div>
                 <div class="col-4 col-md-3"> <a href= <?php echo "VoirProfilSN.php?a=".$a['id']  ?>> voir </a> </div>
               </li>
            <?php } ?>
            </ul>
            <?php } ?>

     </div>
   </div>  

   </body>
</html>