<?php
  session_start();

$bdd = new PDO('mysql:host=localhost;dbname=network', 'upjv', 'upjv2021');

if(isset($_SESSION['id']) AND !empty($_SESSION['id'])) {
  $r = $_GET['r'];

      // conversation
   $mess = $bdd->prepare('SELECT * FROM message WHERE (envoi = ? AND  recoit = ?) OR (envoi = ? AND  recoit = ?) ORDER BY date desc');
   $mess->execute(array($_SESSION['id'],$r,$r,$_SESSION['id']));

    // marque le message comme lu
   $vu = $bdd->prepare('UPDATE message SET lu = 1 WHERE (envoi = ? AND  recoit = ?) OR (envoi = ? AND  recoit = ?)');
   $vu->execute(array($_SESSION['id'],$r,$r,$_SESSION['id']));

   // récupère nom interlocuteur
  $nom = $bdd->prepare('SELECT * FROM user WHERE id = ?');
  $nom->execute(array($r));
  $nom = $nom->fetch();

  if(isset($_POST['envoi'])) {
    $envoyer = $bdd->prepare('INSERT INTO message(envoi, recoit, message) VALUES (?,?,?)');
    $envoyer->execute(array($_SESSION['id'],$r, $_POST['message']));
    header("Refresh:0");
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
                  <a href="messageSN.php" class="current">Chat</a>
                  <a href="DeconnexionSN.php">déconnexion</a>
                  
               </nav>
            </div>
         </header>
      </div></div>

      <div class ="principale" align="center">

         <br/><hr> <h3>Envoyer message à <?php echo $nom['username'] ?> :</h3><hr><br/>

         <form method="POST"> 
         <textarea placeholder="Votre message" name="message"></textarea><br><br>
         <input type="submit" value="Envoyer" name="envoi" />
         <br/>
      </form>
      <br/>

      <br/><hr> <h3>Conversation avec <?php echo $nom['username'] ?> :</h3><hr><br/>


         <?php
            if($mess->rowCount() > 0) { ?>
            <ul>
            <?php 
            while($a = $mess->fetch()) { 
              ?>
               <li>
                  <?php 
                  if ($a['envoi'] != $_SESSION['id']) { ?>
                   <p class="bulle1" ><br> <?php echo $a['message']  ?> <br> -----</p>
                   <p style="text-align: right"> <?php echo  $a['date'] ?> </p>
                 
                 <?php 
                 }
                 else{ ?>
                  
                  <p class="bulle2"> <br> <?php echo $a['message']."<br>"  ?>  ------</p>
                  <p style="text-align: left">  <?php echo  $a['date']."<br>" ?> </p>
                 
                 <?php } ?>
                 
                 <br/>
               </li>
            <?php }}} // fin while ?> 
            </ul>

         
               
            
       
      </div>
   </body>
</html>