// Translations
const translations = {
    cs: {
        // Navbar
        'nav-about': 'O mně',
        'nav-skills': 'Skills',
        'nav-projects': 'Projekty',
        'nav-offer': 'Nabízím',
        'nav-contact': 'Kontakt',
        
        // About section
        'about-hello': 'AHOJ, JSEM',
        'about-name': 'Jirka086',
        'about-role': 'Developer a IT specialista',
        'about-description': 'Jsem nadšenec pro technologie se zaměřením na správu serverů, vývoj webových stránek a webových aplikací. Baví mě experimentovat, hledat nová řešení a neustále se zdokonalovat. Rád pomáhám ostatním, sdílím své znalosti a v současnosti studuji IT.',
        'about-button': 'Kontaktujte mě',
        
        // Skills section
        'skills-title': 'Moje zkušenosti',
        'skills-frontend': 'Frontend',
        'skills-backend': 'Backend',
        'skills-tools': 'Nástroje a platformy',
        'skills-gameservers': 'Technik herních serverů',
        'skills-learning': 'Základy / Učím se',
        
        // Projects section
        'projects-title': 'Moje projekty',
        'project1-title': 'Action Gaming',
        'project1-tech': 'Spolumajitel (Technik)',
        'project1-desc': 'Herní portál Action Gaming hostuje servery pro hru SCP:SL s kvalitním roleplay zážitkem. Jsme komunita, kde si zakládáme na civilizovaném chování a především na již zmíněné kvalitě. Nabízíme vlastní mapy, pluginy a profesionální tým včetně vedení serveru. Naším cílem je posunout hranici zpátky tam, kde byla již před několika lety na jiných serverech, kde roleplay ještě představovalo ten pravý zážitek a každý se měl možnost něco nového naučit.',
        'project1-link': 'Discord invite',
        'project2-title': 'NAUTIXRP',
        'project2-tech': 'Community Management, vývoj webu: HTML5, PHP, CSS',
        'project2-desc': 'NautixRP je česká herní komunita zaměřená na kvalitní roleplay zážitek ve hře GTA V na platformě FiveM. Naším cílem je vytvořit prostředí, kde hráči mohou zažít autentické roleplay situace, rozvíjet své postavy a interagovat s ostatními členy komunity v přátelské a respektující atmosféře.',
        'project2-link': 'Navštívit web',
        'project3-title': 'Portfolio',
        'project3-tech': 'Vývoj webu: HTML5, CSS, PHP, JavaScript',
        'project3-desc': 'Moje osobní portfolio, kde prezentuji své dovednosti, projekty a zkušenosti v oblasti vývoje webových stránek a IT služeb. Portfolio slouží jako ukázka mé práce a umožňuje potenciálním klientům a zaměstnavatelům se lépe seznámit s mými schopnostmi.',
        'project3-link': 'Navštívit web',
        
        // Offer section
        'offer-title': 'Co nabízím',
        'offer1-title': 'Vývoj webu a webových aplikací',
        'offer1-desc': 'Nabízím tvorbu moderních a responzivních webových stránek a aplikací přesně na míru vašim potřebám.',
        'offer2-title': 'Správa serverů',
        'offer2-desc': 'Komplexní správa serverů, nastavování aplikací, herních serverů a další související služby.',
        'offer3-title': 'Přímá komunikace',
        'offer3-desc': 'Žádní prostředníci. Komunikujete přímo se mnou, vývojářem, který na vašem projektu pracuje.',
        'offer4-title': 'Rychlost a efektivita',
        'offer4-desc': 'Jsem flexibilní a pracuji rychle. Váš projekt nebude stát měsíce ve frontě.',
        'offer5-title': 'Návrhy a konzultace',
        'offer5-desc': 'Nejprve vytvořím návrh, který následně společně probereme, abychom zajistili, že splňuje vaše očekávání ještě před zahájením samotné realizace projektu.',
        
        // Contact section
        'contact-title': 'Kontakt',
        'contact-subtitle': 'Máte zájem o mé služby? Kontaktujte mě pomocí formuláře níže.',
        'contact-name': 'Jméno:',
        'contact-name-helper': '2-50 znaků',
        'contact-name-placeholder': 'Vaše jméno',
        'contact-email': 'Email:',
        'contact-email-helper': 'Platná emailová adresa',
        'contact-email-placeholder': 'vas@email.cz',
        'contact-service': 'Služba:',
        'contact-service-select': '-- Vyberte službu --',
        'contact-service-web': 'Vytvoření webové stránky nebo webové aplikace',
        'contact-service-gameserver': 'Technik herního serveru',
        'contact-service-server': 'Technik serveru',
        'contact-service-consult': 'Konzultace a dotazy',
        'contact-message': 'Zpráva:',
        'contact-message-helper': '10-1000 znaků',
        'contact-message-placeholder': 'Popište podrobně, co potřebujete... (10-1000 znaků)',
        'contact-submit': 'Odeslat',
        'contact-social-title': 'Kontaktujte mě také přes sociální sítě:'
    },
    en: {
        // Navbar
        'nav-about': 'About',
        'nav-skills': 'Skills',
        'nav-projects': 'Projects',
        'nav-offer': 'Services',
        'nav-contact': 'Contact',
        
        // About section
        'about-hello': 'HI, I AM',
        'about-name': 'Jirka086',
        'about-role': 'Developer and IT Specialist',
        'about-description': 'I am a technology enthusiast focused on server management, website development, and web applications. I enjoy experimenting, finding new solutions, and constantly improving. I like helping others, sharing my knowledge, and I am currently studying IT.',
        'about-button': 'Contact Me',
        
        // Skills section
        'skills-title': 'My Experience',
        'skills-frontend': 'Frontend',
        'skills-backend': 'Backend',
        'skills-tools': 'Tools and Platforms',
        'skills-gameservers': 'Game Server Technician',
        'skills-learning': 'Basics / Learning',
        
        // Projects section
        'projects-title': 'My Projects',
        'project1-title': 'Action Gaming',
        'project1-tech': 'Co-owner (Technician)',
        'project1-desc': 'Action Gaming gaming portal hosts servers for SCP:SL with a quality roleplay experience. We are a community that values civilized behavior and especially the mentioned quality. We offer custom maps, plugins, and a professional team including server management. Our goal is to push the boundary back to where it was years ago on other servers, where roleplay still represented a true experience and everyone had the opportunity to learn something new.',
        'project1-link': 'Discord invite',
        'project2-title': 'NAUTIXRP',
        'project2-tech': 'Community Management, web development: HTML5, PHP, CSS',
        'project2-desc': 'NautixRP is a Czech gaming community focused on quality roleplay experience in GTA V on the FiveM platform. Our goal is to create an environment where players can experience authentic roleplay situations, develop their characters, and interact with other community members in a friendly and respectful atmosphere.',
        'project2-link': 'Visit website',
        'project3-title': 'Portfolio',
        'project3-tech': 'Web development: HTML5, CSS, PHP, JavaScript',
        'project3-desc': 'My personal portfolio, where I present my skills, projects, and experience in web development and IT services. The portfolio serves as a showcase of my work and allows potential clients and employers to better get to know my abilities.',
        'project3-link': 'Visit website',
        
        // Offer section
        'offer-title': 'What I Offer',
        'offer1-title': 'Web and Web App Development',
        'offer1-desc': 'I offer the creation of modern and responsive websites and applications tailored to your needs.',
        'offer2-title': 'Server Management',
        'offer2-desc': 'Comprehensive server management, application setup, game servers, and other related services.',
        'offer3-title': 'Direct Communication',
        'offer3-desc': 'No middlemen. You communicate directly with me, the developer working on your project.',
        'offer4-title': 'Speed and Efficiency',
        'offer4-desc': 'I am flexible and work quickly. Your project will not sit in a queue for months.',
        'offer5-title': 'Design and Consultation',
        'offer5-desc': 'I will first create a design, which we will then discuss together to ensure it meets your expectations before starting the actual implementation of the project.',
        
        // Contact section
        'contact-title': 'Contact',
        'contact-subtitle': 'Interested in my services? Contact me using the form below.',
        'contact-name': 'Name:',
        'contact-name-helper': '2-50 characters',
        'contact-name-placeholder': 'Your name',
        'contact-email': 'Email:',
        'contact-email-helper': 'Valid email address',
        'contact-email-placeholder': 'your@email.com',
        'contact-service': 'Service:',
        'contact-service-select': '-- Select service --',
        'contact-service-web': 'Website or web application creation',
        'contact-service-gameserver': 'Game server technician',
        'contact-service-server': 'Server technician',
        'contact-service-consult': 'Consultation and inquiries',
        'contact-message': 'Message:',
        'contact-message-helper': '10-1000 characters',
        'contact-message-placeholder': 'Describe in detail what you need... (10-1000 characters)',
        'contact-submit': 'Send',
        'contact-social-title': 'Contact me also via social networks:'
    }
};

