#!/bin/bash

BOARD=(-1 -1 -1 -1 -1 -1 -1 -1 -1)
PLAYER=0
WINNER=-1
MOVE=-1
DRAW=false

declare -A SYMBOL_MAP
SYMBOL_MAP[-1]="-"
SYMBOL_MAP[0]="O"
SYMBOL_MAP[1]="X"

function print_board {
    echo "-----------------"
    for FIELD in {0..6..3}
    do
        echo "|   ${SYMBOL_MAP[${BOARD[${FIELD}]}]}   ${SYMBOL_MAP[${BOARD[$[FIELD+1]]}]}   ${SYMBOL_MAP[${BOARD[$[FIELD+2]]}]}   |"
    done
    echo "-----------------"
}
function read_move {
    echo -n "Ruch ${SYMBOL_MAP[${PLAYER}]}, wybierz pole: "
    read MOVE

    if ! [[ ${MOVE} =~ ^[0-8]$ ]]
    then
        echo "Nieprawidlowy numer pola, dozwolony zakres to 0-8"
        read_move
    elif [ ${BOARD[${MOVE}]} -ge 0 ]
    then
        echo "Pole ${MOVE} jest juz zajete"
        read_move
    fi

    BOARD[${MOVE}]=${PLAYER}
}

function check_line {
    if [ ${BOARD[$1]} -ge 0 ] && [ ${BOARD[$1]} -eq ${BOARD[$2]} ] && [ ${BOARD[$2]} -eq ${BOARD[$3]} ]
    then
        WINNER=${PLAYER}
    fi
}

function check_winner {
    check_line 0 1 2
    check_line 3 4 5
    check_line 6 7 8

    check_line 0 3 6
    check_line 1 4 7
    check_line 2 5 8

    check_line 0 4 8
    check_line 2 4 6
}

function check_draw {
    local SUM=0
    for VALUE in ${BOARD[@]}
    do
        SUM=$[SUM+VALUE]
    done

    if [ ${SUM} -eq 4 ]
    then
        DRAW=true
    fi
}

function switch_player {
    PLAYER=$[(PLAYER+1)%2]
}

echo "Numery pol na planszy:"
echo "0   1   2"
echo "3   4   5"
echo "6   7   8"
echo "Powodzenia!"
echo -e "-----------\n"

while [ ${WINNER} -lt 0 ] && [ "${DRAW}" = false ]
do
    read_move
    print_board
    check_winner
    check_draw
    switch_player
done

echo -n "Koniec gry. "
if [[ ${WINNER} -ge 0 ]]
then
    echo "Wygrywa ${SYMBOL_MAP[${WINNER}]}, gratulacje!"
else
    echo "Gra zakonczona remisem."
fi