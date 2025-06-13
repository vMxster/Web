document.addEventListener("DOMContentLoaded", function() {
    const button = document.querySelector("button")
    const main = document.querySelector("main")
    button.addEventListener("click", function() {

        fetch("data.json")
            .then(response => {
                if (!response.ok) {
                    alert("Documento non Trovato")
                } else {
                    document.querySelector("main").innerHTML = ""
                    return response.json()
                }
            })
            .then(payload => {
                let dataArray;
                if (Array.isArray(payload)) {
                    dataArray = payload;
                } else {
                    // Cerchiamo la prima proprietà di tipo array
                    for (const key in payload) {
                        if (Array.isArray(payload[key])) {
                            dataArray = payload[key];
                            break;
                        }
                    }
                }
                dataArray.forEach(row => {
                    const div = document.createElement("div")
                    const ul = document.createElement("ul")

                    const up = document.createElement("button")
                    up.textContent = "Up"
                    const down = document.createElement("button")
                    down.textContent = "Down"
                    
                    const id = document.createElement("li")
                    id.textContent = row["id"]
                    ul.appendChild(id)

                    const nome = document.createElement("li")
                    nome.textContent = row["name"]
                    ul.appendChild(nome)

                    const tipo = document.createElement("li")
                    tipo.textContent = row["type"]
                    ul.appendChild(tipo)

                    div.appendChild(ul)

                    up.addEventListener("click", function(e) {
                        const div1 = e.target.parentNode
                        const div2 = div1.previousElementSibling
                        if (div2) div2.before(div1)
                    })

                    down.addEventListener("click", function(e) {
                        const div1 = e.target.parentNode
                        const div2 = div1.nextElementSibling
                        if (div2) div2.after(div1)
                    })

                    div.appendChild(up)
                    div.appendChild(down)
                    main.appendChild(div)
                });
            })
    })

})