<?php
// Troque apenas estas informações quando quiser personalizar a página.
$timerMode = "since"; // Use "since" para contar desde a data ou "until" para contagem regressiva.
$timerTitle = "Nosso tempo juntos";
$timerSubtitle = "Cada segundo desse contador guarda um pedacinho da nossa história.";
$importantDates = [
    [
        "date" => "2025-05-17T00:00:00-03:00",
        "label" => "17/05/2025",
        "subtitle" => "GP de Rolimã",
    ],
    [
        "date" => "2025-12-06T00:00:00-03:00",
        "label" => "06/12/2025",
        "subtitle" => "Nosso primeiro beijo",
    ],
    [
        "date" => "2025-12-14T00:00:00-03:00",
        "label" => "14/12/2025",
        "subtitle" => "Nosso primeiro BEEEEIJO",
    ],
    [
        "date" => "2026-02-06T00:00:00-03:00",
        "label" => "06/02/2026",
        "subtitle" => "Pedido de namoro",
    ],
    [
        "date" => "2026-03-19T00:00:00-03:00",
        "label" => "19/03/2026",
        "subtitle" => "Nossa primeira viagem com sua família",
    ],
    [
        "date" => "2026-05-01T00:00:00-03:00",
        "label" => "01/05/2026",
        "subtitle" => "Nossa primeira viagem com minha família",
    ],
    [
        "date" => "2026-06-12T00:00:00-03:00",
        "label" => "12/06/2026",
        "subtitle" => "Nosso 1º dia dos namorados juntos",
    ],
];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timer</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/script.js" defer></script>
    <style>
        .timer-panel {
            padding: clamp(22px, 4vw, 38px);
        }

        .timer-date {
            display: inline-flex;
            margin-bottom: 22px;
            padding: 10px 14px;
            border-radius: 999px;
            color: var(--button-text);
            background: rgba(232, 77, 117, .12);
            font-family: var(--number-font);
            font-weight: 600;
        }

        .important-dates {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 18px;
        }

        .important-date {
            padding: 18px;
            border: 1px solid rgba(232, 77, 117, .18);
            border-radius: 8px;
            color: var(--text);
            background: rgba(255, 255, 255, .48);
        }

        .timer-date {
            margin-bottom: 8px;
        }

        .timer-subtitle {
            margin: 0 0 16px;
            line-height: 1.5;
            color: var(--text);
            opacity: .75;
        }

        .timer-date-value {
            font-family: var(--number-font);
            font-weight: 600;
        }

        body.dark .important-date {
            background: rgba(255, 255, 255, .06);
        }

        .timer-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 8px;
        }

        .timer-unit {
            min-height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 16px 12px;
            border: 1px solid rgba(232, 77, 117, .16);
            border-radius: 8px;
            text-align: center;
            background: rgba(255, 255, 255, .5);
            box-sizing: border-box;
            overflow: hidden;
        }

        body.dark .timer-unit {
            background: rgba(255, 255, 255, .06);
        }

        .timer-number {
            font-family: var(--number-font);
            font-size: clamp(1.6rem, 2.8vw, 2.6rem);
            line-height: 1;
            font-weight: 700;
            color: var(--accent);
            word-break: keep-all;
            white-space: nowrap;
        }

        .timer-label {
            margin-top: 10px;
            font-size: .78rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .timer-note {
            margin: 12px 0 0;
            font-size: .95rem;
            line-height: 1.5;
        }

        @media (max-width: 760px) {
            .timer-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .timer-unit {
                min-height: 112px;
                padding: 14px 10px;
            }
        }
    </style>
</head>

<body class="content-page">
    <?php include "../fragmentos/header.html"; ?>

    <main class="romantic-main">
        <section class="romantic-hero">
            <h1><?php echo htmlspecialchars($timerTitle); ?></h1>
            <p><?php echo htmlspecialchars($timerSubtitle); ?></p>
        </section>

        <section class="romantic-panel timer-panel" aria-label="Contador do casal">
            <div class="important-dates" aria-label="Datas importantes">
                <?php foreach ($importantDates as $importantDate) : ?>
                    <article class="important-date">
                        <div class="timer-date">
                            <span class="timer-date-value"><?php echo htmlspecialchars($importantDate["label"]); ?></span>
                        </div>
                        <p class="timer-subtitle"><?php echo htmlspecialchars($importantDate["subtitle"]); ?></p>

                        <div class="timer-grid" data-important-date="<?php echo htmlspecialchars($importantDate["date"]); ?>" data-timer-mode="<?php echo htmlspecialchars($timerMode); ?>">
                            <div class="timer-unit">
                                <strong class="timer-number" data-time="days">0</strong>
                                <span class="timer-label">Dias</span>
                            </div>
                            <div class="timer-unit">
                                <strong class="timer-number" data-time="hours">0</strong>
                                <span class="timer-label">Horas</span>
                            </div>
                            <div class="timer-unit">
                                <strong class="timer-number" data-time="minutes">0</strong>
                                <span class="timer-label">Minutos</span>
                            </div>
                            <div class="timer-unit">
                                <strong class="timer-number" data-time="seconds">0</strong>
                                <span class="timer-label">Segundos</span>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <canvas id="background"></canvas>

    <script>
        const timers = [...document.querySelectorAll(".timer-grid")].map((timerGrid) => ({
            targetDate: new Date(timerGrid.dataset.importantDate),
            timerMode: timerGrid.dataset.timerMode,
            fields: {
                days: timerGrid.querySelector('[data-time="days"]'),
                hours: timerGrid.querySelector('[data-time="hours"]'),
                minutes: timerGrid.querySelector('[data-time="minutes"]'),
                seconds: timerGrid.querySelector('[data-time="seconds"]')
            }
        }));

        function padTime(value) {
            return String(value).padStart(2, "0");
        }

        function updateTimer(timer) {
            const now = new Date();
            let difference = timer.timerMode === "until" ? timer.targetDate - now : now - timer.targetDate;
            const isFinished = difference < 0;

            if (isFinished) {
                difference = Math.abs(difference);
            }

            const totalSeconds = Math.floor(difference / 1000);
            const days = Math.floor(totalSeconds / 86400);
            const hours = Math.floor((totalSeconds % 86400) / 3600);
            const minutes = Math.floor((totalSeconds % 3600) / 60);
            const seconds = totalSeconds % 60;

            timer.fields.days.textContent = days;
            timer.fields.hours.textContent = padTime(hours);
            timer.fields.minutes.textContent = padTime(minutes);
            timer.fields.seconds.textContent = padTime(seconds);
        }

        function updateTimers() {
            timers.forEach(updateTimer);
        }

        updateTimers();
        setInterval(updateTimers, 1000);
    </script>
</body>
</html>
