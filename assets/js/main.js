// Mobile menu toggle
const menuToggle = document.querySelector('.menu-toggle');
const navLinks = document.querySelector('.nav-links');
const header = document.querySelector('.header');

menuToggle?.addEventListener('click', () => {
  menuToggle.classList.toggle('active');
  navLinks.classList.toggle('active');
});

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute('href'));
    if (target) {
      target.scrollIntoView({
        behavior: 'smooth'
      });
    }
  });
});

// GSAP Animations
gsap.registerPlugin(ScrollTrigger);

// Header animation
gsap.from('header', {
  y: -100,
  opacity: 0,
  duration: 1,
  ease: 'power3.out'
});

// Hero section animations
gsap.from('.hero-title', {
  y: 50,
  opacity: 0,
  duration: 1,
  delay: 0.5,
  ease: 'power3.out'
});

gsap.from('.hero-subtitle', {
  y: 50,
  opacity: 0,
  duration: 1,
  delay: 0.7,
  ease: 'power3.out'
});

gsap.from('.hero-cta', {
  y: 50,
  opacity: 0,
  duration: 1,
  delay: 0.9,
  ease: 'power3.out'
});

// Services section animations
gsap.from('.service-card', {
  scrollTrigger: {
    trigger: '.services',
    start: 'top center',
    toggleActions: 'play none none reverse'
  },
  y: 100,
  opacity: 0,
  duration: 0.8,
  stagger: 0.2,
  ease: 'power3.out'
});

// Projects section animations
gsap.from('.project-card', {
  scrollTrigger: {
    trigger: '.projects',
    start: 'top center',
    toggleActions: 'play none none reverse'
  },
  y: 100,
  opacity: 0,
  duration: 0.8,
  stagger: 0.2,
  ease: 'power3.out'
});

// Contact section animations
gsap.from('.contact-content', {
  scrollTrigger: {
    trigger: '.contact',
    start: 'top center',
    toggleActions: 'play none none reverse'
  },
  y: 50,
  opacity: 0,
  duration: 1,
  ease: 'power3.out'
});

// Cursor animation
const cursor = document.createElement('div');
cursor.className = 'cursor';
document.body.appendChild(cursor);

const cursorDot = document.createElement('div');
cursorDot.className = 'cursor-dot';
document.body.appendChild(cursorDot);

document.addEventListener('mousemove', (e) => {
  gsap.to(cursor, {
    x: e.clientX,
    y: e.clientY,
    duration: 0.5,
    ease: 'power2.out'
  });
  
  gsap.to(cursorDot, {
    x: e.clientX,
    y: e.clientY,
    duration: 0.1
  });
});

// Hover effects for interactive elements
const interactiveElements = document.querySelectorAll('a, button, .card');

interactiveElements.forEach(el => {
  el.addEventListener('mouseenter', () => {
    cursor.classList.add('cursor--active');
    cursorDot.classList.add('cursor-dot--active');
  });
  
  el.addEventListener('mouseleave', () => {
    cursor.classList.remove('cursor--active');
    cursorDot.classList.remove('cursor-dot--active');
  });
});

// Parallax effect for hero section
if (window.innerWidth > 768) {
  const heroContent = document.querySelector('.hero-content');
  
  window.addEventListener('scroll', () => {
    const scrolled = window.pageYOffset;
    gsap.to(heroContent, {
      y: scrolled * 0.3,
      duration: 0.5,
      ease: 'power1.out'
    });
  });
}

// Preloader
window.addEventListener('load', () => {
  const preloader = document.querySelector('.preloader');
  if (preloader) {
    gsap.to(preloader, {
      opacity: 0,
      duration: 1,
      onComplete: () => {
        preloader.style.display = 'none';
      }
    });
  }
});

// Add active class to current nav link
const sections = document.querySelectorAll('section[id]');

// Header scroll effect
let lastScroll = 0;
window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset;

    // Scroll aşağı/yukarı sınıflarını ekle/kaldır
    if (currentScroll <= 0) {
        header.classList.remove('scroll-up');
        header.classList.remove('scroll-down');
        return;
    }

    if (currentScroll > lastScroll && !header.classList.contains('scroll-down')) {
        // Aşağı scroll
        header.classList.remove('scroll-up');
        header.classList.add('scroll-down');
    } else if (currentScroll < lastScroll && header.classList.contains('scroll-down')) {
        // Yukarı scroll
        header.classList.remove('scroll-down');
        header.classList.add('scroll-up');
    }
    lastScroll = currentScroll;
});

window.addEventListener('scroll', () => {
    const scrollY = window.pageYOffset;
    
    sections.forEach(section => {
        const sectionHeight = section.offsetHeight;
        const sectionTop = section.offsetTop - 100;
        const sectionId = section.getAttribute('id');
        
        if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
            document.querySelector(`.nav-links a[href*=${sectionId}]`)?.classList.add('active');
        } else {
            document.querySelector(`.nav-links a[href*=${sectionId}]`)?.classList.remove('active');
        }
    });
});

