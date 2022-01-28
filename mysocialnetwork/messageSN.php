<?php
  session_start();

$bdd = new PDO('mysql:host=localhost;dbname=network', 'upjv', 'upjv2021');

if(isset($_SESSION['id']) AND !empty($_SESSION['id'])) {
   $msg = $bdd->prepare('SELECT * FROM message WHERE recoit = ? ORDER BY id DESC');
   $msg->execute(array($_SESSION['id']));
   $msg_nbr = $msg->rowCount();

      // dernier message recu de chaque pers
   $pers = $bdd->prepare('SELECT *
                          FROM
                            (SELECT
                                  IF( envoi = ?, recoit, envoi ) AS interlocuteur,
                                     MAX( date ) AS max_date
                                  FROM message
                                  WHERE
                                      envoi = ?
                                      OR recoit = ?
                                  GROUP BY IF( envoi = ?, recoit, envoi )
                              ) AS DM
                                  INNER JOIN message M
                                      ON DM.max_date = M.date
                                  INNER JOIN user U
                                      ON DM.interlocuteur = U.id');
   
   $pers->execute(array($_SESSION['id'],$_SESSION['id'],$_SESSION['id'],$_SESSION['id']));

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

         <a href="amisSN.php" >Démarrer nouvelle conversation</a>
         
         <br/><hr> <h3>Dernier messages :</h3><hr><br/>


         <?php
         if($pers->rowCount() > 0) { ?>
            <ul>
            <?php 
            while($a = $pers->fetch()) { 
              if ($a['lu'] == 0 && $a['envoi'] != $_SESSION['id']) { ?>
                  <li>
                 <p style ="font-weight: bold"> <?php echo "recu :  ". $a['username'] ." - ".$a['max_date'] ?> </p>
                 <a href=<?php echo "conversationSN.php?r=".$a['envoi']  ?>> voir conversation</a>
                  <br/><br/>
                  </li>
              <?php 
               }
               else{
               ?>
                <li>
                 <p> <?php 
                 if ($a['recoit'] == $_SESSION['id']) {
                     echo "recu :  " .$a['username'] ." - ".$a['max_date'] ."</p>"; ?>
                     <a href= <?php echo "conversationSN.php?r=".$a['envoi']  ?>> voir conversation </a>
                  <?php } 
                  else{echo "envoyé :  " .$a['username'] . " - ".$a['max(date)'];
                  ?>
                     </p>
                  <a href= <?php echo "conversationSN.php?r=".$a['recoit']  ?>> voir conversation </a>
                  
                  <?php }

                  ?>
                  <br/><br/>
                  </li>             
               
            <?php }}}}// fin while ?> 
            </ul>
       
      </div>
   </body>
</html>