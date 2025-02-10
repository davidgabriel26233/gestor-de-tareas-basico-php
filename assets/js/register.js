import { removeMessageError, showMessageError } from "./helpers/index.js";

document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    const inputUsername = document.querySelector('#username');
    const inputEmail = document.querySelector('#email');
    const inputPassword = document.querySelector('#password');

    const validateUsername = username => {
        if (username === '') {
            showMessageError(inputUsername, 'El nombre de usuario es obligatorio');
            return;
        }

        const regexUsername = /^[a-zA-Z0-9]+$/;

        if (!regexUsername.test(username)) {
            showMessageError(inputUsername, 'El nombre de usuario solo puede tener letras y numeros');
            return;
        }

        if (username.length < 4) {
            showMessageError(inputUsername, 'El nombre de usuario debe tener al menos 4 caracteres');
            return;
        }

        if (username.length > 16) {
            showMessageError(inputUsername, 'El nombre de usuario no debe tener mas de 16 caracteres');
            return;
        }

        removeMessageError(inputUsername);
    }

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

        if (password.length < 8) {
            showMessageError(inputPassword, 'La contraseña debe tener al menos 8 caracteres');
            return;
        }

        removeMessageError(inputPassword);
    }

    const submitForm = event => {
        event.preventDefault();

        validateUsername(inputUsername.value.trim().toLowerCase());
        validateEmail(inputEmail.value.trim().toLowerCase());
        validatePassword(inputPassword.value.trim());

        if (document.querySelector('div.invalid-feedback')) return;

        form.submit();
    }

    form.addEventListener('submit', submitForm);
});