// Mobile Menu
const mobileMenu = document.querySelector('.mobile-menu');

menuToggle.addEventListener('click', () => {
    mobileMenu.classList.toggle('active');
    menuToggle.classList.toggle('active');

    // Menü açıkken toggle butonunun çizgilerini X'e dönüştür
    if (mobileMenu.classList.contains('active')) {
        menuToggle.querySelector('span:first-child').style.transform = 'rotate(45deg) translate(5px, 5px)';
        menuToggle.querySelector('span:nth-child(2)').style.opacity = '0';
        menuToggle.querySelector('span:last-child').style.transform = 'rotate(-45deg) translate(7px, -6px)';
    } else {
        menuToggle.querySelector('span:first-child').style.transform = 'none';
        menuToggle.querySelector('span:nth-child(2)').style.opacity = '1';
        menuToggle.querySelector('span:last-child').style.transform = 'none';
    }
});

// Sayfa yüklendiğinde animasyonları başlat
document.addEventListener('DOMContentLoaded', () => {
    // GSAP animasyonları
    gsap.from('.hero-content', {
        duration: 1,
        y: 50,
        opacity: 0,
        ease: 'power3.out'
    });

    gsap.from('.services-grid > *', {
        duration: 0.8,
        y: 30,
        opacity: 0,
        stagger: 0.2,
        ease: 'power3.out',
        scrollTrigger: {
            trigger: '.services-grid',
            start: 'top center+=100',
            toggleActions: 'play none none reverse'
        }
    });

    gsap.from('.projects-grid > *', {
        duration: 0.8,
        y: 30,
        opacity: 0,
        stagger: 0.2,
        ease: 'power3.out',
        scrollTrigger: {
            trigger: '.projects-grid',
            start: 'top center+=100',
            toggleActions: 'play none none reverse'
        }
    });
});

// Performance optimizations
document.addEventListener('DOMContentLoaded', () => {
    // Lazy loading images
    const lazyImages = document.querySelectorAll('img.lazy');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.add('loaded');
                observer.unobserve(img);
            }
        });
    });

    lazyImages.forEach(img => imageObserver.observe(img));

    // Mobile menu toggle with performance optimization
    const menuToggle = document.querySelector('.menu-toggle');
    const navLinks = document.querySelector('.nav-links');
    const header = document.querySelector('.header');

    if (menuToggle && navLinks) {
        menuToggle.addEventListener('click', () => {
            requestAnimationFrame(() => {
                menuToggle.classList.toggle('active');
                navLinks.classList.toggle('active');
            });
        });
    }

    // Smooth scroll with performance optimization
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Dark mode toggle
    const darkModeToggle = document.querySelector('.dark-mode-toggle');
    if (darkModeToggle) {
        const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');
        
        const currentTheme = localStorage.getItem('theme');
        if (currentTheme === 'dark') {
            document.body.classList.add('dark-theme');
        }

        darkModeToggle.addEventListener('click', () => {
            document.body.classList.toggle('dark-theme');
            const theme = document.body.classList.contains('dark-theme') ? 'dark' : 'light';
            localStorage.setItem('theme', theme);
        });

        prefersDarkScheme.addEventListener('change', (e) => {
            if (!localStorage.getItem('theme')) {
                if (e.matches) {
                    document.body.classList.add('dark-theme');
                } else {
                    document.body.classList.remove('dark-theme');
                }
            }
        });
    }

    // Form validation
    const contactForm = document.querySelector('.contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(contactForm);
            try {
                const response = await fetch('/assets/php/contact.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.text();
                alert(result);
                
                if (response.ok) {
                    contactForm.reset();
                }
            } catch (error) {
                console.error('Form gönderimi sırasında hata:', error);
                alert('Bir hata oluştu. Lütfen daha sonra tekrar deneyin.');
            }
        });
    }
});

// GSAP Animations with performance optimization
if (typeof gsap !== 'undefined') {
    gsap.registerPlugin(ScrollTrigger);

    // Header animation
    gsap.from('header', {
        y: -100,
        opacity: 0,
        duration: 1,
        ease: 'power3.out'
    });

    // Hero section animations
    gsap.from('.hero-title', {
        y: 50,
        opacity: 0,
        duration: 1,
        delay: 0.5,
        ease: 'power3.out'
    });

    // Scroll animations
    gsap.utils.toArray('.animate-on-scroll').forEach(element => {
        gsap.from(element, {
            scrollTrigger: {
                trigger: element,
                start: 'top 80%',
                toggleActions: 'play none none reverse'
            },
            y: 50,
            opacity: 0,
            duration: 1,
            ease: 'power3.out'
        });
    });
} 