import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        // Listen for custom events
        document.addEventListener('item-selected', (event) => {
            this.loadContent(event.detail.url);
        });
    }

    async loadContent(url) {
        try {
            const response = await fetch(url, {
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    "Content-Type": "application/json" 
                }
            });

            if (!response.ok) {
                throw new Error(`Failed to fetch content: ${response.status}`);
            }

            const html = await response.text();
            this.element.querySelector('#dynamic-content').innerHTML = html;
        } catch (error) {
            this.element.querySelector('#dynamic-content').innerHTML = "Failed to load content.";
        }
    }
}
