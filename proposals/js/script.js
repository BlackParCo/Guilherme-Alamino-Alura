$(document).ready(function() {
    $('#myTable').DataTable({
        "pageLength": 3,
        "order": [
            [0, 'asc']
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json"
        }
    });
});