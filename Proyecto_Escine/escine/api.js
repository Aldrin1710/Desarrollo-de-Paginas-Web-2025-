const API_KEY = ' '; 
const BASE_URL = 'https://api.themoviedb.org/3';
const IMG_BASE_URL = 'https://image.tmdb.org/t/p/w500';
const IMG_BIG_URL = 'https://image.tmdb.org/t/p/w1280';
const LANGUAGE = '&language=es-MX'; 

// Variable para controlar la página actual
let currentPage = 1;

//MAPAS DE IDs
const GENRE_IDS = {
    'movie': { 'accion': 28, 'comedia': 35, 'drama': 18, 'sci-fi': 878, 'terror': 27, 'documental': 99 },
    'tv':    { 'accion': 10759, 'comedia': 35, 'drama': 18, 'sci-fi': 10765, 'misterio': 9648, 'documental': 99 }
};

const PROVIDER_IDS = { 'netflix': 8, 'hbo': 384, 'disney': 337, 'prime': 119 };

//INICIALIZACIÓN
document.addEventListener('DOMContentLoaded', () => {
    const path = window.location.pathname;

    // Configuración de búsqueda global
    setupLiveSearch();

    if (path.includes('index.php') || path.endsWith('/')) {
        fetchPopularMovies();
        fetchPopularSeries();
        fetchTopRatedMovies();
        fetchUpcomingMovies();
        fetchMovieNews();
    } else if (path.includes('detalle-pelicula.php')) {
        fetchDetails();
    } else if (path.includes('peliculas.php')) {

        setupFilters('movie');
        fetchMoviesCatalog(currentPage);
        setupPagination(() => fetchMoviesCatalog(currentPage));
    } else if (path.includes('series.php')) {

        setupFilters('tv');
        fetchSeriesCatalog(currentPage);
        setupPagination(() => fetchSeriesCatalog(currentPage));
    } else if (path.includes('mi-perfil.php')) {

    } else if (path.includes('coleccion.php')) {
        fetchCollectionDetails(); 
    }
});

//LÓGICA DE FILTROS
function setupFilters(type) {
    const filters = document.querySelectorAll('.filter-controls select');
    filters.forEach(select => {
        select.addEventListener('change', () => {
            currentPage = 1;
            if (type === 'movie') fetchMoviesCatalog(1);
            else fetchSeriesCatalog(1);
        });
    });
}

function getFilterParams(type) {
    const genre = document.getElementById('filter-genre')?.value;
    const year = document.getElementById('filter-year')?.value;
    const platform = document.getElementById('filter-platform')?.value;
    const sort = document.getElementById('filter-sort')?.value;

    let params = '';

    if (genre && GENRE_IDS[type][genre]) params += `&with_genres=${GENRE_IDS[type][genre]}`;
    
    if (year) {
        if (year.includes('-')) { } 
        else {
            if (type === 'movie') params += `&primary_release_year=${year}`;
            else params += `&first_air_date_year=${year}`;
        }
    }

    if (platform && PROVIDER_IDS[platform]) {
        params += `&with_watch_providers=${PROVIDER_IDS[platform]}&watch_region=MX`;
    }

    if (sort) {
        let sortParam = 'popularity.desc'; 
        switch(sort) {
            case 'popularidad': sortParam = 'popularity.desc'; break;
            case 'rating_desc': sortParam = 'vote_average.desc&vote_count.gte=100'; break;
            case 'fecha_desc': sortParam = (type === 'movie') ? 'primary_release_date.desc' : 'first_air_date.desc'; break;
            case 'titulo_asc': sortParam = (type === 'movie') ? 'original_title.asc' : 'name.asc'; break;
        }
        params += `&sort_by=${sortParam}`;
    }
    return params;
}

//FUNCIONES FETCH
async function fetchPopularMovies() {
    try {
        const res = await fetch(`${BASE_URL}/movie/popular?api_key=${API_KEY}${LANGUAGE}`);
        const data = await res.json();
        renderMovieGrid(data.results, '#popular-section .movie-grid', 5, 'movie'); 
    } catch (error) { console.error(error); }
}

