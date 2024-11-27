document.addEventListener('DOMContentLoaded', function() {
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

    document.getElementById('adminName').textContent = user.admin.nome;
    loadAllTickets();
    loadAllUsers();
    loadAllClients();
});



function logout() {
    sessionStorage.removeItem('userData');
    window.location.href = '../login.html';
}
