<?php
$imageFiles = glob(__DIR__ . "/../img/*.{jpg,jpeg,png,webp,gif}", GLOB_BRACE);

natsort($imageFiles);
$images = array_values(array_map(function ($image) {
    return "../img/" . basename($image);
}, $imageFiles));

// Configure aqui o link do vídeo do YouTube.
// Exemplo: https://www.youtube.com/watch?v=ABC123XYZ ou https://youtu.be/ABC123XYZ
$youtubeUrl = "https://www.youtube.com/watch?v=JJJJ434f3S4";

function getYoutubeEmbedUrl($url) {
    $url = trim($url);
    if ($url === "") {
        return "";
    }

    $parsed = parse_url($url);
    if (!isset($parsed['host'])) {
        return $url;
    }

    $host = strtolower($parsed['host']);
    $query = [];
    if (isset($parsed['query'])) {
        parse_str($parsed['query'], $query);
    }

    $videoId = "";
    if (strpos($host, 'youtu.be') !== false) {
        $videoId = ltrim($parsed['path'] ?? '', '/');
    } elseif (strpos($host, 'youtube.com') !== false || strpos($host, 'youtube-nocookie.com') !== false) {
        if (!empty($query['v'])) {
            $videoId = $query['v'];
        } else {
            $path = trim($parsed['path'] ?? '', '/');
            $parts = explode('/', $path);
            if (isset($parts[0]) && in_array($parts[0], ['embed', 'v', 'shorts']) && isset($parts[1])) {
                $videoId = $parts[1];
            }
        }
    }

    if ($videoId !== "") {
        return "https://www.youtube.com/embed/" . rawurlencode($videoId) . "?rel=0";
    }

    return $url;
}