async function fetchPopularSeries() {
    try {
        const res = await fetch(`${BASE_URL}/tv/popular?api_key=${API_KEY}${LANGUAGE}`);
        const data = await res.json();
        renderMovieGrid(data.results, '#popular-series-section .movie-grid', 5, 'tv'); 
    } catch (error) { console.error(error); }
}

async function fetchMoviesCatalog(page) {
    try {
        const filters = getFilterParams('movie'); 
        const url = `${BASE_URL}/discover/movie?api_key=${API_KEY}${LANGUAGE}&page=${page}${filters}`;
        const res = await fetch(url);
        const data = await res.json();
        renderMovieGrid(data.results, '#catalog-section .movie-grid', null, 'movie');
        updatePaginationUI(page, data.total_pages);
    } catch (error) { console.error(error); }
}

async function fetchSeriesCatalog(page) {
    try {
        const filters = getFilterParams('tv'); 
        const url = `${BASE_URL}/discover/tv?api_key=${API_KEY}${LANGUAGE}&page=${page}${filters}`;
        const res = await fetch(url);
        const data = await res.json();
        renderMovieGrid(data.results, '#catalog-section .movie-grid', null, 'tv');
        updatePaginationUI(page, data.total_pages);
    } catch (error) { console.error(error); }
}

//RENDERIZADO
function renderMovieGrid(items, selector, limit = null, mediaType = 'movie') {
    const grid = document.querySelector(selector);
    if (!grid) return;
    grid.innerHTML = ''; 

    const itemsToShow = limit ? items.slice(0, limit) : items;

    itemsToShow.forEach(item => {
        const title = item.title || item.name; 
        const date = item.release_date || item.first_air_date;
        const year = date ? date.split('-')[0] : 'N/A';
        const poster = item.poster_path ? `${IMG_BASE_URL}${item.poster_path}` : 'img/no-poster.jpg';
        
        // ID único para identificar dónde poner el promedio
        const ratingId = `local-rating-${item.id}`;

        const card = document.createElement('article');
        card.classList.add('movie-card');
        
        card.innerHTML = `
            <a href="detalle-pelicula.php?id=${item.id}&type=${mediaType}" style="text-decoration: none; color: inherit;">
                <img src="${poster}" alt="${title}" onerror="this.onerror=null; this.src='img/no-poster.jpg'"> 
                <div class="movie-info">
                    <h3>${title} (${year})</h3>
                    <div class="rating-container" style="display: flex; justify-content: space-between; align-items: center; font-size: 13px;">
                        
                        <span title="Calificación Mundial (TMDB)">
                            ⭐ ${item.vote_average.toFixed(1)}
                        </span>

                        <span id="${ratingId}" style="color: #4CAF50; font-weight: bold;" title="Calificación Comunidad ESCINE">
                            ...
                        </span>

                    </div>
                </div>
            </a>
        `;
        grid.appendChild(card);

        //FETCH AL PROMEDIO LOCAL
        fetch(`api-promedio.php?id=${item.id}`)
            .then(res => res.json())
            .then(data => {
                const element = document.getElementById(ratingId);
                if (data.promedio !== null) {
                    
                    element.innerHTML = `💚 ${data.promedio}`;
                } else {
                    
                    element.innerHTML = `<span style="color:#666; font-size: 11px;">Sin reseñas</span>`;
                }
            })
            .catch(err => console.error(err));
    });
}


async function fetchDetails() {
    const params = new URLSearchParams(window.location.search);
    const id = params.get('id');
    const type = params.get('type') || 'movie'; 

    if (!id) return;

    try {
        const url = `${BASE_URL}/${type}/${id}?api_key=${API_KEY}${LANGUAGE}&append_to_response=credits,videos`;
        const res = await fetch(url);
        const data = await res.json();

        if (type === 'tv') {
            data.title = data.name;
            data.release_date = data.first_air_date;
            data.runtime = data.episode_run_time ? data.episode_run_time[0] : null;
        }

        renderHero(data, type);
        renderDetails(data);
        fetchRecommendations(id, type);
        fetchWatchProviders(id, type);
        fetchKeywords(id, type);
        renderCollection(data);
        fetchExternalIds(id, type);

    } catch (error) { console.error(error); }
}

