// soluzione.js

document.addEventListener("DOMContentLoaded", function () {
    // Seleziono il pulsante "Genera Rettangolo"
    const button = document.querySelector("button");
    
    button.addEventListener("click", function() {
        // Leggo i valori degli input base e altezza
        const baseInput = document.querySelector("input[name='base']");
        const altezzaInput = document.querySelector("input[name='altezza']");

        const baseVal = parseInt(baseInput.value, 10);
        const altezzaVal = parseInt(altezzaInput.value, 10);

        // Istanzio un nuovo rettangolo
        const rett = new Rettangolo(baseVal, altezzaVal);
        
        // Stampo in console le proprietà, il perimetro e l'area
        rett.stampaInConsole();
        
        // Visualizzo il rettangolo nel DOM, all'interno del <div> subito dopo il pulsante
        rett.visualizzaNelDOM("body > div");
    });

    // Definizione della classe Rettangolo
    class Rettangolo {
        constructor(base, altezza) {
            this.base = base;           // larghezza in pixel
            this.altezza = altezza;     // altezza in pixel
        }

        // Metodo per stampare in console base, altezza, perimetro e area
        stampaInConsole() {
            const perimetro = 2 * (this.base + this.altezza);
            const area = this.base * this.altezza;
            console.log("Base: " + this.base + "px");
            console.log("Altezza: " + this.altezza + "px");
            console.log("Perimetro: " + perimetro + "px");
            console.log("Area: " + area + "px²");
        }

        // Metodo per creare e inserire il rettangolo nel DOM
        // selector è una stringa da passare a querySelector, per individuare il contenitore
        visualizzaNelDOM(selector) {
            // Trovo l'elemento padre in cui inserire il rettangolo
            const container = document.querySelector(selector);
            if (!container) return; // se il selettore non esiste, esco
            
            // Creo il nuovo div che rappresenta il rettangolo
            const newDiv = document.createElement("div");
            newDiv.style.border = "1px solid #000";
            newDiv.style.width = this.base + "px";
            newDiv.style.height = this.altezza + "px";

            // Creo il link "x" per rimuovere questo rettangolo
            const link = document.createElement("a");
            link.href = "#";
            link.textContent = "x";

            // Evento click sul link per rimuovere solo questo rettangolo
            link.addEventListener("click", function(event) {
                newDiv.remove();
            });

            // Inserisco il link dentro il nuovo div
            newDiv.appendChild(link);
            // Infine, appendo il rettangolo al contenitore
            container.appendChild(newDiv);
        }
    }
});
