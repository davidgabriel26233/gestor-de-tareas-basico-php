import { removeMessageError, showMessageError } from "./helpers/index.js";

document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    const inputTitle = document.querySelector('#title');
    const inputDescription = document.querySelector('#description');

    const validateTitle = title => {
        if (title === '') {
            showMessageError(inputTitle, 'El titulo es obligatorio');
            return;
        }

        const regexTitle = /^[a-zA-Z ]+$/;

        if (!regexTitle.test(title)) {
            showMessageError(inputTitle, 'El titulo solo puede tener letras y espacios');
            return;
        }

        removeMessageError(inputTitle);
    }

    const validateDescription = description => {
        if (description === '') {
            showMessageError(inputDescription, 'La descripción es obligatoria');
            return;
        }

        removeMessageError(inputDescription);
    }

    const submitForm = event => {
        event.preventDefault();

        validateTitle(inputTitle.value.trim());
        validateDescription(inputDescription.value.trim());

        if (document.querySelector('div.invalid-feedback')) return;

        form.submit();
    }

    form.addEventListener('submit', submitForm);
});