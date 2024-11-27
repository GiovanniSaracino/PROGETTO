document.addEventListener('DOMContentLoaded', function () {
    // Verifica autenticazione
    const userData = sessionStorage.getItem('userData');
    if (!userData) {
        window.location.href = '../login.html';
        return;
    }

    const user = JSON.parse(userData);
    if (!user.amministratore) {
        window.location.href = '../user/dashboard.html';
        return;
    }

    // Carica i clienti
    loadClienti();
});

// Funzione per caricare i clienti
async function loadClienti() {
    const clientsSection = document.querySelector('.clients-section');

    try {
        const response = await fetch('../api/clienti.php');

        if (!response.ok) {
            throw new Error(`HTTP Error: ${response.status}`);
        }

        const clienti = await response.json();

        if (!Array.isArray(clienti) || clienti.length === 0) {
            clientsSection.innerHTML = `
                <h2>Lista Clienti</h2>
                <div class="empty-state">
                    <p>Nessun cliente presente nel sistema</p>
                </div>
            `;
            return;
        }

        clientsSection.innerHTML = `
            <h2>Lista Clienti</h2>
            <div class="clients-grid">
                ${clienti.map(cliente => `
                    <div class="client-card">
                        <h3>${cliente.nome || 'Nome non disponibile'}</h3>
                        <div class="client-details">
                            <p><strong>Email:</strong> ${cliente.email || 'N/D'}</p>
                            <p><strong>P.IVA:</strong> ${cliente.partitaIva || 'N/D'}</p>
                            <p><strong>Codice Fiscale:</strong> ${cliente.codiceFiscale || 'N/D'}</p>
                            <p><strong>Indirizzo:</strong> ${cliente.indirizzo || 'N/D'}</p>
                            <p><strong>Telefono:</strong> ${cliente.telefono || 'N/D'}</p>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
    } catch (error) {
        console.error('Errore nel caricamento dei clienti:', error);
        clientsSection.innerHTML = `
            <h2>Lista Clienti</h2>
            <div class="error-state">
                <p>Si è verificato un errore nel caricamento dei clienti</p>
            </div>
        `;
    }
}

// Funzione per il logout
