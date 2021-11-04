import { showError } from "./utilities.js";

const newAuction = document.getElementById('createNewAuction');

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