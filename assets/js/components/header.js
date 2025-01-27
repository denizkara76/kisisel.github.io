// Header Component
class Header extends HTMLElement {
    constructor() {
        super();
    }

    connectedCallback() {
        this.innerHTML = `
            <style>
                .nav {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    height: var(--header-height);
                    padding: 0 var(--spacing-lg);
                    background: rgba(255, 255, 255, 0.05);
                    backdrop-filter: blur(10px);
                    border-radius: 30px;
                    margin-top: 20px;
                    margin-left: 20px;
                    margin-right: 20px;
                    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
                }

                .nav-links {
                    display: flex;
                    align-items: center;
                    gap: 2rem;
                    margin: 0 auto;
                    justify-content: center;
                    width: 100%;
                    max-width: 600px;
                }

                .nav-link {
                    color: var(--text-color-muted);
                    text-decoration: none;
                    font-weight: var(--font-weight-medium);
                    transition: var(--transition-base);
                    padding: var(--spacing-xs) var(--spacing-sm);
                }

                .nav-link:hover,
                .nav-link.active {
                    color: var(--text-color);
                }

                .logo {
                    display: flex;
                    align-items: center;
                }

                .logo img {
                    height: 40px;
                    width: auto;
                }

                @media (max-width: 768px) {
                    .nav-links {
                        display: none;
                    }
                }
            </style>
            
            <header class="header">
                <nav class="nav container">
                    <a href="index.html" class="logo">
                        <img src="assets/img/logo.png" alt="Kreatif Tasarımcı Logo">
                    </a>
                    
                    <div class="nav-links">
                        <a href="index.html" class="nav-link">Ana Sayfa</a>
                        <a href="about.html" class="nav-link">Hakkımda</a>
                        <a href="projects.html" class="nav-link">Projeler</a>
                        <a href="contact.html" class="nav-link">İletişim</a>
                    </div>

                    <div class="auth-buttons" style="display: none;">
                        <a href="login.html" class="btn btn-outline">Giriş Yap</a>
                        <a href="register.html" class="btn btn-primary">Kayıt Ol</a>
                    </div>
                    
                    <button class="menu-toggle">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </nav>
            </header>

            <div class="mobile-menu">
                <div class="container">
                    <div class="mobile-menu__links">
                        <a href="index.html">Ana Sayfa</a>
                        <a href="about.html">Hakkımda</a>
                        <a href="projects.html">Projeler</a>
                        <a href="contact.html">İletişim</a>
                    </div>
                </div>
            </div>
        `;
    }
}

customElements.define('site-header', Header); 