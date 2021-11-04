export function showError(input, message) {
    input.style.borderColor = "#dc3545";
    const msg = input.parentNode.querySelector("small").querySelector("Span");
    msg.innerText = message;
    return;
};