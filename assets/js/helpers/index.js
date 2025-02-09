export const showMessageError = (input, message) => {
    input.classList.add('is-invalid');
    if (!input.nextElementSibling) {
        const divError = document.createElement('div');
        divError.classList.add('invalid-feedback');
        input.parentElement.appendChild(divError);
    }

    input.nextElementSibling.textContent = message;
}

export const removeMessageError = input => {
    input.classList.remove('is-invalid');
    if (input.nextElementSibling) {
        input.nextElementSibling.remove();
    }
}