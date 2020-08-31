function refresh(time) {
    setTimeout(function () {
        window.location.reload();
    }, time * 1000);
}
refresh(300);

function stop(event) {
    event.preventDefault();
}

function triMidi() {
    location = `dashboard.php?tri=1`;
}

function triSoir() {
    location = `dashboard.php?tri=2`;
}

function triJournee() {
    location = `dashboard.php?tri=0`;
}


/*
 function supprimer(id_reservation, id_client, event) {
 event.preventDefault();
 if (confirm("Supprimer la réservation ?")) {
 let url = `supprimer.php?id_reservation=${id_reservation}&id_client=${id_client}`;
 fetch(url)
 .then(response => {
 if (response.ok)
 location.reload();
 
 })
 .catch(error => console.error(error));
 }
 
 }
 
 function confirmer(id_reservation, event) {
 event.preventDefault();
 if (confirm("Confirmer la réservation ?")) {
 let url = `confirmer.php?id_reservation=${id_reservation}`;
 fetch(url)
 .then(response => {
 if (response.ok) 
 location.reload();               
 })
 .catch(error => console.error(error));
 }
 }
 function retourConfirmer(id_reservation, event) {
 event.preventDefault();
 if (confirm("Annuler Clients à table ?")) {
 let url = `confirmer.php?id_reservation=${id_reservation}`;
 fetch(url)
 .then(response => {
 if (response.ok) 
 location.reload();               
 })
 .catch(error => console.error(error));
 }
 }
 
 function confirmerClient(id_reservation, event) {
 event.preventDefault();
 if (confirm("Clients à table ?")) {
 let url = `confirmerClient.php?id_reservation=${id_reservation}`;
 fetch(url)
 .then(response => {
 if (response.ok) 
 location.reload();               
 })
 .catch(error => console.error(error));
 }
 }
 function annuler(id_reservation, event) {
 event.preventDefault();
 if (confirm("Refuser la réservation ?")) {
 let url = `annuler.php?id_reservation=${id_reservation}`;
 fetch(url)
 .then(response => {
 if (response.ok) 
 location.reload();               
 })
 .catch(error => console.error(error));
 }
 }
 */



