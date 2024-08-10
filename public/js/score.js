document.addEventListener('DOMContentLoaded', function () {
    fetch('/motus/get-scores')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('scores-table-body');
            tableBody.innerHTML = '';

            data.forEach(score => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${score.username}</td>
                    <td>${score.win}</td>
                    <td>${score.lost}</td>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch(error => console.error('Error fetching scores:', error));
});