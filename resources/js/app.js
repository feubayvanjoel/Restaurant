import Alpine from 'alpinejs';
import Toastify from 'toastify-js';
import 'toastify-js/src/toastify.css';
import './bootstrap';

// Initialiser Alpine.js (sera appelé APRÈS la définition des composants)
window.Alpine = Alpine;

/**
 * Helper global pour afficher des toasts (notifications)
 * @param {string} message - Message à afficher
 * @param {string} type - Type: 'success', 'error', 'info', 'warning'
 */
window.toast = function (message, type = 'success') {
    const colors = {
        success: 'linear-gradient(to right, #10b981, #059669)',
        error: 'linear-gradient(to right, #ef4444, #dc2626)',
        info: 'linear-gradient(to right, #3b82f6, #2563eb)',
        warning: 'linear-gradient(to right, #f59e0b, #d97706)'
    };

    Toastify({
        text: message,
        duration: 3000,
        gravity: 'top',
        position: 'left',
        stopOnFocus: true,
        style: {
            background: colors[type] || colors.info,
        },
    }).showToast();
};

/**
 * Helper pour le polling (actualisation automatique)
 * @param {string} url - URL à interroger
 * @param {function} callback - Fonction de callback avec les données
 * @param {number} interval - Intervalle en millisecondes (défaut: 10000)
 */
window.startPolling = function (url, callback, interval = 10000) {
    const poll = setInterval(async () => {
        try {
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            });

            if (response.ok) {
                const data = await response.json();
                callback(data);
            }
        } catch (error) {
            console.error('Erreur polling:', error);
        }
    }, interval);

    return poll; // Retourner l'ID pour pouvoir arrêter le polling
};

/**
 * Helper pour arrêter le polling
 * @param {number} pollId - ID du polling à arrêter
 */
window.stopPolling = function (pollId) {
    clearInterval(pollId);
};

/**
 * Helper pour formater les prix
 * @param {number} prix - Prix à formater
 * @returns {string} Prix formaté (ex: "12,50 €")
 */
window.formatPrix = function (prix) {
    return new Intl.NumberFormat('fr-BE', {
        style: 'currency',
        currency: 'EUR'
    }).format(prix);
};

/**
 * Helper pour formater les dates
 * @param {string} date - Date ISO à formater
 * @returns {string} Date formatée (ex: "06/12/2025 18:30")
 */
window.formatDate = function (date) {
    return new Intl.DateTimeFormat('fr-BE', {
        dateStyle: 'short',
        timeStyle: 'short'
    }).format(new Date(date));
};

/**
 * Composant Alpine.js pour le compte à rebours
 */
Alpine.data('countdown', (endTime) => ({
    remaining: '',
    timer: null,

    init() {
        this.updateCountdown();
        this.timer = setInterval(() => this.updateCountdown(), 1000);
    },

    updateCountdown() {
        const now = new Date().getTime();
        const end = new Date(endTime).getTime();
        const distance = end - now;

        if (distance < 0) {
            this.remaining = 'Expiré';
            clearInterval(this.timer);
            return;
        }

        const hours = Math.floor(distance / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        this.remaining = `${hours}h ${minutes}m ${seconds}s`;
    },

    destroy() {
        if (this.timer) {
            clearInterval(this.timer);
        }
    }
}));

/**
 * Composant Alpine.js : menuManager (panier + onglets)
 */
Alpine.data('menuManager', () => ({
    init() {
        console.log('menuManager initialized');
    },
    tab: 'plats',
    cart: [],
    showCart: false,

    addToCart(item) {
        console.log('Adding to cart:', item);
        const existing = this.cart.find(i => i.id === item.id && i.type === item.type);
        if (existing) {
            existing.quantite++;
        } else {
            this.cart.push({...item, quantite: 1});
        }
        if (globalThis.toast) globalThis.toast('Ajouté au panier', 'success');
    },

    removeFromCart(index) {
        this.cart.splice(index, 1);
        if (globalThis.toast) globalThis.toast('Retiré du panier', 'info');
    },

    updateQuantity(index, delta) {
        this.cart[index].quantite += delta;
        if (this.cart[index].quantite <= 0) {
            this.removeFromCart(index);
        }
    },

    getTotal() {
        return this.cart.reduce((sum, item) => sum + (item.prix * item.quantite), 0);
    },

    proceedToOrder() {
        if (this.cart.length === 0) {
            if (globalThis.toast) globalThis.toast('Le panier est vide', 'warning');
            return;
        }
        sessionStorage.setItem('cart', JSON.stringify(this.cart));
        location.href = '/client/commandes/create';
    }
}));

// Fallback global function so x-data="menuManager()" works reliably
window.menuManager = () => ({
    init() {
        console.log('menuManager (fallback) initialized');
    },
    tab: 'plats',
    cart: [],
    showCart: false,

    addToCart(item) {
        console.log('Adding to cart:', item);
        const existing = this.cart.find(i => i.id === item.id && i.type === item.type);
        if (existing) {
            existing.quantite++;
        } else {
            this.cart.push({...item, quantite: 1});
        }
        if (window.toast) window.toast('Ajouté au panier', 'success');
    },

    removeFromCart(index) {
        this.cart.splice(index, 1);
        if (window.toast) window.toast('Retiré du panier', 'info');
    },

    updateQuantity(index, delta) {
        this.cart[index].quantite += delta;
        if (this.cart[index].quantite <= 0) {
            this.removeFromCart(index);
        }
    },

    getTotal() {
        return this.cart.reduce((sum, item) => sum + (item.prix * item.quantite), 0);
    },

    proceedToOrder() {
        if (this.cart.length === 0) {
            if (window.toast) window.toast('Le panier est vide', 'warning');
            return;
        }
        sessionStorage.setItem('cart', JSON.stringify(this.cart));
        location.href = '/client/commandes/create';
    }
});

// *** START ALPINE.JS APRÈS définition des composants et fonctions ***
// Alpine.js handles DOM readiness internally, so we can call start() immediately
console.log('About to start Alpine.js');
Alpine.start();
console.log('Alpine.js started successfully');
