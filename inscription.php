<?php
    
    /* page: inscription.php */
//connexion à la base de données:
$BDD = array();
$BDD['host'] = "localhost";
$BDD['user'] = "root";
$BDD['pass'] = "";
$BDD['db'] = "user";
$mysqli = mysqli_connect($BDD['host'], $BDD['user'], $BDD['pass'], $BDD['db']);
if(!$mysqli) {
    echo "Connexion non établie.";
    exit;
}
    //création automatique de la table utilisateur, une fois créée, vous pouvez supprimer les lignes de code suivantes:
    //la table est créée avec les paramètres suivants:
    //champ "id": en auto increment pour un id unique, peux vous servir pour une identification future
    //champ "nom_utilisateur": en varchar de 0 à 25 caractères
    //champ "mot_de_passe": en char fixe de 32 caractères, soit la longueur de la fonction md5()
    //fin création automatique
//par défaut, on affiche le formulaire (quand il validera le formulaire sans erreur avec l'inscription validée, on l'affichera plus)
$AfficherFormulaire=1;
//traitement du formulaire:
if(isset($_POST['nom_utilisateur'],$_POST['mot_de_passe'])){//l'utilisateur à cliqué sur "S'inscrire", on demande donc si les champs sont défini avec "isset"
    if(empty($_POST['nom_utilisateur'])){//le champ nom_utilisateur est vide, on arrête l'exécution du script et on affiche un message d'erreur
        echo "Le champ nom_utilisateur est vide.";
    } elseif(!preg_match("#^[a-z0-9]+$#",$_POST['nom_utilisateur'])){//le champ nom_utilisateur est renseigné mais ne convient pas au format qu'on souhaite qu'il soit, soit: que des lettres minuscule + des chiffres (je préfère personnellement enregistrer le nom_utilisateur de mes utilisateur en minuscule afin de ne pas avoir deux nom_utilisateur identique mais différents comme par exemple: Admin et admin)
        echo "Le nom_utilisateur doit être renseigné en lettres minuscules sans accents, sans caractères spéciaux.";
    } elseif(strlen($_POST['nom_utilisateur'])>25){//le nom_utilisateur est trop long, il dépasse 25 caractères
        echo "Le nom_utilisateur est trop long, il dépasse 25 caractères.";
    } elseif(empty($_POST['mot_de_passe'])){//le champ mot de passe est vide
        echo "Le champ Mot de passe est vide.";
    } elseif(mysqli_num_rows(mysqli_query($mysqli,"SELECT * FROM utilisateur WHERE nom_utilisateur='".$_POST['nom_utilisateur']."'"))==1){//on vérifie que ce nom_utilisateur n'est pas déjà utilisé par un autre membre
        echo "Ce nom_utilisateur est déjà utilisé.";
    } else {
        //toutes les vérifications sont faites, on passe à l'enregistrement dans la base de données:
        //Bien évidement il s'agit là d'un script simplifié au maximum, libre à vous de rajouter des conditions avant l'enregistrement comme la longueur minimum du mot de passe par exemple
        if(!mysqli_query($mysqli,"INSERT INTO utilisateur SET nom_utilisateur='".$_POST['nom_utilisateur']."', mot_de_passe='".md5($_POST['mot_de_passe'])."'")){//on crypte le mot de passe avec la fonction propre à PHP: md5()
            echo "Une erreur s'est produite: ".mysqli_error($mysqli);//je conseille de ne pas afficher les erreurs aux visiteurs mais de l'enregistrer dans un fichier log
        } else {
            echo "Vous êtes inscrit avec succès !";
            //on affiche plus le formulaire
            $AfficherFormulaire=0;
        }
    }
}
if($AfficherFormulaire==1){
    ?>
    <!-- 
    Les balises <form> sert à dire que c'est un formulaire
    on lui demande de faire fonctionner la page inscription.php une fois le bouton "S'inscrire" cliqué
    on lui dit également que c'est un formulaire de type "POST"
     
    Les balises <input> sont les champs de formulaire
    type="text" sera du texte
    type="password" sera des petits points noir (texte caché)
    type="submit" sera un bouton pour valider le formulaire
    name="nom de l'input" sert à le reconnaitre une fois le bouton submit cliqué, pour le code PHP
     -->
     <html>
     <head>
         <meta charset="UTF-8">
         <meta http-equiv="X-UA-Compatible" content="IE=edge">
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
         <title>Inscription</title>
     </head>
     <body class=".body">
     <form method="post" action="inscription.php">
        nom_utilisateurnyme : <input  class="form-control" type="text" name="nom_utilisateur">
        <br />
        Mot de passe : <input class="form-control" type="password" name="mot_de_passe">
        <br />
        <button class="btn btn-success m-1" type="submit" value="S'inscrire" href="http://127.0.0.1:5500/index3.html">S'inscrire</button >
    </form>
     </body>
     </html>
     <script>
setTimeout(function(){
windows.location = "http://127.0.0.1:5500/index3.html";
}, 1000);
</script>

    <?php
}
?>