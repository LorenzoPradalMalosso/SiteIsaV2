<?php
// Edite os textos abaixo para transformar a página na carta de vocês.
$pageTitle = "Mensagens para você";
$pageSubtitle = "Um cantinho para guardar palavras que eu quero que você leia sempre que sentir saudade.";
$mainMessageTitle = "Minha carta principal";
$mainMessage = "Como começar, meu amor, é simplesmente impossível te dizer tudo que sinto em uma c arta só, porque esse amor que eu sinto por você é simplesmente maior do que qualquer coisa nesse universo. Você é simplesmente tudo que eu penso o tempo todo, e parece que nada nunca vai ser suficiente de descrever o quanto eu te amo, e como você é tudo pra mim. Mas acho que pra tudo isso, primeiro tenho que te agradecer por simplesmente ser quem você é, por ser tão carinhosa, atenciosa, gentil, perfeita e maravilhosa pra mim, você é tudo que eu sempre pedi a Deus poder ter um dia. Você é a melhor namorada e companheira que alguém poderia ter nesse mundo, e eu me sinto a pessoa mais sortuda desse mundo por poder ser essa pessoa sortuda, você não faz ideia do quão feliz me faz todos os dias, porque eu te digo que é você que me dá forças todos os dias pra levantar, e enfrentar os desafios da vida, pra ser cada vez melhor pra você e pra gente realizar todos os nossos sonhos.";

$messages = [
    [
        "title" => "Quando eu penso em nós",
        "text" => "Pra ser sincero, não há um momento que eu não esteja pensando em nós dois, e em como eu queria estar grudadinho na minha princesa todo segundinho da minha vida, e confesso que a coisa mais difícil do mundo é ter que esperar uma semana toda pra te ver, eu vivo pelos finais de semana, porque é quando posso estar juntinho de você e te encher de carinho, abraços, e muuuuuitoss beijinhos!!",
    ],
    [
        "title" => "O que eu amo em você",
        "text" => "Falar o que eu amo em você é facil, e impossível falar algo que eu não ame em você. O seu jeito me fascina, sempre preocupada com todos a sua volta, gentil com todas as pessoas, quem quer que elas sejam, uma namorada extremamente amorosa, carinhosa, e tudo o que há de melhor em uma pessoa. A sua beleza me encanta de uma forma surreal, porque eu ficaria minha vida toda com uma únicavisão, e essa visão seria você, eu sou completamente apaixonado por cada detalhezinho seu, eu poderia falar por horas sobre tudo o que eu amo em você, e ainda assim não chegaria a nem 1% de todo o amor que sinto por você.",
    ],
    [
        "title" => "Para o nosso futuro",
        "text" => "Eu não vejo a hora de poder viver grudado em você, meu amor, isso é literalmente tudo que eu mais quero nesse mundo, e a única coisa que penso o tempo todo, porque só consigo imginar no quão bom vai ser dormir e acordar todos os dias ao seu lado com um beijo seu. Fora isso, não vejo a hora de realizarmos todos os nossos sonhos juntos, não vejo a hora de estarmos viajando o mundo toooodo, e conhecendo cada lugarzinho dele, não vejo a hora de estarmos decorando nossa casa juntos, não vejo a hora de estar te esperando em cima de um altar, e você com um vestido branco lindo vindo na minha direção, não vejo a hora de termos a nossa família, com nossos filhos, e todos os nossos animaizinhos pela casa!!!",
    ]
];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensagens</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/script.js" defer></script>
    <style>
        .letter-panel {
            padding: clamp(22px, 4vw, 40px);
            margin-bottom: 20px;
        }

        .letter-panel h2 {
            margin-bottom: 14px;
            font-size: clamp(1.45rem, 3vw, 2.1rem);
            letter-spacing: 0;
        }

        .letter-panel p,
        .message-card p {
            font-size: 1.02rem;
            line-height: 1.75;
        }

        .messages-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
        }

        .message-card {
            min-height: 250px;
            display: flex;
            flex-direction: column;
            padding: 22px;
        }

        .message-tag {
            width: fit-content;
            margin-bottom: 14px;
            padding: 6px 10px;
            border-radius: 999px;
            color: #fff;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            font-size: .8rem;
            font-weight: 700;
        }

        .message-card h3 {
            margin-bottom: 12px;
            font-size: 1.25rem;
            letter-spacing: 0;
        }

        @media (max-width: 900px) {
            .messages-grid {
                grid-template-columns: 1fr;
            }

            .message-card {
                min-height: auto;
            }
        }
    </style>
</head>

<body class="content-page">
    <?php include "../fragmentos/header.html"; ?>

    <main class="romantic-main">
        <section class="romantic-hero">
            <h1><?php echo htmlspecialchars($pageTitle); ?></h1>
            <p><?php echo htmlspecialchars($pageSubtitle); ?></p>
        </section>

        <section class="romantic-panel letter-panel">
            <h2><?php echo htmlspecialchars($mainMessageTitle); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($mainMessage)); ?></p>
        </section>

        <section aria-label="Mensagens especiais">
            <h2 class="section-title">Pequenos bilhetes</h2>
            <div class="messages-grid">
                <?php foreach ($messages as $message): ?>
                    <article class="message-card">
                        <h3><?php echo htmlspecialchars($message["title"]); ?></h3>
                        <p><?php echo nl2br(htmlspecialchars($message["text"])); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <canvas id="background"></canvas>
</body>
</html>
