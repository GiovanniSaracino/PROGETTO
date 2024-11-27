document.addEventListener('DOMContentLoaded', function() {
    const userData = sessionStorage.getItem('userData');
    if (!userData) {
        window.location.href = '../login.html';
        return;
    }

    const user = JSON.parse(userData);
    if (user.amministratore) {
        window.location.href = '../admin/dashboard.html';
        return;
    }

    document.getElementById('userName').textContent = user.username;
    loadTickets();
});

function logout() {
    sessionStorage.removeItem('userData');
    window.location.href = '../login.html';
}

async function loadTickets() {
    try {
        const userData = JSON.parse(sessionStorage.getItem('userData'));
        const response = await fetch('../api/tickets.php?clienteId=' + userData.cliente.id);
        const tickets = await response.json();
        
        const ticketsList = document.getElementById('ticketsList');
        ticketsList.innerHTML = tickets.map(ticket => `
            <div class="ticket-card">
                <h3>Ticket #${ticket.id}</h3>
                <p>${ticket.descrizione}</p>
                <div class="ticket-footer">
                    <span class="status ${ticket.stato.toLowerCase()}">${ticket.stato}</span>
                    <span class="date">${new Date(ticket.dataCreazione).toLocaleDateString()}</span>
                </div>
            </div>
        `).join('');
    } catch (error) {
        console.error('Errore nel caricamento dei ticket:', error);
        alert('Errore nel caricamento dei ticket. Riprova più tardi.');
    }
}

// Modal per nuovo ticket
const modal = document.getElementById('newTicketModal');
const closeBtn = document.getElementsByClassName('close')[0];

function openNewTicketModal() {
    modal.style.display = "block";
}

closeBtn.onclick = function() {
    modal.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

// Gestione creazione nuovo ticket
document.getElementById('newTicketForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const descrizione = document.getElementById('ticketDescription').value;
    const userData = JSON.parse(sessionStorage.getItem('userData'));

    try {
        const response = await fetch('../api/tickets.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                clienteId: userData.cliente.id,
                descrizione: descrizione,
                stato: 'APERTO'
            })
        });

        if (response.ok) {
            modal.style.display = "none";
            document.getElementById('ticketDescription').value = '';
            loadTickets(); // Ricarica la lista dei ticket
            alert('Ticket creato con successo!');
        } else {
            alert('Errore durante la creazione del ticket');
        }
    } catch (error) {
        console.error('Errore:', error);
        alert('Errore durante la creazione del ticket');
    }
}); 