var board = ["", "", "", "", "", "", "", "", ""];
var playerSymbol = "X";
var cpuSymbol = "O";
var currentPlayer = "X";
var isGameOver = false;
var moveHistory = [];   // stores board string after each move

var winPatterns = [
  [0, 1, 2],
  [3, 4, 5],
  [6, 7, 8],
  [0, 3, 6],
  [1, 4, 7],
  [2, 5, 8],
  [0, 4, 8],
  [2, 4, 6],
];

let isLoggedIn = false;
let currentUserID = null;

function checkLogin() {
  fetch("get_session.php")
    .then(res => res.json())
    .then(data => {
      if (data.loggedIn) {
        isLoggedIn = true;
        currentUserID = data.userID;
      } else {
        isLoggedIn = false;
        document.getElementById("login-warning").style.display = "block";
      }
    });
}

checkLogin();

//Load greeting message
fetch('get_session.php')
      .then(res => res.json())
      .then(data => {
        const greeting = document.getElementById('greeting');
        const signout = document.getElementById('signout');

        if (data.loggedIn) {
          greeting.textContent = `Hello, ${data.username}`;
          signout.style.display = 'block'; 
        } else {
          greeting.textContent = ''; // or "Hello, Guest"
          signout.style.display = 'none';
        }
      })
      .catch(err => console.error(err));


var cells = document.querySelectorAll(".cell");
var statusEl = document.getElementById("status");

function selectSymbol(symbol) {
  playerSymbol = symbol;
  cpuSymbol = symbol === "X" ? "O" : "X";
  document.getElementById("pick-x").classList.toggle("active", symbol === "X");
  document.getElementById("pick-o").classList.toggle("active", symbol === "O");
  resetBoard();
}

function resetBoard() {
  board = ["", "", "", "", "", "", "", "", ""];
  moveHistory = [];
  isGameOver = false;
  currentPlayer = "X";
  cells.forEach(function (cell) {
    cell.textContent = "";
    cell.removeAttribute("data-val");
    cell.disabled = false;
  });
  statusEl.textContent = "Your turn (" + playerSymbol + ")";
  if (currentPlayer === cpuSymbol) {
    setTimeout(triggerCpuMove, 400);
  }
}

function handleCellClick(index) {
  if (isGameOver || currentPlayer !== playerSymbol || board[index] !== "")
    return;
  makeMove(index, playerSymbol);
  if (!isGameOver) {
    currentPlayer = cpuSymbol;
    statusEl.textContent = "AI is thinking...";
    setTimeout(triggerCpuMove, 400);
  }
}

function makeMove(index, symbol) {
  board[index] = symbol;
  cells[index].textContent = symbol;
  cells[index].setAttribute("data-val", symbol);
  cells[index].disabled = true;

  // Record board state after this move as a comma-separated string e.g. "X,,O,X,,,O,,"
  moveHistory.push(board.join(","));

  if (checkWin(symbol)) {
    var playerWon = symbol === playerSymbol;
    statusEl.textContent = playerWon ? "🎉 You win!" : "🤖 AI wins!";
    isGameOver = true;
    cells.forEach(function (cell) { cell.disabled = true; });
    saveGame(playerWon ? "win" : "loss");

  } else if (board.every(function (v) { return v !== ""; })) {
    statusEl.textContent = "🤝 It's a draw!";
    isGameOver = true;
    saveGame("draw");
  }
}

function saveGame(result) {
  if (!isLoggedIn || !currentUserID) return;

  fetch("save_game.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      result: result,
      moves: moveHistory    // array of board strings, one per move
    })
  })
    .then(res => res.json())
    .then(data => {
      if (!data.success) {
        console.warn("Game save failed:", data.message);
      }
    })
    .catch(err => console.error("Save error:", err));
}

function triggerCpuMove() {
  if (isGameOver) return;
  var move = getMediumMove();
  if (move !== null) {
    makeMove(move, cpuSymbol);
    if (!isGameOver) {
      currentPlayer = playerSymbol;
      statusEl.textContent = "Your turn (" + playerSymbol + ")";
    }
  }
}

function getMediumMove() {
  var empty = getEmptyCells();
  if (Math.random() < 0.25)
    return empty[Math.floor(Math.random() * empty.length)];
  var win = findWinningMove(cpuSymbol);
  if (win !== null) return win;
  var block = findWinningMove(playerSymbol);
  if (block !== null) return block;
  if (board[4] === "") return 4;
  return empty[Math.floor(Math.random() * empty.length)];
}

function findWinningMove(symbol) {
  var empty = getEmptyCells();
  for (var i = 0; i < empty.length; i++) {
    board[empty[i]] = symbol;
    var won = checkWin(symbol);
    board[empty[i]] = "";
    if (won) return empty[i];
  }
  return null;
}

function checkWin(symbol) {
  return winPatterns.some(function (p) {
    return p.every(function (i) {
      return board[i] === symbol;
    });
  });
}

function getEmptyCells() {
  return board
    .map(function (v, i) { return v === "" ? i : null; })
    .filter(function (v) { return v !== null; });
}

selectSymbol("X"); 