function renderHero(data, type) { 
    document.querySelector('.movie-title').textContent = `${data.title} (${data.release_date ? data.release_date.split('-')[0] : 'N/A'})`;
    
    let crewLabel = '';
    if (type === 'movie') {
        const director = data.credits.crew.find(p => p.job === 'Director');
        crewLabel = director ? `Dirigido por ${director.name}` : '';
    } else {
        if (data.created_by && data.created_by.length > 0) {
            crewLabel = `Creado por ${data.created_by[0].name}`;
        }
    }
    document.querySelector('.movie-director').textContent = crewLabel;

    let runtimeLabel = '';
    if (type === 'movie') {
        runtimeLabel = data.runtime ? `${data.runtime} min` : 'N/A';
    } else {
        runtimeLabel = `${data.number_of_seasons} Temp. / ${data.number_of_episodes} Eps.`;
    }
    document.querySelector('.movie-runtime').textContent = runtimeLabel;
    document.querySelector('.movie-year').textContent = data.release_date ? data.release_date.split('-')[0] : 'N/A';
    document.querySelector('.rating-value').textContent = `⭐ ${data.vote_average.toFixed(1)}/10`;
    document.querySelector('.fan-count').textContent = `${data.vote_count} Votos`;

    if (data.poster_path) {
        const img = document.querySelector('.movie-hero-poster img');
        img.src = `${IMG_BASE_URL}${data.poster_path}`;
        img.onerror = function() { this.onerror = null; this.src = 'img/no-poster.jpg'; };
    }

    if (data.backdrop_path) {
        const heroSection = document.querySelector('.movie-hero');
        const bgUrl = `${IMG_BIG_URL}${data.backdrop_path}`;
        heroSection.style.backgroundImage = `linear-gradient(to right, rgba(0,0,0,0.9) 20%, rgba(0,0,0,0.3) 100%), url('${bgUrl}')`;
        heroSection.style.backgroundSize = 'cover'; 
        heroSection.style.backgroundPosition = 'center top'; 
    } else {
        document.querySelector('.movie-hero').style.backgroundImage = 'none';
    }
}

