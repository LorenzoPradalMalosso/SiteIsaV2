const canvas = document.getElementById("background");
const ctx = canvas.getContext("2d");
const toggleBtn = document.getElementById("theme-toggle");
const siteHeader = document.querySelector(".site-header");
const menuToggle = document.querySelector(".mobile-menu-toggle");
const navigation = document.getElementById("primary-navigation");
function resize() {
  canvas.width = window.innerWidth;
  canvas.height = window.innerHeight;
}
resize();
window.addEventListener("resize", resize);
// ===========================
// TEMA
// ===========================
const savedTheme = localStorage.getItem("theme");
let darkMode = savedTheme !== "light";

function applyTheme() {
  document.body.classList.toggle("dark", darkMode);
  document.body.classList.toggle("light", !darkMode);
  toggleBtn.textContent = darkMode ? "Modo Claro" : "Modo Escuro";
}

applyTheme();

toggleBtn.addEventListener("click", () => {
  darkMode = !darkMode;
  localStorage.setItem("theme", darkMode ? "dark" : "light");
  applyTheme();
});

// ===========================
// MENU MOBILE
// ===========================
function closeMobileMenu() {
  if (!siteHeader || !menuToggle) return;

  siteHeader.classList.remove("menu-open");
  menuToggle.setAttribute("aria-expanded", "false");
  menuToggle.setAttribute("aria-label", "Abrir menu");
}

if (siteHeader && menuToggle && navigation) {
  menuToggle.addEventListener("click", () => {
    const isOpen = siteHeader.classList.toggle("menu-open");

    menuToggle.setAttribute("aria-expanded", String(isOpen));
    menuToggle.setAttribute("aria-label", isOpen ? "Fechar menu" : "Abrir menu");
  });

  navigation.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", closeMobileMenu);
  });

  window.addEventListener("resize", () => {
    if (window.innerWidth > 760) {
      closeMobileMenu();
    }
  });

  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape") {
      closeMobileMenu();
    }
  });
}

function isDark() {
  return document.body.classList.contains("dark");
}
// ===========================
// ESTRELAS
// ===========================
const stars = [];
const shootingStars = [];
class Star {
  constructor() {
    this.x = Math.random() * canvas.width;
    this.y = Math.random() * canvas.height;
    this.radius = Math.random() * 2;
    this.opacity = Math.random();
    this.direction =
      Math.random() > 0.5
        ? 0.005
        : -0.005;
  }
  update() {
    this.opacity += this.direction;
    if (this.opacity >= 1) {
      this.direction = -0.005;
    }
    if (this.opacity <= 0.1) {
      this.direction = 0.005;
    }
  }
  draw() {
    ctx.beginPath();
    ctx.fillStyle = isDark()
      ? `rgba(255,182,193,${this.opacity})`
      : `rgba(70,90,130,${this.opacity})`;

    ctx.arc(
      this.x,
      this.y,
      this.radius,
      0,
      Math.PI * 2
    );
    ctx.fill();
  }
}

// ===========================
// CRIA ESTRELAS
// ===========================
for (let i = 0; i < 250; i++) {
  stars.push(new Star());
}

// ===========================
// ESTRELAS CADENTES
// ===========================
const MAX_SHOOTING_STARS = 10;

class ShootingStar {
  constructor() {
    this.reset();
  }

  reset() {
  if (Math.random() < 0.5) {
    // Surge pelo topo
    this.x =
      Math.random() * canvas.width;

    this.y =
      -200 - Math.random() * 400;
  } else {
    // Surge pela direita
    this.x =
      canvas.width +
      Math.random() * 400;

    this.y =
      Math.random() * canvas.height * 0.6;
  }

  this.length =
    120 + Math.random() * 120;

  this.speed =
    20 + Math.random() * 10;
}

  update() {
    this.x -= this.speed;
    this.y += this.speed;

    if (
      this.x < -this.length ||
      this.y > canvas.height + this.length
    ) {
      this.reset();
    }
  }

  draw() {
    const color = isDark()
      ? "255,182,193"
      : "80,120,200";

    const gradient =
      ctx.createLinearGradient(
        this.x,
        this.y,
        this.x + this.length,
        this.y - this.length
      );

    gradient.addColorStop(
      0,
      `rgba(${color},1)`
    );

    gradient.addColorStop(
      1,
      `rgba(${color},0)`
    );

    ctx.beginPath();
    ctx.strokeStyle = gradient;
    ctx.lineWidth = 2;

    ctx.moveTo(this.x, this.y);
    ctx.lineTo(
      this.x + this.length,
      this.y - this.length
    );

    ctx.stroke();
  }
}

// Cria as estrelas cadentes uma única vez
for (
  let i = 0;
  i < MAX_SHOOTING_STARS;
  i++
) {
  shootingStars.push(
    new ShootingStar()
  );
}

// ===========================
// ANIMAÇÃO
// ===========================
function animate() {
  ctx.clearRect(
    0,
    0,
    canvas.width,
    canvas.height
  );

  stars.forEach(star => {
    star.update();
    star.draw();
  });

  shootingStars.forEach(star => {
    star.update();
    star.draw();
  });

  requestAnimationFrame(
    animate
  );
}

animate();
