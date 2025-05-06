<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Lady-PHP' ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <header>
        <h1><?= $title ?? 'Lady-PHP' ?></h1>
    </header>
    
    <main>
        <p><?= $message ?? '' ?></p>
    </main>
    
    <footer>
        <p>&copy; <?= date('Y') ?> Lady-PHP</p>
    </footer>
</body>
</html>