function renderDetails(data) {
    document.querySelector('.movie-tagline').textContent = data.tagline || '';
    document.querySelector('.movie-overview').textContent = data.overview;

    const genresContainer = document.getElementById('genres-container');
    if (genresContainer) {
        genresContainer.innerHTML = data.genres.map(g => `<span class="tag">${g.name}</span>`).join('');
    }

    const castContainer = document.getElementById('cast-container');
    if (castContainer && data.credits && data.credits.cast) {
        const topCast = data.credits.cast.slice(0, 10);
        const castHTML = topCast.map(actor => `<span class="tag">${actor.name}</span>`).join('');
        castContainer.innerHTML = castHTML;
    }

    const trailer = data.videos.results.find(v => v.site === 'YouTube' && v.type === 'Trailer');
    const trailerContainer = document.querySelector('.trailer-container');
    if (trailer && trailerContainer) {
        trailerContainer.innerHTML = `<iframe width="100%" height="100%" src="https://www.youtube.com/embed/${trailer.key}" frameborder="0" allowfullscreen></iframe>`;
    } else if (trailerContainer) {
        trailerContainer.style.display = 'none';
    }



    // BOTONES DE RESEÑA 
    const reviewBtns = document.querySelectorAll('.movie-actions .btn-primary, .review-actions .btn-primary');
    
    if (reviewBtns.length > 0) {
        const date = data.release_date || data.first_air_date;
        const year = date ? date.split('-')[0] : '';
        const title = data.title || data.name;
        const posterEncoded = encodeURIComponent(`${IMG_BASE_URL}${data.poster_path}`);
        
        reviewBtns.forEach(btn => {
            btn.onclick = () => {
                // REDIRIGIR A PHP
                window.location.href = `escribir-resena.php?id=${data.id}&title=${encodeURIComponent(title)}&year=${year}&poster=${posterEncoded}`;
            };
        });
    }




    const sidebarList = document.getElementById('sidebar-details-list');
    if (sidebarList) {
        let sidebarHTML = '';
        const originalTitle = data.original_title || data.original_name;
        const currentTitle = data.title || data.name;
        if (originalTitle && originalTitle !== currentTitle) {
            sidebarHTML += `<li><i class="fas fa-heading"></i> <strong class="item-title">Orig.:</strong> <span>${originalTitle}</span></li>`;
        }
        if (data.status) {
            const statusMap = { 'Returning Series': 'En Emisión', 'Ended': 'Finalizada', 'Canceled': 'Cancelada', 'Released': 'Estrenada' };
            const status = statusMap[data.status] || data.status;
            sidebarHTML += `<li><i class="fas fa-info-circle"></i> <strong class="item-title">Estado:</strong> <span>${status}</span></li>`;
        }
        const date = data.release_date || data.first_air_date;
        if (date) {
            const dateObj = new Date(date);
            sidebarHTML += `<li><i class="far fa-calendar-alt"></i> <strong class="item-title">Fecha:</strong> <span>${dateObj.toLocaleDateString('es-MX')}</span></li>`;
        }
        let runtime = '';
        if (data.runtime) runtime = `${data.runtime} min`;
        else if (data.number_of_seasons) runtime = `${data.number_of_seasons} Temp. / ${data.number_of_episodes} Eps.`;
        if (runtime) sidebarHTML += `<li><i class="far fa-clock"></i> <strong class="item-title">Tiempo:</strong> <span>${runtime}</span></li>`;

        const formatter = new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD', maximumFractionDigits: 0 });
        if (data.budget && data.budget > 0) sidebarHTML += `<li><i class="fas fa-money-bill-wave"></i> <strong class="item-title">Ppto:</strong> <span>${formatter.format(data.budget)}</span></li>`;
        if (data.revenue && data.revenue > 0) sidebarHTML += `<li><i class="fas fa-chart-line"></i> <strong class="item-title">Taquilla:</strong> <span>${formatter.format(data.revenue)}</span></li>`;

        if (data.original_language) {
            sidebarHTML += `<li><i class="fas fa-language"></i> <strong class="item-title">Idioma:</strong> <span>${data.original_language.toUpperCase()}</span></li>`;
        }
        sidebarList.innerHTML = sidebarHTML;
    }

    // BOTÓN WATCHLIST
    const watchlistBtn = document.querySelector('.movie-actions .btn-secondary'); 
    if (watchlistBtn) {
        let watchlist = JSON.parse(localStorage.getItem('escine_watchlist')) || [];
        const isSaved = watchlist.some(item => item.id === data.id);
        
        if (isSaved) {
            watchlistBtn.innerHTML = '<i class="fas fa-check"></i> En tu lista';
            watchlistBtn.classList.add('btn-success'); 
        }

        watchlistBtn.onclick = () => {
            watchlist = JSON.parse(localStorage.getItem('escine_watchlist')) || [];
            if (!isSaved) {
                const movieData = {
                    id: data.id,
                    title: data.title || data.name,
                    poster_path: data.poster_path,
                    vote_average: data.vote_average,
                    release_date: data.release_date || data.first_air_date,
                    type: data.title ? 'movie' : 'tv'
                };
                watchlist.push(movieData);
                localStorage.setItem('escine_watchlist', JSON.stringify(watchlist));
                alert('¡Añadido a tu lista!');
                location.reload();
            } else {
                const newWatchlist = watchlist.filter(item => item.id !== data.id);
                localStorage.setItem('escine_watchlist', JSON.stringify(newWatchlist));
                alert('Eliminado de tu lista.');
                location.reload();
            }
        };
    }
}

// PAGINACIÓN
function updatePaginationUI(page, totalPages) {
    document.getElementById('page-info').textContent = `Página ${page}`;
    document.getElementById('prev-page').disabled = (page <= 1);
    document.getElementById('next-page').disabled = (page >= totalPages);
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function setupPagination(loadFunction) {
    const prevBtn = document.getElementById('prev-page');
    const nextBtn = document.getElementById('next-page');

    if (prevBtn && nextBtn) {
        const newPrev = prevBtn.cloneNode(true);
        const newNext = nextBtn.cloneNode(true);
        prevBtn.parentNode.replaceChild(newPrev, prevBtn);
        nextBtn.parentNode.replaceChild(newNext, nextBtn);

        newPrev.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                loadFunction();
            }
        });
        newNext.addEventListener('click', () => {
            currentPage++;
            loadFunction();
        });
    }
}


