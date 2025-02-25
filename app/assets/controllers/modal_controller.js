import { Controller } from "@hotwired/stimulus";
import { Modal } from "bootstrap";

export default class extends Controller {
    connect() {
        // Attach event listener for when modal opens
        this.element.addEventListener("shown.bs.modal", () => {
            let input = this.element.querySelector(".modal .modal-body input");
            if (input) {
                input.focus(); // ✅ Automatically focuses on the first input field

                // ✅ Move cursor to the end if there is a value
                let length = input.value.length;
                input.setSelectionRange(length, length);
            }
        });
    }

    submit(event) {
        let overlay = document.getElementById("spinner");

        // ✅ Show overlay when form is submitted
        if (overlay) {
            overlay.classList.remove("d-none");
        }
    }

    close() {
        // ✅ Hide overlay after Turbo successfully processes response
        let overlay = document.getElementById("spinner");
        if (overlay) {
            overlay.classList.add("d-none");
        }

        // ✅ Reset the form fields
        let form = this.element.querySelector("form");
        if (form) {
            form.reset();
        }

        let modal = this.element.closest(".modal");
        let modalInstance = Modal.getInstance(modal);
        if (modalInstance) {
            modalInstance.hide();
        }
    }
}