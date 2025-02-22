import { Controller } from "@hotwired/stimulus";
import { Modal } from "bootstrap";

export default class extends Controller {
    close() {
        let modal = this.element.closest(".modal");
        let modalInstance = Modal.getInstance(modal);
        if (modalInstance) {
            modalInstance.hide();
        }
    }
}