async function fetchRecommendations(id, type) {
    try {
        const res = await fetch(`${BASE_URL}/${type}/${id}/recommendations?api_key=${API_KEY}${LANGUAGE}`);
        const data = await res.json();
        if (data.results.length > 0) {
            const recSection = document.getElementById('recommendations-section');
            if (recSection) {
                recSection.style.display = 'block';
                renderMovieGrid(data.results, '#recommendations-section .movie-grid', 5, type);
            }
        }
    } catch (error) { console.error("Error recomendaciones:", error); }
}

// DÓNDE VER
async function fetchWatchProviders(id, type) {
    try {
        const res = await fetch(`${BASE_URL}/${type}/${id}/watch/providers?api_key=${API_KEY}`);
        const data = await res.json();
        const countryData = data.results && data.results.MX; 
        const container = document.getElementById('watch-providers-container');

        if (!container) return;
        container.innerHTML = ''; 

        if (countryData && countryData.flatrate) {
            const watchLink = countryData.link; 
            countryData.flatrate.forEach(provider => {
                const logo = `${IMG_BASE_URL}${provider.logo_path}`;
                const link = document.createElement('a');
                link.className = 'platform-item';
                link.href = watchLink; 
                link.target = "_blank"; 
                link.innerHTML = `<img src="${logo}" alt="${provider.provider_name}"><span>${provider.provider_name}</span><span class="availability">VER</span>`;
                container.appendChild(link);
            });
        } else {
            container.innerHTML = '<p style="color: #aaa;">No disponible en streaming en tu región.</p>';
        }
        const attribution = document.createElement('div');
        attribution.innerHTML = '<a href="https://www.themoviedb.org/" target="_blank" style="font-size: 10px; color: #666; margin-top: 10px; display: block;">Datos de JustWatch via TMDb</a>';
        container.appendChild(attribution);
    } catch (error) { console.error("Error providers:", error); }
}

// PALABRAS CLAVE
async function fetchKeywords(id, type) {
    try {
        const res = await fetch(`${BASE_URL}/${type}/${id}/keywords?api_key=${API_KEY}`);
        const data = await res.json();
        const keywords = data.keywords || data.results || [];
        const container = document.getElementById('keywords-container');
        const widget = document.getElementById('keywords-widget');

        if (keywords.length > 0 && container) {
            widget.style.display = 'block'; 
            container.innerHTML = keywords.map(k => `<span class="keyword-tag" style="background: rgba(255,255,255,0.1); padding: 5px 10px; border-radius: 5px; font-size: 12px; color: #ccc; border: 1px solid #444;">${k.name}</span>`).join('');
        }
    } catch (error) { console.error("Error keywords:", error); }
}

// COLECCIÓN
function renderCollection(data) {
    const widget = document.getElementById('collection-widget');
    const container = document.getElementById('collection-container');

    if (data.belongs_to_collection && container) {
        widget.style.display = 'block';
        const col = data.belongs_to_collection;
        const bgImage = col.backdrop_path ? `${IMG_BASE_URL}${col.backdrop_path}` : `${IMG_BASE_URL}${col.poster_path}`;

        container.innerHTML = `
            <img src="${bgImage}" alt="${col.name}" class="collection-bg" onerror="this.style.display='none'">
            <div class="collection-info">
                <h4>${col.name}</h4>
                <span class="collection-btn">Ver Colección &rarr;</span>
            </div>
        `;
        container.onclick = () => window.location.href = `coleccion.php?id=${col.id}`;
    } else {
        if(widget) widget.style.display = 'none';
    }
}

// DETALLES DE COLECCIÓN
async function fetchCollectionDetails() {
    const params = new URLSearchParams(window.location.search);
    const collectionId = params.get('id');
    if (!collectionId) return;

    try {
        const res = await fetch(`${BASE_URL}/collection/${collectionId}?api_key=${API_KEY}${LANGUAGE}`);
        const data = await res.json();
        document.getElementById('collection-title').textContent = data.name;
        const overviewEl = document.getElementById('collection-overview');
        if(overviewEl) overviewEl.textContent = data.overview;
        renderMovieGrid(data.parts, '#catalog-section .movie-grid', null, 'movie');
    } catch (error) { console.error("Error collection:", error); }
}

