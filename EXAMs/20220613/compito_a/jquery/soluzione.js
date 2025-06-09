document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const newBtn = document.querySelector('main > div > button');
    const evalBtn = document.querySelectorAll('main > div > button')[1];
    const winSpan = document.querySelector('.win');
    const loseSpan = document.getElementById('lose');
    const table = document.querySelector('table');

    // iniziale: nascondi form, spans, evalBtn
    form.style.display = 'none';
    winSpan.style.display = 'none';
    loseSpan.style.display = 'none';
    evalBtn.style.display = 'none';

    newBtn.addEventListener('click', function() {
        fetch('index.php')
            .then(res => res.json())
            .then(data => {
                const board = data.board;
                // crea tabella
                table.innerHTML = '';
                for (let r = 0; r < 9; r++) {
                    const tr = document.createElement('tr');
                    for (let c = 0; c < 9; c++) {
                        const td = document.createElement('td');
                        const val = board[r*9 + c];
                        if (val !== '0') {
                            td.textContent = val;
                            td.classList.add('fixed');
                        }
                        tr.appendChild(td);
                    }
                    table.appendChild(tr);
                }
                // mostra form e reset inputs
                form.style.display = '';
                form.reset();
                // mostra evalBtn
                evalBtn.style.display = '';
                // nascondi spans
                winSpan.style.display = 'none';
                loseSpan.style.display = 'none';
            });
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const r = parseInt(document.getElementById('riga').value, 10);
        const c = parseInt(document.getElementById('colonna').value, 10);
        const v = parseInt(document.getElementById('valore').value, 10);
        if (!(r >= 1 && r <= 9 && c >= 1 && c <= 9 && v >= 1 && v <= 9)) {
            alert('Riga, colonna e valore devono essere tra 1 e 9');
            return;
        }
        const cell = table.rows[r-1].cells[c-1];
        cell.textContent = v;
    });

    evalBtn.addEventListener('click', function() {
        // legge board
        let board = '';
        for (let r = 0; r < 9; r++) {
            for (let c = 0; c < 9; c++) {
                const txt = table.rows[r].cells[c].textContent;
                board += txt === '' ? '0' : txt;
            }
        }
        fetch('index.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ board: board })
        })
        .then(res => res.json())
        .then(resp => {
            if (resp.valid) {
                winSpan.style.display = '';
                loseSpan.style.display = 'none';
            } else {
                winSpan.style.display = 'none';
                loseSpan.style.display = '';
            }
        });
    });
});