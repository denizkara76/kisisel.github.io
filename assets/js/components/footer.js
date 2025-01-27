// Footer Component
class Footer extends HTMLElement {
    constructor() {
        super();
    }

    connectedCallback() {
        this.innerHTML = `
            <footer class="footer">
                <div class="container">
                    <div class="footer-content">
                        <div class="footer-brand">
                            <img src="../assets/img/logo.png" alt="Kreatif Tasarımcı Logo">
                            <p>Web tasarım ve geliştirme hizmetleri</p>
                        </div>
                        <div class="footer-links">
                            <div class="footer-nav">
                                <h4>Sayfalar</h4>
                                <a href="/pages/index.html">Ana Sayfa</a>
                                <a href="/pages/about.html">Hakkımda</a>
                                <a href="/pages/projects.html">Projeler</a>
                                <a href="/pages/contact.html">İletişim</a>
                            </div>
                            <div class="footer-social">
                                <h4>Sosyal Medya</h4>
                                <a href="#" target="_blank">Instagram</a>
                                <a href="#" target="_blank">Twitter</a>
                                <a href="#" target="_blank">LinkedIn</a>
                                <a href="#" target="_blank">GitHub</a>
                            </div>
                        </div>
                    </div>
                    <div class="footer-bottom">
                        <p>&copy; 2024 Kreatif Tasarımcı. Tüm hakları saklıdır.</p>
                    </div>
                </div>
            </footer>
        `;
    }
}

customElements.define('site-footer', Footer); 