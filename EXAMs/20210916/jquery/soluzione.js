// 1. Alla fine del caricamento della pagina, creiamo la tabella "numeri"
document.addEventListener("DOMContentLoaded", function () { 
    const main = document.querySelector("main");

    // Creiamo la tabella con id "numeri"
    const tableNumeri = document.createElement("table");
    tableNumeri.id = "numeri";

    const row = document.createElement("tr");
    for (let i = 1; i < 10; i++) {
        const cell = document.createElement("td");
        cell.textContent = i;
        cell.addEventListener("click", numeroClick);
        row.appendChild(cell);
    }
    tableNumeri.appendChild(row);
    main.appendChild(tableNumeri);

    // 2. Aggiungiamo l’event listener alle celle del tabellone (class="tabellone")
    const tabelloneCells = document.querySelectorAll("table.tabellone td");
    tabelloneCells.forEach(cell => {
        cell.addEventListener("click", tabClick);
    });
});

/**
 * Funzione invocata al click su una cella del tabellone (class="tabellone").
 * Seleziona/deseleziona la cella, assicurando che sia evidenziata (background #cacaca)
 * al più una cella per volta.
 */
function tabClick(event) {
    const cella = event.target;

    // Cerchiamo eventualmente la cella già evidenziata (background = rgb(202,202,202))
    const evidenziata = document.querySelector(
        "table.tabellone td[style*='background-color: rgb(202, 202, 202)']"
    );

    // Se la cella cliccata è già evidenziata, la deselezioniamo
    if (cella.style.backgroundColor === "rgb(202, 202, 202)") {
        cella.style.backgroundColor = "";
    } else {
        // Se c’è un’altra cella evidenziata, la deselezioniamo prima
        if (evidenziata && evidenziata !== cella) {
            evidenziata.style.backgroundColor = "";
        }
        // Evidenziamo la cella cliccata
        cella.style.backgroundColor = "#cacaca";
    }
}

/**
 * Funzione invocata al click su una cella della tabella "numeri" (id="numeri").
 * Se non c’è alcuna cella evidenziata nel tabellone, mostra "Cella non selezionata";
 * altrimenti copia il numero nella cella evidenziata, deseleziona quest’ultima
 * e scrive "Numero inserito correttamente" nel paragrafo .log.
 */
function numeroClick(event) {
    const logPar = document.querySelector("p.log");
    const numeroSelezionato = event.target.textContent;

    // Troviamo la cella evidenziata nel tabellone
    const evidenziata = document.querySelector(
        "table.tabellone td[style*='background-color: rgb(202, 202, 202)']"
    );

    if (!evidenziata) {
        logPar.textContent = "Cella non selezionata";
    } else {
        // Inseriamo il numero all’interno della cella evidenziata
        evidenziata.textContent = numeroSelezionato;
        // La deselezioniamo subito dopo
        evidenziata.style.backgroundColor = "";
        logPar.textContent = "Numero inserito correttamente";
    }
}