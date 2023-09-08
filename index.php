<?php
require_once((__DIR__ . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "Message.php"));
require_once((__DIR__ . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "GuestBook.php"));
require_once((__DIR__ . DIRECTORY_SEPARATOR . "Functions" . DIRECTORY_SEPARATOR . "validationForm"));
/* Gestion des erreurs */
$error = null;
$state = null;
/** Déclaration du fichier */
$file = __DIR__ . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "messages";
$guestbook = new GuestBook($file);

if (isset($_POST['pseudo'], $_POST['message'])) {
    $pseudo = $_POST['pseudo'];
    $userMessage = $_POST['message'];
    $message = new Message($pseudo, $userMessage);

    if ($message->isValid()) {
        $guestbook->addMessage($message);
        $state = "success";
        $errors[] = "Merci Pour Votre message";
        $pseudo = null;
        $userMessage = null;
    } else {
        $errors = $message->getErrors();
        $state = "danger";
    }
}
if(filesize($file) > 0){
    $messages = $guestbook->getMessage();
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Livre d'Or</title>
</head>

<body>
    <div class="row my-3">
        <div class="col-md-6 mx-auto bg-dark rounded shadow-lg">
            <h1 class="text-primary text-center my-2">Livre d'Or</h1>
            <div class="container-fluid">
                <form action="" method="post">
                    <div class="alert alert-<?= $state ?>">
                        <?php if (isset($errors)) : ?>
                            <?php foreach ($errors as $error) : ?>
                                <strong>
                                    <li><?= $error ?></li>
                                </strong>
                            <?php endforeach ?>
                        <?php endif ?>
                    </div>
                    <div class="form-floating my-2 has-validation">
                        <input type="text" name="pseudo" id="pseudo" class="form-control <?= $validationFeedBackState ?>" placeholder="Nom d'Utilisateur" <?php if (isset($pseudo)) : ?> value="<?= htmlentities($pseudo) ?>" <?php endif ?>>
                        <label for="pseudo">Nom d'Utilisateur</label>
                        
                    </div>
                    <div class="form-floating my-2">
                        <textarea name="message" id="message" class="form-control" placeholder="Message"><?php if (isset($userMessage)) : echo htmlentities($userMessage) ?><?php endif ?></textarea>
                        <label for="message">Message</label>
                    </div>
                    <!-- Button trigger modal -->
                    <button type="submit" id="btn" class="btn btn-outline-success mt-3
                    float-end my-2">Envoyer le message</button>
                </form>
            </div>
        </div>

        <?php if (!empty($messages)) : ?>
            <div class="col-md-10 mx-auto text-primary">
                <h2>Messages :</h2>
                <?php foreach ($messages as $message) : ?>
                    <?= $message->toHTML() ?>
                <?php endforeach ?>
            </div>
        <?php endif ?>
    </div>









    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>