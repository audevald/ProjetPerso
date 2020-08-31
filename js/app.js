if ("serviceWorker" in navigator) {
  // on déclare le service worker via la fonction `register`
  navigator.serviceWorker
    .register("./sw.js")
    .then(registration => {
      // On a réussi ! 
      console.log(
        "App: Achievement unlocked."
      );
    })
    .catch(error => {
      // Il y a eu un problème
      console.error(
        "App: Crash de Service Worker",
        error
      );
    });
} else {
  // Si le navigateur ne permet pas d'utiliser un Service Worker on ne fait rien de particulier.
  // Le site devrait continuer à fonctionner normalement.
}
