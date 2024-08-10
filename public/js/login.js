const formEl = document.getElementById('login-form');
        const messageEl = document.getElementById('message');

        formEl.addEventListener('submit', async (event) => {
            event.preventDefault();
            messageEl.textContent = "";

            const formData = new FormData(event.target);
            const request = await fetch("/motus/login", { method: 'POST', body: formData });
            const responseData = await request.json();

            messageEl.textContent = responseData.message;
            console.log(responseData);

            if (responseData.status === "Autenticate") {
                window.location = "/motus/game";
            }
        });