// ENLACES EXTERNOS
async function fetchExternalIds(id, type) {
    try {
        const res = await fetch(`${BASE_URL}/${type}/${id}/external_ids?api_key=${API_KEY}`);
        const data = await res.json();
        const container = document.getElementById('social-links-container');
        const widget = document.getElementById('social-links-widget');
        let linksHTML = '';

        if (data.imdb_id) linksHTML += `<a href="https://www.imdb.com/title/${data.imdb_id}" target="_blank" class="social-icon-btn" title="IMDb"><i class="fab fa-imdb"></i></a>`;
        if (data.facebook_id) linksHTML += `<a href="https://www.facebook.com/${data.facebook_id}" target="_blank" class="social-icon-btn" title="Facebook"><i class="fab fa-facebook-f"></i></a>`;
        if (data.instagram_id) linksHTML += `<a href="https://www.instagram.com/${data.instagram_id}" target="_blank" class="social-icon-btn" title="Instagram"><i class="fab fa-instagram"></i></a>`;
        if (data.twitter_id) linksHTML += `<a href="https://twitter.com/${data.twitter_id}" target="_blank" class="social-icon-btn" title="Twitter"><i class="fab fa-twitter"></i></a>`;

        if (linksHTML && container) {
            widget.style.display = 'block';
            container.innerHTML = linksHTML;
        }
    } catch (error) { console.error("Error external IDs:", error); }
}

// PERFIL
function loadUserProfile() {
  

    const watchlist = JSON.parse(localStorage.getItem('escine_watchlist')) || [];
    const grid = document.getElementById('watchlist-grid');
    const statCount = document.getElementById('stat-watchlist');
    
    if (statCount) statCount.textContent = watchlist.length;

    if (watchlist.length > 0 && grid) {
        grid.innerHTML = ''; 
        watchlist.forEach(item => {
            const year = item.release_date ? item.release_date.split('-')[0] : 'N/A';
            const poster = item.poster_path ? `${IMG_BASE_URL}${item.poster_path}` : 'img/no-poster.jpg';
            const type = item.type || 'movie'; 
            
            const card = document.createElement('article');
            card.classList.add('movie-card');
            
            card.innerHTML = `
                <a href="detalle-pelicula.php?id=${item.id}&type=${type}" style="text-decoration: none; color: inherit;">
                    <img src="${poster}" alt="${item.title}" onerror="this.src='img/no-poster.jpg'">
                    <div class="movie-info">
                        <h3>${item.title} (${year})</h3>
                        <div class="rating">⭐ ${item.vote_average.toFixed(1)}/10</div>
                    </div>
                </a>
            `;
            grid.appendChild(card);
        });
    }
}



// TOP RATED
async function fetchTopRatedMovies() {
    try {
        const res = await fetch(`${BASE_URL}/movie/top_rated?api_key=${API_KEY}${LANGUAGE}`);
        const data = await res.json();
        renderMovieGrid(data.results, '#top-rated-section .movie-grid', 5, 'movie');
    } catch (error) { console.error("Error cargando top rated:", error); }
}

// UPCOMING
async function fetchUpcomingMovies() {
    try {
        const res = await fetch(`${BASE_URL}/movie/upcoming?api_key=${API_KEY}${LANGUAGE}&page=1&region=MX`);
        const data = await res.json();
        const listContainer = document.getElementById('upcoming-list');
        if (!listContainer) return;

        const today = new Date();
        let upcoming = data.results.filter(movie => {
            if (!movie.release_date) return false;
            const releaseDate = new Date(movie.release_date);
            return releaseDate >= today;
        });
        upcoming.sort((a, b) => new Date(a.release_date) - new Date(b.release_date));
        upcoming = upcoming.slice(0, 5);
        
        if (upcoming.length === 0) {
            listContainer.innerHTML = '<li style="color:#888; font-size:14px;">No hay estrenos próximos confirmados pronto.</li>';
            return;
        }

        listContainer.innerHTML = upcoming.map(movie => {
            const dateObj = new Date(movie.release_date + 'T00:00:00'); 
            const dateFormatted = dateObj.toLocaleDateString('es-MX', { day: 'numeric', month: 'short' });
            const poster = movie.poster_path ? `${IMG_BASE_URL}${movie.poster_path}` : 'img/no-poster.jpg';
          
            return `
                <li style="margin-bottom: 15px;">
                    <a href="detalle-pelicula.php?id=${movie.id}&type=movie" style="text-decoration: none; display: flex; gap: 10px; align-items: center;">
                        <img src="${poster}" alt="${movie.title}" style="width: 45px; height: 68px; object-fit: cover; border-radius: 4px;" onerror="this.src='img/no-poster.jpg'">
                        <div style="display: flex; flex-direction: column;">
                            <span style="font-weight: bold; color: var(--color-secondary); font-size: 14px; line-height: 1.2;">${movie.title}</span>
                            <span style="font-size: 12px; color: var(--color-primary); margin-top: 4px; font-weight: bold;">📅 Estreno: ${dateFormatted}</span>
                        </div>
                    </a>
                </li>
            `;
        }).join('');
    } catch (error) { console.error("Error upcoming:", error); }
}

