document.addEventListener("DOMContentLoaded", function() {
  const table = document.querySelector("table");
  const trs = table.querySelectorAll("tr");
  const numRows = trs.length;
  const numCols = trs[0].querySelectorAll("td").length;
  const totalCells = numRows * numCols;
  const N = Math.max(numRows, numCols);  // numero di “mine” da piazzare

  // 1. Creazione della matrice inizializzata a 0
  const matrix = [];
  for (let i = 0; i < numRows; i++) {
    matrix[i] = new Array(numCols).fill(0);
  }

  // 2. Piazzamento casuale di esattamente N “1” senza duplicati
  let minesPlaced = 0;
  while (minesPlaced < N) {
    const r = getRandomInt(0, numRows);
    const c = getRandomInt(0, numCols);
    if (matrix[r][c] === 0) {
      matrix[r][c] = 1;
      minesPlaced++;
    }
  }

  // Contatore di celle sicure aperte
  let safeOpened = 0;
  // Totale di celle sicure = totalCells - N
  const totalSafe = totalCells - N;
  // Flag per stato partita
  let gameOver = false;

  // 3. Impostazione degli attributi data-row/data-col e dell'handler di click per ogni td
  const tds = table.querySelectorAll("td");
  tds.forEach(function(td, idx) {
    // Calcolo riga e colonna dall'indice lineare
    const rowIdx = Math.floor(idx / numCols);
    const colIdx = idx % numCols;
    td.dataset.row = rowIdx;
    td.dataset.col = colIdx;
    td.textContent = "[ ]";  // inizializzazione visuale (potrebbe già esserci)

    td.addEventListener("click", function(e) {
      if (gameOver) return;  // se la partita è già finita, ignoro

      const tr = parseInt(td.dataset.row, 10);
      const tc = parseInt(td.dataset.col, 10);
      const val = matrix[tr][tc];

      if (val === 0) {
        // Cella sicura
        td.textContent = "#";
        // Impedisco ulteriori click su questa cella
        td.style.pointerEvents = "none";
        safeOpened++;
        // Controllo vittoria
        if (safeOpened === totalSafe) {
          const p = document.querySelector("div > p");
          p.textContent = "Partita vinta";
          gameOver = true;
          // Disabilito tutti i click rimanenti
          disableAllCells();
        }
      } else {
        // Mina: partita persa
        td.textContent = "*";
        const p = document.querySelector("div > p");
        p.textContent = "Partita persa";
        gameOver = true;
        // Disabilito tutti i click rimanenti
        disableAllCells();
      }
    });
  });

  // Funzione per disabilitare i click su tutte le celle
  function disableAllCells() {
    tds.forEach(function(cell) {
      cell.style.pointerEvents = "none";
    });
  }

  // Funzione di utilità per ottenere un intero casuale in [min, max)
  function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min) + min);
  }
});
