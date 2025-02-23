import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    loadItems(event) {
        let spinner = document.getElementById('spinner');
        if (spinner) {
            spinner.classList.remove("d-none");
        }

        event.preventDefault();
        const url = event.currentTarget.dataset.todolistControlsUrl;
        this.element.dispatchEvent(
            new CustomEvent('item-selected', {
                detail: { url},
                bubbles: true,
            })
        );
    }
}