// Language Management
let currentLang = 'cs';

// Detect user language
function detectLanguage() {
    // Check URL path
    if (window.location.pathname.includes('/en/') || window.location.pathname.endsWith('/en')) {
        return 'en';
    }
    
    // Check localStorage
    const savedLang = localStorage.getItem('language');
    if (savedLang) {
        return savedLang;
    }
    
    // Default to Czech
    return 'cs';
}

// Apply translations
function applyTranslations(lang) {
    document.querySelectorAll('[data-translate]').forEach(element => {
        const key = element.getAttribute('data-translate');
        if (translations[lang][key]) {
            if (element.hasAttribute('placeholder')) {
                element.placeholder = translations[lang][key];
            } else if (element.tagName === 'OPTION') {
                element.textContent = translations[lang][key];
            } else {
                element.textContent = translations[lang][key];
            }
        }
    });
    
    console.log('Language changed to:', lang, '- Applied to', document.querySelectorAll('[data-translate]').length, 'elements');
}

// Change language
function changeLanguage(lang) {
    currentLang = lang;
    localStorage.setItem('language', lang);
    
    // Update HTML lang attribute
    document.documentElement.lang = lang;
    
    // Redirect to appropriate URL for SEO
    const currentPath = window.location.pathname;
    
    if (lang === 'en') {
        if (!currentPath.includes('/en/') && !currentPath.endsWith('/en')) {
            // Redirect to English version
            window.location.href = '/en/';
            return; // Exit to prevent applying translations before redirect
        }
    } else {
        if (currentPath.includes('/en/') || currentPath.endsWith('/en')) {
            // Redirect to Czech version (root)
            window.location.href = '/';
            return; // Exit to prevent applying translations before redirect
        }
    }
    
    applyTranslations(lang);
}

