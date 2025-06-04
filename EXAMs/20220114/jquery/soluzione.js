// soluzione.js

document.addEventListener("DOMContentLoaded", function() {
    const ROWS = 6;
    const COLS = 7;

    // 1) Creiamo in memoria la “matrice” 6×7 con valori casuali 1 o 2
    const matrix = [];
    for (let i = 0; i < ROWS; i++) {
        const rowArr = [];
        for (let j = 0; j < COLS; j++) {
            // Math.random() * 2 genera [0,2[, Math.floor → 0 o 1, +1 → 1 o 2
            rowArr.push(Math.floor(Math.random() * 2) + 1);
        }
        matrix.push(rowArr);
    }

    // 2) Selezioniamo le due tabelle vuote (da non toccare nell’HTML!)
    //    - La PRIMA tabella è la prima <table> dentro <main>
    //    - La SECONDA tabella è quella dentro <div class="copia">
    const table1 = document.querySelector("main > table");
    const table2 = document.querySelector(".copia > table");
    const button = document.querySelector("button");

    // 3) Funzione che (ri)costruisce completamente la prima tabella in base a `matrix`
    function renderTable1() {
        // Svuoto qualsiasi contenuto precedente
        table1.innerHTML = "";

        for (let i = 0; i < ROWS; i++) {
            const tr = document.createElement("tr");
            for (let j = 0; j < COLS; j++) {
                const val = matrix[i][j];
                const td = document.createElement("td");

                // 3a) Assegno una classe del tipo "val-1" o "val-2"
                //     in modo che il CSS (vd. nota in fondo) gestisca il colore di sfondo.
                td.classList.add("val-" + val);

                // 3b) Memorizzo riga e colonna nei data-attributes del TD
                td.dataset.r = i;
                td.dataset.c = j;

                tr.appendChild(td);
            }
            table1.appendChild(tr);
        }
    }

    // Al caricamento iniziale genero la prima tabella
    renderTable1();

    // 4) Quando clicco su una cella della prima tabella:
    //    - Aggiorno matrix[i][j] = 0
    //    - Rimuovo la classe "val-1" o "val-2" dal TD, così da ereditare lo sfondo del padre
    table1.addEventListener("click", function(event) {
        const target = event.target;
        if (target.tagName !== "TD") return; // esco se non è <td>

        const i = parseInt(target.dataset.r, 10);
        const j = parseInt(target.dataset.c, 10);

        // 4a) Aggiorno la matrice
        matrix[i][j] = 0;

        // 4b) Rimuovo tutte le classi “val-<numero>” (val-1 o val-2)
        //     in modo tale che non rimanga lo sfondo colorato inline.
        target.classList.forEach(cls => {
            if (/^val-\d+$/.test(cls)) {
                target.classList.remove(cls);
            }
        });
        // Adesso questo <td> non ha più alcuna classe val-*, quindi
        // eredita il background grigio del <table>.
    });

    // 5) Nascondo inizialmente la seconda tabella (non si vede finché non clicco Bottone)
    table2.style.display = "none";

    // 6) Al click sul bottone “Genera Copia”:
    //    - Svuoto la seconda tabella
    //    - La ricreo riga per riga, mostrando i numeri correnti di matrix (0,1 o 2)
    //    - La rendo visibile
    button.addEventListener("click", function() {
        // 6a) Svuoto la seconda tabella
        table2.innerHTML = "";

        // 6b) La ricreo in base allo “stato corrente” di matrix
        for (let i = 0; i < ROWS; i++) {
            const tr = document.createElement("tr");
            for (let j = 0; j < COLS; j++) {
                const td = document.createElement("td");
                td.textContent = matrix[i][j];
                tr.appendChild(td);
            }
            table2.appendChild(tr);
        }

        // 6c) Rendo visibile la seconda tabella
        table2.style.display = "";
    });
});