$video = !empty($youtubeUrl) ? getYoutubeEmbedUrl($youtubeUrl) : "";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeria</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/script.js" defer></script>
    <style>
        body.gallery-page {
            overflow-x: hidden;
            overflow-y: auto;
        }

        .gallery-main {
            position: relative;
            z-index: 1;
            width: min(1180px, calc(100% - 32px));
            margin: 0 auto;
            padding: 24px 0 56px;
            color: var(--button-text);
        }

        .gallery-loader {
            min-height: 260px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 14px;
            color: var(--button-text);
            font-weight: 800;
            text-align: center;
        }

        .gallery-spinner {
            width: 46px;
            height: 46px;
            flex: 0 0 auto;
            border: 4px solid rgba(255, 255, 255, .22);
            border-top-color: var(--accent);
            border-radius: 50%;
            animation: gallery-spin .85s linear infinite;
        }

        body.light .gallery-spinner {
            border-color: rgba(32, 45, 90, .16);
            border-top-color: var(--accent);
        }

        .gallery-content {
            opacity: 1;
            transition: opacity .35s ease;
        }

        .gallery-main.is-loading .gallery-content {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        .gallery-main:not(.is-loading) .gallery-loader {
            display: none;
        }

        @keyframes gallery-spin {
            to {
                transform: rotate(360deg);
            }
        }

        .gallery-slider {
            position: relative;
            height: clamp(280px, 54vw, 520px);
            margin-bottom: 18px;
            overflow: hidden;
            border-radius: 8px;
            background: rgba(255, 255, 255, .08);
            box-shadow: 0 22px 70px rgba(0, 0, 0, .34);
        }

        body.light .gallery-slider {
            background: rgba(255, 255, 255, .48);
            box-shadow: 0 22px 60px rgba(24, 36, 76, .18);
        }

        .gallery-slide {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transform: scale(1.02);
            transition: opacity .8s ease, transform 1.2s ease;
        }

        .gallery-slide.active {
            opacity: 1;
            transform: scale(1);
            z-index: 1;
        }

        .gallery-slide img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: contain;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
        }

        .gallery-grid {
            column-count: 4;
            column-gap: 8px;
        }

        .gallery-item {
            display: block;
            width: 100%;
            margin: 0 0 8px;
            overflow: hidden;
            break-inside: avoid;
            border-radius: 4px;
            background: rgba(255, 255, 255, .08);
            box-shadow: 0 10px 30px rgba(0, 0, 0, .26);
        }

        body.light .gallery-item {
            background: rgba(255, 255, 255, .44);
            box-shadow: 0 10px 26px rgba(24, 36, 76, .12);
        }

        .gallery-item img {
            height: auto;
            transition: transform .35s ease, filter .35s ease;
        }

        .gallery-item:hover img {
            transform: scale(1.035);
            filter: saturate(1.06);
        }

        .gallery-empty {
            color: var(--button-text);
            padding: 40px 20px;
            text-align: center;
            font-weight: 700;
        }

        .gallery-lightbox {
            position: fixed;
            inset: 0;
            z-index: 30;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 72px 18px 24px;
            background: rgba(4, 8, 18, .88);
            backdrop-filter: blur(12px);
        }

        .gallery-lightbox.open {
            display: flex;
        }

        .lightbox-stage {
            width: min(100%, 1120px);
            height: min(78vh, 760px);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: auto;
            border-radius: 8px;
            cursor: grab;
        }

        .lightbox-stage:active {
            cursor: grabbing;
        }

        .lightbox-image {
            max-width: 100%;
            max-height: 100%;
            display: block;
            object-fit: contain;
            transform: scale(1);
            transform-origin: center center;
            transition: transform .18s ease;
            user-select: none;
        }

        .lightbox-close,
        .lightbox-zoom {
            position: fixed;
            z-index: 31;
            width: 44px;
            height: 44px;
            border: 0;
            border-radius: 50%;
            color: #111;
            background: rgba(255, 255, 255, .92);
            box-shadow: 0 12px 28px rgba(0, 0, 0, .28);
            cursor: pointer;
            font-size: 24px;
            font-weight: 700;
            line-height: 1;
        }

        .lightbox-close {
            top: 18px;
            right: 18px;
        }

        .lightbox-controls {
            position: fixed;
            left: 50%;
            bottom: 20px;
            z-index: 31;
            display: flex;
            gap: 10px;
            transform: translateX(-50%);
        }

        .lightbox-zoom {
            position: static;
        }

        body.lightbox-lock {
            overflow: hidden;
        }

        .gallery-video-button {
            position: fixed;
            right: 18px;
            bottom: 18px;
            z-index: 20;
            width: 54px;
            height: 54px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 50%;
            color: #111;
            background: rgba(255, 255, 255, .94);
            box-shadow: 0 14px 34px rgba(0, 0, 0, .3);
            cursor: pointer;
            font-size: 24px;
            line-height: 1;
        }

        .gallery-video-button:hover {
            transform: translateY(-2px);
        }

        .gallery-video-modal {
            position: fixed;
            inset: 0;
            z-index: 34;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 72px 18px 24px;
            background: rgba(4, 8, 18, .88);
            backdrop-filter: blur(12px);
        }

        .gallery-video-modal.open {
            display: flex;
        }

        .gallery-video {
            width: min(100%, 980px);
            height: min(78vh, 620px);
            max-height: 78vh;
            border-radius: 8px;
            background: #000;
            box-shadow: 0 22px 70px rgba(0, 0, 0, .34);
        }

        .video-close {
            position: fixed;
            top: 18px;
            right: 18px;
            z-index: 35;
            width: 44px;
            height: 44px;
            border: 0;
            border-radius: 50%;
            color: #111;
            background: rgba(255, 255, 255, .92);
            box-shadow: 0 12px 28px rgba(0, 0, 0, .28);
            cursor: pointer;
            font-size: 24px;
            font-weight: 700;
            line-height: 1;
        }

        @media (max-width: 980px) {
            .gallery-grid {
                column-count: 3;
            }
        }

        @media (max-width: 760px) {
            .gallery-main {
                width: min(100% - 18px, 560px);
                padding-top: 12px;
            }

            .gallery-slider {
                height: clamp(260px, 86vw, 430px);
                margin-bottom: 8px;
            }

            .gallery-grid {
                column-count: 2;
                column-gap: 5px;
            }

            .gallery-item {
                margin-bottom: 5px;
                border-radius: 3px;
            }

            .gallery-lightbox {
                padding: 66px 10px 78px;
            }

            .lightbox-stage {
                height: 72vh;
            }

            .gallery-video-button {
                right: 14px;
                bottom: 14px;
                width: 50px;
                height: 50px;
            }
        }
    </style>
</head>

