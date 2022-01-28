	<?php
session_start();
 
$bdd = new PDO('mysql:host=localhost;dbname=network', 'upjv', 'upjv2021');

if(isset($_POST['connexion'])) {
   $email = htmlspecialchars($_POST['email']);
   $password = $_POST['password'];
   if(!empty($email) AND !empty($password)) {
      $requete = $bdd->prepare("SELECT * FROM user WHERE email = ? AND password = ?");
      $requete->execute(array($email, $password));
      $res = $requete->rowCount();
      if($res == 1) {
         $userinfo = $requete->fetch();
         $_SESSION['id'] = $userinfo['id'];
         $_SESSION['username'] = $userinfo['username'];
         $_SESSION['email'] = $userinfo['email'];
         $_SESSION['nom'] = $userinfo['nom'];
         $_SESSION['prenom'] = $userinfo['prenom'];
         header("Location: MonProfilSN.php?id=".$_SESSION['id']);
      }
      else {
         $message = "mail ou mot de passe incorrect !";
      }

   }
   else {
         $message = "veuillez renseigner tout les champs !";
      }
   
}
?>
<html><head>
      <title>Connexion</title>
      <link rel="stylesheet" type="text/css" href="style.css">
      <meta charset="utf-8">
   </head>
   
   <body>
      <div class="principale" align="center"  >
         
         <img src="SN.png" height="300px" width="300px" />
         <br /><br />
         <form method="POST" action="">
            <input type="email" name="email" placeholder="Mail" />
            <input type="password" name="password" placeholder="Mot de passe" />
            <br /><br />
            <input type="submit" name="connexion" value="Se connecter !" />
         </form>
         <?php
         if(isset($message)) {
            echo '<font color="red">'.$message."</font>";
         }
         echo "<p> Vous n'avez pas encore de compte ?</p> <a href=\"InscriptionSN.php\"> Inscription</a>";
         ?>
      </div>
   </body>
</html>