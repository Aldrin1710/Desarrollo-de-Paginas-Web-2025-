document.addEventListener("DOMContentLoaded", () => {

    const themeToggleBtn = document.getElementById('theme-toggle');
    const body = document.body;

    // Revisar si hay un tema guardado
    const currentTheme = localStorage.getItem('escine-theme');
    if (currentTheme === 'light') {
        body.classList.add('light-mode');
        if(themeToggleBtn) themeToggleBtn.textContent = '☀️';
    } else {
        if(themeToggleBtn) themeToggleBtn.textContent = '🌙';
    }

    // Cambiar tema al hacer clic
    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', () => {
            body.classList.toggle('light-mode');

            if (body.classList.contains('light-mode')) {
                localStorage.setItem('escine-theme', 'light');
                themeToggleBtn.textContent = '☀️';
            } else {
                localStorage.setItem('escine-theme', 'dark');
                themeToggleBtn.textContent = '🌙';
            }
        });
    }

    //  Validación
    const reviewForm = document.getElementById('review-form');
    const errorMessageDiv = document.getElementById('form-error-message');

    if (reviewForm) {
        reviewForm.addEventListener('submit', (event) => {
            event.preventDefault(); 
            
            if(errorMessageDiv) errorMessageDiv.textContent = '';

            const movieTitle = document.getElementById('movie-title').value;
            const movieRating = document.getElementById('movie-rating').value;
            const movieComment = document.getElementById('movie-comment').value;

            if (movieTitle.trim() === '' || movieRating === '' || movieComment.trim() === '') {
                if(errorMessageDiv) errorMessageDiv.textContent = 'Error: Debes completar todos los campos.';
                return; 
            }

            console.log('Formulario válido. Datos:', { movieTitle, movieRating, movieComment });
            alert('¡Reseña enviada con éxito! (Prototipo)');
            reviewForm.reset();
        });
    }


    const searchForm = document.getElementById('search-form');
    
    if(searchForm) {
        searchForm.addEventListener('submit', (event) => {
            event.preventDefault();
            const searchInput = document.getElementById('search-input');
            const query = searchInput ? searchInput.value : '';
            
            console.log(`(AJAX) Buscando: ${query}`);
            
            const resultsContainer = document.getElementById('search-results');
            
            if (resultsContainer) {
                resultsContainer.innerHTML = `<p>Mostrando resultados para: <strong>${query}</strong> (Simulado)</p>`;
            }
        });
    }

    // Lógica de navegación de usuario
    updateUserNavigation();
});

// GESTIÓN DE USUARIO 
function updateUserNavigation() {
    const navList = document.querySelector('.main-nav ul');
    
    const isLoggedIn = false; 

    if (isLoggedIn && navList) {
        
        
        let userProfile = JSON.parse(localStorage.getItem('escine_profile'));
        
        
        if (!userProfile) {
            userProfile = { name: "Usuario", avatarCustom: null };
        }

        const userName = userProfile.name || "Usuario";
        
        
        let userAvatar;
        
        if (userProfile.avatarCustom) {
            
            userAvatar = userProfile.avatarCustom; 
        } else {
            
            userAvatar = `https://api.dicebear.com/7.x/avataaars/svg?seed=${encodeURIComponent(userName)}`;
        }

        
        const loginBtn = navList.querySelector('.btn-secondary');
        const registerBtn = navList.querySelector('.btn-primary');

        
        if (loginBtn && registerBtn) {
            
            
            if(loginBtn.parentElement) loginBtn.parentElement.remove();
            if(registerBtn.parentElement) registerBtn.parentElement.remove();

            
            const userLi = document.createElement('li');
            userLi.innerHTML = `
                <a href="mi-perfil.php" class="nav-profile-link" title="Ir a mi perfil">
                    <span class="nav-username">${userName}</span>
                    <img src="${userAvatar}" alt="Perfil" class="nav-avatar">
                </a>
            `;
            
            
            const logoutLi = document.createElement('li');
            logoutLi.innerHTML = `<a href="cerrar-sesion.php" style="font-size: 15px; color: #E50914;">Salir</a>`;

            
            navList.appendChild(userLi);
            navList.appendChild(logoutLi);
        }
    }
}