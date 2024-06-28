// Kiedy dokument HTML jest gotowy do przetwarzania przez JavaScript
$(document).ready(function() {
    // Ładowanie biletów przy starcie
    loadTickets();

    // Obsługa zdarzenia zmiany dla rozwijanego menu filtra
    $('#filter').change(function() {
        filterTickets();
    });
});

// Funkcja ładująca bilety z opcjonalnymi parametrami filtra
function loadTickets(filterType = 'all', filterValue = '') {
    // Wykonanie żądania AJAX do serwera, aby pobrać bilety
    $.ajax({
        url: "../assets/php/fetch_tickets.php", // URL skryptu PHP obsługującego żądanie
        type: "POST", // Typ żądania HTTP
        data: { filterType: filterType, filterValue: filterValue }, // Dane wysyłane do serwera
        success: function(response) {
            // Umieszczenie odpowiedzi serwera w tabeli biletów
            $('#ticket-table').html(response);
        }
    });
}

// Funkcja filtrująca bilety w zależności od wybranego typu filtra
function filterTickets() {
    const filterType = $('#filter').val(); // Pobranie wartości filtra z rozwijanego menu
    let filterValue = '';

    // Sprawdzenie typu filtra i pobranie odpowiedniej wartości od użytkownika
    if (filterType === 'department') {
        filterValue = prompt("Enter Department:"); // Pobranie działu od użytkownika
    } else if (filterType === 'priority') {
        filterValue = prompt("Enter Priority (low, medium, high):"); // Pobranie priorytetu od użytkownika
    } else if (filterType === 'date') {
        filterValue = prompt("Enter Date (YYYY-MM-DD):"); // Pobranie daty od użytkownika
    }

    // Załadowanie biletów z wybranym filtrem
    loadTickets(filterType, filterValue);
}

// Funkcja oznaczająca bilet jako zakończony
function markAsDone(ticketId) {
    // Wykonanie żądania AJAX do serwera, aby zaktualizować status biletu
    $.ajax({
        url: "../assets/php/mark_done.php", // URL skryptu PHP obsługującego żądanie
        type: "POST", 
        data: { id: ticketId }, // Dane wysyłane do serwera, zawierające ID biletu
        success: function(response) {
            if (response === "success") {
                // Jeśli aktualizacja się powiodła, ponownie załaduj bilety
                loadTickets();
            } else {
                // Jeśli aktualizacja się nie powiodła, wyświetl komunikat o błędzie
                alert("Failed to update the status.");
            }
        }
    });
}