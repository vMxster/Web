document.addEventListener("DOMContentLoaded", function () {
    
    const main = document.querySelector("main")
    const table = document.createElement("table")
    table.id = "numeri"

    const row = document.createElement("tr")
    for (let index = 1; index < 10; index++) {
        const td = document.createElement("td")
        td.textContent = index
        td.addEventListener("click", function (e) {
            const number = e.target.textContent
            const p = document.querySelector("p.log")
            const evidenziata = document.querySelector(
                "table.tabellone td[style*='background-color: rgb(202, 202, 202)']"
            );

            if (evidenziata) {
                evidenziata.textContent = number
                evidenziata.style.backgroundColor = "";
                p.textContent = "Numero inserito correttamente"
            } else {
                p.textContent = "Cella non selezionata"
            }
        })
        row.appendChild(td)
    }
    table.appendChild(row)
    main.appendChild(table)

    const tds = document.querySelectorAll("table.tabellone td")
    tds.forEach( td => {
        td.addEventListener("click", function(e) {
            const cella = e.target;
            const evidenziata = document.querySelector(
                "table.tabellone td[style*='background-color: rgb(202, 202, 202)']"
            );

            if (cella.style.backgroundColor === "rgb(202, 202, 202)") {
                cella.style.backgroundColor = "";
            } else {
                if (evidenziata && evidenziata !== cella) {
                    evidenziata.style.backgroundColor = "";
                }
                cella.style.backgroundColor = "#cacaca";
            }
        })
    })

})