// Initialize language
function initLanguage() {
    const detectedLang = detectLanguage();
    currentLang = detectedLang;
    
    console.log('Initializing language:', currentLang);
    
    // Update toggle state
    const langToggle = document.getElementById('language-toggle');
    if (langToggle) {
        langToggle.checked = (currentLang === 'en');
        console.log('Toggle state set to:', langToggle.checked);
    }
    
    applyTranslations(currentLang);
}

// Navbar Active State
function updateActiveNavLink() {
    const sections = document.querySelectorAll('section, div[id]');
    const navLinks = document.querySelectorAll('.links-center ul li a');
    
    let current = '';
    
    sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.clientHeight;
        if (window.scrollY >= (sectionTop - 100)) {
            current = section.getAttribute('id');
        }
    });
    
    navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href').substring(1) === current) {
            link.classList.add('active');
        }
    });
}

// Scroll Indicator
const scrollIndicator = document.querySelector('.scroll-indicator');
let scrolled = false;

window.addEventListener('scroll', () => {
    // Update active nav link
    updateActiveNavLink();
    
    // Hide scroll indicator
    if (!scrolled && window.scrollY > 50) {
        if (scrollIndicator) {
            scrollIndicator.classList.add('hidden');
            scrolled = true;
        }
    }
});

