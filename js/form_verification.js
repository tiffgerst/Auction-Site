const newAuction = document.getElementById('createNewAuction');

function showError(input, message) {
    input.style.borderColor = "#dc3545";
    const msg = input.parentNode.querySelector("small").querySelector("Span");
    msg.innerText = message;
    return;
}

newAuction.addEventListener('submit', function(event) {
    event.preventDefault();

    var endDate = new Date(newAuction.elements["auctionEndDate"].value);
    var currentDate = new Date();
    
    if (endDate < currentDate) {
        showError(newAuction.elements["auctionEndDate"],"Please enter a valid date.");
    }
    
    else {
        newAuction.submit();
    }
});