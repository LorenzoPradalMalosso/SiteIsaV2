<?php
// Cole aqui os links normais do YouTube ou links no formato embed.
$youtubeLinks = [
    "https://www.youtube.com/watch?v=b8m9zhNAgKs",
    "https://www.youtube.com/watch?v=Q6-tYpWp0Ck",
    "https://www.youtube.com/watch?v=Zs0tcD7LpuI",
    "https://www.youtube.com/watch?v=sRxrwjOtIag",
    "https://www.youtube.com/watch?v=X0Jti9F-oQA",
    "https://www.youtube.com/watch?v=6hzrDeceEKc",
    "https://www.youtube.com/watch?v=JocAXINz-YE",
    "https://www.youtube.com/watch?v=eCGV26aj-mM"

];

$musicTitles = [
    "Nossa música 1",
    "Nossa música 2",
    "Nossa música 3",
    "Nossa música 4",
    "Nossa música 5",
    "Nossa música 6",
    "Nossa música 7",
    "Nossa música 8"
];

$spotifyPlaylistUrl = "https://open.spotify.com/playlist/01VpdwM6BWuV04HoPnjAIX?si=9M5MjICgRm-HTmQbb-WTHQ";

function youtubeEmbedUrl($url) {
    if (strpos($url, "COLE_AQUI") !== false || trim($url) === "") {
        return "";
    }

    if (strpos($url, "/embed/") !== false) {
        return $url;
    }

    $parts = parse_url($url);

    if (!empty($parts["host"]) && strpos($parts["host"], "youtu.be") !== false) {
        return "https://www.youtube.com/embed/" . ltrim($parts["path"], "/");
    }

    if (!empty($parts["query"])) {
        parse_str($parts["query"], $query);

        if (!empty($query["v"])) {
            return "https://www.youtube.com/embed/" . $query["v"];
        }
    }

    return "";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Músicas</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/script.js" defer></script>
    <style>
        .music-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 16px;
        }

        .spotify-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            min-height: 44px;
            padding: 0 16px;
            border-radius: 999px;
            color: #fff;
            background: #1db954;
            text-decoration: none;
            font-weight: 800;
            box-shadow: 0 16px 34px rgba(29, 185, 84, .28);
            transition: transform .2s ease, box-shadow .25s ease;
        }

        .spotify-link:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 40px rgba(29, 185, 84, .34);
        }

        .spotify-link svg {
            width: 22px;
            height: 22px;
            flex: 0 0 auto;
            fill: currentColor;
        }

        .music-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .music-card {
            overflow: hidden;
        }

        .music-card h3 {
            padding: 16px 16px 12px;
            font-size: 1.06rem;
            letter-spacing: 0;
        }

        .music-frame,
        .music-placeholder {
            width: 100%;
            aspect-ratio: 16 / 9;
            display: block;
            border: 0;
            background: rgba(0, 0, 0, .18);
        }

        .music-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 18px;
            color: var(--button-text);
            text-align: center;
            font-weight: 700;
            line-height: 1.4;
        }

        @media (max-width: 820px) {
            .music-top {
                align-items: flex-start;
                flex-direction: column;
            }

            .music-grid {
                grid-template-columns: 1fr;
            }

            .spotify-link {
                width: 100%;
            }
        }
    </style>
</head>

<body class="content-page">
    <?php include "../fragmentos/header.html"; ?>

    <main class="romantic-main">
        <section class="romantic-hero">
            <h1>Músicas que lembram nós dois</h1>
            <p>Seis espaços para guardar clipes, versões ao vivo ou músicas que tenham a cara da história de vocês.</p>
        </section>

        <section aria-label="Músicas especiais">
            <div class="music-top">
                <h2 class="section-title">Nossa playlist</h2>
                <a class="spotify-link" href="<?php echo htmlspecialchars($spotifyPlaylistUrl); ?>" target="_blank" rel="noopener">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 1.8A10.2 10.2 0 1 0 12 22.2 10.2 10.2 0 0 0 12 1.8Zm4.68 14.72a.63.63 0 0 1-.86.21c-2.36-1.44-5.33-1.77-8.83-.97a.63.63 0 1 1-.28-1.23c3.83-.88 7.12-.49 9.76 1.13.3.18.39.57.21.86Zm1.25-2.79a.78.78 0 0 1-1.07.26c-2.7-1.66-6.82-2.14-10.01-1.17a.78.78 0 1 1-.45-1.49c3.64-1.1 8.18-.57 11.27 1.33.37.22.49.7.26 1.07Zm.11-2.91C14.8 8.89 9.45 8.7 6.36 9.66a.94.94 0 0 1-.56-1.79c3.55-1.11 9.46-.88 13.21 1.34a.94.94 0 1 1-.97 1.61Z"/>
                    </svg>
                    Abrir no Spotify
                </a>
            </div>

            <div class="music-grid">
                <?php foreach ($youtubeLinks as $index => $youtubeLink): ?>
                    <?php $embedUrl = youtubeEmbedUrl($youtubeLink); ?>
                    <article class="music-card">
                        <h3><?php echo htmlspecialchars($musicTitles[$index] ?? "Nossa música"); ?></h3>
                        <?php if ($embedUrl): ?>
                            <iframe class="music-frame" src="<?php echo htmlspecialchars($embedUrl); ?>" title="<?php echo htmlspecialchars($musicTitles[$index] ?? "Música"); ?>" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                        <?php else: ?>
                            <div class="music-placeholder">Cole um link do YouTube no topo deste arquivo para ativar este frame.</div>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <canvas id="background"></canvas>
</body>
</html>