// Event Listeners
document.addEventListener('DOMContentLoaded', () => {
    // Initialize language
    initLanguage();
    
    // Update portfolio image based on theme and language
    function updatePortfolioImage() {
        const portfolioImg = document.getElementById('portfolio-preview');
        if (portfolioImg) {
            const isLightTheme = document.body.classList.contains('light-theme');
            const isEnglish = window.location.pathname.includes('/en/');
            const theme = isLightTheme ? 'light' : 'dark';
            const lang = isEnglish ? 'en' : 'cz';
            const basePath = isEnglish ? '../images/' : 'images/';
            portfolioImg.src = `${basePath}portfolio-${theme}-${lang}.webp`;
        }
    }
    
    // Theme toggle
    const themeToggle = document.getElementById('theme-toggle');
    const savedTheme = localStorage.getItem('theme');
    
    if (savedTheme === 'light') {
        document.body.classList.add('light-theme');
        if (themeToggle) themeToggle.checked = true;
    }
    
    // Update portfolio image on load
    updatePortfolioImage();
    
    if (themeToggle) {
        themeToggle.addEventListener('change', (e) => {
            if (e.target.checked) {
                document.body.classList.add('light-theme');
                localStorage.setItem('theme', 'light');
            } else {
                document.body.classList.remove('light-theme');
                localStorage.setItem('theme', 'dark');
            }
            updatePortfolioImage();
        });
    }
    
    // Language toggle
    const langToggle = document.getElementById('language-toggle');
    console.log('Language toggle element:', langToggle);
    if (langToggle) {
        langToggle.addEventListener('change', (e) => {
            console.log('Toggle changed! Checked:', e.target.checked);
            changeLanguage(e.target.checked ? 'en' : 'cs');
        });
        console.log('Language toggle listener added');
    } else {
        console.error('Language toggle element not found!');
    }
    
    // Smooth scroll for indicator
    if (scrollIndicator) {
        scrollIndicator.addEventListener('click', () => {
            const skillsSection = document.querySelector('.skills');
            if (skillsSection) {
                skillsSection.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }
    
    // Initial active nav link
    updateActiveNavLink();
    
    // Hamburger menu
    const hamburger = document.querySelector('.hamburger');
    const linksCenter = document.querySelector('.links-center');
    const controlsRight = document.querySelector('.controls-right');
    const navLinks = document.querySelectorAll('.links-center ul li a');
    
    if (hamburger) {
        hamburger.addEventListener('click', (e) => {
            e.stopPropagation();
            const isActive = linksCenter.classList.contains('active');
            
            if (isActive) {
                linksCenter.classList.remove('active');
                controlsRight.classList.remove('active');
                hamburger.querySelector('.material-symbols-outlined').textContent = 'menu';
            } else {
                linksCenter.classList.add('active');
                controlsRight.classList.add('active');
                hamburger.querySelector('.material-symbols-outlined').textContent = 'close';
            }
        });
        
        // Close menu when clicking nav links
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                linksCenter.classList.remove('active');
                controlsRight.classList.remove('active');
                hamburger.querySelector('.material-symbols-outlined').textContent = 'menu';
            });
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (linksCenter.classList.contains('active') && 
                !linksCenter.contains(e.target) && 
                !hamburger.contains(e.target) &&
                !controlsRight.contains(e.target)) {
                linksCenter.classList.remove('active');
                controlsRight.classList.remove('active');
                hamburger.querySelector('.material-symbols-outlined').textContent = 'menu';
            }
        });
    }
    
    // Optimized scroll animations using IntersectionObserver
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                // Unobserve after animation for better performance
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Observe sections and items
    const animateElements = [
        '.skills',
        '.projects',
        '.offer',
        '.contact',
        '.skill_item',
        '.project_item',
        '.offer_item'
    ];
    
    animateElements.forEach(selector => {
        const elements = document.querySelectorAll(selector);
        elements.forEach((el, index) => {
            el.classList.add('animate-on-scroll');
            // Stagger animation delay for items
            if (selector.includes('_item')) {
                el.style.transitionDelay = `${index * 0.05}s`;
            }
            observer.observe(el);
        });
    });
});