<body class="gallery-page">
    <?php include "../fragmentos/header.html"; ?>

    <main class="gallery-main <?php echo !empty($images) ? "is-loading" : ""; ?>" aria-label="Galeria de fotos" <?php echo !empty($images) ? 'aria-busy="true"' : ""; ?>>
        <section class="romantic-hero">
            <h1>Galeria de lembranças</h1>
            <p>Um cantinho para guardar fotos, detalhes e pedaços bonitos da nossa história.</p>
        </section>

        <?php if (!empty($images)): ?>
            <div class="gallery-loader" id="gallery-loader" role="status" aria-live="polite">
                <span class="gallery-spinner" aria-hidden="true"></span>
                <span>Carregando fotos...</span>
            </div>
        <?php endif; ?>

        <div class="gallery-content" id="gallery-content">
        <?php if (!empty($images)): ?>
            <section class="gallery-slider" aria-label="Fotos em destaque">
                <?php foreach ($images as $index => $image): ?>
                    <figure class="gallery-slide <?php echo $index === 0 ? "active" : ""; ?>">
                        <img src="<?php echo htmlspecialchars($image); ?>" alt="Foto em destaque <?php echo $index + 1; ?>" <?php echo $index === 0 ? 'loading="eager"' : 'loading="lazy"'; ?>>
                    </figure>
                <?php endforeach; ?>
            </section>

            <section class="gallery-grid" aria-label="Todas as fotos">
                <?php foreach ($images as $index => $image): ?>
                    <a class="gallery-item" href="<?php echo htmlspecialchars($image); ?>" data-lightbox-image="<?php echo htmlspecialchars($image); ?>">
                        <img src="<?php echo htmlspecialchars($image); ?>" alt="Foto da galeria <?php echo $index + 1; ?>" loading="lazy">
                    </a>
                <?php endforeach; ?>
            </section>
        <?php else: ?>
            <p class="gallery-empty">Nenhuma foto encontrada em assets/img.</p>
        <?php endif; ?>
        </div>
    </main>

    <div class="gallery-lightbox" id="gallery-lightbox" aria-hidden="true">
        <button class="lightbox-close" type="button" aria-label="Fechar foto">x</button>
        <div class="lightbox-stage">
            <img class="lightbox-image" src="" alt="Foto ampliada">
        </div>
        <div class="lightbox-controls" aria-label="Controles de zoom">
            <button class="lightbox-zoom" type="button" data-zoom="out" aria-label="Diminuir zoom">-</button>
            <button class="lightbox-zoom" type="button" data-zoom="reset" aria-label="Restaurar zoom">1x</button>
            <button class="lightbox-zoom" type="button" data-zoom="in" aria-label="Aumentar zoom">+</button>
        </div>
    </div>

    <?php if (!empty($video)): ?>
        <button class="gallery-video-button" type="button" aria-label="Abrir vídeo do YouTube" data-open-video data-video-src="<?php echo htmlspecialchars($video); ?>">&#9658;</button>

        <div class="gallery-video-modal" id="gallery-video-modal" aria-hidden="true">
            <button class="video-close" type="button" aria-label="Fechar vídeo">x</button>
            <iframe class="gallery-video" src="" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen title="Vídeo do YouTube"></iframe>
        </div>
    <?php endif; ?>

    <canvas id="background"></canvas>

    <script>
        const galleryMain = document.querySelector(".gallery-main");
        const galleryLoader = document.getElementById("gallery-loader");
        const galleryImages = Array.from(document.querySelectorAll(".gallery-content img"));
        const galleryLoaderStartedAt = performance.now();
        const minimumLoaderTime = 900;
        const galleryLoadMaxTime = 15000;
        let galleryLoadingFinished = false;
        let galleryLoadTimeout;

        function finishGalleryLoading() {
            if (galleryLoadingFinished || !galleryMain || !galleryMain.classList.contains("is-loading")) return;

            galleryLoadingFinished = true;
            clearTimeout(galleryLoadTimeout);

            const elapsedTime = performance.now() - galleryLoaderStartedAt;
            const remainingTime = Math.max(0, minimumLoaderTime - elapsedTime);

            setTimeout(() => {
                galleryMain.classList.remove("is-loading");
                galleryMain.setAttribute("aria-busy", "false");

                if (galleryLoader) {
                    galleryLoader.setAttribute("aria-hidden", "true");
                }
            }, remainingTime);
        }

        function preloadGalleryImages() {
            if (galleryImages.length === 0) {
                finishGalleryLoading();
                return;
            }

            const imagePromises = galleryImages.map((img) => new Promise((resolve) => {
                const preload = new Image();
                preload.src = img.src;

                if (preload.complete) {
                    resolve();
                    return;
                }

                preload.addEventListener("load", resolve, { once: true });
                preload.addEventListener("error", resolve, { once: true });
            }));

            Promise.all(imagePromises).then(finishGalleryLoading);
            galleryLoadTimeout = setTimeout(finishGalleryLoading, galleryLoadMaxTime);
        }

        preloadGalleryImages();

        const slides = document.querySelectorAll(".gallery-slide");
        let activeSlide = 0;
        let slideTimer;

        function showSlide(index) {
            if (!slides.length) return;

            activeSlide = (index + slides.length) % slides.length;

            slides.forEach((slide, slideIndex) => {
                slide.classList.toggle("active", slideIndex === activeSlide);
            });

        }

        function startSlider() {
            if (slides.length < 2) return;

            clearInterval(slideTimer);
            slideTimer = setInterval(() => {
                showSlide(activeSlide + 1);
            }, 3000);
        }

        startSlider();

        const galleryLinks = document.querySelectorAll("[data-lightbox-image]");
        const lightbox = document.getElementById("gallery-lightbox");
        const lightboxImage = lightbox.querySelector(".lightbox-image");
        const lightboxStage = lightbox.querySelector(".lightbox-stage");
        const closeLightboxButton = lightbox.querySelector(".lightbox-close");
        const zoomButtons = lightbox.querySelectorAll("[data-zoom]");
        let lightboxZoom = 1;

        function applyLightboxZoom() {
            lightboxImage.style.transform = `scale(${lightboxZoom})`;
            lightboxStage.style.cursor = lightboxZoom > 1 ? "grab" : "default";
        }

        function openLightbox(imageSrc, imageAlt) {
            lightboxZoom = 1;
            lightboxImage.src = imageSrc;
            lightboxImage.alt = imageAlt || "Foto ampliada";
            applyLightboxZoom();
            lightbox.classList.add("open");
            lightbox.setAttribute("aria-hidden", "false");
            document.body.classList.add("lightbox-lock");
            closeLightboxButton.focus();
        }

        function closeLightbox() {
            lightbox.classList.remove("open");
            lightbox.setAttribute("aria-hidden", "true");
            document.body.classList.remove("lightbox-lock");
            lightboxImage.src = "";
        }

        galleryLinks.forEach((link) => {
            link.addEventListener("click", (event) => {
                event.preventDefault();
                const image = link.querySelector("img");
                openLightbox(link.dataset.lightboxImage, image ? image.alt : "");
            });
        });

        closeLightboxButton.addEventListener("click", closeLightbox);

        lightbox.addEventListener("click", (event) => {
            if (event.target === lightbox) {
                closeLightbox();
            }
        });

        zoomButtons.forEach((button) => {
            button.addEventListener("click", () => {
                const action = button.dataset.zoom;

                if (action === "in") {
                    lightboxZoom = Math.min(lightboxZoom + .25, 3);
                } else if (action === "out") {
                    lightboxZoom = Math.max(lightboxZoom - .25, 1);
                } else {
                    lightboxZoom = 1;
                    lightboxStage.scrollTo({ top: 0, left: 0 });
                }

                applyLightboxZoom();
            });
        });

        document.addEventListener("keydown", (event) => {
            if (!lightbox.classList.contains("open")) return;

            if (event.key === "Escape") {
                closeLightbox();
            }

            if (event.key === "+" || event.key === "=") {
                lightboxZoom = Math.min(lightboxZoom + .25, 3);
                applyLightboxZoom();
            }

            if (event.key === "-") {
                lightboxZoom = Math.max(lightboxZoom - .25, 1);
                applyLightboxZoom();
            }
        });

        const openVideoButton = document.querySelector("[data-open-video]");
        const videoModal = document.getElementById("gallery-video-modal");

        if (openVideoButton && videoModal) {
            const videoFrame = videoModal.querySelector(".gallery-video");
            const closeVideoButton = videoModal.querySelector(".video-close");
            const videoSrc = openVideoButton.dataset.videoSrc;

            function openVideo() {
                if (videoSrc) {
                    videoFrame.src = videoSrc;
                }
                videoModal.classList.add("open");
                videoModal.setAttribute("aria-hidden", "false");
                document.body.classList.add("lightbox-lock");
                closeVideoButton.focus();
            }

            function closeVideo() {
                videoModal.classList.remove("open");
                videoModal.setAttribute("aria-hidden", "true");
                document.body.classList.remove("lightbox-lock");
                videoFrame.src = "";
            }

            openVideoButton.addEventListener("click", openVideo);
            closeVideoButton.addEventListener("click", closeVideo);

            videoModal.addEventListener("click", (event) => {
                if (event.target === videoModal) {
                    closeVideo();
                }
            });

            document.addEventListener("keydown", (event) => {
                if (event.key === "Escape" && videoModal.classList.contains("open")) {
                    closeVideo();
                }
            });
        }
    </script>
</body>
</html>
