var board = ["", "", "", "", "", "", "", "", ""];
var playerSymbol = "X";
var cpuSymbol = "O";
var currentPlayer = "X";
var isGameOver = false;

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

var cells = document.querySelectorAll(".cell");
var statusEl = document.getElementById("status");

function selectSymbol(symbol) {
  playerSymbol = symbol;
  cpuSymbol = symbol === "X" ? "O" : "X";
  resetBoard();
}

function resetBoard() {
  board = ["", "", "", "", "", "", "", "", ""];
  isGameOver = false;
  currentPlayer = "X";
  cells.forEach(function (cell) {
    cell.textContent = "";
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

// copied make move function from medium mode and Cpu move from the Github repo
function makeMove(index, symbol) {
  board[index] = symbol;
  cells[index].textContent = symbol;
  cells[index].disabled = true;
  if (checkWin(symbol)) {
    statusEl.textContent = symbol === playerSymbol ? "You win!" : "AI wins!";
    isGameOver = true;
    cells.forEach(function (cell) {
      cell.disabled = true;
    });
  } else if (
    board.every(function (v) {
      return v !== "";
    })
  ) {
    statusEl.textContent = "It's a draw!";
    isGameOver = true;
  }
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
    .map(function (v, i) {
      return v === "" ? i : null;
    })
    .filter(function (v) {
      return v !== null;
    });
}

selectSymbol("X");
