<?php
$bdd = new PDO('mysql:host=localhost;dbname=network', 'upjv', 'upjv2021');
 
if(isset($_POST['inscription'])) { 
   $username = htmlspecialchars($_POST['username']);
   $email = htmlspecialchars($_POST['email']);
   $password = $_POST['password'];
   $password2 = $_POST['password2'];
   $photoBase = "base.png"; // photo que tout le monde aura de base.
   if(!empty($_POST['username']) AND !empty($_POST['email']) AND  !empty($_POST['password']) AND  !empty($_POST['password2'])) {
         
         if(filter_var($email, FILTER_VALIDATE_EMAIL)) { //vérifi mail
            $mail = $bdd->prepare("SELECT * FROM user WHERE email = ?");
            $mail->execute(array($email));
            $existe = $mail->rowCount();
            
            if($existe == 0) {
               if($password == $password2) {                     
                  $insere = $bdd->prepare("INSERT INTO user(username, email, password, photo) VALUES(?, ?, ?,?)");
                  $insere->execute(array($username, $email, $password,$photoBase));
                  $message = "Votre compte a bien été créé ! <a href=\"index.php\">Me connecter</a>";
                }
               else {
                     $message = "Vos mots de passes ne correspondent pas !";
               }
            }
            else {
                  $message = "Adresse mail déjà utilisée !";
               }
         }
         else {
               $message = "Votre adresse mail n'est pas valide !";
         }
      }
else {
   $message = "Tous les champs doivent être complétés !";
}
} 


?>

<html>
   <head>
      <title>Inscription</title>
      <meta charset="utf-8">
      <link rel="stylesheet" type="text/css" href="style.css">

   </head>
   <body>
      <div class="principale" align="center">
         <br>
         <img src="SN.png" height="300px" width="300px" />
         <br>
         <form method="POST" action="">

                        <input type="text" placeholder="Votre pseudo" id="username" name="username"  />
                        <br>
                  
                        <input type="email" placeholder="Votre mail" id="email" name="email" />
                        <br>
                     
                        <input type="password" placeholder="Votre mot de passe" id="password" name="password" />
                        <br>

                        
                        <input type="password" placeholder="Confirmation" id="password2" name="password2" />
                        <br>
                     
                        <br/>
                        <input type="submit" name="inscription" value="Je m'inscris" />
         </form>
         <?php
         if(isset($message)) {
            echo '<font color="red">'.$message."</font>";
         }
         echo "<br><br> <p> Vous avez deja un compte ?</p> <a href=\"index.php\">Je me connecte</a>";
         ?>
      </div>
   </body>
</html>