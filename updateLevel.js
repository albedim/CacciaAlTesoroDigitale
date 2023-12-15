function updateLevel(level) {
    $.ajax({
      url: 'update_data.php', // Specifica l'URL della tua pagina PHP
      method: 'get', // Metodo HTTP da utilizzare (GET o POST)
      success: function(response) {
        // Gestisci la risposta dal server qui
        console.log('Risposta dal server:', response);
      },
      error: function(xhr, status, error) {
        // Gestisci eventuali errori qui
        console.error('Errore nella chiamata AJAX:', error);
      }
    });
  }