// NEWS
async function fetchMovieNews() {
    try {
        const res = await fetch(`${BASE_URL}/movie/now_playing?api_key=${API_KEY}${LANGUAGE}&page=1&region=MX`);
        const data = await res.json();
        const newsContainer = document.getElementById('news-grid-container');
        if (!newsContainer) return;

        const newsItems = data.results.slice(0, 3);
        newsContainer.innerHTML = newsItems.map(movie => {
            const image = movie.backdrop_path ? `${IMG_BASE_URL}${movie.backdrop_path}` : 'img/no-poster.jpg';
            const overview = movie.overview ? movie.overview.substring(0, 100) + '...' : 'Conoce todos los detalles de este gran estreno.';

            return `
                <article class="news-card">
                    <img src="${image}" alt="${movie.title}" onerror="this.src='img/no-poster.jpg'">
                    <div class="news-card-content">
                        <h3>¡Ya en cines! ${movie.title} arrasa en taquilla</h3>
                        <p>${overview}</p>
                        <a href="detalle-pelicula.php?id=${movie.id}&type=movie" class="btn btn-secondary btn-small">Leer más</a>
                    </div>
                </article>
            `;
        }).join('');
    } catch (error) { console.error("Error news:", error); }
}

// BÚSQUEDA
let searchTimeout; 
function setupLiveSearch() {
    const inputIndex = document.getElementById('search-input');
    const inputMovies = document.getElementById('search-input-peliculas');
    const inputSeries = document.getElementById('search-input-series');

    if (inputIndex) configureSearch(inputIndex, 'multi', '#search-results'); 
    if (inputMovies) configureSearch(inputMovies, 'movie', '#catalog-section .movie-grid');
    if (inputSeries) configureSearch(inputSeries, 'tv', '#catalog-section .movie-grid');
}

function configureSearch(inputElement, type, resultsSelector) {
    inputElement.addEventListener('input', (e) => {
        const query = e.target.value.trim();
        clearTimeout(searchTimeout);

        if (query.length === 0) {
            restoreOriginalView();
            if (type === 'multi') document.querySelector(resultsSelector).innerHTML = ''; 
            return;
        }

        searchTimeout = setTimeout(() => {
            performSearch(query, type, resultsSelector);
        }, 500);
    });

    const form = inputElement.closest('form');
    if (form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            clearTimeout(searchTimeout);
            performSearch(inputElement.value, type, resultsSelector);
        });
    }
}

async function performSearch(query, type, selector) {
    if (!query) return;
    try {
        const url = `${BASE_URL}/search/${type}?api_key=${API_KEY}${LANGUAGE}&query=${encodeURIComponent(query)}&page=1`;
        const res = await fetch(url);
        const data = await res.json();
        const container = document.querySelector(selector);
        
        if (data.results.length === 0) {
            container.innerHTML = `<p style="text-align:center; width:100%; padding: 20px;">No se encontraron resultados para "${query}".</p>`;
        } else {
            renderMovieGrid(data.results, selector, null, type === 'multi' ? 'movie' : type);
        }
    } catch (error) { console.error("Error en búsqueda:", error); }
}

function restoreOriginalView() {
    const path = window.location.pathname;
    if (path.includes('peliculas.php')) {
        fetchMoviesCatalog(1); 
    } else if (path.includes('series.php')) {
        fetchSeriesCatalog(1);
    }
}