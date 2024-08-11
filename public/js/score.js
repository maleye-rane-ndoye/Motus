document.addEventListener('DOMContentLoaded', function () {
    fetch('/motus/get-scores')
        .then(response => response.json())
        .then(data => {
            console.log(data);
            
        })
        .catch(error => console.error('Error fetching scores:', error));
});