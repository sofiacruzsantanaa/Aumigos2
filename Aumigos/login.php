<?php 

include_once 'includes/header.php' ?> 
<link rel="stylesheet" href="assets/css/login.css">
 <h2>Entre em sua conta</h2>
<main> 
  

  <form action="controllers/con_login.php" method="post"> 
    <input type="text" name="usuario" placeholder="usuario">
     <input type="password" name="senha" id="" placeholder="senha"> 
     <input type="submit" value="Entrar">
    
  </form> </main> 






<?php if (isset($_GET['erro'])): ?>
    <p style="color:red; text-align:center;">Email ou senha incorretos!</p>
<?php endif; ?>


<?php include_once 'includes/footer.php' ?>