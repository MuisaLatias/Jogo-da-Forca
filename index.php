<?php
session_start();
$palavras = ['elefante', 'computador', 'programa', 'felicidade', 'amigo', 'aviao', 'musica', 'sol', 'flor', 'nuvem'];
$dicas = [
    'elefante' => ['Maior animal terrestre.'],
    'computador' => ['Máquina essencial para tecnologia moderna.'],
    'programa' => ['Pode ser criado com linguagens como Python ou Java.'],
    'felicidade' => ['Muitos a associam com conquistas ou momentos especiais.'],
    'amigo' => ['Pessoa com quem você compartilha confiança e afeto.'],
    'aviao' => ['Meio de transporte aéreo rápido.'],
    'musica' => ['Arte de combinar sons de forma harmoniosa.'],
    'sol' => ['Estrela central do sistema solar.'],
    'flor' => ['Muitas pessoas adoram receber como presente.'],
    'nuvem' => ['Pode anunciar chuva ou simplesmente embelezar o céu.']
];

if (!isset($_SESSION['palavra'])) {
    $_SESSION['palavra'] = $palavras[array_rand($palavras)];
    $_SESSION['acertos'] = [];
    $_SESSION['erros'] = 0;
}
if (!isset($_SESSION['palavra_atual'])) {
    $_SESSION['palavra_atual'] = $_SESSION['palavra'];
}
if (!isset($_SESSION['dicas_pedidas'])) {
    $_SESSION['dicas_pedidas'] = 0;
}

if (!isset($_SESSION['dicas_usadas'])) {
    $_SESSION['dicas_usadas'] = [];
}

$dica_escolhida = '';

if (isset($_POST['pedir_dica'])) {
    if ($_SESSION['dicas_pedidas'] < 2) {
        $palavra_atual = $_SESSION['palavra_atual'];
        if (isset($dicas[$palavra_atual]) && count($dicas[$palavra_atual]) > 0) {
            $dica_escolhida = array_pop($dicas[$palavra_atual]);
            $_SESSION['dicas_usadas'][] = $dica_escolhida;
            $_SESSION['dicas_pedidas']++;
        }
    }
}

function mostrarPalavra() {
    $palavra = $_SESSION['palavra'];
    $acertos = $_SESSION['acertos'];
    $resultado = ' ';

    foreach (str_split($palavra) as $letra) {
        if (in_array($letra, $acertos)) {
            $resultado .= $letra . ' ';
        } else {
            $resultado .= '_ ';
        }
    }
    return rtrim($resultado);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['letra']) && !empty($_POST['letra'])) {
    $letra = strtolower($_POST['letra']);

    if (strpos($_SESSION['palavra'], $letra) !== false) {
        if (!in_array($letra, $_SESSION['acertos'])) {
            $_SESSION['acertos'][] = $letra;
        }
    } else {
        $_SESSION['erros']++;
    }

    $palavraCompleta = str_replace(' ', '', mostrarPalavra());
    if ($palavraCompleta === $_SESSION['palavra']) {
        $mensagem = "Parabéns! Você acertou a palavra: {$_SESSION['palavra']}";
        session_destroy();
    }

    if ($_SESSION['erros'] >= 5) {
        $mensagem = "Você perdeu! A palavra era: {$_SESSION['palavra']}";
        session_destroy();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogo da Forca!</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to bottom, #F4F1DE, #FFFDF7);
            color: #333333;
            font-family: 'Lato', sans-serif;
            margin: 0;
            text-align: center;
            padding: 20px;
        }

        .letras {
            font-size: 2rem;
            letter-spacing: 0.3rem;
            margin: 20px 0;
            font-family: 'Courier New', monospace;
            color: #81B29A;
            text-align: center;
        }

        .caixinha {
            display: inline-block;
            width: 40px;
            height: 50px;
            border: 2px solid #6D597A;
            background-color: #FFFDF7;
            border-radius: 8px;
            font-size: 24px;
            line-height: 50px;
            margin: 5px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
        }

        .mensagem {
            font-size: 1.2rem;
            color: #E63946;
            margin-top: 10px;
            margin-bottom: 20px;
            word-wrap: break-word;
        }

        button {
            background-color: #81B29A;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 1.2rem;
            transition: background-color 0.3s, transform 0.2s;
        }

        button:hover {
            background-color: #6A994E;
            animation: click 0.3s;
            transform: scale(1.05);
        }

        input[type="text"] {
            padding: 10px;
            border: 2px solid #A8D5BA;
            border-radius: 20px;
            font-size: 1rem;
            margin-top: 10px;
            outline: none;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease-in-out;
        }

        input[type="text"]:focus {
            border-color: #6A994E;
            box-shadow: 0 0 8px rgba(106, 153, 78, 0.6);
        }

        @keyframes click {
            0% {}
            100% {
                transform: scale(0.9);
            }
        }

        h1 {
            color: #6D597A;
            font-size: 3rem;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
            text-align: center;
            font-family: 'Poppins', sans-serif;
        }

        p {
            color: #555;
            font-size: 1.2rem;
            line-height: 1.6;
            font-family: 'Poppins', sans-serif;
        }

        a {
            color: #E0A5F;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            color: #D08B5E;
            text-decoration: underline;
        }

        label {
            font-family: 'Poppins', sans-serif;
            font-size: 1.2rem;
            color: #6D597A;
        }

        .replay-link {
            display: inline-block;
            margin-top: 20px;
            font-family: 'Poppins', sans-serif;
            font-size: 1.2rem;
            font-weight: bold;
            color: #6D597A;
            text-decoration: none;
            padding: 10px 20px;
            border: 2px solid #6D597A;
            border-radius: 10px;
            transition: all 0.3s ease-in-out;
        }

        .replay-link:hover {
            background-color: #6D597A;
            color: #FFFDF7;
            transform: scale(1.1);
        }
        #botaodica{
            margin-top: 5px;s
        }
    </style>
</head>
<body>
    <h1>Jogo da Forca</h1>
    <div class="letras">
        <?php echo mostrarPalavra(); ?>
    </div>
    <p>Erros: <?php echo $_SESSION['erros']; ?>/5</p>

    <?php if (isset($_POST['pedir_dica']) && !empty($dica_escolhida)) { ?>
        <p class="mensagem">Dica: <?php echo $dica_escolhida; ?></p>
    <?php } ?>

    <form action="" method="POST">
        <label for="letra">Digite uma letra:</label><br>
        <input type="text" name="letra" maxlength="1" required><br>
        <button type="submit" id=>Tentar</button><br>
    </form>

    <?php if ($_SESSION['dicas_pedidas'] < 2) { ?>
        <form action="" method="POST">
            <button type="submit" name="pedir_dica" id="botaodica">Pedir Dica</button>
        </form>
    <?php } ?>

    <?php if (isset($mensagem)) { ?>
        <p class="mensagem"><?php echo $mensagem; ?></p>
        <a href="index.php" class="replay-link">Jogar novamente</a>
    <?php } ?>
</body>
</html>
