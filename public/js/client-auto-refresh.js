/**
 * Système de rafraîchissement silencieux pour les pages client
 * Actualise les données toutes les 2 secondes sans perturber l'utilisateur
 */

class ClientAutoRefresh {
    constructor(refreshUrl, updateCallback, interval = 2000) {
        this.refreshUrl = refreshUrl;
        this.updateCallback = updateCallback;
        this.interval = interval;
        this.intervalId = null;
        this.isUpdating = false;
    }

    /**
     * Démarrer le rafraîchissement automatique
     */
    start() {
        // Premier rafraîchissement immédiat (optionnel, commenté pour éviter double chargement initial)
        // this.refresh();

        // Rafraîchissement toutes les 2 secondes
        this.intervalId = setInterval(() => this.refresh(), this.interval);
    }

    /**
     * Arrêter le rafraîchissement automatique
     */
    stop() {
        if (this.intervalId) {
            clearInterval(this.intervalId);
            this.intervalId = null;
        }
    }

    /**
     * Effectuer le rafraîchissement
     */
    async refresh() {
        // Éviter les requêtes simultanées
        if (this.isUpdating) return;

        this.isUpdating = true;

        try {
            const response = await fetch(this.refreshUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                console.error('Erreur de rafraîchissement:', response.status);
                return;
            }

            const data = await response.json();

            // Appeler le callback pour mettre à jour l'interface
            this.updateCallback(data);

        } catch (error) {
            console.error('Erreur lors du rafraîchissement:', error);
        } finally {
            this.isUpdating = false;
        }
    }
}

/**
 * Utilitaires pour mettre à jour le DOM sans perturber l'utilisateur
 */
const DOMUtils = {
    /**
     * Mettre à jour le contenu d'un élément en préservant le scroll
     */
    updateElement(selector, newContent) {
        const element = document.querySelector(selector);
        if (!element) return;

        // Sauvegarder la position de scroll
        const scrollTop = window.scrollY;
        const scrollLeft = window.scrollX;

        // Mettre à jour le contenu
        element.innerHTML = newContent;

        // Restaurer la position de scroll
        window.scrollTo(scrollLeft, scrollTop);
    },

    /**
     * Mettre à jour un texte sans changer le DOM si identique
     */
    updateText(selector, newText) {
        const element = document.querySelector(selector);
        if (!element) return;

        if (element.textContent !== newText) {
            element.textContent = newText;
        }
    },

    /**
     * Mettre à jour une badge/compteur
     */
    updateBadge(selector, count) {
        const element = document.querySelector(selector);
        if (!element) return;

        const currentCount = parseInt(element.textContent) || 0;
        if (currentCount !== count) {
            element.textContent = count;
        }
    },

    /**
     * Comparer deux contenus HTML (ignore les espaces)
     */
    areEquivalent(html1, html2) {
        const normalize = (html) => html.replace(/\s+/g, ' ').trim();
        return normalize(html1) === normalize(html2);
    }
};
