// Assicuriamoci che il DOM sia caricato prima di registrare gli event listener
document.addEventListener('DOMContentLoaded', function() {
    const button = document.querySelector('button');
    const main = document.querySelector('main');

    button.addEventListener('click', loadData);

    function loadData() {
        // Recuperiamo il JSON da "data.json" con Fetch API
        fetch('data.json')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Errore nel caricamento: ${response.status}`);
                }
                return response.json();
            })
            .then(payload => {
                // Determiniamo se il JSON restituito è già un array oppure se contiene un array al suo interno
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

                if (!Array.isArray(dataArray)) {
                    console.error('Il JSON non contiene un array alla radice né in una proprietà.');
                    return;
                }

                // Svuotiamo eventuali dati già presenti
                main.innerHTML = '';

                dataArray.forEach(item => {
                    // Creiamo un div contenitore per l’elemento
                    const container = document.createElement('div');
                    container.setAttribute('role', 'group');

                    // Creiamo una lista non ordinata per le proprietà
                    const ul = document.createElement('ul');

                    // id
                    const liId = document.createElement('li');
                    liId.textContent = `id: ${item.id}`;
                    ul.appendChild(liId);

                    // nome
                    const liNome = document.createElement('li');
                    liNome.textContent = `nome: ${item.nome}`;
                    ul.appendChild(liNome);

                    // tipo
                    const liTipo = document.createElement('li');
                    liTipo.textContent = `tipo: ${item.tipo}`;
                    ul.appendChild(liTipo);

                    container.appendChild(ul);

                    // Creiamo i bottoni Up e Down
                    const upButton = document.createElement('button');
                    upButton.setAttribute('type', 'button');
                    upButton.textContent = 'Up';

                    const downButton = document.createElement('button');
                    downButton.setAttribute('type', 'button');
                    downButton.textContent = 'Down';

                    // Event listener per il bottone Up
                    upButton.addEventListener('click', function() {
                        const previous = container.previousElementSibling;
                        if (previous) {
                            main.insertBefore(container, previous);
                        }
                    });

                    // Event listener per il bottone Down
                    downButton.addEventListener('click', function() {
                        const next = container.nextElementSibling;
                        if (next) {
                            main.insertBefore(next, container);
                        }
                    });

                    // Aggiungiamo i bottoni al contenitore
                    container.appendChild(upButton);
                    container.appendChild(downButton);

                    // Infine, inseriamo il container dentro <main>
                    main.appendChild(container);
                });
            })
            .catch(error => {
                console.error('Errore:', error);
            });
    }
});