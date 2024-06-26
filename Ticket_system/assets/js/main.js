$(document).ready(function() {
    loadTickets();

    // Handle the change event for the filter dropdown
    $('#filter').change(function() {
        filterTickets();
    });
});

function loadTickets(filterType = 'all', filterValue = '') {
    $.ajax({
        url: "../assets/php/fetch_tickets.php",
        type: "POST",
        data: { filterType: filterType, filterValue: filterValue },
        success: function(response) {
            $('#ticket-table').html(response);
        }
    });
}

function filterTickets() {
    const filterType = $('#filter').val();
    let filterValue = '';

    if (filterType === 'department') {
        filterValue = prompt("Enter Department:");
    } else if (filterType === 'priority') {
        filterValue = prompt("Enter Priority (low, medium, high):");
    } else if (filterType === 'date') {
        filterValue = prompt("Enter Date (YYYY-MM-DD):");
    }

    loadTickets(filterType, filterValue);
}

function markAsDone(ticketId) {
    $.ajax({
        url: "../assets/php/mark_done.php",
        type: "POST",
        data: { id: ticketId },
        success: function(response) {
            if (response === "success") {
                loadTickets();
            } else {
                alert("Failed to update the status.");
            }
        }
    });
}