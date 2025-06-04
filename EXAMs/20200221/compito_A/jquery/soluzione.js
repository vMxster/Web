document.addEventListener("DOMContentLoaded", function () {
    // Seleziono il bottone “Carica Dati” e il paragrafo sotto l'H2
    const loadButton = document.querySelector("main button");
    const statusPara = document.querySelector("main p");
    const table = document.querySelector("main table");
    let columns = []; // Array per memorizzare i nomi delle colonne, estratte dal primo oggetto JSON

    loadButton.addEventListener("click", function () {
        // 1. Cambio immediatamente il paragrafo
        statusPara.textContent = "Caricamento dati in corso...";

        // 2. Faccio la fetch (AJAX) di sw_a.json
        fetch("sw_a.json")
            .then(response => {
                if (!response.ok) {
                    // Se c'è un errore HTTP, riporto testo di errore
                    throw new Error("Errore nel caricamento del JSON");
                }
                return response.json(); // Parso il JSON in oggetto JS
            })
            .then(data => {
                // 3. Se arrivo qui, ho i dati in "data" come array di oggetti
                buildTable(data);
                statusPara.textContent = "Caricamento dei dati avvenuto con successo.";
            })
            .catch(err => {
                console.error(err);
                statusPara.textContent = "Dati Non Caricati";
            });
    });

    /**
     * Crea la tabella completa a partire dall'array di oggetti JSON.
     * - Prima riga: <thead> con tutte le intestazioni + colonna "Azione"
     * - Poi tante righe (<tbody>) quante sono le voci in data
     * - Nell’ultima colonna di ogni riga, un bottone “Modifica Riga” che
     *   abilita la modifica in linea (e diventa “Conferma”).
     */
    function buildTable(data) {
        // Svuoto eventuale contenuto precedente
        table.innerHTML = "";

        if (!Array.isArray(data) || data.length === 0) {
            // Se l’array è vuoto o non valido, non faccio nulla
            return;
        }

        // 1) Estraggo le colonne (chiavi) dal primo oggetto JSON
        //    (si assume che tutti gli oggetti abbiano le stesse proprietà)
        columns = Object.keys(data[0]);

        // 2) Costruisco l’intestazione (<thead>)
        const thead = document.createElement("thead");
        const headerRow = document.createElement("tr");
        columns.forEach(colName => {
            const th = document.createElement("th");
            th.setAttribute("scope", "col");
            th.id = colName;
            th.textContent = colName;
            headerRow.appendChild(th);
        });
        // Colonna "Azione" in più
        const thAction = document.createElement("th");
        thAction.setAttribute("scope", "col");
        thAction.id = "Azione"
        thAction.textContent = "Azione";
        headerRow.appendChild(thAction);
        thead.appendChild(headerRow);
        table.appendChild(thead);

        // 3) Costruisco il <tbody> con i dati riga per riga
        const tbody = document.createElement("tbody");
        data.forEach(item => {
            const tr = document.createElement("tr");

            // Per ogni colonna definita, aggiungo un <td>
            columns.forEach(colName => {
                const td = document.createElement("td");
                td.setAttribute("headers", colName);
                // Se l’attributo è "colore preferito", uso il valore per lo sfondo
                if (colName.toLowerCase() === "colore preferito") {
                    // Si assume che il valore sia una stringa tipo "#ff0000" o "red"
                    td.style.backgroundColor = item[colName];
                    // Per accessibilità, lascio comunque il testo (il colore)
                    td.textContent = item[colName];
                } else {
                    td.textContent = item[colName];
                }
                tr.appendChild(td);
            });

            // Colonna finale con il pulsante “Modifica Riga”
            const tdBtn = document.createElement("td");
            tdBtn.setAttribute("headers", "Azione");
            const btn = document.createElement("button");
            btn.textContent = "Modifica Riga";
            // Attribuisco un listener che passa a onActionClick
            btn.addEventListener("click", onActionClick);
            tdBtn.appendChild(btn);
            tr.appendChild(tdBtn);

            tbody.appendChild(tr);
        });

        table.appendChild(tbody);
    }

    /**
     * Handler unico per click su “Modifica Riga” o “Conferma”.
     * - Se il testo del bottone è “Modifica Riga”, trasformo i <td> in <input>
     * - Se è “Conferma”, leggo i <input> e ripristino i <td> con il valore
     */
    function onActionClick(event) {
        const btn = event.target;
        const tr = btn.closest("tr");
        // Prendo tutti i <td> (incluso l’ultimo con il bottone)
        const tds = Array.from(tr.querySelectorAll("td"));

        if (btn.textContent === "Modifica Riga") {
            // --- PASSO A MODIFICA ---
            // Per ogni <td> tranne l’ultimo (che contiene il bottone)
            tds.slice(0, -1).forEach((td, idx) => {
                const colName = columns[idx];
                const oldValue = td.textContent;
                // Svuoto il td e ci inserisco un input
                td.textContent = "";

                let input = document.createElement("input");
                input.setAttribute("aria-label", colName); // etichetta accessibile

                if (colName.toLowerCase() === "colore preferito") {
                    // Potrebbe venire un valore in formato "rgb(r, g, b)" o nome colore: converto in esadecimale
                    const rawColor = item[colName];
                    const hexColor = rgb2hex(rawColor);
                    td.style.backgroundColor = hexColor;
                    td.dataset.color = hexColor;
                } else if (colName.toLowerCase() === "email") {
                    // Input di tipo email per validazione HTML5
                    input.type = "email";
                    input.value = oldValue;
                } else {
                    // Input testo generico
                    input.type = "text";
                    input.value = oldValue;
                }

                td.appendChild(input);
            });

            // Cambio il testo del bottone
            btn.textContent = "Conferma";
        } else {
            // --- PASSO A CONFERMA ---
            tds.slice(0, -1).forEach((td, idx) => {
                const colName = columns[idx];
                const input = td.querySelector("input");
                const newValue = input.value;
                // Ripristino il contenuto del <td> come testo
                td.textContent = newValue;

                if (colName.toLowerCase() === "colore preferito") {
                    // Se necessario, imposto il colore di sfondo
                    td.style.backgroundColor = newValue;
                } else {
                    // Per sicurezza, reset dello sfondo
                    td.style.backgroundColor = "";
                }
            });

            // Ripristino il pulsante a “Modifica Riga”
            btn.textContent = "Modifica Riga";
        }
    }

    // Funzione di utilità: converte "rgb(r, g, b)" (o "rgba(r, g, b, a)") in "#rrggbb"
    // Se la stringa non è in formato rgb, restituisce l'originale
    function rgb2hex(orig) {
        let rgb = orig.replace(/\s/g, '').match(/^rgba?\((\d+),(\d+),(\d+)/i);
        return (rgb && rgb.length === 4) ? "#" +
            ("0" + parseInt(rgb[1], 10).toString(16)).slice(-2) +
            ("0" + parseInt(rgb[2], 10).toString(16)).slice(-2) +
            ("0" + parseInt(rgb[3], 10).toString(16)).slice(-2) : orig;
    }
});
