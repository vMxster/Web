document.addEventListener("DOMContentLoaded", function() {

    const table = document.querySelector("main>table")
    const button = document.querySelector("button")

    matrix = []
    for (let i = 0; i < 6; i++) {
        matrix[i] = []
        const row = document.createElement("tr")
        for (let j = 0; j < 7; j++) {
            matrix[i][j] = Math.floor(Math.random() * 2) + 1;
            const td = document.createElement("td")
            td.addEventListener("click", function(e) {
                const td = e.target
                matrix[td.dataset.i][td.dataset.j] = 0
                td.style.backgroundColor = ""
            })
            td.dataset.i = i
            td.dataset.j = j
            if (matrix[i][j] === 1) {
                td.style.backgroundColor = "red"
            } else {
                td.style.backgroundColor = "blue"
            }
            row.appendChild(td)
        }
        table.appendChild(row)
    }

    button.addEventListener("click", loadCopy)

    function loadCopy(matrix) {
        const copia = document.querySelector("table.copia")
        copia.innerHTML = ""
        for (let i = 0; i < 6; i++) {
            const row = document.createElement("tr")
            for (let j = 0; j < 7; j++) {
                const td = document.createElement("td")
                td.textContent = matrix[i][j]
                row.appendChild(td)
            }
            copia.appendChild(row)
        }
    }

})