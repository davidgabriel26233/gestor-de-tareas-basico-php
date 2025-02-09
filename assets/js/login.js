import { removeMessageError, showMessageError } from "./helpers/index.js";

document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    const inputEmail = document.querySelector('#email');
    const inputPassword = document.querySelector('#password');

    const validateEmail = email => {
        if (email === '') {
            showMessageError(inputEmail, 'El email es obligatorio');
            return;
        }

        const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!regexEmail.test(email)) {
            showMessageError(inputEmail, 'El email no es valido');
            return;
        }

        removeMessageError(inputEmail);
    }

    const validatePassword = password => {
        if (password === '') {
            showMessageError(inputPassword, 'La contraseña es obligatoria');
            return;
        }
        removeMessageError(inputPassword);
    }

    const submitForm = event => {
        event.preventDefault();

        const email = inputEmail.value;
        const password = inputPassword.value;

        validateEmail(email.trim().toLowerCase());
        validatePassword(password.trim());

        if (document.querySelector('div.invalid-feedback')) return;

        form.submit();
    }

    form.addEventListener('submit', submitForm);
});